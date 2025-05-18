<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Booking; // Untuk relasi
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Untuk check-in by user
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode; // Import Facade QR Code

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['booking.user', 'booking.destination', 'checker']) // Eager load
                       ->latest('created_at'); // Urutkan dari tiket terbaru

        // Filter berdasarkan Status Tiket
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan Tanggal Kunjungan Booking
        if ($request->filled('visit_date')) {
            $query->whereHas('booking', function($q) use ($request) {
                $q->whereDate('booking_date', $request->visit_date);
            });
        }

        // Filter berdasarkan Kode Tiket atau Kode Booking
        if ($request->filled('search')) {
             $searchTerm = $request->search; // Tidak perlu % karena UUID atau kode booking biasanya exact match
             $query->where(function($q) use ($searchTerm) {
                 $q->where('ticket_code', $searchTerm)
                   ->orWhereHas('booking', function($bookingQuery) use ($searchTerm) {
                       $bookingQuery->where('booking_code', $searchTerm);
                   });
             });
        }

        $tickets = $query->paginate(15)->withQueryString();

         // Data untuk filter
         $statuses = [
             Ticket::STATUS_VALID => 'Valid',
             Ticket::STATUS_USED => 'Sudah Digunakan',
             Ticket::STATUS_EXPIRED => 'Kadaluarsa',
             Ticket::STATUS_CANCELLED => 'Dibatalkan',
         ];

        return view('admin.tickets.index', compact('tickets', 'statuses'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['booking.user', 'booking.destination', 'booking.facilities', 'checker']);

        // Generate QR Code Data URI (langsung embed di HTML)
        // Data yang di-encode bisa berupa kode tiket saja, atau URL ke halaman verifikasi
        // $qrCodeData = route('ticket.verify', $ticket->ticket_code); // Contoh jika ada halaman verifikasi publik
        $qrCodeData = $ticket->ticket_code; // Encode kode tiket saja
        $qrCode = QrCode::size(250)->generate($qrCodeData);


        return view('admin.tickets.show', compact('ticket', 'qrCode'));
    }

    // Aksi Check-in Manual dari halaman detail
    public function checkIn(Ticket $ticket)
    {
         if (!$ticket->canBeCheckedIn()) {
            $errorMessage = 'Tiket tidak dapat di check-in. Status: ' . $ticket->status_label;
             if ($ticket->status === Ticket::STATUS_VALID && !$ticket->booking?->booking_date?->isToday()){
                  $errorMessage .= ' (Tanggal kunjungan bukan hari ini).';
             }
             return redirect()->route('admin.tickets.show', $ticket)->with('error', $errorMessage);
        }

        try {
            $ticket->status = Ticket::STATUS_USED;
            $ticket->checked_in_at = now();
            $ticket->checked_in_by = Auth::id(); // Catat admin yg melakukan check-in
            $ticket->save();

             Log::info("Tiket #{$ticket->id} (booking #{$ticket->booking_id}) berhasil di check-in manual oleh user ID: " . Auth::id());
            return redirect()->route('admin.tickets.show', $ticket)->with('success', 'Tiket berhasil ditandai sebagai SUDAH DIGUNAKAN.');

        } catch (\Exception $e) {
             Log::error("Gagal check-in manual tiket #{$ticket->id}: " . $e->getMessage());
            return redirect()->route('admin.tickets.show', $ticket)->with('error', 'Gagal melakukan check-in: ' . $e->getMessage());
        }
    }

    // --- Fitur Check / Scan (Opsional) ---

    // Menampilkan form input kode tiket
    public function showCheckForm(Request $request)
    {
        $result = null;
        $ticket = null;
        $message = $request->session()->get('check_message'); // Ambil pesan dari redirect
        $status = $request->session()->get('check_status');

        return view('admin.tickets.check', compact('result', 'ticket', 'message', 'status'));
    }

    // Memproses kode tiket yang diinput/scan
    public function processCheck(Request $request)
    {
        $request->validate(['ticket_code' => 'required|string']);
        $code = $request->ticket_code;

        $ticket = Ticket::with('booking.user', 'booking.destination')->where('ticket_code', $code)->first();

        $result = false;
        $message = '';
        $status = 'error'; // default status message

        if (!$ticket) {
            $message = "Tiket dengan kode '{$code}' tidak ditemukan.";
        } elseif ($ticket->status === Ticket::STATUS_USED) {
            $message = "Tiket sudah digunakan pada: " . $ticket->checked_in_at->format('d M Y H:i') . ".";
        } elseif ($ticket->status === Ticket::STATUS_CANCELLED) {
            $message = "Tiket ini telah dibatalkan.";
        } elseif ($ticket->status === Ticket::STATUS_EXPIRED) {
            $message = "Tiket ini sudah kadaluarsa.";
        } elseif ($ticket->booking?->booking_date?->isFuture()) { // Cek tanggal kunjungan
             $message = "Tiket ini valid untuk tanggal " . $ticket->booking->booking_date->format('d M Y') . ", belum waktunya check-in.";
        } elseif ($ticket->booking?->booking_date?->isPast() && !$ticket->booking?->booking_date?->isToday()) {
             $message = "Tiket ini valid untuk tanggal " . $ticket->booking->booking_date->format('d M Y') . ", sudah lewat.";
             // Opsional: Otomatis set expired jika terlewat?
             // $ticket->status = Ticket::STATUS_EXPIRED;
             // $ticket->save();
        } elseif ($ticket->status === Ticket::STATUS_VALID && $ticket->booking?->booking_date?->isToday()) {
            // Kondisi valid untuk Check-in!
             try {
                $ticket->status = Ticket::STATUS_USED;
                $ticket->checked_in_at = now();
                $ticket->checked_in_by = Auth::id();
                $ticket->save();

                $result = true;
                $message = "Check-in Berhasil! Tiket valid untuk: " . $ticket->booking->user->name . " - " . $ticket->booking->destination->name . " (" . $ticket->booking->num_tickets . " orang).";
                $status = 'success';
                Log::info("Tiket #{$ticket->id} (booking #{$ticket->booking_id}) berhasil di check-in via form oleh user ID: " . Auth::id());

            } catch (\Exception $e) {
                $message = "Gagal melakukan check-in: " . $e->getMessage();
                Log::error("Gagal check-in via form tiket #{$ticket->id}: " . $e->getMessage());
            }
        } else {
            // Kondisi lain yg tidak terduga
            $message = "Status tiket tidak diketahui atau tanggal booking tidak valid.";
        }

        // Redirect kembali ke form check dengan pesan hasil
        return redirect()->route('admin.tickets.checkForm')
                         ->with('check_message', $message)
                         ->with('check_status', $status)
                         ->withInput(); // Bawa kembali input code
    }
}