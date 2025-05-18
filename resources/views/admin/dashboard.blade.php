<x-admin-layout> {{-- Menggunakan layout admin --}}
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="row">
        {{-- Card Statistik --}}
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Destinasi</h5>
                    <p class="card-text fs-4">{{ $totalDestinations }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Pemesanan Hari Ini</h5>
                    <p class="card-text fs-4">{{ $todayBookings }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Pembayaran Sukses (Hari Ini)</h5>
                    <p class="card-text fs-4">{{ $successfulPaymentsToday }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h5 class="card-title">Pembayaran Gagal (Hari Ini)</h5>
                    <p class="card-text fs-4">{{ $failedPaymentsToday }}</p>
                </div>
            </div>
        </div>
        {{-- Tambahkan card lain untuk Statistik Bulanan dll --}}
    </div>

    <div class="row mt-4">
        {{-- Top Destinasi --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Top 5 Destinasi Terlaris</div>
                <ul class="list-group list-group-flush">
                    @forelse ($topDestinations as $dest)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $dest->name }}
                            <span class="badge bg-primary rounded-pill">{{ $dest->bookings_count }}</span>
                        </li>
                    @empty
                        <li class="list-group-item">Belum ada data.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        {{-- Notifikasi Transaksi Terbaru --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Transaksi Terbaru</div>
                <ul class="list-group list-group-flush">
                    @forelse ($recentBookings as $booking)
                        <li class="list-group-item">
                            <small class="text-muted">{{ $booking->created_at->diffForHumans() }}</small><br>
                            <strong>{{ $booking->user->name ?? 'User Deleted' }}</strong> memesan
                            <strong>{{ $booking->destination->name ?? 'Destinasi Deleted' }}</strong>.
                            Status: <span
                                class="badge bg-{{ $booking->payment?->status == 'success' ? 'success' : ($booking->payment?->status == 'pending' ? 'warning' : 'danger') }}">{{ $booking->payment?->status ?? ($booking->payment_status ?? 'N/A') }}</span>
                            {{-- Tambah link ke detail booking jika perlu --}}
                        </li>
                    @empty
                        <li class="list-group-item">Belum ada transaksi terbaru.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

</x-admin-layout>
