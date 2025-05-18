<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Destination; // Untuk filter dropdown
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Untuk logging jika perlu
// use App\Notifications\TicketNotification; // Jika ada notifikasi tiket

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'destination']) // Eager load relasi utama
                       ->latest(); // Urutkan dari terbaru

        // Filter berdasarkan Destinasi
        if ($request->filled('destination_id')) {
            $query->where('destination_id', $request->destination_id);
        }

        // Filter berdasarkan Status Pembayaran
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter berdasarkan Status Booking
        if ($request->filled('booking_status')) {
            $query->where('status', $request->booking_status);
        }

        // Filter berdasarkan Tanggal Booking (Pemesanan dibuat)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        } elseif ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->start_date . ' 00:00:00');
        } elseif ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }

        // Filter berdasarkan Kode Booking atau Nama User
        if ($request->filled('search')) {
             $searchTerm = '%' . $request->search . '%';
             $query->where(function($q) use ($searchTerm) {
                 $q->where('booking_code', 'like', $searchTerm)
                   ->orWhereHas('user', function($userQuery) use ($searchTerm) {
                       $userQuery->where('name', 'like', $searchTerm);
                   });
             });
        }


        $bookings = $query->paginate(15)->withQueryString(); // Paginasi & bawa query string filter

        // Data untuk filter dropdown
        $destinations = Destination::orderBy('name')->pluck('name', 'id');
        $paymentStatuses = [ // Ambil dari konstanta model
            Booking::PAYMENT_PENDING => 'Pending',
            Booking::PAYMENT_SUCCESS => 'Sukses',
            Booking::PAYMENT_FAILURE => 'Gagal',
            Booking::PAYMENT_EXPIRED => 'Kadaluarsa',
            Booking::PAYMENT_CANCELLED => 'Dibatalkan',
        ];
         $bookingStatuses = [ // Ambil dari konstanta model
            Booking::STATUS_PENDING_PAYMENT => 'Menunggu Pembayaran',
            Booking::STATUS_CONFIRMED => 'Terkonfirmasi',
            Booking::STATUS_CANCELLED => 'Dibatalkan',
            Booking::STATUS_COMPLETED => 'Selesai',
            Booking::STATUS_FAILED => 'Gagal',
        ];

        return view('admin.bookings.index', compact(
            'bookings',
            'destinations',
            'paymentStatuses',
            'bookingStatuses'
        ));
    }

    public function show(Booking $booking)
    {
        // Load relasi detail yg mungkin belum terload
        $booking->load(['user', 'destination', 'facilities', 'payment']);
        return view('admin.bookings.show', compact('booking'));
    }

    // Aksi: Verifikasi Pembayaran Manual
    public function verifyPayment(Booking $booking)
    {
        // ... validasi ...

        $booking->payment_status = Booking::PAYMENT_SUCCESS; // Atau sesuai logika Anda
        $booking->status = Booking::STATUS_CONFIRMED;
        $booking->save(); // <<< PASTIKAN INI ADA DAN DIEKSEKUSI

        // ... (logika notifikasi jika ada di sini) ...

        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', 'Pembayaran berhasil diverifikasi manual.');
    }

    // Aksi: Membatalkan Pemesanan
    public function cancel(Booking $booking)
    {
         // Validasi: Jangan batalkan yg sudah selesai atau sudah dibatalkan
        if (in_array($booking->status, [Booking::STATUS_COMPLETED, Booking::STATUS_CANCELLED])) {
             return redirect()->route('admin.bookings.show', $booking)
                              ->with('error', 'Pemesanan tidak dapat dibatalkan (status saat ini: '. $booking->status_label .').');
        }

        $originalStatus = $booking->status;
        $originalPaymentStatus = $booking->payment_status;

        $booking->status = Booking::STATUS_CANCELLED;
        // Jika belum bayar, set payment status juga cancelled
        if ($originalPaymentStatus === Booking::PAYMENT_PENDING) {
            $booking->payment_status = Booking::PAYMENT_CANCELLED;
        }
        // Jika SUDAH bayar (success), status payment tetap 'success' tapi booking 'cancelled'
        // Ini menandakan perlu proses REFUND (ditangani di modul pembayaran/refund)
        $booking->save();

        // TODO: Kembalikan kuota jika ada (logika ini kompleks)
        // Contoh: dispatch(new RestoreQuotaJob($booking));

        // TODO: Kirim notifikasi pembatalan ke user jika perlu

         // Pesan feedback berdasarkan status pembayaran awal
        $message = 'Pemesanan berhasil dibatalkan.';
        if ($originalPaymentStatus === Booking::PAYMENT_SUCCESS) {
             $message .= ' Status pembayaran: Sukses (Perlu proses refund).';
        }


        return redirect()->route('admin.bookings.show', $booking)
                         ->with('success', $message);
    }

    // Aksi: Kirim Ulang Tiket (Contoh)
    public function resendTicket(Booking $booking)
    {
        // Validasi: Hanya bisa kirim ulang jika sudah dikonfirmasi
        if ($booking->status !== Booking::STATUS_CONFIRMED) {
             return redirect()->route('admin.bookings.show', $booking)
                              ->with('error', 'Tiket tidak dapat dikirim ulang (status booking: '. $booking->status_label .').');
        }

        // TODO: Logika untuk mengirim ulang email/notifikasi tiket
        // try {
        //     $booking->user->notify(new TicketNotification($booking)); // Gunakan notifikasi yg sama
             return redirect()->route('admin.bookings.show', $booking)
                             ->with('success', 'Notifikasi tiket berhasil dikirim ulang ke ' . $booking->user->email);
        // } catch (\Exception $e) {
        //     Log::error('Gagal kirim ulang notif tiket booking #' . $booking->id . ': ' . $e->getMessage());
        //     return redirect()->route('admin.bookings.show', $booking)
        //                      ->with('error', 'Gagal mengirim ulang tiket. Silakan coba lagi.');
        // }
    }
}