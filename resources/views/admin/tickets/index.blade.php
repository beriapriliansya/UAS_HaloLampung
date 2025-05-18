<x-admin-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold">
                {{ __('Manajemen Tiket') }}
            </h2>
        </div>
    </x-slot>

    {{-- Filter Form --}}
    <div class="card mb-4">
        <div class="card-body">
             <form method="GET" action="{{ route('admin.tickets.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Cari (Kode Tiket / Kode Booking)</label>
                        <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}" placeholder="Kode tiket atau kode booking...">
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status Tiket</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Semua Status</option>
                            @foreach($statuses as $value => $label)
                                <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                     <div class="col-md-3">
                        <label for="visit_date" class="form-label">Tanggal Kunjungan</label>
                        <input type="date" name="visit_date" id="visit_date" class="form-control" value="{{ request('visit_date') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                        <a href="{{ route('admin.tickets.index') }}" class="btn btn-secondary w-100 mt-1">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if (session('success')) <div class="alert alert-success"> {{ session('success') }} </div> @endif
    @if (session('error')) <div class="alert alert-danger"> {{ session('error') }} </div> @endif

    {{-- Tickets Table --}}
    <div class="card">
        <div class="card-body">
             <table class="table table-striped table-hover table-sm">
                 <thead>
                    <tr>
                        <th>Kode Tiket</th>
                        <th>Kode Booking</th>
                        <th>User</th>
                        <th>Destinasi</th>
                        <th>Tgl Kunjungan</th>
                        <th>Status</th>
                        <th>Waktu Check-in</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                 <tbody>
                    @forelse ($tickets as $ticket)
                        <tr>
                            <td><code>{{ $ticket->ticket_code }}</code></td>
                            <td>
                                @if($ticket->booking)
                                    <a href="{{ route('admin.bookings.show', $ticket->booking) }}">{{ $ticket->booking->booking_code }}</a>
                                @else N/A @endif
                            </td>
                            <td>{{ $ticket->booking->user->name ?? 'N/A' }}</td>
                            <td>{{ $ticket->booking->destination->name ?? 'N/A' }}</td>
                            <td>{{ $ticket->booking?->booking_date?->format('d M Y') ?? '-' }}</td>
                            <td><span class="badge {{ $ticket->status_badge_class }}">{{ $ticket->status_label }}</span></td>
                            <td>{{ $ticket->checked_in_at ? $ticket->checked_in_at->format('d M Y H:i') : '-' }}</td>
                            <td>
                                <a href="{{ route('admin.tickets.show', $ticket) }}" class="btn btn-sm btn-info" title="Lihat Detail & QR">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data tiket ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
             </table>
        </div>
        <div class="card-footer">
            {{ $tickets->links() }}
        </div>
    </div>
    {{-- Include Font Awesome if you use icons --}}
    @push('scripts')
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @endpush
</x-admin-layout>