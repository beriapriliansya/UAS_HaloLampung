<x-public-layout>
    <x-slot name="title">Daftar Destinasi Wisata | Eksplor Indonesia</x-slot>

    <!-- Hero Section dengan Ilustrasi -->
    <section class="hero-section py-5  bg-light rounded-3 position-relative overflow-hidden">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mb-4 mb-lg-0">
                    <h1 class="display-4 fw-bold text-primary mb-3">Jelajahi Keindahan Indonesia</h1>
                    <p class="lead mb-4">Temukan destinasi wisata terbaik dan dapatkan pengalaman perjalanan yang tak
                        terlupakan bersama kami.</p>
                    <div class="d-flex gap-3">
                        <a href="#destinations" class="btn btn-primary btn-lg shadow-sm">Eksplor Sekarang</a>
                    </div>
                </div>
                <div class="col-lg-4 px-4">
                    <img src="{{ asset('images/bg2.png') }}" class="img-fluid" alt="Wisata Indonesia Illustration">
                    <!-- Div ini nantinya bisa digunakan untuk animasi -->
                    <div class="animation-element position-absolute d-none d-lg-block"
                        style="bottom: 20px; right: 20px;">
                        <!-- Elemen animasi akan ditambahkan di sini -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Elemen dekoratif -->
        <div class="position-absolute d-none d-lg-block"
            style="top: -30px; right: -30px; width: 150px; height: 150px; background-color: rgba(13, 110, 253, 0.1); border-radius: 50%;">
        </div>
        <div class="position-absolute d-none d-lg-block"
            style="bottom: -50px; left: -50px; width: 200px; height: 200px; background-color: rgba(13, 110, 253, 0.05); border-radius: 50%;">
        </div>
    </section>

    <!-- Fitur Highlight -->
    <section class="feature-section mb-5">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-md-4">
                    <div class="p-4 bg-white rounded-3 shadow-sm h-100 border-top border-primary border-4">
                        <div class="mb-3 text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor"
                                class="bi bi-geo-alt" viewBox="0 0 16 16">
                                <path
                                    d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A31.493 31.493 0 0 1 8 14.58a31.481 31.481 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94zM8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10z" />
                                <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                            </svg>
                        </div>
                        <h3>Lokasi Terbaik</h3>
                        <p class="text-muted">Kumpulan destinasi terbaik yang sudah terverifikasi di seluruh Indonesia.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 bg-white rounded-3 shadow-sm h-100 border-top border-success border-4">
                        <div class="mb-3 text-success">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor"
                                class="bi bi-ticket-perforated" viewBox="0 0 16 16">
                                <path
                                    d="M4 4.85v.9h1v-.9H4Zm7 0v.9h1v-.9h-1Zm-7 1.8v.9h1v-.9H4Zm7 0v.9h1v-.9h-1Zm-7 1.8v.9h1v-.9H4Zm7 0v.9h1v-.9h-1Zm-7 1.8v.9h1v-.9H4Zm7 0v.9h1v-.9h-1Z" />
                                <path
                                    d="M1.5 3A1.5 1.5 0 0 0 0 4.5V6a.5.5 0 0 0 .5.5 1.5 1.5 0 1 1 0 3 .5.5 0 0 0-.5.5v1.5A1.5 1.5 0 0 0 1.5 13h13a1.5 1.5 0 0 0 1.5-1.5V10a.5.5 0 0 0-.5-.5 1.5 1.5 0 0 1 0-3A.5.5 0 0 0 16 6V4.5A1.5 1.5 0 0 0 14.5 3h-13ZM1 4.5a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 .5.5v1.05a2.5 2.5 0 0 0 0 4.9v1.05a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-1.05a2.5 2.5 0 0 0 0-4.9V4.5Z" />
                            </svg>
                        </div>
                        <h3>Harga Terjangkau</h3>
                        <p class="text-muted">Nikmati harga tiket yang terjangkau dengan berbagai pilihan paket wisata.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 bg-white rounded-3 shadow-sm h-100 border-top border-warning border-4">
                        <div class="mb-3 text-warning">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor"
                                class="bi bi-star" viewBox="0 0 16 16">
                                <path
                                    d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z" />
                            </svg>
                        </div>
                        <h3>Pengalaman Terbaik</h3>
                        <p class="text-muted">Dapatkan pengalaman wisata terbaik dengan panduan dan fasilitas
                            terlengkap.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Daftar Destinasi Section -->
    <section id="destinations" class="destination-section mb-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-lg-6">
                    <h2 class="fw-bold">Temukan Destinasi Impianmu</h2>
                    <p class="text-muted">Koleksi destinasi wisata terbaik dengan pemandangan menakjubkan di Indonesia.
                    </p>
                </div>
            </div>

            <!-- Daftar Destinasi dengan card modern -->
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @forelse ($destinations as $destination)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm overflow-hidden">
                        <div class="position-relative">
                            <img src="{{ $destination->main_photo_url }}" class="card-img-top"
                                alt="{{ $destination->name }}" style="height: 220px; object-fit: cover;">
                            <div class="position-absolute bottom-0 start-0 p-3 w-100 bg-gradient-dark text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-primary rounded-pill px-3 py-2">
                                        <i class="bi bi-tag-fill me-1"></i>
                                        Rp {{ number_format($destination->base_ticket_price, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title fw-bold">{{ $destination->name }}</h5>
                            <p class="card-text text-muted">
                                {{ Str::limit(strip_tags($destination->description), 100) }}
                            </p>
                        </div>
                        <div class="card-footer bg-white border-0 pt-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-geo-alt-fill me-1" viewBox="0 0 16 16">
                                        <path
                                            d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z" />
                                    </svg>
                                    Indonesia
                                </small>
                                <a href="{{ route('destinations.show', $destination->slug) }}"
                                    class="btn btn-primary stretched-link">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info text-center p-5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor"
                            class="bi bi-info-circle mb-3" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                            <path
                                d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
                        </svg>
                        <h4 class="mb-3">Belum ada destinasi yang tersedia</h4>
                        <p class="mb-0">Silakan cek kembali nanti untuk melihat destinasi wisata terbaru dari
                            kami.</p>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Paginasi dengan style yang lebih modern -->
            <div class="mt-5 d-flex justify-content-center">
                {{ $destinations->links() }}
            </div>
        </div>
    </section>

    <!-- Script untuk animasi -->
    <script>
    // Tambahkan Bootstrap Icons
    document.head.innerHTML +=
        '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">';

    // Tambahkan sedikit animasi pada hero section
    document.addEventListener('DOMContentLoaded', function() {
        // Animasi untuk hero image
        const heroImage = document.getElementById('hero-illustration');
        if (heroImage) {
            heroImage.style.transition = 'transform 0.3s ease';
            heroImage.addEventListener('mouseover', function() {
                this.style.transform = 'translateY(-10px)';
            });
            heroImage.addEventListener('mouseout', function() {
                this.style.transform = 'translateY(0)';
            });
        }

        // Animasi untuk kartu destinasi
        const cards = document.querySelectorAll('.destination-section .card');
        cards.forEach(card => {
            card.style.transition = 'transform 0.3s ease, box-shadow 0.3s ease';
            card.addEventListener('mouseover', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.boxShadow = '0 10px 20px rgba(0,0,0,0.1)';
            });
            card.addEventListener('mouseout', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 .125rem .25rem rgba(0,0,0,.075)';
            });
        });
    });
    </script>

    <!-- Tambahkan CSS untuk gradien dan efek latar belakang -->
    <style>
    /* Background Gradient untuk Hero Section */
    .bg-gradient-dark {
        background: linear-gradient(to top, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0) 100%);
    }

    /* Perbaikan untuk tampilan kartu */
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    /* Rating stars */
    .rating .bi-star-fill {
        font-size: 0.8rem;
    }

    /* CTA Section dengan gradien */
    .cta-section {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    }

    /* Animasi untuk tombol */
    .btn {
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
    }

    /* Customisasi pagination */
    .pagination {
        --bs-pagination-active-bg: #0d6efd;
        --bs-pagination-active-border-color: #0d6efd;
    }
    </style>
</x-public-layout>