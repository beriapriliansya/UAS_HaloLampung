<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Temukan dan pesan tiket untuk destinasi wisata favorit Anda dengan mudah dan cepat.">

    <title>{{ $title ?? config('app.name', 'Laravel') }} - Pesan Tiket Wisata Online</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Owl Carousel -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #f72585;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --gray-color: #6c757d;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        
        /* Button Styling */
        .btn {
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }
        
        /* Card Styling */
        .card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        /* Footer Styling */
        footer {
            background: linear-gradient(135deg, #232526, #414345);
            color: white;
            padding: 60px 0 20px;
            position: relative;
        }
        
        .footer-title {
            position: relative;
            margin-bottom: 25px;
            font-weight: 600;
        }
        
        .footer-title::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -10px;
            width: 40px;
            height: 3px;
            background-color: var(--primary-color);
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
        }
        
        .footer-links li {
            margin-bottom: 12px;
        }
        
        .footer-links a {
            color: #ddd;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .footer-links a:hover {
            color: white;
            padding-left: 5px;
        }
        
        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            margin-right: 10px;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background-color: var(--primary-color);
            transform: translateY(-3px);
        }
        
        .newsletter-form .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            border-radius: 30px 0 0 30px;
            padding: 12px 20px;
        }
        
        .newsletter-form .btn {
            border-radius: 0 30px 30px 0;
            padding: 12px 20px;
        }
        
        .copyright {
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 40px;
        }
        
        /* Custom utilities */
        .text-gradient {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .bg-gradient {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
        }
        
        /* Back to Top Button */
        .back-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 45px;
            height: 45px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            z-index: 999;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
        }
        
        .back-to-top.active {
            opacity: 1;
            visibility: visible;
        }
        
        .back-to-top:hover {
            background-color: var(--secondary-color);
            transform: translateY(-3px);
        }
        
        /* Helper classes */
        .hover-scale {
            transition: transform 0.3s ease;
        }
        
        .hover-scale:hover {
            transform: scale(1.05);
        }
        
        .shadow-custom {
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.07);
        }
        
        /* Animation classes */
        .fade-up {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.5s ease;
        }
        
        .fade-up.show {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <!-- Back to top button -->
    <a href="#" class="back-to-top">
        <i class="fas fa-arrow-up"></i>
    </a>
    
    <div class="d-flex flex-column min-vh-100">
        {{-- Include Navigasi Publik --}}
        @include('layouts.public_navigation')

        <!-- Page Heading (Opsional, jika halaman detail butuh header khusus) -->
        @if (isset($header))
            <header class="bg-white shadow-sm">
                <div class="container py-4">
                    <div data-aos="fade-right">
                        {{ $header }}
                    </div>
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="flex-grow-1">
            {{ $slot }} 
        </main>

        <!-- Footer -->
        <footer class="mt-auto">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-4">
                        <a href="{{ route('home') }}" class="text-white text-decoration-none">
                            <h3 class="mb-3">{{ config('app.name', 'Laravel') }}</h3>
                        </a>
                        <p class="text-light mb-4">Temukan destinasi wisata terbaik di Indonesia dan pesan tiket Anda dengan mudah dan cepat melalui platform kami.</p>
                        <div class="social-links mb-4">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                    
                    <div class="col-sm-6 col-lg-2">
                        <h5 class="footer-title">Destinasi</h5>
                        <ul class="footer-links">
                            <li><a href="#"><i class="fas fa-angle-right me-1"></i>Bali</a></li>
                            <li><a href="#"><i class="fas fa-angle-right me-1"></i>Yogyakarta</a></li>
                            <li><a href="#"><i class="fas fa-angle-right me-1"></i>Raja Ampat</a></li>
                            <li><a href="#"><i class="fas fa-angle-right me-1"></i>Lombok</a></li>
                            <li><a href="#"><i class="fas fa-angle-right me-1"></i>Labuan Bajo</a></li>
                        </ul>
                    </div>
                    
                    <div class="col-sm-6 col-lg-2">
                        <h5 class="footer-title">Informasi</h5>
                        <ul class="footer-links">
                            <li><a href="#"><i class="fas fa-angle-right me-1"></i>Tentang Kami</a></li>
                            <li><a href="#"><i class="fas fa-angle-right me-1"></i>FAQ</a></li>
                            <li><a href="#"><i class="fas fa-angle-right me-1"></i>Syarat & Ketentuan</a></li>
                            <li><a href="#"><i class="fas fa-angle-right me-1"></i>Kebijakan Privasi</a></li>
                            <li><a href="#"><i class="fas fa-angle-right me-1"></i>Hubungi Kami</a></li>
                        </ul>
                    </div>
                    
                    <div class="col-lg-4">
                        <h5 class="footer-title">Berlangganan Newsletter</h5>
                        <p class="text-light mb-4">Dapatkan informasi terbaru dan promo menarik langsung ke email Anda.</p>
                        <form class="newsletter-form d-flex mb-4">
                            <input type="email" class="form-control" placeholder="Email Anda" required>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                        <p class="text-light small">
                            <i class="fas fa-shield-alt me-1"></i> Kami menjamin keamanan data pribadi Anda
                        </p>
                    </div>
                </div>
                
                <div class="text-center copyright">
                    <p class="mb-0">© {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All Rights Reserved.</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Owl Carousel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    
    <!-- AOS Animation Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        $(document).ready(function() {
            // Initialize AOS
            AOS.init({
                duration: 800,
                easing: 'ease',
                once: true
            });
            
            // Navbar scroll behavior
            $(window).scroll(function() {
                if ($(this).scrollTop() > 50) {
                    $('.navbar').addClass('navbar-scrolled');
                    $('.back-to-top').addClass('active');
                } else {
                    $('.navbar').removeClass('navbar-scrolled');
                    $('.back-to-top').removeClass('active');
                }
            });
            
            // Back to top button
            $('.back-to-top').click(function(e) {
                e.preventDefault();
                $('html, body').animate({scrollTop: 0}, 300);
            });
            
            // Initialize any carousels
            if ($('.testimonial-carousel').length) {
                $(".testimonial-carousel").owlCarousel({
                    items: 2,
                    loop: true,
                    margin: 30,
                    nav: false,
                    dots: true,
                    autoplay: true,
                    autoplayTimeout: 5000,
                    responsive: {
                        0: {
                            items: 1
                        },
                        992: {
                            items: 2
                        }
                    }
                });
            }
            
            // Add fade-in animation to elements
            $(window).scroll(function() {
                $('.fade-up').each(function() {
                    var elementPosition = $(this).offset().top;
                    var topOfWindow = $(window).scrollTop();
                    var windowHeight = $(window).height();
                    
                    if (elementPosition < topOfWindow + windowHeight - 100) {
                        $(this).addClass('show');
                    }
                });
            });
        });
    </script>
    
    @stack('scripts')
    <!-- Tambahkan ini di bagian bawah file layout utama, sebelum tag </body> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>