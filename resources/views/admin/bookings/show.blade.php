<x-admin-layout>
    <x-slot name="header">
         <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold">
                Detail Pemesanan #{{ $booking->booking_code }}
            </h2>
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">Kembali ke Daftar</a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success"> {{ session('success') }} </div>
    @endif
     @if (session('error'))
        <div class="alert alert-danger"> {{ session('error') }} </div>
    @endif

    <div class="row">
        {{-- Kolom Kiri: Detail Utama & Aksi --}}
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">Informasi Pemesanan</div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Kode Booking</dt>
                        <dd class="col-sm-8">{{ $booking->booking_code }}</dd>

                        <dt class="col-sm-4">Tanggal Pesan</dt>
                        <dd class="col-sm-8">{{ $booking->created_at->format('d M Y, H:i:s') }}</dd>

                        <dt class="col-sm-4">Tanggal Kunjungan</dt>
                        <dd class="col-sm-8">{{ $booking->booking_date->format('d M Y') }}</dd>

                        <dt class="col-sm-4">Destinasi</dt>
                        <dd class="col-sm-8">{{ $booking->destination->name ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Pemesan</dt>
                        <dd class="col-sm-8">{{ $booking->user->name ?? 'N/A' }} ({{ $booking->user->email ?? 'N/A' }})</dd>

                        <dt class="col-sm-4">Status Booking</dt>
                        <dd class="col-sm-8"><span class="badge {{ $booking->status_badge_class }}">{{ $booking->status_label }}</span></dd>

                        <dt class="col-sm-4">Status Pembayaran</dt>
                        <dd class="col-sm-8"><span class="badge {{ $booking->payment_status_badge_class }}">{{ $booking->payment_status_label }}</span></dd>

                         <dt class="col-sm-4">Metode Pembayaran</dt>
                        <dd class="col-sm-8">{{ $booking->payment_method ?? '-' }}</dd>

                         @if($booking->notes)
                            <dt class="col-sm-4">Catatan</dt>
                            <dd class="col-sm-8">{{ $booking->notes }}</dd>
                         @endif
                    </dl>
                </div>
            </div>

            <div class="card mb-4">
                 <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Aksi yang Tersedia</span>
                 </div>
                <div class="card-body">
                     {{-- Tombol Aksi muncul kondisional --}}

                     {{-- Verifikasi Manual --}}
                     @if ($booking->payment_status == \App\Models\Booking::PAYMENT_PENDING && $booking->status == \App\Models\Booking::STATUS_PENDING_PAYMENT)
                         <form action="{{ route('admin.bookings.verifyPayment', $booking) }}" method="POST" class="d-inline me-1" onsubmit="return confirm('Anda yakin ingin menandai pembayaran ini sebagai SUKSES secara manual?');">
                             @csrf
                             @method('PUT')
                             <button type="submit" class="btn btn-success btn-sm">
                                 <i class="fas fa-check"></i> Verifikasi Pembayaran Manual
                             </button>
                         </form>
                     @endif

                     {{-- Batalkan Booking --}}
                      @if (!in_array($booking->status, [\App\Models\Booking::STATUS_COMPLETED, \App\Models\Booking::STATUS_CANCELLED, \App\Models\Booking::STATUS_FAILED]))
                         <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST" class="d-inline me-1" onsubmit="return confirm('Anda yakin ingin MEMBATALKAN pemesanan ini? @if($booking->payment_status == \App\Models\Booking::PAYMENT_SUCCESS) Pembayaran sudah sukses, tindakan ini mungkin memerlukan proses refund. @endif');">
                             @csrf
                             @method('PUT')
                             <button type="submit" class="btn btn-danger btn-sm">
                                 <i class="fas fa-times"></i> Batalkan Pemesanan
                             </button>
                         </form>
                     @endif

                     {{-- Kirim Ulang Tiket --}}
                     @if ($booking->status == \App\Models\Booking::STATUS_CONFIRMED)
                        <form action="{{ route('admin.bookings.resendTicket', $booking) }}" method="POST" class="d-inline" onsubmit="return confirm('Kirim ulang notifikasi tiket ke email {{ $booking->user->email ?? 'user' }}?');">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm">
                                <i class="fas fa-envelope"></i> Kirim Ulang Tiket
                            </button>
                        </form>
                     @endif

                     {{-- Jika tidak ada aksi tersedia --}}
                     @if(
                         !($booking->payment_status == \App\Models\Booking::PAYMENT_PENDING && $booking->status == \App\Models\Booking::STATUS_PENDING_PAYMENT) &&
                         in_array($booking->status, [\App\Models\Booking::STATUS_COMPLETED, \App\Models\Booking::STATUS_CANCELLED, \App\Models\Booking::STATUS_FAILED]) &&
                         !($booking->status == \App\Models\Booking::STATUS_CONFIRMED)
                     )
                        <p class="text-muted mb-0">Tidak ada aksi yang dapat dilakukan untuk status pemesanan ini.</p>
                     @endif
                </div>
            </div>

        </div>

        {{-- Kolom Kanan: Detail Harga & Fasilitas --}}
        <div class="col-md-4">
             <div class="card">
                <div class="card-header">Rincian Harga</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Tiket Dasar ({{ $booking->num_tickets }} x Rp {{ number_format($booking->base_ticket_price_at_booking, 0, ',', '.') }})
                            <span>Rp {{ number_format($booking->num_tickets * $booking->base_ticket_price_at_booking, 0, ',', '.') }}</span>
                        </li>

                        @if($booking->facilities->isNotEmpty())
                            <li class="list-group-item">
                                <strong>Fasilitas Tambahan:</strong>
                                <ul class="list-unstyled mt-2 mb-0">
                                     @foreach($booking->facilities as $facility)
                                        <li class="d-flex justify-content-between align-items-center mb-1">
                                            <small>{{ $facility->name }} ({{ $facility->pivot->quantity }} x Rp {{ number_format($facility->pivot->price_at_booking, 0, ',', '.') }})</small>
                                            <small>Rp {{ number_format($facility->pivot->quantity * $facility->pivot->price_at_booking, 0, ',', '.') }}</small>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                             <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Subtotal Fasilitas</strong>
                                <strong>Rp {{ number_format($booking->total_facility_price, 0, ',', '.') }}</strong>
                            </li>
                        @endif

                         <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                            <strong class="fs-5">Total Pembayaran</strong>
                            <strong class="fs-5">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</strong>
                        </li>
                    </ul>

                     {{-- Detail Pembayaran dari Tabel Payment (jika ada) --}}
                    @if($booking->payment)
                    <hr>
                    <h6>Detail Transaksi Pembayaran</h6>
                    <dl class="row mt-2">
                         <dt class="col-sm-5">ID Transaksi</dt>
                        <dd class="col-sm-7">{{ $booking->payment->transaction_id ?? '-' }}</dd> {{-- Asumsi kolom di tabel Payment --}}
                         <dt class="col-sm-5">Waktu Bayar</dt>
                        <dd class="col-sm-7">{{ $booking->payment->paid_at ? $booking->payment->paid_at->format('d M Y H:i') : '-' }}</dd> {{-- Asumsi kolom di tabel Payment --}}
                         {{-- Tambahkan detail lain dari payment jika perlu --}}
                    </dl>
                    @endif

                </div>
            </div>
        </div>
    </div>

     {{-- Include Font Awesome if you use icons --}}
    @push('scripts')
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @endpush

</x-admin-layout>