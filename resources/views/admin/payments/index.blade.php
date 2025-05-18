<x-admin-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Manajemen Pembayaran') }}
        </h2>
    </x-slot>

    {{-- Filter Form --}}
    <div class="card mb-4">
        <div class="card-body">
             <form method="GET" action="{{ route('admin.payments.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label">Cari (Trx ID/Kode Booking)</label>
                        <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}" placeholder="ID Transaksi / Kode booking...">
                    </div>
                     <div class="col-md-2">
                        <label for="status" class="form-label">Status Bayar</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Semua Status</option>
                            @foreach($statuses as $value => $label)
                                <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                     <div class="col-md-2">
                        <label for="refund_status" class="form-label">Status Refund</label>
                        <select name="refund_status" id="refund_status" class="form-select">
                            <option value="">Semua Status</option>
                            @foreach($refundStatuses as $value => $label)
                                <option value="{{ $value }}" {{ request('refund_status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                     <div class="col-md-2">
                        <label for="gateway" class="form-label">Gateway</label>
                        <select name="gateway" id="gateway" class="form-select">
                            <option value="">Semua Gateway</option>
                            @foreach($gateways as $gateway)
                                <option value="{{ $gateway }}" {{ request('gateway') == $gateway ? 'selected' : '' }}>{{ $gateway }}</option>
                            @endforeach
                        </select>
                    </div>
                     <div class="col-md-3">
                         <label for="paid_start_date" class="form-label">Tgl Bayar Dari</label>
                         <input type="date" name="paid_start_date" id="paid_start_date" class="form-control" value="{{ request('paid_start_date') }}">
                     </div>
                      <div class="col-md-3">
                         <label for="paid_end_date" class="form-label">Tgl Bayar Sampai</label>
                         <input type="date" name="paid_end_date" id="paid_end_date" class="form-control" value="{{ request('paid_end_date') }}">
                     </div>
                    <div class="col-md-3 align-self-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary ms-2">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if (session('success')) <div class="alert alert-success"> {{ session('success') }} </div> @endif
    @if (session('error')) <div class="alert alert-danger"> {{ session('error') }} </div> @endif

    {{-- Payments Table --}}
    <div class="card">
        <div class="card-body">
             <table class="table table-striped table-hover table-sm">
                <thead>
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Kode Booking</th>
                        <th>User</th>
                        <th>Amount</th>
                        <th>Gateway</th>
                        <th>Status</th>
                        <th>Tgl Bayar</th>
                        <th>Status Refund</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                 <tbody>
                    @forelse ($payments as $payment)
                        <tr>
                            <td>{{ $payment->transaction_id ?? '-' }}</td>
                            <td>
                                 @if($payment->booking)
                                    <a href="{{ route('admin.bookings.show', $payment->booking) }}">{{ $payment->booking->booking_code }}</a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $payment->booking->user->name ?? 'N/A' }}</td>
                            <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td>{{ $payment->payment_gateway ?? '-' }}</td>
                            <td><span class="badge {{ $payment->status_badge_class }}">{{ $payment->status_label }}</span></td>
                            <td>{{ $payment->paid_at ? $payment->paid_at->format('d M Y H:i') : '-' }}</td>
                            <td><span class="badge {{ $payment->refund_status_badge_class }}">{{ $payment->refund_status_label }}</span></td>
                            <td>
                                <a href="{{ route('admin.payments.show', $payment) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data pembayaran ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
             </table>
        </div>
         <div class="card-footer">
            {{ $payments->links() }}
        </div>
    </div>
    {{-- Include Font Awesome if you use icons --}}
    @push('scripts')
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @endpush
</x-admin-layout>