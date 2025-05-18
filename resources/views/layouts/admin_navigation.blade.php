{{-- File: resources/views/layouts/admin_navigation.blade.php --}}

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">{{ config('app.name', 'Laravel') }} Admin</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar"
            aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                {{-- Dashboard --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                        href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                {{-- Manajemen Destinasi --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.destinations.*') ? 'active' : '' }}"
                        href="{{ route('admin.destinations.index') }}">Destinasi</a>
                </li>
                {{-- Manajemen Fasilitas (Master) --}}
                <li class="nav-item">
                    {{-- Link ke halaman index fasilitas master --}}
                    <a class="nav-link {{ request()->routeIs('admin.facilities.*') ? 'active' : '' }}"
                        href="{{ route('admin.facilities.index') }}">Fasilitas</a>
                </li>
                {{-- Manajemen Pemesanan --}}
                <li class="nav-item">
                    {{-- Link ke halaman index booking --}}
                    <a class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}"
                        href="{{ route('admin.bookings.index') }}">Pemesanan</a>
                </li>
                {{-- Manajemen Pembayaran --}}
                <li class="nav-item">
                    {{-- Link ke halaman index payment --}}
                    <a class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}"
                        href="{{ route('admin.payments.index') }}">Pembayaran</a>
                </li>
                {{-- Manajemen Tiket --}}
                <li class="nav-item">
                    {{-- Link ke halaman index ticket --}}
                    <a class="nav-link {{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}"
                        href="{{ route('admin.tickets.index') }}">Tiket</a>
                </li>
                {{-- Laporan --}}
                <li class="nav-item">
                    {{-- Link ke halaman form generator laporan --}}
                    <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"
                        href="{{ route('admin.reports.index') }}">Laporan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }}"
                       href="{{ route('admin.news.index') }}">Berita</a>
                </li>
            </ul>
            {{-- User Dropdown (Sudah Benar) --}}
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                        {{-- Breeze profile route --}}
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
