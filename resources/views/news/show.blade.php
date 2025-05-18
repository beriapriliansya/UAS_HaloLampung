<x-public-layout :title="$newsItem->title">
    <!-- Hero Section with Parallax Effect -->
    <div class="news-hero position-relative overflow-hidden mb-4" style="height: 60vh; min-height: 400px;">
        <div class="parallax-bg position-absolute w-100 h-100"
            style="background: url('{{ $newsItem->featured_image_url ?? '/api/placeholder/1200/800' }}') no-repeat center center; background-size: cover; transform: translateZ(0);">
            <div class="overlay position-absolute w-100 h-100 bg-dark" style="opacity: 0.6;"></div>
        </div>
        <div class="container h-100 d-flex align-items-end">
            <div class="hero-content text-white p-4 mb-4 animate__animated animate__fadeInUp">
                <div class="badge bg-primary mb-2 animate__animated animate__fadeInLeft animate__delay-1s">
                    News
                </div>
                <h1 class="display-4 fw-bold text-shadow mb-2">{{ $newsItem->title }}</h1>
                <div class="d-flex align-items-center">
                    @if ($newsItem->author)
                    @endif
                    <div class="meta-info">
                        <div class="text-white-50">
                            <i class="far fa-calendar-alt me-1"></i>
                            {{ $newsItem->published_at ? $newsItem->published_at->isoFormat('D MMMM YYYY') : 'N/A' }}
                            @if ($newsItem->author)
                                <span class="mx-2">|</span> <i class="far fa-user me-1"></i>
                                {{ $newsItem->author->name }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="row g-5">
            <!-- Main Content Column (8 columns on lg screens) -->
            <div class="col-lg-8 animate__animated animate__fadeIn">
                <article class="card border-0 shadow-lg rounded-3 overflow-hidden">
                    <div class="card-body p-lg-5 p-4">
                        @if ($newsItem->excerpt)
                            <div
                                class="lead fw-bold fs-5 mb-4 text-primary border-start border-5 border-primary ps-3 py-2 bg-light rounded-end animate__animated animate__fadeInLeft animate__delay-1s">
                                {{ $newsItem->excerpt }}
                            </div>
                        @endif

                        <div class="article-content fs-5">
                            {!! $newsItem->content !!}
                        </div>


                        <!-- Share Buttons -->
                        <div class="share-buttons d-flex align-items-center mt-4 py-3 border-top">
                            <span class="me-3 fw-bold text-uppercase text-muted fs-sm">Share this:</span>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-primary rounded-circle" data-bs-toggle="tooltip"
                                    title="Share on Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </button>
                                <button class="btn btn-sm btn-info rounded-circle text-white" data-bs-toggle="tooltip"
                                    title="Share on Twitter">
                                    <i class="fab fa-twitter"></i>
                                </button>
                                <button class="btn btn-sm btn-success rounded-circle" data-bs-toggle="tooltip"
                                    title="Share on WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </button>
                                <button class="btn btn-sm btn-danger rounded-circle" data-bs-toggle="tooltip"
                                    title="Share on Pinterest">
                                    <i class="fab fa-pinterest"></i>
                                </button>
                                <button class="btn btn-sm btn-secondary rounded-circle" data-bs-toggle="tooltip"
                                    title="Copy Link">
                                    <i class="fas fa-link"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </article>
            </div>

            <!-- Sidebar Column (4 columns on lg screens) -->
            <div class="col-lg-4">
                <!-- Sticky Sidebar -->
                <div class="sticky-top pt-lg-5" style="top: 2rem; z-index: 1000;">
                    <!-- Newsletter Card -->
                    <div
                        class="card border-0 shadow-lg rounded-3 overflow-hidden mb-4 animate__animated animate__fadeInRight animate__delay-1s">
                        <div class="card-body p-4 bg-primary text-white">
                            <h4 class="card-title">Subscribe to Newsletter</h4>
                            <p class="card-text">Get the latest news and updates delivered to your inbox.</p>
                            <form>
                                <div class="mb-3">
                                    <input type="email" class="form-control form-control-lg"
                                        placeholder="Your email address">
                                </div>
                                <button type="submit" class="btn btn-light w-100">Subscribe</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <!-- Include Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
        <!-- Include Animate.css -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

        <style>
            /* General Styling */
            body {
                font-family: 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
                line-height: 1.8;
                color: #333;
                background-color: #f8f9fa;
            }

            /* Text shadow for hero text */
            .text-shadow {
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            }

            /* Article Content Styling */
            .article-content {
                line-height: 1.9;
            }

            .article-content p {
                margin-bottom: 1.5rem;
            }

            .article-content img {
                max-width: 100%;
                height: auto;
                border-radius: 8px;
                margin: 1.5rem 0;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            }

            .article-content h2,
            .article-content h3,
            .article-content h4 {
                margin-top: 2rem;
                margin-bottom: 1rem;
            }

            /* Card hover effect */
            .hover-lift {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .hover-lift:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
            }

            /* Hover background effect */
            .hover-bg-light {
                transition: background-color 0.3s ease;
            }

            .hover-bg-light:hover {
                background-color: rgba(0, 0, 0, 0.03);
            }

            /* Parallax effect for hero */
            .parallax-bg {
                background-attachment: fixed;
            }

            /* Button hover effects */
            .btn {
                transition: all 0.3s ease;
            }

            .btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
            }

            /* Custom font sizes */
            .fs-sm {
                font-size: 0.85rem;
            }
        </style>

        <script>
            // Initialize tooltips
            document.addEventListener('DOMContentLoaded', function() {
                // Check if Bootstrap's tooltip function exists
                if (typeof bootstrap !== 'undefined' && typeof bootstrap.Tooltip !== 'undefined') {
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl);
                    });
                }

                // Simple parallax effect
                window.addEventListener('scroll', function() {
                    const parallaxBg = document.querySelector('.parallax-bg');
                    if (parallaxBg) {
                        let scrollPosition = window.pageYOffset;
                        parallaxBg.style.transform = 'translateY(' + scrollPosition * 0.5 + 'px)';
                    }
                });

                // Animate elements when they come into view
                const animateOnScroll = function() {
                    const elements = document.querySelectorAll(
                        '.animate__animated:not(.animate__animated--triggered)');

                    elements.forEach(element => {
                        const elementPosition = element.getBoundingClientRect().top;
                        const windowHeight = window.innerHeight;

                        if (elementPosition < windowHeight - 100) {
                            const animationClasses = Array.from(element.classList).filter(className =>
                                className.startsWith('animate__') &&
                                className !== 'animate__animated' &&
                                !className.startsWith('animate__delay')
                            );

                            // If no animation classes found besides animate__animated, add fadeIn
                            if (animationClasses.length === 0) {
                                element.classList.add('animate__fadeIn');
                            }

                            element.classList.add('animate__animated--triggered');
                        }
                    });
                };

                // Run on load
                animateOnScroll();

                // Run on scroll
                window.addEventListener('scroll', animateOnScroll);

                // Share buttons functionality
                const shareButtons = document.querySelectorAll('.share-buttons button');
                shareButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        // Simple animation effect on click
                        this.classList.add('animate__animated', 'animate__rubberBand');

                        // Remove animation classes after animation completes
                        setTimeout(() => {
                            this.classList.remove('animate__animated', 'animate__rubberBand');
                        }, 1000);

                        // Here you would implement actual sharing functionality
                        // This is just a placeholder for the animation effect
                    });
                });
            });
        </script>
    @endpush
</x-public-layout>
