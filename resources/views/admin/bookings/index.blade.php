<x-admin-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Manajemen Pemesanan') }}
        </h2>
    </x-slot>

    {{-- Filter Form --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.bookings.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label">Cari (Kode/Nama)</label>
                        <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}" placeholder="Kode booking / Nama user...">
                    </div>
                    <div class="col-md-3">
                        <label for="destination_id" class="form-label">Destinasi</label>
                        <select name="destination_id" id="destination_id" class="form-select">
                            <option value="">Semua Destinasi</option>
                            @foreach($destinations as $id => $name)
                                <option value="{{ $id }}" {{ request('destination_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="payment_status" class="form-label">Status Bayar</label>
                        <select name="payment_status" id="payment_status" class="form-select">
                            <option value="">Semua Status</option>
                            @foreach($paymentStatuses as $value => $label)
                                <option value="{{ $value }}" {{ request('payment_status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                     <div class="col-md-2">
                        <label for="booking_status" class="form-label">Status Booking</label>
                        <select name="booking_status" id="booking_status" class="form-select">
                            <option value="">Semua Status</option>
                            @foreach($bookingStatuses as $value => $label)
                                <option value="{{ $value }}" {{ request('booking_status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                     <div class="col-md-3">
                         <label for="start_date" class="form-label">Tgl Pesan Dari</label>
                         <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                     </div>
                      <div class="col-md-3">
                         <label for="end_date" class="form-label">Tgl Pesan Sampai</label>
                         <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                     </div>
                    <div class="col-md-3 align-self-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary ms-2">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

     @if (session('success'))
        <div class="alert alert-success"> {{ session('success') }} </div>
    @endif
     @if (session('error'))
        <div class="alert alert-danger"> {{ session('error') }} </div>
    @endif

    {{-- Bookings Table --}}
    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-hover table-sm">
                <thead>
                    <tr>
                        <th>Kode Booking</th>
                        <th>User</th>
                        <th>Destinasi</th>
                        <th>Tgl Kunjungan</th>
                        <th>Total Harga</th>
                        <th>Status Bayar</th>
                        <th>Status Booking</th>
                        <th>Tgl Pesan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $booking)
                        <tr>
                            <td>
                                <a href="{{ route('admin.bookings.show', $booking) }}">{{ $booking->booking_code }}</a>
                            </td>
                            <td>{{ $booking->user->name ?? 'N/A' }}</td>
                            <td>{{ $booking->destination->name ?? 'N/A' }}</td>
                            <td>{{ $booking->booking_date->format('d M Y') }}</td>
                            <td>Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</td>
                            <td><span class="badge {{ $booking->payment_status_badge_class }}">{{ $booking->payment_status_label }}</span></td>
                            <td><span class="badge {{ $booking->status_badge_class }}">{{ $booking->status_label }}</span></td>
                            <td>{{ $booking->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                {{-- Tambahkan icon jika ingin lebih ringkas --}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data pemesanan ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $bookings->links() }}
        </div>
    </div>

    {{-- Include Font Awesome if you use icons --}}
    @push('scripts')
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @endpush

</x-admin-layout>