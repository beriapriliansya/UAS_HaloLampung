<x-public-layout :title="'Konfirmasi Pemesanan - ' . $booking->booking_code">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                         {{-- Tampilkan pesan sukses jika ada --}}
                         @if(session('success'))
                            <h4 class="mb-0"><i class="fas fa-check-circle me-2"></i> {{ session('success') }}</h4>
                         @else
                             <h4 class="mb-0"><i class="fas fa-info-circle me-2"></i> Ringkasan Pemesanan Anda</h4>
                         @endif
                    </div>
                    <div class="card-body p-4">

                        <div class="alert alert-warning">
                            <strong>Penting:</strong> Ini adalah ringkasan pemesanan Anda. Mohon periksa kembali sebelum melanjutkan ke pembayaran. Pemesanan belum aktif hingga pembayaran berhasil.
                        </div>

                        <h5 class="mb-3">Detail Pemesanan (Kode: {{ $booking->booking_code }})</h5>
                         <dl class="row">
                            <dt class="col-sm-4">Destinasi</dt>
                            <dd class="col-sm-8">{{ $booking->destination->name }}</dd>

                            <dt class="col-sm-4">Tanggal Kunjungan</dt>
                            <dd class="col-sm-8">{{ $booking->booking_date->format('d M Y') }}</dd>

                            <dt class="col-sm-4">Pemesan</dt>
                            <dd class="col-sm-8">{{ $booking->user->name }} ({{ $booking->user->email }})</dd>

                             <dt class="col-sm-4">Jumlah Tiket</dt>
                            <dd class="col-sm-8">{{ $booking->num_tickets }} Orang</dd>

                            @if($booking->notes)
                            <dt class="col-sm-4">Catatan</dt>
                            <dd class="col-sm-8">{{ $booking->notes }}</dd>
                            @endif

                            <dt class="col-sm-4">Status</dt>
                            <dd class="col-sm-8"><span class="badge {{ $booking->status_badge_class }}">{{ $booking->status_label }}</span></dd>

                             <dt class="col-sm-4">Pembayaran</dt>
                            <dd class="col-sm-8"><span class="badge {{ $booking->payment_status_badge_class }}">{{ $booking->payment_status_label }}</span></dd>
                        </dl>

                        <hr>

                        <h5 class="mb-3">Rincian Biaya</h5>
                         <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                Tiket Masuk ({{ $booking->num_tickets }} x Rp {{ number_format($booking->base_ticket_price_at_booking, 0, ',', '.') }})
                                <span>Rp {{ number_format($booking->num_tickets * $booking->base_ticket_price_at_booking, 0, ',', '.') }}</span>
                            </li>

                            @if($booking->facilities->isNotEmpty())
                                <li class="list-group-item px-0 pt-3">
                                    <strong>Fasilitas Tambahan:</strong>
                                    <ul class="list-unstyled mt-2 mb-0">
                                         @foreach($booking->facilities as $facility)
                                            <li class="d-flex justify-content-between align-items-center mb-1 ps-3">
                                                <small>{{ $facility->name }} ({{ $facility->pivot->quantity }} x Rp {{ number_format($facility->pivot->price_at_booking, 0, ',', '.') }})</small>
                                                <small>Rp {{ number_format($facility->pivot->quantity * $facility->pivot->price_at_booking, 0, ',', '.') }}</small>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                                 <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <strong>Subtotal Fasilitas</strong>
                                    <strong>Rp {{ number_format($booking->total_facility_price, 0, ',', '.') }}</strong>
                                </li>
                            @endif

                             <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-light fs-5 fw-bold">
                                <span>Total Pembayaran</span>
                                <span>Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                            </li>
                        </ul>

                        <hr>

                         {{-- Tombol Pembayaran --}}
                         @if($booking->status === \App\Models\Booking::STATUS_PENDING_PAYMENT)
                            <div class="d-grid">
                                {{-- Tombol ini akan memicu proses pembayaran (Langkah Berikutnya) --}}
                                <a href="{{ route('payment.initiate', $booking->booking_code) }}"
                                    class="btn btn-success btn-lg">
                                     <i class="fas fa-credit-card me-2"></i> Lanjut ke Pembayaran
                                 </a>
                            </div>
                             <small class="d-block text-center mt-2 text-muted">Anda akan diarahkan ke halaman pembayaran.</small>
                         @else
                              <div class="alert alert-info">
                                 Status pemesanan ini adalah '{{ $booking->status_label }}'. Pembayaran tidak dapat dilakukan.
                                 {{-- Tambahkan link ke riwayat booking --}}
                                 {{-- <a href="{{ route('my.bookings') }}">Lihat Riwayat Pemesanan</a> --}}
                             </div>
                         @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

     {{-- Include Font Awesome --}}
    @push('scripts')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    @endpush

</x-public-layout>