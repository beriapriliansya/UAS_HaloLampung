<nav class="navbar navbar-expand-lg sticky-top" 
    style="background: linear-gradient(to right, #1a73e8, #0d47a1); box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
    <div class="container">
        <!-- Logo and Brand -->
        <a class="navbar-brand fw-bold text-white d-flex align-items-center" href="{{ route('home') }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor"
                class="bi bi-airplane me-2" viewBox="0 0 16 16">
                <path
                    d="M6.428 1.151C6.708.591 7.213 0 8 0s1.292.592 1.572 1.151C9.861 1.73 10 2.431 10 3v3.691l5.17 2.585a1.5 1.5 0 0 1 .83 1.342V12a.5.5 0 0 1-.582.493l-5.507-.918-.375 2.253 1.318 1.318A.5.5 0 0 1 10.5 16h-5a.5.5 0 0 1-.354-.854l1.319-1.318-.376-2.253-5.507.918A.5.5 0 0 1 0 12v-1.382a1.5 1.5 0 0 1 .83-1.342L6 6.691V3c0-.568.14-1.271.428-1.849Z" />
            </svg>
            <span class="fs-4">{{ config('app.name', 'Wisata Booking') }}</span>
        </a>

        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#publicNavbar" aria-controls="publicNavbar" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon bg-light rounded"></span>
        </button>

        <div class="collapse navbar-collapse" id="publicNavbar">
            <!-- Main Menu -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link text-white px-3 mx-1 d-flex align-items-center position-relative {{ request()->routeIs('home') ? 'active' : '' }}" 
                       href="{{ route('home') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                            class="bi bi-house-door me-2" viewBox="0 0 16 16">
                            <path
                                d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146ZM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4H2.5Z" />
                        </svg> 
                        <span class="fw-medium">Home</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white px-3 mx-1 d-flex align-items-center position-relative {{ request()->routeIs('destinations.index') || request()->routeIs('destinations.show') ? 'active' : '' }}"
                        href="{{ route('destinations.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                            class="bi bi-map me-2" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M15.817.113A.5.5 0 0 1 16 .5v14a.5.5 0 0 1-.402.49l-5 1a.502.502 0 0 1-.196 0L5.5 15.01l-4.902.98A.5.5 0 0 1 0 15.5v-14a.5.5 0 0 1 .402-.49l5-1a.5.5 0 0 1 .196 0L10.5.99l4.902-.98a.5.5 0 0 1 .415.103zM10 1.91l-4-.8v12.98l4 .8V1.91zm1 12.98 4-.8V1.11l-4 .8v12.98zm-6-.8V1.11l-4 .8v12.98l4-.8z" />
                        </svg> 
                        <span class="fw-medium">Destinasi</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white px-3 mx-1 d-flex align-items-center position-relative {{ request()->routeIs('news.index') || request()->routeIs('news.show') ? 'active' : '' }}"
                        href="{{ route('news.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" 
                            class="bi bi-newspaper me-2" viewBox="0 0 16 16">
                            <path d="M0 2.5A1.5 1.5 0 0 1 1.5 1h11A1.5 1.5 0 0 1 14 2.5v10.528c0 .3-.05.654-.238.972h.738a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 1 1 0v9a1.5 1.5 0 0 1-1.5 1.5H1.497A1.497 1.497 0 0 1 0 13.5v-11zM12 14c.37 0 .654-.211.853-.441.092-.106.147-.279.147-.531V2.5a.5.5 0 0 0-.5-.5h-11a.5.5 0 0 0-.5.5v11c0 .278.223.5.497.5H12z"/>
                            <path d="M2 3h10v2H2V3zm0 3h4v3H2V6zm0 4h4v1H2v-1zm0 2h4v1H2v-1zm5-6h2v1H7V6zm3 0h2v1h-2V6zM7 8h2v1H7V8zm3 0h2v1h-2V8zm-3 2h2v1H7v-1zm3 0h2v1h-2v-1zm-3 2h2v1H7v-1zm3 0h2v1h-2v-1z"/>
                        </svg>
                        <span class="fw-medium">Berita</span>
                    </a>
                </li>
            </ul>

            <!-- Authentication Menu -->
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link btn text-white border border-white px-4 mx-2 d-flex align-items-center justify-content-center"
                                style="min-width: 110px; transition: all 0.3s ease; border-radius: 20px;"
                                onmouseover="this.style.backgroundColor='rgba(255,255,255,0.2)'"
                                onmouseout="this.style.backgroundColor=''" href="{{ route('login') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                    class="bi bi-box-arrow-in-right me-2" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0v-2z" />
                                    <path fill-rule="evenodd"
                                        d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z" />
                                </svg> Login
                            </a>
                        </li>
                    @endif
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link btn fw-medium px-4 mx-2 d-flex align-items-center justify-content-center"
                                style="min-width: 110px; background-color: #ffffff; color: #1a73e8; transition: all 0.3s ease; border-radius: 20px;"
                                onmouseover="this.style.backgroundColor='#f0f7ff'; this.style.color='#0d47a1';"
                                onmouseout="this.style.backgroundColor='#ffffff'; this.style.color='#1a73e8';"
                                href="{{ route('register') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                    class="bi bi-person-plus me-2" viewBox="0 0 16 16">
                                    <path
                                        d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z" />
                                    <path fill-rule="evenodd"
                                        d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z" />
                                </svg> Register
                            </a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#"
                            id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="rounded-circle bg-white text-primary d-flex justify-content-center align-items-center me-2"
                                style="width: 36px; height: 36px; font-size: 16px; font-weight: 600; color: #1a73e8;">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="fw-medium">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0"
                            style="margin-top: 12px; border-radius: 10px;" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item py-2 px-3 d-flex align-items-center" href="{{ route('profile.edit') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#1a73e8"
                                        class="bi bi-person me-2" viewBox="0 0 16 16">
                                        <path
                                            d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z" />
                                    </svg> Profil Saya
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2 px-3 d-flex align-items-center {{ request()->routeIs('my.bookings') ? 'active' : '' }}"
                                    href="{{ route('my.bookings') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        fill="{{ request()->routeIs('my.bookings') ? '#1a73e8' : 'currentColor' }}"
                                        class="bi bi-calendar-check me-2" viewBox="0 0 16 16">
                                        <path
                                            d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z" />
                                        <path
                                            d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
                                    </svg> Riwayat Pemesanan
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider my-2">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="dropdown-item py-2 px-3 d-flex align-items-center text-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                            fill="currentColor" class="bi bi-box-arrow-right me-2" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z" />
                                            <path fill-rule="evenodd"
                                                d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z" />
                                        </svg> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<style>
    /* Enhanced CSS for better design */
    .navbar {
        font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }
    
    .navbar-brand {
        letter-spacing: 0.5px;
    }

    .navbar .nav-link {
        position: relative;
        transition: all 0.25s ease;
        padding: 10px 15px;
        margin: 0 4px;
        border-radius: 8px;
    }

    .navbar .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.15);
        transform: translateY(-1px);
    }
    
    .navbar .nav-link.active {
        position: relative;
        font-weight: 600;
    }
    
    .navbar .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 15%;
        width: 70%;
        height: 3px;
        background-color: white;
        border-radius: 2px;
    }

    .navbar .dropdown-item {
        padding: 8px 16px;
        border-radius: 6px;
        margin: 4px 8px;
        transition: all 0.2s ease;
    }

    .navbar .dropdown-item:hover {
        background-color: #e8f0fe;
        color: #1a73e8;
        transform: translateX(2px);
    }
    
    .navbar .dropdown-item.active {
        background-color: #e8f0fe;
        color: #1a73e8;
        font-weight: 500;
    }

    .navbar .dropdown-menu {
        padding: 8px 0;
        animation: dropdownAnimation 0.3s ease;
    }

    @keyframes dropdownAnimation {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 991.98px) {
        .navbar-collapse {
            background: linear-gradient(to right, #1a73e8, #0d47a1);
            padding: 15px;
            border-radius: 8px;
            margin-top: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .navbar-nav .nav-link {
            padding: 12px 18px;
            margin: 6px 0;
            border-radius: 8px;
        }
        
        .navbar .nav-link.active::after {
            display: none;
        }
        
        .navbar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .navbar-nav .btn {
            width: 100%;
            margin: 8px 0 !important;
            justify-content: center;
            border-radius: 8px !important;
        }
        
        .dropdown-menu {
            background-color: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 8px !important;
            margin-top: 0 !important;
        }
    }
</style>