<x-public-layout title="Riwayat Pemesanan Saya">
    <!-- Header Section -->
    <div class="bg-light border-bottom py-4 mb-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="h3 fw-semibold mb-1 text-primary">
                        <i class="bi bi-calendar2-check me-2"></i>Riwayat Pemesanan Saya
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                            <li class="breadcrumb-item active">Riwayat Pemesanan</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <a href="{{ route('destinations.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-map me-1"></i> Jelajahi Destinasi Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container pb-5">
        <!-- Alert Messages Section -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('info'))
            <div class="alert alert-info alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                <div>{{ session('info') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Booking History Card -->
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3 border-bottom">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0 fw-semibold">Daftar Pemesanan</h5>
                    </div>
                    <!-- Optional: Add filters or search here -->
                    <div class="col-md-4 col-xl-3">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cari booking code..." 
                                   id="booking-search">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="booking-table">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Kode Booking</th>
                                <th>Destinasi</th>
                                <th>Tgl Kunjungan</th>
                                <th class="text-end">Total Harga</th>
                                <th>Status Booking</th>
                                <th>Status Bayar</th>
                                <th>Tgl Pesan</th>
                                <th class="text-center pe-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bookings as $booking)
                                <tr>
                                    <td class="ps-3">
                                        <a href="{{ route('booking.show', $booking->booking_code) }}" 
                                           class="fw-bold text-decoration-none">
                                            {{ $booking->booking_code }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded p-2 me-2 d-none d-md-block">
                                                <i class="bi bi-geo-alt text-primary"></i>
                                            </div>
                                            <div>{{ $booking->destination->name ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-calendar3 text-muted me-2"></i>
                                            {{ $booking->booking_date->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td class="text-end fw-semibold">
                                        Rp {{ number_format($booking->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <span class="badge {{ $booking->status_badge_class }} rounded-pill">
                                            {{ $booking->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $booking->payment_status_badge_class }} rounded-pill">
                                            {{ $booking->payment_status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted d-flex align-items-center">
                                            <i class="bi bi-clock me-1"></i>
                                            {{ $booking->created_at->format('d M Y H:i') }}
                                        </small>
                                    </td>
                                    <td class="text-center pe-3">
                                        <div class="btn-group">
                                            @if ($booking->status == \App\Models\Booking::STATUS_PENDING_PAYMENT)
                                                <a href="{{ route('payment.initiate', $booking->booking_code) }}" 
                                                   class="btn btn-sm btn-success" title="Lanjutkan Pembayaran">
                                                    <i class="bi bi-credit-card me-1"></i> Bayar
                                                </a>
                                            @else
                                                <a href="{{ route('booking.show', $booking->booking_code) }}" 
                                                   class="btn btn-sm btn-info" title="Lihat Detail">
                                                    <i class="bi bi-eye me-1"></i> Detail
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="bi bi-calendar-x text-muted display-4"></i>
                                            <h5 class="mt-3">Belum Ada Pemesanan</h5>
                                            <p class="text-muted mb-4">Anda belum memiliki riwayat pemesanan saat ini.</p>
                                            <a href="{{ route('destinations.index') }}" class="btn btn-primary">
                                                <i class="bi bi-compass me-1"></i> Mulai Jelajahi Destinasi!
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            {{-- Paginasi --}}
            @if ($bookings->hasPages())
            <div class="card-footer bg-white py-3">
                <div class="d-flex justify-content-center">
                    {{ $bookings->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Include Icons --}}
    @push('scripts')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        
        <script>
            // Simple search functionality
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('booking-search');
                const table = document.getElementById('booking-table');
                
                if (searchInput && table) {
                    searchInput.addEventListener('keyup', function() {
                        const searchText = this.value.toLowerCase();
                        const rows = table.querySelectorAll('tbody tr');
                        
                        rows.forEach(row => {
                            const text = row.textContent.toLowerCase();
                            row.style.display = text.includes(searchText) ? '' : 'none';
                        });
                    });
                }
            });
        </script>
    @endpush

    <style>
        /* Badge styling */
        .badge {
            padding: 0.5em 0.8em;
            font-weight: 500;
        }
        
        /* Hover effect on table rows */
        .table tr:hover {
            background-color: rgba(13, 110, 253, 0.04);
        }
        
        /* Button styling */
        .btn-outline-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(13, 110, 253, 0.15);
        }
        
        /* Card styling */
        .card {
            transition: all 0.3s ease;
        }
        
        /* Pagination styling */
        .pagination {
            margin-bottom: 0;
        }
    </style>
</x-public-layout>