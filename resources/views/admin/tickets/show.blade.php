<x-admin-layout>
    <x-slot name="header">
         <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold">
                Detail Tiket #{{ $ticket->ticket_code }}
            </h2>
             <a href="{{ route('admin.tickets.index') }}" class="btn btn-secondary btn-sm">Kembali ke Daftar</a>
        </div>
    </x-slot>

    @if (session('success')) <div class="alert alert-success"> {{ session('success') }} </div> @endif
    @if (session('error')) <div class="alert alert-danger"> {{ session('error') }} </div> @endif

     <div class="row">
        {{-- Kolom Kiri: Detail Tiket & QR --}}
        <div class="col-md-6">
            <div class="card mb-4 text-center">
                <div class="card-header">Kode QR Tiket</div>
                <div class="card-body">
                     <div class="mb-3">
                        {!! $qrCode !!} {{-- Tampilkan SVG QR Code --}}
                     </div>
                     <code>{{ $ticket->ticket_code }}</code>
                     <p class="mt-3">Status: <span class="badge fs-6 {{ $ticket->status_badge_class }}">{{ $ticket->status_label }}</span></p>

                     @if($ticket->status == \App\Models\Ticket::STATUS_USED)
                         <p class="text-muted mb-0">
                             <small>Digunakan pada: {{ $ticket->checked_in_at->format('d M Y H:i') }}
                             @if($ticket->checker) oleh: {{ $ticket->checker->name }} @endif
                             </small>
                         </p>
                     @endif

                     {{-- Tombol Check-in Manual --}}
                     @if($ticket->canBeCheckedIn())
                         <form action="{{ route('admin.tickets.checkIn', $ticket) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menandai tiket ini sebagai SUDAH DIGUNAKAN (Check-in)?');" class="mt-3">
                             @csrf
                             @method('PUT')
                             <button type="submit" class="btn btn-success"><i class="fas fa-check-circle"></i> Tandai Sudah Digunakan (Check-in Manual)</button>
                         </form>
                      @elseif($ticket->status == \App\Models\Ticket::STATUS_VALID && !$ticket->booking?->booking_date?->isToday())
                          <p class="text-warning mt-3"><small>Check-in manual hanya bisa dilakukan pada tanggal kunjungan ({{ $ticket->booking?->booking_date?->format('d M Y') }})</small></p>
                     @endif

                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Detail Booking Terkait --}}
        <div class="col-md-6">
             <div class="card mb-4">
                <div class="card-header">Informasi Booking Terkait</div>
                <div class="card-body">
                    @if($ticket->booking)
                     <dl class="row">
                        <dt class="col-sm-4">Kode Booking</dt>
                        <dd class="col-sm-8"><a href="{{ route('admin.bookings.show', $ticket->booking) }}">{{ $ticket->booking->booking_code }}</a></dd>

                         <dt class="col-sm-4">Pemesan</dt>
                        <dd class="col-sm-8">{{ $ticket->booking->user->name ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Destinasi</dt>
                        <dd class="col-sm-8">{{ $ticket->booking->destination->name ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Tanggal Kunjungan</dt>
                        <dd class="col-sm-8">{{ $ticket->booking->booking_date->format('d M Y') }}</dd>

                         <dt class="col-sm-4">Jumlah Orang</dt>
                        <dd class="col-sm-8">{{ $ticket->booking->num_tickets }}</dd>

                         <dt class="col-sm-4">Fasilitas</dt>
                        <dd class="col-sm-8">
                            @forelse($ticket->booking->facilities as $facility)
                                <span class="badge bg-info me-1">{{ $facility->name }} ({{ $facility->pivot->quantity }})</span>
                            @empty
                                -
                            @endforelse
                        </dd>
                     </dl>
                    @else
                        <p class="text-danger">Data booking tidak ditemukan.</p>
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