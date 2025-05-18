<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Booking;
use App\Models\Facility; // Import Facility
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Untuk Transaction
use Illuminate\Support\Facades\Log;
use Carbon\Carbon; // Untuk validasi tanggal

class BookingController extends Controller
{
    // Menampilkan form booking
    public function create(Destination $destination)
    {
        // Eager load fasilitas yang tersedia untuk destinasi ini
        $destination->load(['facilities' => function ($query) {
            // Pastikan mengambil pivot data yang relevan
            $query->select('facilities.id', 'facilities.name', 'destination_facility.price', 'destination_facility.quota');
        }]);

        // Cek apakah destinasi ada, jika tidak (meski route model binding harusnya handle)
        if (!$destination) {
            abort(404, 'Destinasi tidak ditemukan.');
        }

        return view('booking.create', compact('destination'));
    }

    // Menyimpan data booking dari form
    public function store(Request $request, Destination $destination)
    {
        $validated = $request->validate([
            'booking_date' => ['required', 'date', 'after_or_equal:' . Carbon::today()->toDateString()],
            'num_tickets' => ['required', 'integer', 'min:1'],
            'facilities' => ['nullable', 'array'],
            // Validasi setiap item dalam array facilities
            'facilities.*.id' => ['required_with:facilities', 'integer', 'exists:facilities,id'],
            'facilities.*.quantity' => ['required_with:facilities', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        // --- PERHITUNGAN HARGA SERVER-SIDE (SANGAT PENTING!) ---
        $baseTicketPrice = $destination->base_ticket_price;
        $numTickets = (int)$validated['num_tickets'];
        $totalTicketPrice = $baseTicketPrice * $numTickets;
        $totalFacilityPrice = 0;
        $selectedFacilitiesData = []; // Untuk menyimpan data fasilitas yg akan di-attach

        if (!empty($validated['facilities'])) {
            $availableFacilities = $destination->facilities()
                ->whereIn('facilities.id', array_column($validated['facilities'], 'id'))
                ->get()
                ->keyBy('id'); // Key by ID for easy lookup

            foreach ($validated['facilities'] as $selectedFacility) {
                $facilityId = $selectedFacility['id'];
                $quantity = (int)$selectedFacility['quantity'];

                // Pastikan fasilitas ada di daftar available & harga sesuai dari DB
                if (isset($availableFacilities[$facilityId])) {
                    $facility = $availableFacilities[$facilityId];
                    $pricePerFacility = $facility->pivot->price; // Ambil harga dari pivot
                    $totalFacilityPrice += ($pricePerFacility * $quantity);

                    // Simpan data untuk di-attach ke pivot booking_facility
                    $selectedFacilitiesData[$facilityId] = [
                        'quantity' => $quantity,
                        'price_at_booking' => $pricePerFacility, // Simpan harga saat booking
                    ];
                } else {
                    // Handle jika user mencoba memasukkan facility ID yg tidak valid/tidak tersedia
                    // Seharusnya tidak terjadi jika form & JS benar, tapi bagus untuk keamanan
                    Log::warning("User " . Auth::id() . " mencoba memesan facility ID {$facilityId} yang tidak valid/tersedia untuk destination {$destination->id}");
                    // Bisa return error atau abaikan fasilitas ini
                    return back()->withErrors(['facilities' => 'Terjadi kesalahan pada pemilihan fasilitas.'])->withInput();
                }
            }
        }

        $totalAmount = $totalTicketPrice + $totalFacilityPrice;
        // --- AKHIR PERHITUNGAN HARGA SERVER-SIDE ---

        // Gunakan DB Transaction untuk memastikan konsistensi data
        DB::beginTransaction();
        try {
            // 1. Buat record Booking
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'destination_id' => $destination->id,
                'booking_date' => $validated['booking_date'],
                'num_tickets' => $numTickets,
                'base_ticket_price_at_booking' => $baseTicketPrice,
                'total_facility_price' => $totalFacilityPrice,
                'total_amount' => $totalAmount,
                'notes' => $validated['notes'] ?? null,

            ]);

            // 2. Attach fasilitas yang dipilih ke tabel pivot booking_facility
            if (!empty($selectedFacilitiesData)) {
                $booking->facilities()->attach($selectedFacilitiesData);
            }

            // TODO: Implementasi pengurangan kuota jika diperlukan (logic kompleks)

            DB::commit(); // Semua berhasil, simpan perubahan

            return redirect()->route('booking.show', ['bookingCode' => $booking->booking_code])
                ->with('success', 'Pemesanan awal berhasil dibuat. Silakan lanjutkan ke pembayaran.');
        } catch (\Exception $e) {
            DB::rollBack(); // Terjadi error, batalkan semua perubahan DB
            Log::error("Gagal membuat booking untuk user " . Auth::id() . " di destination {$destination->id}: " . $e->getMessage());

            return back()->with('error', 'Gagal memproses pemesanan. Silakan coba lagi. Error: ' . $e->getMessage())->withInput();
        }
    }


    public function show($bookingCode)
    {
        // Decodifikasi URL jika ada karakter yang di-encode
        $bookingCode = urldecode($bookingCode);

        $booking = Booking::where('booking_code', $bookingCode)->firstOrFail();

        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke pemesanan ini.');
        }

        $booking->load(['destination', 'facilities', 'user']);
        return view('booking.show', compact('booking'));
    }

    public function myBookings()
    {
        $userId = Auth::id(); // Dapatkan ID user yg login

        $bookings = Booking::where('user_id', $userId)
                            ->with(['destination', 'payment', 'ticket']) // Eager load relasi
                            ->latest() // Urutkan dari terbaru
                            ->paginate(10); // Paginasi

        return view('booking.my_index', compact('bookings')); // Kirim ke view baru
    }
}
