<x-public-layout title="Proses Pembayaran">

    <div class="container py-5 text-center">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body p-5">
                        <h2 class="mb-3">Lanjutkan Pembayaran</h2>
                        <p class="mb-4">Anda akan diarahkan ke halaman pembayaran aman Midtrans untuk menyelesaikan transaksi <strong>{{ $booking->booking_code }}</strong> sebesar <strong>Rp {{ number_format($booking->total_amount, 0,',','.') }}</strong>.</p>

                        {{-- Tombol untuk memicu Midtrans Snap --}}
                        <button id="pay-button" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-credit-card me-2"></i> Bayar Sekarang
                        </button>

                        <div class="mt-4">
                             <a href="{{ route('booking.show', $booking->booking_code) }}" class="text-muted">Kembali ke Detail Pemesanan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Include Font Awesome --}}
    @push('scripts')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

        {{-- Midtrans Snap JS --}}
        {{-- Pastikan URL environment benar (sandbox/production) --}}
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $midtransClientKey }}"></script>
        <script type="text/javascript">
            document.getElementById('pay-button').onclick = function(){
                // SnapToken
                snap.pay('{{ $snapToken }}', {
                    // Optional
                    onSuccess: function(result){
                        /* You may add your own implementation here */
                        // alert("payment success!"); console.log(result);
                        // Redirect ke halaman finish kita setelah sukses dari sisi frontend
                        window.location.href = '{{ route("payment.finish") }}?order_id=' + result.order_id + '&status_code=' + result.status_code + '&transaction_status=' + result.transaction_status;
                    },
                    // Optional
                    onPending: function(result){
                        /* You may add your own implementation here */
                        // alert("wating your payment!"); console.log(result);
                         window.location.href = '{{ route("payment.unfinish") }}?order_id=' + result.order_id + '&status_code=' + result.status_code + '&transaction_status=' + result.transaction_status;
                    },
                    // Optional
                    onError: function(result){
                        /* You may add your own implementation here */
                        // alert("payment failed!"); console.log(result);
                         window.location.href = '{{ route("payment.error") }}?order_id=' + result.order_id + '&status_code=' + result.status_code + '&transaction_status=failed'; // Asumsi status failed
                    },
                     // Optional: Dipanggil saat user menutup popup Snap tanpa memilih metode bayar
                    onClose: function(){
                        /* You may add your own implementation here */
                         alert('Anda menutup popup tanpa menyelesaikan pembayaran.');
                          window.location.href = '{{ route("booking.show", $booking->booking_code) }}'; // Kembali ke summary
                    }
                });
            };
        </script>
    @endpush

</x-public-layout>