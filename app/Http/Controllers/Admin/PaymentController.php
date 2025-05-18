<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Booking; // Untuk relasi
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Untuk transaksi DB
use Illuminate\Support\Facades\Log; // Untuk logging error

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['booking.user', 'booking.destination']) // Eager load relasi
                       ->latest();

        // Filter berdasarkan Status Pembayaran
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan Status Refund
        if ($request->filled('refund_status')) {
            $query->where('refund_status', $request->refund_status);
        }

         // Filter berdasarkan Gateway
        if ($request->filled('gateway')) {
            $query->where('payment_gateway', $request->gateway);
        }

        // Filter berdasarkan Tanggal Bayar (paid_at)
        if ($request->filled('paid_start_date') && $request->filled('paid_end_date')) {
            $query->whereBetween('paid_at', [$request->paid_start_date . ' 00:00:00', $request->paid_end_date . ' 23:59:59']);
        } elseif ($request->filled('paid_start_date')) {
            $query->where('paid_at', '>=', $request->paid_start_date . ' 00:00:00');
        } elseif ($request->filled('paid_end_date')) {
            $query->where('paid_at', '<=', $request->paid_end_date . ' 23:59:59');
        }

        // Filter berdasarkan Kode Booking atau Transaction ID
        if ($request->filled('search')) {
             $searchTerm = '%' . $request->search . '%';
             $query->where(function($q) use ($searchTerm) {
                 $q->where('transaction_id', 'like', $searchTerm)
                   ->orWhereHas('booking', function($bookingQuery) use ($searchTerm) {
                       $bookingQuery->where('booking_code', 'like', $searchTerm);
                   });
             });
        }

        $payments = $query->paginate(15)->withQueryString();

         // Data untuk filter
         $statuses = [
            Payment::STATUS_PENDING => 'Pending',
            Payment::STATUS_SUCCESS => 'Sukses',
            Payment::STATUS_FAILURE => 'Gagal',
            Payment::STATUS_EXPIRED => 'Kadaluarsa',
            Payment::STATUS_REFUNDED => 'Direfund Penuh',
            Payment::STATUS_PARTIALLY_REFUNDED => 'Direfund Sebagian',
         ];
         $refundStatuses = [
             Payment::REFUND_NONE => 'Tidak Ada',
            Payment::REFUND_REQUESTED => 'Diminta',
            Payment::REFUND_PROCESSING => 'Diproses',
            Payment::REFUND_COMPLETED => 'Selesai',
            Payment::REFUND_FAILED => 'Gagal',
         ];
         // Ambil daftar gateway unik dari DB (efisien jika tidak terlalu banyak)
         $gateways = Payment::distinct()->whereNotNull('payment_gateway')->pluck('payment_gateway')->toArray();


        return view('admin.payments.index', compact('payments', 'statuses', 'refundStatuses', 'gateways'));
    }

    public function show(Payment $payment)
    {
        $payment->load(['booking.user', 'booking.destination', 'booking.facilities']);
        return view('admin.payments.show', compact('payment'));
    }

    // Memulai proses refund (dari sisi internal sistem)
    public function initiateRefund(Request $request, Payment $payment)
    {
        if (!$payment->canBeRefunded()) {
             return redirect()->route('admin.payments.show', $payment)
                              ->with('error', 'Pembayaran ini tidak dapat direfund (Status: ' . $payment->status_label . ', Refund: ' . $payment->refund_status_label . ').');
        }

        $request->validate([
            'refund_amount' => 'required|numeric|min:0.01|max:' . $payment->amount, // Maksimal sejumlah amount payment
            'refund_reason' => 'required|string|max:500',
        ]);

        // Gunakan transaksi database untuk konsistensi
        DB::beginTransaction();
        try {
            $refundAmount = $request->refund_amount;
            $isFullRefund = ($refundAmount == $payment->amount);

            // Update status Payment
            $payment->refund_status = Payment::REFUND_REQUESTED; // Atau langsung 'processing' jika API gateway dipanggil di sini
            $payment->refunded_amount = $refundAmount;
            $payment->refund_reason = $request->refund_reason;
            // Jangan update 'refunded_at' dulu, tunggu konfirmasi selesai

            // Jika full refund, update status payment utama juga? (Opsional, tergantung flow)
            // if ($isFullRefund) {
            //     $payment->status = Payment::STATUS_REFUNDED; // Atau biarkan success tapi refund_status = completed
            // } else {
            //     $payment->status = Payment::STATUS_PARTIALLY_REFUNDED; // Idem
            // }
            $payment->save();

            // TODO (PENTING): Panggil API Payment Gateway untuk proses refund sebenarnya
            // $gatewaySuccess = YourPaymentGatewayService::processRefund($payment->transaction_id, $refundAmount, $request->refund_reason);
            // if (!$gatewaySuccess) {
            //     throw new \Exception("Gagal memproses refund melalui payment gateway.");
            // }

            // Update status Booking jika perlu (misal jadi 'cancelled' jika full refund)
            if ($payment->booking) {
                // Jika refund berhasil diminta/diproses, mungkin booking dibatalkan?
                // $payment->booking->status = Booking::STATUS_CANCELLED;
                // $payment->booking->save();
            }

            DB::commit();

            // Beri feedback ke admin bahwa permintaan refund DICATAT/DIPROSES
            // Konfirmasi final mungkin datang dari webhook atau update manual nanti
            return redirect()->route('admin.payments.show', $payment)
                             ->with('success', 'Permintaan refund sebesar Rp ' . number_format($refundAmount, 0,',','.') . ' telah dicatat/diproses.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal initiate refund payment #{$payment->id}: " . $e->getMessage());
            return redirect()->route('admin.payments.show', $payment)
                             ->with('error', 'Terjadi kesalahan saat memproses refund: ' . $e->getMessage());
        }
    }

     // Update manual status refund (jika tidak ada webhook atau perlu intervensi)
    public function updateRefundStatus(Request $request, Payment $payment)
    {
         // Hanya bisa update jika refund sedang diminta atau diproses
         if (!in_array($payment->refund_status, [Payment::REFUND_REQUESTED, Payment::REFUND_PROCESSING])) {
             return redirect()->route('admin.payments.show', $payment)
                              ->with('error', 'Status refund tidak dapat diubah (Status saat ini: ' . $payment->refund_status_label . ').');
        }

         $request->validate([
            'new_refund_status' => ['required', Rule::in([Payment::REFUND_COMPLETED, Payment::REFUND_FAILED])], // Hanya boleh jadi completed atau failed
            'update_reason' => 'nullable|string|max:255',
        ]);

         DB::beginTransaction();
         try {
             $newStatus = $request->new_refund_status;
             $payment->refund_status = $newStatus;

             if ($newStatus === Payment::REFUND_COMPLETED) {
                 $payment->refunded_at = now();
                 // Sesuaikan status payment utama jika perlu
                 if ($payment->refunded_amount == $payment->amount) {
                     $payment->status = Payment::STATUS_REFUNDED;
                 } else {
                      $payment->status = Payment::STATUS_PARTIALLY_REFUNDED;
                 }
                 // Mungkin update booking status ke cancelled
                 if ($payment->booking && $payment->status === Payment::STATUS_REFUNDED) {
                      $payment->booking->status = Booking::STATUS_CANCELLED;
                      $payment->booking->save();
                 }

             } elseif ($newStatus === Payment::REFUND_FAILED) {
                  // Mungkin kembalikan refunded_amount ke null atau tambahkan catatan
                  $payment->refund_reason = ($payment->refund_reason ? $payment->refund_reason . "\n" : '') . "Update Manual: Refund Gagal. Alasan: " . $request->update_reason;
             }

             $payment->save();
             DB::commit();

              return redirect()->route('admin.payments.show', $payment)
                             ->with('success', 'Status refund berhasil diperbarui menjadi: ' . $payment->refund_status_label);

         } catch (\Exception $e) {
             DB::rollBack();
             Log::error("Gagal update refund status payment #{$payment->id}: " . $e->getMessage());
             return redirect()->route('admin.payments.show', $payment)
                              ->with('error', 'Gagal memperbarui status refund: ' . $e->getMessage());
         }
    }
}