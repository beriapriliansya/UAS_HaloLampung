<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment; // Import Payment model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str; // Untuk order ID jika perlu
use Midtrans; // Import Midtrans namespace
use Midtrans\Snap; // Import Midtrans Snap
use Illuminate\Support\Facades\DB; // Untuk Transaction
use Carbon\Carbon; 


class PaymentController extends Controller
{
    // Memulai proses pembayaran -> Mendapatkan token Snap
    public function initiatePayment($bookingCode)
    {
        // 1. Validasi: Pastikan booking milik user yg login & statusnya pending
        $bookingCode = urldecode($bookingCode);
    
        // Cari booking berdasarkan booking_code
        $booking = Booking::where('booking_code', $bookingCode)->firstOrFail();
        
        // Lanjutkan dengan kode yang ada
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }
        
        if ($booking->status !== Booking::STATUS_PENDING_PAYMENT) {
            return redirect()->route('booking.show', $booking->booking_code)->with('error', 'Pemesanan ini tidak bisa dibayar (status: ' . $booking->status_label . ').');
        }

        // 2. Buat atau Update Record Payment di DB
        DB::beginTransaction();
        try {
            $payment = Payment::updateOrCreate(
                ['booking_id' => $booking->id], // Cari berdasarkan booking_id
                [
                    'amount' => $booking->total_amount,
                    'status' => Payment::STATUS_PENDING, // Set/Reset ke pending
                    'payment_gateway' => 'midtrans', // Tandai gateway
                    'transaction_id' => null, // Akan diisi setelah transaksi dibuat
                    'paid_at' => null, // Reset paid_at
                    'gateway_details' => null, // Reset details
                    'refund_status' => Payment::REFUND_NONE, // Reset refund
                    'refunded_amount' => null,
                    'refunded_at' => null,
                    'refund_reason' => null,
                ]
            );
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal membuat/update payment record untuk booking #{$booking->id}: " . $e->getMessage());
            return redirect()->route('booking.show', $booking->booking_code)->with('error', 'Gagal memulai pembayaran. Silakan coba lagi.');
        }

        // 3. Siapkan Parameter untuk Midtrans Snap
        $transactionDetails = [
            'order_id' => $booking->booking_code . '-' . time(), // Buat unik order ID (Booking Code + timestamp)
            'gross_amount' => (int) $booking->total_amount, // Amount harus integer
        ];

        // Item Details (Rinci)
        $itemDetails = [];
        // Tiket masuk
        $itemDetails[] = [
            'id' => 'TICKET_' . $booking->destination_id,
            'price' => (int) $booking->base_ticket_price_at_booking,
            'quantity' => $booking->num_tickets,
            'name' => Str::limit('Tiket Masuk ' . $booking->destination->name, 50), // Max 50 char
        ];
        // Fasilitas
        foreach ($booking->facilities as $facility) {
            $itemDetails[] = [
                'id' => 'FAC_' . $facility->id,
                'price' => (int) $facility->pivot->price_at_booking,
                'quantity' => (int) $facility->pivot->quantity,
                'name' => Str::limit($facility->name, 50), // Max 50 char
            ];
        }

        // Customer Details
        $customerDetails = [
            'first_name' => $booking->user->name,
            'email' => $booking->user->email,
            // 'phone' => $booking->user->phone, // Jika ada nomor telepon
        ];

        // Gabungkan semua parameter
        $midtransParams = [
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            'customer_details' => $customerDetails,
            // 'enabled_payments' => ['gopay', 'shopeepay', 'bca_va'], // Filter metode pembayaran (Opsional)
            // 'callbacks' => [ // URL Redirect setelah user bayar di Midtrans
            //     'finish' => route('payment.finish') // Wajib ada jika pakai Snap Redirect
            // ]
        ];

        // 4. Dapatkan Token Snap dari Midtrans
        try {
            $snapToken = Snap::getSnapToken($midtransParams);

            // Simpan order_id Midtrans ke payment record (order_id dari $transactionDetails)
            // Sebaiknya transaction_id diisi saat notifikasi, tapi order_id penting untuk dicatat sekarang
            $payment->gateway_details = ['midtrans_order_id' => $transactionDetails['order_id']];
            $payment->save();


            // 5. Kirim token ke View untuk diproses oleh Snap JS
            return view('payment.initiate', [
                'snapToken' => $snapToken,
                'booking' => $booking, // Kirim booking untuk info
                'midtransClientKey' => config('midtrans.client_key') // Kirim client key
            ]);
        } catch (\Exception $e) {
            Log::error("Midtrans Snap Exception untuk booking #{$booking->id}: " . $e->getMessage());
            return redirect()->route('booking.show', $booking->booking_code)->with('error', 'Gagal menghubungi gateway pembayaran: ' . $e->getMessage());
        }
    }

    // Menangani Notifikasi/Webhook dari Midtrans
    public function handleNotification(Request $request)
    {
        // 1. Buat instance Midtrans Notification
        // Gunakan Server Key lagi di sini untuk verifikasi signature (meski sudah di config global)
        $notification = new Midtrans\Notification(); // Server Key akan diambil dari config global

        // 2. Validasi Signature (SANGAT PENTING!)
        // Ini memastikan notifikasi benar-benar dari Midtrans
        // Jika pakai $notification->getResponse(), signature otomatis divalidasi

        DB::beginTransaction();
        try {
            $transactionStatus = $notification->transaction_status;
            $paymentType = $notification->payment_type;
            $orderId = $notification->order_id; // Order ID yg kita kirim (booking_code-timestamp)
            $fraudStatus = $notification->fraud_status;
            $statusCode = $notification->status_code; // HTTP status code Midtrans
            $grossAmount = $notification->gross_amount; // Amount dari Midtrans
            $midtransTransactionId = $notification->transaction_id; // ID unik dari Midtrans

            Log::info('Midtrans Notification Received:', (array) $notification); // Log seluruh notifikasi

            // 3. Cari Payment Record berdasarkan Order ID
            // Kita perlu parse booking_code dari order_id
            $bookingCode = explode('-', $orderId)[0];
            $payment = Payment::whereHas('booking', fn($q) => $q->where('booking_code', $bookingCode))
                ->with('booking') // Eager load booking
                ->first();

            if (!$payment) {
                Log::error("Webhook Error: Payment record not found for Order ID: {$orderId} (Booking Code: {$bookingCode})");
                DB::rollBack(); // Tidak perlu rollback jika hanya tidak ketemu, tapi pastikan tidak ada perubahan
                return response()->json(['message' => 'Payment not found'], 404);
            }

            // 4. Hindari update ganda (Idempotency)
            // Jika status sudah 'success' atau 'refunded', jangan proses lagi notifikasi pending/expire
            if (in_array($payment->status, [Payment::STATUS_SUCCESS, Payment::STATUS_REFUNDED, Payment::STATUS_PARTIALLY_REFUNDED])) {
                if (!in_array($transactionStatus, ['settlement', 'capture'])) { // Hanya proses settlement/capture jika sudah sukses
                    Log::info("Webhook Ignored: Payment #{$payment->id} already processed (status: {$payment->status}). Received: {$transactionStatus}");
                    DB::commit(); // Commit tanpa perubahan
                    return response()->json(['message' => 'Payment already processed'], 200);
                }
            }

            // 5. Update Status Payment berdasarkan Notifikasi Midtrans
            $newPaymentStatus = $payment->status; // Default status lama
            $newBookingStatus = $payment->booking->status; // Default status lama
            $paidAt = $payment->paid_at;

            // https://docs.midtrans.com/en/support-articles/what-is-the-linkage-between-transaction-status-and-fraud-status
            if ($transactionStatus == 'capture') { // Untuk kartu kredit
                if ($fraudStatus == 'accept') {
                    $newPaymentStatus = Payment::STATUS_SUCCESS;
                    $newBookingStatus = Booking::STATUS_CONFIRMED;
                    $paidAt = Carbon::parse($notification->settlement_time ?? now());
                } else {
                    // Fraud status bisa 'challenge' atau 'deny'
                    // Kita anggap gagal jika bukan 'accept'
                    $newPaymentStatus = Payment::STATUS_FAILURE;
                    $newBookingStatus = Booking::STATUS_FAILED;
                }
            } elseif ($transactionStatus == 'settlement') { // Untuk metode lain (VA, e-wallet, dll)
                $newPaymentStatus = Payment::STATUS_SUCCESS;
                $newBookingStatus = Booking::STATUS_CONFIRMED;
                $paidAt = Carbon::parse($notification->settlement_time ?? now());
            } elseif ($transactionStatus == 'pending') {
                $newPaymentStatus = Payment::STATUS_PENDING; // Tetap pending
                $newBookingStatus = Booking::STATUS_PENDING_PAYMENT; // Pastikan booking juga pending
            } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
                $newPaymentStatus = Payment::STATUS_FAILURE;
                if ($transactionStatus == 'expire') $newPaymentStatus = Payment::STATUS_EXPIRED;
                $newBookingStatus = Booking::STATUS_FAILED; // Atau CANCELLED jika dari user cancel?
            }

            // Update Payment Record
            $payment->status = $newPaymentStatus;
            $payment->transaction_id = $midtransTransactionId; // Simpan ID transaksi Midtrans
            $payment->paid_at = $paidAt;
            // Simpan raw notification untuk referensi
            $details = $payment->gateway_details ?? []; // Ambil detail lama jika ada
            $details['notification_log'][] = (array) $notification; // Tambahkan log notifikasi baru
            $payment->gateway_details = $details;
            $payment->save();

            // Update Booking Record
            if ($payment->booking->status !== $newBookingStatus) {
                $payment->booking->status = $newBookingStatus;
                // Update juga payment_status di booking jika masih dipakai
                $payment->booking->payment_status = $newPaymentStatus; // Samakan dengan status payment
                $payment->booking->save();

                // Trigger Observer Booking (untuk generate tiket jika confirmed, dll)
                // Observer akan otomatis terpanggil saat booking di-save jika status berubah
            }

            DB::commit(); // Simpan semua perubahan

            // Beri response OK ke Midtrans
            return response()->json(['message' => 'Notification processed successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            // Evaluasi order ID dulu
            $errorOrderId = $notification->order_id ?? 'N/A';
            Log::critical("Midtrans Webhook Processing Error for Order ID: " . $errorOrderId . ": " . $e->getMessage() . "\nStack Trace: " . $e->getTraceAsString());
            // Jangan beri tahu error detail ke Midtrans
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    // Menangani Redirect dari Midtrans (Finish, Unfinish, Error)
    public function handleRedirect(Request $request)
    {
        $statusCode = $request->query('status_code');
        $orderId = $request->query('order_id');
        $transactionStatus = $request->query('transaction_status'); // Mungkin ada, mungkin tidak

        // Cari booking berdasarkan order_id (parse lagi)
        $bookingCode = explode('-', $orderId)[0];
        $booking = Booking::where('booking_code', $bookingCode)->first();

        if (!$booking) {
            // Tampilkan halaman error umum jika booking tidak ketemu
            return redirect()->route('home')->with('error', 'Pemesanan tidak ditemukan.');
        }

        // Tampilkan pesan berdasarkan status code dari Midtrans / route name
        $routeName = $request->route()->getName();
        $message = 'Status pembayaran Anda sedang diproses.'; // Default
        $alertType = 'info';

        if ($routeName === 'payment.finish' || ($statusCode == '200' && $transactionStatus == 'settlement')) {
            $message = 'Pembayaran Anda telah berhasil diterima! Tiket akan segera dikirim atau dapat dilihat di riwayat pemesanan.';
            $alertType = 'success';
        } elseif ($routeName === 'payment.unfinish' || ($statusCode == '201' && $transactionStatus == 'pending')) {
            $message = 'Pembayaran Anda tertunda. Silakan selesaikan pembayaran sesuai instruksi.';
            $alertType = 'warning';
        } elseif ($routeName === 'payment.error' || $statusCode == '202') {
            $message = 'Pembayaran Anda gagal diproses. Silakan coba lagi atau hubungi dukungan.';
            $alertType = 'danger';
        } else {
            // Jika status tidak jelas dari redirect, cek status terakhir di DB
            if ($booking->payment && $booking->payment->status === Payment::STATUS_SUCCESS) {
                $message = 'Pembayaran Anda telah berhasil diterima!';
                $alertType = 'success';
            } elseif ($booking->payment && $booking->payment->status === Payment::STATUS_PENDING) {
                $message = 'Pembayaran Anda masih tertunda.';
                $alertType = 'warning';
            } elseif ($booking->payment && in_array($booking->payment->status, [Payment::STATUS_FAILURE, Payment::STATUS_EXPIRED])) {
                $message = 'Pembayaran Anda gagal atau kadaluarsa.';
                $alertType = 'danger';
            }
        }

        // Redirect ke halaman detail booking dengan pesan flash
        return redirect()->route('booking.show', $booking->booking_code)
            ->with($alertType, $message);
    }
    
}
