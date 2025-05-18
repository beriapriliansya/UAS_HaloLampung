<x-public-layout>
    <!-- Header sederhana dan profesional -->
    <div class="bg-light border-bottom py-4 mb-4">
        <div class="container">
            <h1 class="h3 mb-0 fw-semibold text-primary">
                <i class="bi bi-person-circle me-2"></i>{{ __('Profil Pengguna') }}
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 mt-1">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Profil</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row">
            <!-- Sidebar Menu -->
            <div class="col-lg-3 mb-4">
                <div class="list-group shadow-sm rounded">
                    <a href="#profile-info" class="list-group-item list-group-item-action active d-flex align-items-center" data-bs-toggle="list">
                        <i class="bi bi-person me-3"></i> Informasi Profil
                    </a>
                    <a href="#password" class="list-group-item list-group-item-action d-flex align-items-center" data-bs-toggle="list">
                        <i class="bi bi-lock me-3"></i> Update Password
                    </a>
                    <a href="#delete-account" class="list-group-item list-group-item-action d-flex align-items-center text-danger" data-bs-toggle="list">
                        <i class="bi bi-trash me-3"></i> Hapus Akun
                    </a>
                </div>
                
                <!-- Profile Overview Card -->
                <div class="card mt-4 shadow-sm">
                    <div class="card-body text-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px;">
                            <span class="text-primary fw-bold fs-3">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                        <h5 class="card-title mb-1">{{ Auth::user()->name }}</h5>
                        <p class="text-muted small mb-3">{{ Auth::user()->email }}</p>
                        <a href="{{ route('my.bookings') }}" class="btn btn-sm btn-outline-primary w-100">
                            <i class="bi bi-calendar-check me-1"></i> Lihat Pemesanan Saya
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Content Area -->
            <div class="col-lg-9">
                <div class="tab-content">
                    <!-- Profile Information Tab -->
                    <div class="tab-pane fade show active" id="profile-info">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0 fw-semibold">Informasi Profil</h5>
                                <p class="text-muted small mb-0">Update informasi profil dan alamat email Anda</p>
                            </div>
                            <div class="card-body">
                                @include('profile.partials.update-profile-information-form')
                            </div>
                        </div>
                    </div>
                    
                    <!-- Password Tab -->
                    <div class="tab-pane fade" id="password">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0 fw-semibold">Update Password</h5>
                                <p class="text-muted small mb-0">Pastikan akun Anda menggunakan password yang aman dan acak</p>
                            </div>
                            <div class="card-body">
                                @include('profile.partials.update-password-form')
                            </div>
                        </div>
                    </div>
                    
                    <!-- Delete Account Tab -->
                    <div class="tab-pane fade" id="delete-account">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white py-3 border-bottom border-danger border-opacity-25">
                                <h5 class="mb-0 fw-semibold text-danger">Hapus Akun</h5>
                                <p class="text-muted small mb-0">Menghapus akun Anda secara permanen</p>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-warning d-flex align-items-center">
                                    <i class="bi bi-exclamation-triangle-fill fs-4 me-2"></i>
                                    <div>
                                        Setelah akun Anda dihapus, semua data dan riwayat pemesanan akan dihapus secara permanen. Sebelum menghapus akun, pastikan untuk mengunduh data yang ingin Anda simpan.
                                    </div>
                                </div>
                                @include('profile.partials.delete-user-form')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CSS untuk styling tambahan -->
    <style>
        /* Styling untuk list-group */
        .list-group-item {
            padding: 0.75rem 1rem;
            border-left: 3px solid transparent;
        }
        
        .list-group-item.active {
            background-color: #f8f9fa;
            color: var(--bs-primary);
            border-left: 3px solid var(--bs-primary);
            font-weight: 500;
        }
        
        .list-group-item:hover:not(.active) {
            background-color: #f8f9fa;
            border-left: 3px solid #dee2e6;
        }
        
        /* Card styling */
        .card {
            border-radius: 0.5rem;
            overflow: hidden;
        }
        
        .card-header {
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        
        /* Form styling enhancements */
        .form-control:focus, .form-select:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }
        
        /* Responsive adjustments */
        @media (max-width: 992px) {
            .list-group {
                display: flex;
                flex-direction: row;
                flex-wrap: nowrap;
                overflow-x: auto;
                margin-bottom: 1.5rem;
            }
            
            .list-group-item {
                flex: 0 0 auto;
                border: 1px solid rgba(0,0,0,0.125);
                border-radius: 0.25rem !important;
                margin-right: 0.5rem;
                white-space: nowrap;
            }
            
            .list-group-item.active {
                border-left: 1px solid var(--bs-primary);
            }
        }
    </style>

    <!-- Link untuk Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</x-public-layout>