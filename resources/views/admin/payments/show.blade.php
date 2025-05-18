<x-admin-layout>
    <x-slot name="header">
         <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold">
                Detail Pembayaran (Booking #{{ $payment->booking->booking_code ?? 'N/A' }})
            </h2>
            <div>
                 @if($payment->booking)
                    <a href="{{ route('admin.bookings.show', $payment->booking) }}" class="btn btn-info btn-sm me-2">Lihat Booking</a>
                @endif
                <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary btn-sm">Kembali ke Daftar</a>
            </div>
        </div>
    </x-slot>

     @if (session('success')) <div class="alert alert-success"> {{ session('success') }} </div> @endif
    @if (session('error')) <div class="alert alert-danger"> {{ session('error') }} </div> @endif

    <div class="row">
        <div class="col-md-7">
            {{-- Detail Payment --}}
             <div class="card mb-4">
                <div class="card-header">Informasi Transaksi</div>
                <div class="card-body">
                    <dl class="row">
                         <dt class="col-sm-4">ID Transaksi Gateway</dt>
                        <dd class="col-sm-8">{{ $payment->transaction_id ?? '-' }}</dd>

                        <dt class="col-sm-4">Kode Booking</dt>
                        <dd class="col-sm-8">{{ $payment->booking->booking_code ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Pemesan</dt>
                        <dd class="col-sm-8">{{ $payment->booking->user->name ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Jumlah Pembayaran</dt>
                        <dd class="col-sm-8">Rp {{ number_format($payment->amount, 0, ',', '.') }}</dd>

                         <dt class="col-sm-4">Gateway</dt>
                        <dd class="col-sm-8">{{ $payment->payment_gateway ?? '-' }}</dd>

                        <dt class="col-sm-4">Status Pembayaran</dt>
                        <dd class="col-sm-8"><span class="badge {{ $payment->status_badge_class }}">{{ $payment->status_label }}</span></dd>

                        <dt class="col-sm-4">Waktu Pembayaran</dt>
                        <dd class="col-sm-8">{{ $payment->paid_at ? $payment->paid_at->format('d M Y, H:i:s') : '-' }}</dd>

                        <dt class="col-sm-4">Waktu Dibuat</dt>
                        <dd class="col-sm-8">{{ $payment->created_at->format('d M Y, H:i:s') }}</dd>

                         <dt class="col-sm-4">Waktu Kadaluarsa</dt>
                        <dd class="col-sm-8">{{ $payment->expired_at ? $payment->expired_at->format('d M Y, H:i:s') : '-' }}</dd>
                    </dl>
                </div>
             </div>

             {{-- Detail Gateway (Jika Ada) --}}
              @if($payment->gateway_details)
                <div class="card mb-4">
                    <div class="card-header">Detail dari Gateway</div>
                    <div class="card-body">
                        <pre class="bg-light p-2 rounded"><code>{{ json_encode($payment->gateway_details, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                    </div>
                </div>
            @endif

        </div>

        <div class="col-md-5">
            {{-- Manajemen Refund --}}
            <div class="card mb-4">
                <div class="card-header">Manajemen Refund</div>
                <div class="card-body">
                     <dl class="row mb-3">
                        <dt class="col-sm-5">Status Refund</dt>
                        <dd class="col-sm-7"><span class="badge {{ $payment->refund_status_badge_class }}">{{ $payment->refund_status_label }}</span></dd>

                        @if($payment->refunded_amount)
                            <dt class="col-sm-5">Jumlah Refund</dt>
                            <dd class="col-sm-7">Rp {{ number_format($payment->refunded_amount, 0, ',', '.') }}</dd>
                        @endif
                         @if($payment->refunded_at)
                            <dt class="col-sm-5">Waktu Refund Selesai</dt>
                            <dd class="col-sm-7">{{ $payment->refunded_at->format('d M Y, H:i:s') }}</dd>
                        @endif
                         @if($payment->refund_reason)
                            <dt class="col-sm-5">Alasan/Catatan</dt>
                            <dd class="col-sm-7">{!! nl2br(e($payment->refund_reason)) !!}</dd>
                        @endif
                     </dl>

                     <hr>

                     {{-- Form Initiate Refund --}}
                    @if($payment->canBeRefunded())
                        <h5 class="mb-3">Ajukan Refund</h5>
                        <form action="{{ route('admin.payments.initiateRefund', $payment) }}" method="POST" onsubmit="return confirm('Anda yakin ingin mengajukan refund untuk transaksi ini? Pastikan detail sudah benar.');">
                            @csrf
                            <div class="mb-3">
                                <label for="refund_amount" class="form-label">Jumlah Refund (Rp)</label>
                                <input type="number" step="1000" name="refund_amount" id="refund_amount" class="form-control @error('refund_amount') is-invalid @enderror" value="{{ old('refund_amount', $payment->amount) }}" required min="1" max="{{ $payment->amount }}">
                                <small class="form-text text-muted">Jumlah maksimal: Rp {{ number_format($payment->amount, 0, ',', '.') }}</small>
                                @error('refund_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                             <div class="mb-3">
                                <label for="refund_reason" class="form-label">Alasan Refund</label>
                                <textarea name="refund_reason" id="refund_reason" rows="3" class="form-control @error('refund_reason') is-invalid @enderror" required>{{ old('refund_reason') }}</textarea>
                                @error('refund_reason') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <button type="submit" class="btn btn-warning"><i class="fas fa-undo-alt"></i> Proses Permintaan Refund</button>
                        </form>
                     {{-- Form Update Manual Status Refund --}}
                     @elseif(in_array($payment->refund_status, [\App\Models\Payment::REFUND_REQUESTED, \App\Models\Payment::REFUND_PROCESSING]))
                        <h5 class="mb-3">Update Status Refund Manual</h5>
                         <form action="{{ route('admin.payments.updateRefundStatus', $payment) }}" method="POST" onsubmit="return confirm('Anda yakin ingin mengubah status refund ini secara manual?');">
                            @csrf
                            @method('PUT')
                             <div class="mb-3">
                                 <label for="new_refund_status" class="form-label">Status Baru</label>
                                 <select name="new_refund_status" id="new_refund_status" class="form-select @error('new_refund_status') is-invalid @enderror" required>
                                     <option value="">-- Pilih Status --</option>
                                     <option value="{{ \App\Models\Payment::REFUND_COMPLETED }}" {{ old('new_refund_status') == \App\Models\Payment::REFUND_COMPLETED ? 'selected' : '' }}>Selesai (Completed)</option>
                                     <option value="{{ \App\Models\Payment::REFUND_FAILED }}" {{ old('new_refund_status') == \App\Models\Payment::REFUND_FAILED ? 'selected' : '' }}>Gagal (Failed)</option>
                                 </select>
                                 @error('new_refund_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                             </div>
                             <div class="mb-3">
                                 <label for="update_reason" class="form-label">Catatan Update (Opsional)</label>
                                 <textarea name="update_reason" id="update_reason" rows="2" class="form-control @error('update_reason') is-invalid @enderror">{{ old('update_reason') }}</textarea>
                                 @error('update_reason') <div class="invalid-feedback">{{ $message }}</div> @enderror
                             </div>
                             <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Status Refund</button>
                         </form>
                     @else
                         <p class="text-muted">Tidak ada aksi refund yang dapat dilakukan untuk status saat ini.</p>
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