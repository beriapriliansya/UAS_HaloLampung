<section class="password-form">
    <header class="mb-4">
        <h2 class="h4 fw-semibold text-primary">
            <i class="bi bi-shield-lock me-2"></i>{{ __('Update Password') }}
        </h2>
        <p class="text-muted small">
            {{ __('Pastikan akun Anda menggunakan password yang panjang dan acak untuk menjaga keamanan.') }}
        </p>
        <hr class="mt-3">
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-4">
        @csrf
        @method('put')

        <div class="row g-3">
            <!-- Current Password Field -->
            <div class="col-md-12 mb-3">
                <label for="update_password_current_password" class="form-label fw-medium">{{ __('Password Saat Ini') }}</label>
                <div class="input-group">
                    <span class="input-group-text bg-light">
                        <i class="bi bi-key"></i>
                    </span>
                    <input id="update_password_current_password" name="current_password" type="password" 
                           class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                           autocomplete="current-password">
                    <button class="btn btn-outline-secondary toggle-password" type="button" 
                            data-target="update_password_current_password">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                @error('current_password', 'updatePassword')
                    <div class="invalid-feedback d-block mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- New Password Field -->
            <div class="col-md-6 mb-3">
                <label for="update_password_password" class="form-label fw-medium">{{ __('Password Baru') }}</label>
                <div class="input-group">
                    <span class="input-group-text bg-light">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input id="update_password_password" name="password" type="password"
                           class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                           autocomplete="new-password">
                    <button class="btn btn-outline-secondary toggle-password" type="button"
                            data-target="update_password_password">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                @error('password', 'updatePassword')
                    <div class="invalid-feedback d-block mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Confirm Password Field -->
            <div class="col-md-6 mb-3">
                <label for="update_password_password_confirmation" class="form-label fw-medium">{{ __('Konfirmasi Password') }}</label>
                <div class="input-group">
                    <span class="input-group-text bg-light">
                        <i class="bi bi-lock-fill"></i>
                    </span>
                    <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                           class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                           autocomplete="new-password">
                    <button class="btn btn-outline-secondary toggle-password" type="button"
                            data-target="update_password_password_confirmation">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                @error('password_confirmation', 'updatePassword')
                    <div class="invalid-feedback d-block mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <!-- Password Strength Indicator -->
        <div class="password-strength mb-4 mt-2">
            <label class="form-label small text-muted">Kekuatan Password:</label>
            <div class="progress" style="height: 6px;">
                <div class="progress-bar bg-danger" role="progressbar" id="password-strength-bar" style="width: 0%"></div>
            </div>
            <div class="d-flex justify-content-between mt-1">
                <small class="text-muted">Lemah</small>
                <small class="text-muted">Kuat</small>
            </div>
        </div>

        <!-- Password Requirements -->
        <div class="password-requirements alert alert-light small mb-4">
            <div class="fw-medium mb-1">Password harus memenuhi kriteria berikut:</div>
            <ul class="mb-0 ps-3">
                <li>Minimal 8 karakter</li>
                <li>Mengandung huruf besar dan huruf kecil</li>
                <li>Mengandung angka</li>
                <li>Mengandung karakter khusus (misalnya: !@#$%^&*)</li>
            </ul>
        </div>

        <!-- Save Button and Status -->
        <div class="d-flex align-items-center gap-3 mt-4">
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-lg me-1"></i>{{ __('Simpan Password') }}
            </button>

            @if (session('status') === 'password-updated')
                <div class="alert alert-success py-1 px-3 mb-0 d-inline-flex align-items-center fade show"
                     x-data="{ show: true }"
                     x-show="show"
                     x-transition
                     x-init="setTimeout(() => show = false, 2000)">
                    <i class="bi bi-check-circle me-1"></i>
                    <small>{{ __('Password berhasil diperbarui!') }}</small>
                </div>
            @endif
        </div>
    </form>

    <!-- JavaScript for Password Toggle & Strength Checker -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            document.querySelectorAll('.toggle-password').forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const inputField = document.getElementById(targetId);
                    const icon = this.querySelector('i');
                    
                    if (inputField.type === 'password') {
                        inputField.type = 'text';
                        icon.classList.remove('bi-eye');
                        icon.classList.add('bi-eye-slash');
                    } else {
                        inputField.type = 'password';
                        icon.classList.remove('bi-eye-slash');
                        icon.classList.add('bi-eye');
                    }
                });
            });
            
            // Password strength checker
            const passwordInput = document.getElementById('update_password_password');
            const strengthBar = document.getElementById('password-strength-bar');
            
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;
                
                // Length check
                if (password.length >= 8) strength += 25;
                
                // Contains lowercase
                if (/[a-z]/.test(password)) strength += 25;
                
                // Contains uppercase
                if (/[A-Z]/.test(password)) strength += 20;
                
                // Contains number
                if (/[0-9]/.test(password)) strength += 15;
                
                // Contains special character
                if (/[^A-Za-z0-9]/.test(password)) strength += 15;
                
                // Update progress bar
                strengthBar.style.width = strength + '%';
                
                // Change color based on strength
                if (strength < 30) {
                    strengthBar.className = 'progress-bar bg-danger';
                } else if (strength < 60) {
                    strengthBar.className = 'progress-bar bg-warning';
                } else if (strength < 80) {
                    strengthBar.className = 'progress-bar bg-info';
                } else {
                    strengthBar.className = 'progress-bar bg-success';
                }
            });
        });
    </script>
</section>

<style>
    .password-form .form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }
    
    .password-form .input-group-text {
        border-right: none;
    }
    
    .password-form .form-control {
        border-left: none;
    }
    
    .toggle-password {
        z-index: 10;
    }
    
    .password-requirements {
        background-color: #f8f9fa;
        border-left: 4px solid #6c757d;
    }
    
    .btn-primary {
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(13, 110, 253, 0.2);
    }
</style>