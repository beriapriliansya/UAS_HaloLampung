<section class="profile-form">
    <header class="mb-4">
        <h2 class="h4 fw-semibold text-primary">
            <i class="bi bi-person-vcard me-2"></i>{{ __('Informasi Profil') }}
        </h2>
        <p class="text-muted small">
            {{ __("Update informasi profil dan alamat email akun Anda.") }}
        </p>
        <hr class="mt-3">
    </header>

    <!-- Verification Form -->
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <!-- Main Profile Form -->
    <form method="post" action="{{ route('profile.update') }}" class="mt-4">
        @csrf
        @method('patch')

        <div class="row g-3">
            <!-- Name Field -->
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label fw-medium">{{ __('Nama') }}</label>
                <div class="input-group">
                    <span class="input-group-text bg-light">
                        <i class="bi bi-person"></i>
                    </span>
                    <input id="name" name="name" type="text" 
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $user->name) }}" 
                           required autofocus autocomplete="name">
                </div>
                @error('name')
                    <div class="invalid-feedback d-block mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Email Field -->
            <div class="col-md-6 mb-3">
                <label for="email" class="form-label fw-medium">{{ __('Email') }}</label>
                <div class="input-group">
                    <span class="input-group-text bg-light">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input id="email" name="email" type="email" 
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $user->email) }}" 
                           required autocomplete="username">
                </div>
                @error('email')
                    <div class="invalid-feedback d-block mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            
            <!-- Email Verification Status -->
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="col-12">
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <span>{{ __('Alamat email Anda belum diverifikasi.') }}</span>
                            <button form="send-verification" 
                                    class="btn btn-sm btn-outline-primary ms-3">
                                {{ __('Kirim ulang email verifikasi') }}
                            </button>
                        </div>
                    </div>

                    @if (session('status') === 'verification-link-sent')
                        <div class="alert alert-success d-flex align-items-center mt-2" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <div>
                                {{ __('Link verifikasi baru telah dikirim ke alamat email Anda.') }}
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Save Button and Status -->
        <div class="d-flex align-items-center gap-3 mt-4">
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-lg me-1"></i>{{ __('Simpan Perubahan') }}
            </button>

            @if (session('status') === 'profile-updated')
                <div class="alert alert-success py-1 px-3 mb-0 d-inline-flex align-items-center fade show"
                     x-data="{ show: true }"
                     x-show="show"
                     x-transition
                     x-init="setTimeout(() => show = false, 2000)">
                    <i class="bi bi-check-circle me-1"></i>
                    <small>{{ __('Perubahan tersimpan!') }}</small>
                </div>
            @endif
        </div>
    </form>
</section>

<style>
    .profile-form .form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }
    
    .profile-form .input-group-text {
        border-right: none;
    }
    
    .profile-form .form-control {
        border-left: none;
    }
    
    .btn-primary {
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(13, 110, 253, 0.2);
    }
    
    .alert {
        border-radius: 8px;
    }
</style>