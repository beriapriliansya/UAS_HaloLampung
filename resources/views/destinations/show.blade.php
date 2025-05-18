<x-public-layout :title="$destination->name">

    {{-- Hero Section with Destination Image --}}
    <div class="hero-banner position-relative mb-5">
        <img src="{{ $destination->main_photo_url }}" class="img-fluid w-100 object-cover rounded-lg shadow"
            alt="{{ $destination->name }}" style="height: 380px; object-fit: cover;">
        <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-25 rounded-lg"></div>
        <div class="hero-content position-absolute bottom-0 start-0 w-100 p-4 p-md-5 text-white">
            <div class="container">
                <h1 class="display-5 fw-bold text-shadow mb-0">{{ $destination->name }}</h1>
                <div class="d-flex align-items-center mt-2">
                    <i class="fas fa-map-marker-alt me-2"></i>
                    <span class="text-shadow">{{ $destination->location ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row g-4">
            {{-- Main Content Column --}}
            <div class="col-lg-8">
                {{-- Description Card --}}
                <div class="card shadow-sm border-0 rounded-lg mb-4">
                    <div class="card-header bg-white py-3">
                        <h4 class="mb-0"><i class="fas fa-info-circle text-primary me-2"></i>Tentang Destinasi</h4>
                    </div>
                    <div class="card-body">
                        <div class="description-content">
                            {!! nl2br(e($destination->description)) !!}
                        </div>
                    </div>
                </div>

                {{-- Information Cards --}}
                <div class="row g-4 mb-4">
                    {{-- Operating Hours Card --}}
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm rounded-lg">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div
                                        class="icon-circle bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-3">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <h5 class="mb-0">Jam Operasional</h5>
                                </div>
                                <p class="mb-0">
                                    {{ $destination->operating_hours ?? 'Tidak ada informasi jam operasional' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Location Card --}}
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm rounded-lg">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div
                                        class="icon-circle bg-success bg-opacity-10 text-success rounded-circle p-3 me-3">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <h5 class="mb-0">Lokasi</h5>
                                </div>
                                <p class="mb-0">{{ $destination->location ?? 'Tidak ada informasi lokasi' }}</p>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            {{-- Booking Card Column --}}
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow rounded-lg ">
                    <div class="card-header bg-primary text-white py-3">
                        <h4 class="mb-0"><i class="fas fa-ticket-alt me-2"></i>Pesan Tiket</h4>
                    </div>
                    <div class="card-body">
                        <div class="price-display text-center mb-4">
                            <span class="fs-3 fw-bold text-primary">Rp
                                {{ number_format($destination->base_ticket_price, 0, ',', '.') }}</span>
                            <span class="text-muted d-block">per orang (tiket masuk)</span>
                        </div>

                        <div class="facilities-section">
                            <h5 class="border-bottom pb-2 mb-3"><i class="fas fa-plus-circle me-2"></i>Fasilitas
                                Tambahan</h5>
                            @if ($destination->facilities->isNotEmpty())
                                <ul class="list-group list-group-flush mb-4">
                                    @foreach ($destination->facilities as $facility)
                                        <li
                                            class="list-group-item px-0 d-flex justify-content-between align-items-center bg-transparent">
                                            <div>
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                <span>{{ $facility->name }}</span>
                                            </div>
                                            <span class="badge bg-light text-dark">+ Rp
                                                {{ number_format($facility->pivot->price, 0, ',', '.') }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="alert alert-light mb-4">
                                    <i class="fas fa-info-circle me-2 text-muted"></i>
                                    <span class="text-muted">Tidak ada fasilitas tambahan khusus.</span>
                                </div>
                            @endif
                        </div>

                        @auth
                            <a href="{{ route('booking.create', $destination->slug) }}"
                                class="btn btn-primary btn-lg w-100 rounded-pill mb-3">
                                <i class="fas fa-calendar-alt me-2"></i> Pesan Sekarang
                            </a>
                        @else
                            <a href="{{ route('login', ['redirect' => url()->current()]) }}"
                                class="btn btn-primary btn-lg w-100 rounded-pill mb-3">
                                <i class="fas fa-sign-in-alt me-2"></i> Login untuk Memesan
                            </a>
                            <div class="text-center">
                                <small class="text-muted">Belum punya akun? <a href="{{ route('register') }}"
                                        class="text-decoration-none">Daftar di sini</a></small>
                            </div>
                        @endauth
                    </div>
                    <div class="card-footer bg-light py-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-shield-alt text-success me-2"></i>
                            <small class="text-muted">Pembayaran aman & terjamin</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CSS and Font Awesome --}}
    @push('styles')
        <style>
            .text-shadow {
                text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.6);
            }

            .icon-circle {
                width: 45px;
                height: 45px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .gallery-thumbnail {
                overflow: hidden;
                height: 120px;
            }

            .gallery-thumbnail img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.3s ease;
            }

            .gallery-thumbnail img:hover {
                transform: scale(1.05);
            }

            .description-content {
                line-height: 1.7;
            }
        </style>
    @endpush

    @push('scripts')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
            integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
    @endpush

</x-public-layout>
