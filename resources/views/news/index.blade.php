<x-public-layout title="Berita & Informasi">
    <!-- Hero Section dengan animasi dan ilustrasi -->
    <div class="hero-section position-relative overflow-hidden bg-primary text-white">
        <div class="container ">
            <div class="row align-items-center">
                <div class="col-lg-8 py-4 animate__animated animate__fadeInLeft">
                    <h1 class="display-4 fw-bold mb-3">Berita & Informasi Terkini</h1>
                    <p class="lead mb-4">Temukan update informasi dan berita penting yang perlu Anda ketahui. Tetap terhubung dengan perkembangan terbaru.</p>
                </div>
                <div class="col-lg-4 d-none d-lg-block animate__animated animate__fadeInRight">
                    <!-- Placeholder for cartoon illustration -->
                    <div class="text-center">
                        <img src="{{ asset('images/bg3.png') }}" class="img-fluid" alt="Wisata Indonesia Illustration">
                    </div>
                </div>
            </div>
        </div>
        <!-- Wave SVG separator -->
        <div class="wave-divider">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 100" preserveAspectRatio="none">
                <path fill="#ffffff" fill-opacity="1" d="M0,32L60,42.7C120,53,240,75,360,74.7C480,75,600,53,720,48C840,43,960,53,1080,58.7C1200,64,1320,64,1380,64L1440,64L1440,100L1380,100C1320,100,1200,100,1080,100C960,100,840,100,720,100C600,100,480,100,360,100C240,100,120,100,60,100L0,100Z"></path>
            </svg>
        </div>
    </div>
    
    <!-- Kategori Filter -->
    <div class="container py-4">

        
        <!-- Section Title -->
        <div class="row">
            <div class="col-12">
                <h2 class="border-start border-primary border-5 ps-3 mb-4">Berita Terbaru</h2>
            </div>
        </div>

        @if ($newsItems->isEmpty())
            <div class="alert alert-info text-center p-5 shadow-sm animate__animated animate__fadeIn">
                <i class="fas fa-newspaper fa-3x mb-3"></i>
                <h4>Belum ada berita yang dipublikasikan saat ini.</h4>
                <p class="mb-0">Silakan kunjungi kembali halaman ini nanti.</p>
            </div>
        @else
            <!-- Featured News -->
            @if($newsItems->isNotEmpty())
                <div class="row mb-5 animate__animated animate__fadeIn">
                    <div class="col-12">
                        <div class="card border-0 bg-light shadow-lg">
                            <div class="row g-0">
                                <div class="col-md-6">
                                    <img src="{{ $newsItems->first()->featured_image_url }}" class="img-fluid rounded-start h-100" style="object-fit: cover;" alt="{{ $newsItems->first()->title }}">
                                </div>
                                <div class="col-md-6">
                                    <div class="card-body p-4 p-lg-5">
                                        <span class="badge bg-primary mb-2">Berita Utama</span>
                                        <h3 class="card-title">{{ $newsItems->first()->title }}</h3>
                                        <p class="card-text">{{ Str::limit(strip_tags($newsItems->first()->excerpt ?: $newsItems->first()->content), 250) }}</p>
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $newsItems->first()->author->name ?? 'Admin' }}</strong>
                                                <small class="text-muted d-block">{{ $newsItems->first()->published_at ? $newsItems->first()->published_at->isoFormat('D MMMM YYYY') : 'N/A' }}</small>
                                            </div>
                                        </div>
                                        <a href="{{ route('news.show', $newsItems->first()->slug) }}" class="btn btn-primary">Baca Selengkapnya <i class="fas fa-arrow-right ms-1"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- News Grid -->
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach ($newsItems->skip(1) as $news)
                    <div class="col animate__animated animate__fadeInUp" style="animation-delay: {{ $loop->index * 0.1 }}s">
                        <div class="card h-100 border-0 shadow-sm hover-card transition">
                            <div class="position-relative">
                                <a href="{{ route('news.show', $news->slug) }}">
                                    <img src="{{ $news->featured_image_url }}" class="card-img-top" alt="{{ $news->title }}" style="height: 220px; object-fit: cover;">
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-primary">Berita</span>
                                    </div>
                                </a>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title mb-2">
                                    <a href="{{ route('news.show', $news->slug) }}" class="text-decoration-none text-dark stretched-link">{{ $news->title }}</a>
                                </h5>
                                <p class="card-text text-muted flex-grow-1"><small>{{ Str::limit(strip_tags($news->excerpt ?: $news->content), 150) }}</small></p>
                                <div class="mt-auto pt-3 border-top">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-alt me-1"></i> {{ $news->published_at ? $news->published_at->isoFormat('D MMM YYYY') : 'N/A' }}
                                        </small>
                                        <small class="text-muted">
                                            <i class="fas fa-user me-1"></i> {{ $news->author->name ?? 'Admin' }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginasi dengan styling yang lebih modern -->
            <div class="mt-5 d-flex justify-content-center">
                <nav>
                    {{ $newsItems->links('pagination::bootstrap-5') }}
                </nav>
            </div>
        @endif
        
    </div>

    <!-- Include CSS and JavaScript -->
    @push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
        /* Custom styling */
        .hover-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }
        .transition {
            transition: all 0.3s ease;
        }
        .hero-section {
            position: relative;
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            overflow: hidden;
        }
        .wave-divider {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
        }
        .wave-divider svg {
            display: block;
            width: calc(100% + 1.3px);
            height: 100px;
        }
        .category-filters .btn {
            border-radius: 20px;
            padding: 8px 20px;
        }
    </style>
    @endpush

    @push('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <script>
        // Optional JavaScript for enhanced interactivity
        document.addEventListener('DOMContentLoaded', function() {
            // Activate animations on scroll
            const animateElements = document.querySelectorAll('.animate__animated');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.visibility = 'visible';
                        entry.target.style.animationPlayState = 'running';
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });
            
            animateElements.forEach(element => {
                element.style.visibility = 'hidden';
                element.style.animationPlayState = 'paused';
                observer.observe(element);
            });
            
            // Category filter functionality
            const filterButtons = document.querySelectorAll('.category-filters .btn');
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    // Add actual filtering logic here if needed
                });
            });
        });
    </script>
    @endpush
</x-public-layout>