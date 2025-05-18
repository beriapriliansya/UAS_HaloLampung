<?php

namespace App\Notifications;

use App\Models\Booking; // Import Booking
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use SimpleSoftwareIO\QrCode\Facades\QrCode; // Import QR Code Facade
use Illuminate\Support\Facades\Log; // Untuk debug

class BookingConfirmedNotification extends Notification implements ShouldQueue // Implement ShouldQueue agar pengiriman email tidak memblokir request utama
{
    use Queueable;

    public Booking $booking; // Properti untuk menyimpan data booking
    public $qrCodeDataUri = null; // Properti untuk QR code

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking->load(['user', 'destination', 'ticket']); // Load relasi yg dibutuhkan

         // Generate QR Code jika tiket sudah ada
        if ($this->booking->ticket) {
            try {
                // Data yang di-encode: Kode tiket unik
                $qrData = $this->booking->ticket->ticket_code;
                 // Generate sebagai Data URI (langsung embed di email)
                 // Gunakan format PNG atau SVG. PNG lebih umum didukung email client.
                $this->qrCodeDataUri = 'data:image/png;base64,' . base64_encode(
                    QrCode::format('png')->size(250)->errorCorrection('H')->generate($qrData)
                );
            } catch (\Exception $e) {
                Log::error("Gagal generate QR Code untuk notifikasi tiket booking #{$this->booking->id}: " . $e->getMessage());
                $this->qrCodeDataUri = null; // Set null jika gagal
            }
        } else {
             Log::warning("Notifikasi BookingConfirmed dipanggil untuk booking #{$this->booking->id} tetapi tiket belum tergenerate.");
        }
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail']; // Kirim via email
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $subject = 'Konfirmasi Pemesanan & Tiket Wisata Anda - ' . $this->booking->booking_code;

        // Kirim data booking dan QR code ke view email
        return (new MailMessage)
                    ->subject($subject)
                    ->markdown('emails.booking_confirmed', [
                        'booking' => $this->booking,
                        'qrCodeDataUri' => $this->qrCodeDataUri, // Kirim data URI QR Code
                    ]);

        // --- Alternatif jika ingin melampirkan PDF ---
        // 1. Generate PDF Tiket (lihat langkah opsional di bawah)
        // $pdfTicket = $this->generatePdfTicket();

        // return (new MailMessage)
        //             ->subject($subject)
        //             ->markdown('emails.booking_confirmed_with_attachment', [ // View yg berbeda mungkin
        //                 'booking' => $this->booking
        //             ])
        //             ->attachData($pdfTicket->output(), 'Tiket-' . $this->booking->booking_code . '.pdf', [
        //                 'mime' => 'application/pdf',
        //             ]);
        // --- Akhir Alternatif PDF ---
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        // Digunakan jika channel lain (misal: database) dipakai
        return [
            'booking_id' => $this->booking->id,
            'message' => 'Pemesanan ' . $this->booking->booking_code . ' telah dikonfirmasi.',
        ];
    }

    // --- Method Helper untuk Generate PDF (Opsional) ---
    // protected function generatePdfTicket()
    // {
    //     if(!$this->booking->ticket) return null; // Butuh tiket
    //     try {
    //         $qrCodePdf = QrCode::format('png')->size(200)->generate($this->booking->ticket->ticket_code);
    //         $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('tickets.pdf_template', [ // View template PDF tiket
    //                 'booking' => $this->booking,
    //                 'qrCodeImage' => $qrCodePdf // Kirim data gambar QR
    //         ]);
    //         return $pdf;
    //     } catch (\Exception $e) {
    //         Log::error("Gagal generate PDF tiket untuk booking #{$this->booking->id}: " . $e->getMessage());
    //         return null;
    //     }
    // }
    // --- Akhir Helper PDF ---
}