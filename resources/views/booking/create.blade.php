<x-public-layout :title="'Pesan Tiket - ' . $destination->name">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Form Pemesanan: {{ $destination->name }}</h4>
                    </div>
                    <div class="card-body p-4">

                        {{-- Tampilkan error umum --}}
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        {{-- Tampilkan error validasi --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('booking.store', $destination->slug) }}" method="POST" id="bookingForm">
                            @csrf
                            {{-- Simpan base price untuk JS --}}
                            <input type="hidden" id="baseTicketPrice" value="{{ $destination->base_ticket_price }}">

                            {{-- Pilih Tanggal --}}
                            <div class="mb-4">
                                <label for="booking_date" class="form-label fs-5"><i class="fas fa-calendar-alt me-2"></i>Pilih Tanggal Kunjungan</label>
                                <input type="date"
                                       name="booking_date"
                                       id="booking_date"
                                       class="form-control form-control-lg @error('booking_date') is-invalid @enderror"
                                       value="{{ old('booking_date') }}"
                                       min="{{ now()->toDateString() }}" {{-- Minimal hari ini --}}
                                       required>
                                @error('booking_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Jumlah Tiket --}}
                            <div class="mb-4">
                                <label for="num_tickets" class="form-label fs-5"><i class="fas fa-ticket-alt me-2"></i>Jumlah Tiket Masuk</label>
                                <input type="number"
                                       name="num_tickets"
                                       id="num_tickets"
                                       class="form-control form-control-lg @error('num_tickets') is-invalid @enderror"
                                       value="{{ old('num_tickets', 1) }}"
                                       min="1"
                                       required>
                                <small class="form-text text-muted">Harga tiket masuk: Rp {{ number_format($destination->base_ticket_price, 0, ',', '.') }} / orang</small>
                                @error('num_tickets') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Fasilitas Tambahan --}}
                            @if($destination->facilities->isNotEmpty())
                                <div class="mb-4">
                                    <label class="form-label fs-5"><i class="fas fa-plus-circle me-2"></i>Pilih Fasilitas Tambahan (Opsional)</label>
                                    <div class="list-group">
                                        @foreach($destination->facilities as $facility)
                                            <label class="list-group-item d-flex align-items-center">
                                                <input class="form-check-input me-3 facility-checkbox"
                                                       type="checkbox"
                                                       name="facilities[{{ $loop->index }}][id]"
                                                       value="{{ $facility->id }}"
                                                       data-price="{{ $facility->pivot->price }}"
                                                       data-index="{{ $loop->index }}"
                                                       {{ old("facilities.{$loop->index}.id") ? 'checked' : '' }}>
                                                <div class="flex-grow-1">
                                                    {{ $facility->name }}
                                                    <small class="text-muted">(+ Rp {{ number_format($facility->pivot->price, 0, ',', '.') }})</small>
                                                </div>
                                                {{-- Input quantity muncul jika checkbox dipilih --}}
                                                <input type="number"
                                                       name="facilities[{{ $loop->index }}][quantity]"
                                                       class="form-control facility-quantity ms-3 @error("facilities.{$loop->index}.quantity") is-invalid @enderror"
                                                       min="1"
                                                       value="{{ old("facilities.{$loop->index}.quantity", 1) }}"
                                                       data-index="{{ $loop->index }}"
                                                       style="width: 80px; {{ old("facilities.{$loop->index}.id") ? '' : 'display: none;' }}" {{-- Sembunyikan awal --}}
                                                       {{ old("facilities.{$loop->index}.id") ? '' : 'disabled' }}> {{-- Disable awal --}}
                                                 @error("facilities.{$loop->index}.quantity") <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Catatan --}}
                            <div class="mb-4">
                                <label for="notes" class="form-label"><i class="fas fa-sticky-note me-2"></i>Catatan Tambahan (Opsional)</label>
                                <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                                @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <hr>

                            {{-- Rincian Harga Dinamis --}}
                            <div class="mb-4 p-3 bg-light rounded">
                                <h5 class="mb-3">Rincian Harga</h5>
                                <dl class="row mb-1">
                                    <dt class="col-sm-7">Subtotal Tiket Masuk (<span id="ticketCountDisplay">1</span> orang)</dt>
                                    <dd class="col-sm-5 text-end">Rp <span id="ticketSubtotalDisplay">0</span></dd>
                                </dl>
                                <dl class="row mb-1">
                                    <dt class="col-sm-7">Subtotal Fasilitas</dt>
                                    <dd class="col-sm-5 text-end">Rp <span id="facilitySubtotalDisplay">0</span></dd>
                                </dl>
                                <hr class="my-2">
                                <dl class="row fs-5 fw-bold">
                                    <dt class="col-sm-7">Total Pembayaran</dt>
                                    <dd class="col-sm-5 text-end">Rp <span id="grandTotalDisplay">0</span></dd>
                                </dl>
                            </div>

                            {{-- Tombol Submit --}}
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-check-circle me-2"></i> Lanjut ke Konfirmasi
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Include Font Awesome --}}
    @push('scripts')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const bookingForm = document.getElementById('bookingForm');
                const baseTicketPrice = parseFloat(document.getElementById('baseTicketPrice').value) || 0;
                const numTicketsInput = document.getElementById('num_tickets');
                const facilityCheckboxes = document.querySelectorAll('.facility-checkbox');
                const facilityQuantities = document.querySelectorAll('.facility-quantity');

                const ticketCountDisplay = document.getElementById('ticketCountDisplay');
                const ticketSubtotalDisplay = document.getElementById('ticketSubtotalDisplay');
                const facilitySubtotalDisplay = document.getElementById('facilitySubtotalDisplay');
                const grandTotalDisplay = document.getElementById('grandTotalDisplay');

                // Function to format number as currency
                function formatCurrency(amount) {
                    return amount.toLocaleString('id-ID');
                }

                // Function to calculate and update totals
                function calculateTotal() {
                    let numTickets = parseInt(numTicketsInput.value) || 0;
                    if (numTickets < 1) numTickets = 1; // Min 1 ticket

                    let ticketSubtotal = baseTicketPrice * numTickets;
                    let facilitySubtotal = 0;

                    facilityCheckboxes.forEach(checkbox => {
                        const index = checkbox.dataset.index;
                        const quantityInput = document.querySelector(`.facility-quantity[data-index="${index}"]`);

                        if (checkbox.checked) {
                            quantityInput.style.display = 'inline-block'; // Tampilkan input quantity
                            quantityInput.disabled = false;
                            let price = parseFloat(checkbox.dataset.price) || 0;
                            let quantity = parseInt(quantityInput.value) || 0;
                             if (quantity < 1) quantity = 1; // Min 1 quantity jika dipilih
                            quantityInput.value = quantity; // Update value jika diubah ke < 1
                            facilitySubtotal += price * quantity;
                        } else {
                            quantityInput.style.display = 'none'; // Sembunyikan input quantity
                             quantityInput.disabled = true;
                             quantityInput.value = 1; // Reset quantity
                        }
                    });

                    let grandTotal = ticketSubtotal + facilitySubtotal;

                    // Update display
                    ticketCountDisplay.textContent = numTickets;
                    ticketSubtotalDisplay.textContent = formatCurrency(ticketSubtotal);
                    facilitySubtotalDisplay.textContent = formatCurrency(facilitySubtotal);
                    grandTotalDisplay.textContent = formatCurrency(grandTotal);
                }

                // Add event listeners
                numTicketsInput.addEventListener('input', calculateTotal);
                facilityCheckboxes.forEach(checkbox => checkbox.addEventListener('change', calculateTotal));
                facilityQuantities.forEach(input => input.addEventListener('input', calculateTotal));

                // Initial calculation on page load
                calculateTotal();
            });
        </script>
    @endpush

</x-public-layout>