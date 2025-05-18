<x-admin-layout>
    <x-slot name="header">
       <h2 class="h4 font-weight-bold">
           {{ __('Cek Validitas / Scan Tiket') }}
       </h2>
   </x-slot>

   <div class="row justify-content-center">
       <div class="col-md-6">
           <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.tickets.processCheck') }}" method="POST" id="checkTicketForm">
                        @csrf
                        <div class="mb-3">
                            <label for="ticket_code" class="form-label">Masukkan Kode Tiket</label>
                            <input type="text" name="ticket_code" id="ticket_code" class="form-control form-control-lg @error('ticket_code') is-invalid @enderror" value="{{ old('ticket_code') }}" placeholder="Scan atau ketik kode..." required autofocus>
                            @error('ticket_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                         <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Cek Validitas</button>
                    </form>

                    {{-- Hasil Pengecekan --}}
                    @if(session('check_message'))
                        <div class="alert alert-{{ session('check_status') == 'success' ? 'success' : 'danger' }} mt-4" role="alert">
                           <h5 class="alert-heading">
                               @if(session('check_status') == 'success')
                                   <i class="fas fa-check-circle"></i> Check-in Berhasil!
                               @else
                                   <i class="fas fa-times-circle"></i> Gagal / Tidak Valid
                               @endif
                           </h5>
                           <p>{{ session('check_message') }}</p>
                           {{-- Tampilkan detail booking jika sukses/ditemukan --}}
                           {{-- Anda perlu pass data $ticket dari controller jika ingin menampilkan ini --}}
                           {{-- @if(session('check_status') == 'success' && isset($ticket))
                                <hr>
                                <p class="mb-0">Detail: {{ $ticket->booking->user->name }} - {{ $ticket->booking->destination->name }}</p>
                           @endif --}}
                        </div>
                    @endif
                </div>
           </div>
       </div>
   </div>

   {{-- Optional: Add JS for scanner integration if needed --}}
    {{-- Include Font Awesome if you use icons --}}
   @push('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- Auto-submit form after scan (simple example) --}}
    <script>
        // const ticketInput = document.getElementById('ticket_code');
        // ticketInput.addEventListener('change', function() { // Atau event lain dari scanner
        //     if (this.value.length > 10) { // Asumsi kode tiket cukup panjang
        //         document.getElementById('checkTicketForm').submit();
        //     }
        // });
    </script>
   @endpush
</x-admin-layout>