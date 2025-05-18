<?php

namespace App\Observers;

use App\Models\Booking;
use App\Models\Ticket; // Import Ticket
use Illuminate\Support\Facades\Log; // Untuk logging
use App\Notifications\BookingConfirmedNotification;

class BookingObserver
{
    /**
     * Handle the Booking "created" event.
     */
    public function created(Booking $booking): void
    {
        //
    }

    /**
     * Handle the Booking "updated" event.
     */
    public function updated(Booking $booking): void
    {
        // Cek apakah kolom 'status' diubah dan nilai barunya adalah 'confirmed'
        if ($booking->isDirty('status') && $booking->status === Booking::STATUS_CONFIRMED) {
            $this->createTicketForBooking($booking);
        }

        // Cek apakah kolom 'status' diubah dan nilai barunya adalah 'cancelled'
        if ($booking->isDirty('status') && $booking->status === Booking::STATUS_CANCELLED) {
            $this->cancelTicketForBooking($booking);
        }

        // Tambahkan logika lain jika perlu (misal saat payment success)
        // if ($booking->isDirty('payment_status') && $booking->payment_status === Booking::PAYMENT_SUCCESS) {
        //     // Bisa juga trigger dari sini jika payment_status menentukan konfirmasi booking
        //     if($booking->status === Booking::STATUS_CONFIRMED) { // Pastikan status booking juga sesuai
        //          $this->createTicketForBooking($booking);
        //     }
        // }
    }

    /**
     * Create Ticket if booking is confirmed and ticket doesn't exist.
     */
    protected function createTicketForBooking(Booking $booking): void
    {
        // Cek dulu apakah tiket sudah ada untuk booking ini
        if (!$booking->ticket()->exists()) {
            try {
                Ticket::create([
                    'booking_id' => $booking->id,
                    // ticket_code akan digenerate otomatis oleh boot() method di Ticket model
                    'status' => Ticket::STATUS_VALID,
                ]);
                Log::info("Tiket berhasil dibuat untuk booking #{$booking->id}");

                try {
                    // Load ulang booking DENGAN tiket yg baru dibuat + user
                    $booking->load('user', 'ticket');
                    $booking->user->notify(new BookingConfirmedNotification($booking));
                    Log::info("Notifikasi konfirmasi booking #{$booking->id} dikirim ke {$booking->user->email}");
                } catch (\Exception $e) {
                    // Tangani error pengiriman email (misal: log, coba lagi nanti)
                    Log::error("Gagal mengirim notifikasi konfirmasi booking #{$booking->id}: " . $e->getMessage());
                }
                // === AKHIR PENGIRIMAN NOTIFIKASI ===

            } catch (\Exception $e) {
                Log::error("Gagal membuat tiket untuk booking #{$booking->id}: " . $e->getMessage());
            }
        } else {
            Log::info("Tiket untuk booking #{$booking->id} sudah ada, tidak dibuat ulang.");
        }
    }

    /**
     * Cancel Ticket if booking is cancelled.
     */
    protected function cancelTicketForBooking(Booking $booking): void
    {
        // Cari tiket yang terhubung
        $ticket = $booking->ticket;
        if ($ticket && $ticket->status !== Ticket::STATUS_CANCELLED && $ticket->status !== Ticket::STATUS_USED) {
            try {
                $ticket->status = Ticket::STATUS_CANCELLED;
                $ticket->save();
                Log::info("Tiket #{$ticket->id} (booking #{$booking->id}) dibatalkan.");
            } catch (\Exception $e) {
                Log::error("Gagal membatalkan tiket #{$ticket->id} untuk booking #{$booking->id}: " . $e->getMessage());
            }
        }
    }

    /**
     * Handle the Booking "deleted" event.
     */
    public function deleted(Booking $booking): void
    {
        //
    }

    /**
     * Handle the Booking "restored" event.
     */
    public function restored(Booking $booking): void
    {
        //
    }

    /**
     * Handle the Booking "force deleted" event.
     */
    public function forceDeleted(Booking $booking): void
    {
        //
    }
}
