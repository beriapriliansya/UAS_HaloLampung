<x-public-layout>
    <!-- Hero Section with Cartoon Illustration and Animation -->
    <section class="hero-section position-relative overflow-hidden">
        <div class="container-fluid px-0">
            <div class="row g-0">
                <div class="col-lg-7 d-flex align-items-center hero-content-wrapper py-3 px-2 px-md-5">
                    <div class="hero-content" data-aos="fade-right" data-aos-duration="1000">
                        <span
                            class="badge bg-primary-subtle text-primary fw-semibold rounded-pill px-3 py-2 mb-3">Petualangan
                            Baru Menunggumu</span>
                        <h1 class="display-4 fw-bold mb-3">Temukan Keajaiban <span class="text-primary">Lampung</span>
                            Bersama Kami</h1>
                        <p class="lead mb-4">Jelajahi destinasi menakjubkan, nikmati pengalaman tak terlupakan, dan
                            ciptakan kenangan indah di setiap perjalananmu.</p>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('destinations.index') }}"
                                class="btn btn-primary btn-lg rounded-pill px-4 fw-semibold">
                                <i class="fas fa-compass me-2"></i>Mulai Petualangan
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 hero-illustration-wrapper position-relative ">
                    <!-- Placeholder for your cartoon illustration -->
                    <div class="hero-illustration" data-aos="fade-left" data-aos-duration="1000">
                        <img src="{{ asset('images/bg.png') }}" class="img-fluid" alt="Wisata Indonesia Illustration">
                    </div>
                    <!-- Floating elements animation -->
                    <div class="floating-element floating-1 position-absolute">
                        <span class="badge bg-warning text-dark rounded-pill px-3 ">
                            <i class="fas fa-camera me-1"></i>Tempat Instagramable
                        </span>
                    </div>
                    <div class="floating-element floating-2 position-absolute">
                        <span class="badge bg-info text-white rounded-pill px-3 py-2">
                            <i class="fas fa-umbrella-beach me-1"></i>Pantai Eksotis
                        </span>
                    </div>
                    <div class="floating-element floating-3 position-absolute">
                        <span class="badge bg-success text-white rounded-pill px-3 py-2">
                            <i class="fas fa-mountain me-1"></i>Petualangan Alam
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Destinasi Populer Section with Improved UI -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold display-6">Jelajahi Destinasi Kamu</h2>
                <p class="text-muted mx-auto" style="max-width: 600px;">Pengalaman luar biasa dari mereka yang telah
                    menjelajahi keindahan Indonesia bersama kami</p>
            </div>
            <div class="row mb-4 align-items-center">
                <div class="col-md-6" data-aos="fade-right">
                    <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2 mb-2">Pilihan
                        Terbaik</span>
                    <h2 class="fw-bold display-6 mb-0">
                        <i class="fas fa-fire text-danger me-2"></i>Destinasi Populer
                    </h2>
                </div>
                <div class="col-md-6 text-md-end" data-aos="fade-left">

                    <a href="{{ route('destinations.index') }}"
                        class="btn btn-link text-decoration-none p-0 fw-semibold">
                        Lihat Semua Destinasi<i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @forelse ($destinations as $destination)
                    <div class="col" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden hover-card">
                            <div class="position-relative">
                                <img src="{{ $destination->main_photo_url }}" class="card-img-top"
                                    alt="{{ $destination->name }}" style="height: 240px; object-fit: cover;">
                                <div class="position-absolute bottom-0 start-0 bg-gradient-dark w-100 text-white p-3">
                                    <h5 class="card-title mb-0 fw-bold">{{ $destination->name }}</h5>
                                    <div class="d-flex">
                                        <span class="me-2"><i
                                                class="fas fa-map-marker-alt me-1 text-warning"></i>{{ $destination->location ?? 'Indonesia' }}</span>
                                    </div>
                                </div>
                                <div class="position-absolute top-0 end-0 m-3">
                                    <span class="badge bg-primary rounded-pill px-3 py-2 fw-semibold">
                                        Rp {{ number_format($destination->base_ticket_price, 0, ',', '.') }}
                                    </span>
                                </div>
                               
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <span class="badge bg-success-subtle text-success me-1 rounded-pill px-3 py-2">
                                        <i class="fas fa-check-circle me-1"></i>Terverifikasi
                                    </span>
                                    <span class="badge bg-info-subtle text-info rounded-pill px-3 py-2">
                                        <i class="fas fa-umbrella-beach me-1"></i>Wisata Alam
                                    </span>
                                </div>
                                <p class="card-text">{{ Str::limit(strip_tags($destination->description), 100) }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div>
                                        <span class="text-muted small"><i class="fas fa-clock me-1"></i>Buka Setiap
                                            Hari</span>
                                    </div>
                                    <a href="{{ route('destinations.show', $destination->slug) }}"
                                        class="btn btn-outline-primary rounded-pill">
                                        <i class="fas fa-info-circle me-1"></i>Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center py-5">
                            <i class="fas fa-info-circle fa-3x mb-3"></i>
                            <p class="mb-0">Belum ada destinasi yang tersedia.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Berita & Informasi Terbaru with Improved UI -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge bg-info-subtle text-info rounded-pill px-3 py-2 mb-2">Info Terkini</span>
                <h2 class="fw-bold display-6"><i class="fas fa-newspaper text-primary me-2"></i>Berita & Informasi
                    Terbaru</h2>
                <p class="text-muted mx-auto" style="max-width: 700px;">Temukan informasi terbaru seputar destinasi
                    wisata, tips perjalanan, dan berita menarik lainnya untuk petualangan terbaik Anda.</p>
            </div>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @forelse ($latestNews as $news)
                    <div class="col" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden hover-card">
                            <a href="{{ route('news.show', $news->slug) }}" class="text-decoration-none">
                                <div class="position-relative">
                                    <img src="{{ $news->featured_image_url }}" class="card-img-top"
                                        alt="{{ $news->title }}" style="height: 220px; object-fit: cover;">
                                    <div class="position-absolute top-0 start-0 m-3">
                                        <span class="badge bg-danger rounded-pill px-3 py-2">Berita Terbaru</span>
                                    </div>
                                    <div class="position-absolute bottom-0 start-0 w-100 p-3 bg-gradient-dark">
                                        <div class="d-flex text-white small">
                                            <span class="me-3"><i
                                                    class="far fa-calendar-alt me-1"></i>{{ $news->published_at ? $news->published_at->isoFormat('D MMM YYYY') : 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="fas fa-user text-primary"></i>
                                    </div>
                                    <div>
                                        <small
                                            class="text-muted">{{ $news->author ? $news->author->name : 'Admin' }}</small>
                                    </div>
                                    <div class="ms-auto">
                                        <span class="badge bg-info-subtle text-info rounded-pill">Artikel</span>
                                    </div>
                                </div>
                                <h5 class="card-title fw-bold">
                                    <a href="{{ route('news.show', $news->slug) }}"
                                        class="text-decoration-none text-dark hover-primary">{{ $news->title }}</a>
                                </h5>
                                <p class="card-text text-muted">
                                    {{ Str::limit(strip_tags($news->excerpt ?: $news->content), 100) }}</p>
                                <a href="{{ route('news.show', $news->slug) }}"
                                    class="btn btn-outline-primary rounded-pill mt-2">
                                    <i class="fas fa-book-reader me-1"></i>Baca selengkapnya
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center py-5">
                            <i class="fas fa-info-circle fa-3x mb-3"></i>
                            <p class="mb-0">Belum ada berita terbaru yang tersedia.</p>
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="text-center mt-5" data-aos="fade-up">
                <a href="{{ route('news.index') }}" class="btn btn-primary btn-lg rounded-pill px-4 fw-semibold">
                    Lihat Semua Berita<i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </section>



    <!-- Newsletter Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="card border-0 shadow-lg rounded-4 p-4" data-aos="zoom-in">
                <div class="card-body text-center">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <h3 class="fw-bold mb-3">Dapatkan Informasi Terbaru</h3>
                            <p class="text-muted mb-4">Berlangganan newsletter kami untuk mendapatkan update terbaru
                                tentang promo, destinasi baru, dan tips perjalanan</p>
                            <form action="{{ route('newsletter.subscribe') }}" method="POST" id="newsletterForm"
                                class="d-flex flex-column flex-sm-row gap-2">
                                @csrf
                                <div class="form-floating flex-grow-1">
                                    <input type="email" name="email" class="form-control rounded-pill"
                                        id="email" placeholder="Alamat Email" required>
                                    <label for="email"><i class="fas fa-envelope me-2"></i>Alamat Email</label>
                                </div>
                                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold">
                                    <i class="fas fa-paper-plane me-2"></i>Berlangganan
                                </button>

                                {{-- Tampilkan pesan sukses/error spesifik newsletter --}}
                                @if (session('success_newsletter'))
                                    <div class="alert alert-success p-2 small w-100 mt-2">
                                        {{ session('success_newsletter') }}</div>
                                @endif
                                @if (session('error_newsletter'))
                                    <div class="alert alert-danger p-2 small w-100 mt-2">
                                        {{ session('error_newsletter') }}</div>
                                @endif
                                @error('email', 'default')
                                    <div class="alert alert-danger p-2 small w-100 mt-2">{{ $message }}</div>
                                @enderror
                            </form>

                            <small class="text-muted mt-2 d-block">Kami akan mengirimkan email maksimal 2x dalam
                                sebulan</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CSS Tambahan -->
    <style>
        /* Improved Hover Effects */
        .hover-card {
            transition: all 0.4s ease;
        }

        .hover-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, .175) !important;
        }

        /* Gradient Backgrounds */
        .bg-gradient-dark {
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0));
        }

        /* Floating Animation for Hero Elements */
        .floating-element {
            animation-name: floating;
            animation-duration: 3s;
            animation-iteration-count: infinite;
            animation-timing-function: ease-in-out;
        }

        .floating-1 {
            top: 15%;
            right: 10%;
            animation-delay: 0s;
        }

        .floating-2 {
            top: 45%;
            right: 15%;
            animation-delay: 1s;
        }

        .floating-3 {
            top: 70%;
            right: 25%;
            animation-delay: 2s;
        }

        @keyframes floating {
            0% {
                transform: translate(0, 0px);
            }

            50% {
                transform: translate(0, 15px);
            }

            100% {
                transform: translate(0, -0px);
            }
        }

        /* Hero Section Styles */
        .hero-section {
            position: relative;
            min-height: 500px;
            overflow: hidden;
        }

        .hero-content-wrapper {
            min-height: 500px;
        }

        .hero-illustration-wrapper {
            min-height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Avatar Group */
        .avatar-group {
            display: flex;
        }

        .avatar-group .avatar {
            margin-left: -10px;
            z-index: 1;
        }

        .avatar-group .avatar:first-child {
            margin-left: 0;
        }

        .avatar {
            width: 32px;
            height: 32px;
            position: relative;
        }

        /* Link hover effect */
        .hover-primary:hover {
            color: var(--bs-primary) !important;
        }

        /* Responsive adjustments */
        @media (max-width: 991.98px) {
            .hero-illustration-wrapper {
                min-height: 400px;
            }

            .hero-content-wrapper {
                min-height: auto;
                padding-top: 3rem;
                padding-bottom: 3rem;
            }
        }
    </style>

    <!-- JavaScript for Animations -->
    <script>
        // Initialize AOS (Animate on Scroll)
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS animation library if it exists
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 800,
                    easing: 'ease-in-out',
                    once: true,
                    mirror: false
                });
            }

            // Counter animation
            const counters = document.querySelectorAll('.counter');
            if (counters.length) {
                counters.forEach(counter => {
                    const updateCount = () => {
                        const target = +counter.getAttribute('data-target') || parseInt(counter
                            .innerText.replace(/[^\d]/g, ''));
                        const count = parseInt(counter.innerText.replace(/[^\d]/g, ''));
                        const increment = target / 100;

                        if (count < target) {
                            counter.innerText = Math.ceil(count + increment);
                            setTimeout(updateCount, 40);
                        } else {
                            counter.innerText = target;
                        }
                    };
                    updateCount();
                });
            }

            // Initialize Bootstrap components
            // Tooltips
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            if (tooltipTriggerList.length && typeof bootstrap !== 'undefined') {
                const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(
                    tooltipTriggerEl));
            }

            // Handle smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;

                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        targetElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Simple parallax effect for hero section
            const handleParallax = () => {
                const scrollPosition = window.pageYOffset;
                const heroElements = document.querySelectorAll('.parallax-effect');

                heroElements.forEach(element => {
                    const speed = element.getAttribute('data-speed') || 0.3;
                    element.style.transform = `translateY(${scrollPosition * speed}px)`;
                });
            };

            window.addEventListener('scroll', handleParallax);

            // Mobile menu toggle
            const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', function() {
                    document.body.classList.toggle('mobile-menu-open');
                });
            }

            // Add active class to menu items based on current page
            const currentLocation = location.pathname;
            const menuItems = document.querySelectorAll('.nav-link');

            menuItems.forEach(item => {
                if (item.getAttribute('href') === currentLocation) {
                    item.classList.add('active');
                }
            });

            // Image lazy loading
            if ('loading' in HTMLImageElement.prototype) {
                const images = document.querySelectorAll('img[loading="lazy"]');
                images.forEach(img => {
                    img.src = img.dataset.src;
                });
            } else {
                // Fallback for browsers that don't support lazy loading
                const script = document.createElement('script');
                script.src = 'https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js';
                document.body.appendChild(script);
            }
        });
    </script>
</x-public-layout>
