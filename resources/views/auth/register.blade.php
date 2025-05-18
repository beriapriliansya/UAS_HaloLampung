<x-public-layout>
    <!-- CSS untuk animasi dan styling tambahan -->
    <style>
        body {
            background: white;
            min-height: 100vh;
        }
        .register-container {
            padding-top: 5%;
            padding-bottom: 5%;
        }
        .register-card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }
        .register-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
        .card-header {
            background: linear-gradient(45deg, #3a7bd5, #00d2ff);
            border-bottom: none;
            position: relative;
            overflow: hidden;
            padding: 25px 0;
        }
        .card-header::after {
            content: "";
            position: absolute;
            width: 200%;
            height: 200%;
            bottom: -50%;
            left: -50%;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }
        .register-icon {
            font-size: 28px;
            height: 60px;
            width: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            margin: 0 auto 15px;
        }
        .form-floating {
            position: relative;
            margin-bottom: 25px;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #eaeaea;
            background-color: #f9f9f9;
            transition: all 0.3s;
        }
        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(58, 123, 213, 0.15);
            border-color: #3a7bd5;
            background-color: #fff;
        }
        .btn-register {
            background: linear-gradient(45deg, #3a7bd5, #00d2ff);
            border: none;
            border-radius: 30px;
            padding: 12px 30px;
            font-weight: 600;
            letter-spacing: 0.5px;
            box-shadow: 0 5px 15px rgba(58, 123, 213, 0.3);
            transition: all 0.3s;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(58, 123, 213, 0.4);
        }
        .register-footer {
            background-color: #f8f9fa;
            border-top: none;
        }
        .login-link {
            color: #3a7bd5;
            font-weight: 500;
            transition: all 0.3s;
        }
        .login-link:hover {
            color: #00d2ff;
            text-decoration: none;
        }
        .register-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0;
        }
        .register-subtitle {
            font-size: 0.9rem;
            opacity: 0.8;
            margin-top: 5px;
        }
        
        /* Animasi */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in-up {
            animation: fadeInUp 0.6s ease forwards;
        }
        
        .delay-1 {
            animation-delay: 0.1s;
        }
        
        .delay-2 {
            animation-delay: 0.2s;
        }
        
        .delay-3 {
            animation-delay: 0.3s;
        }
        
        .delay-4 {
            animation-delay: 0.4s;
        }
        
        .delay-5 {
            animation-delay: 0.5s;
        }
        
        .delay-6 {
            animation-delay: 0.6s;
        }
        
        /* Password Strength */
        .password-strength {
            height: 5px;
            margin-top: 5px;
            border-radius: 5px;
            transition: all 0.3s;
        }
        
        .password-tips {
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 5px;
        }
        
        .strength-weak {
            background-color: #dc3545;
            width: 25%;
        }
        .strength-medium {
            background-color: #ffc107;
            width: 50%;
        }
        .strength-strong {
            background-color: #28a745;
            width: 100%;
        }
    </style>
    
    <div class="container register-container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="text-center text-grey mb-4 fade-in-up">
                    <h1 class="display-5 fw-bold">Create Account</h1>
                    <p class="lead">Join our community today</p>
                </div>
                
                <div class="register-card card border-0 fade-in-up delay-1">
                    <div class="card-header text-center text-white">
                        <div class="register-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h3 class="register-title">Register</h3>
                        <p class="register-subtitle">Create your new account</p>
                    </div>
                    
                    <div class="card-body p-4 p-lg-5">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <!-- Name -->
                            <div class="form-floating fade-in-up delay-2">
                                <input type="text" name="name" id="name" class="form-control" 
                                    placeholder="Full Name" value="{{ old('name') }}" 
                                    required autofocus autocomplete="name">
                                <label for="name">
                                    <i class="fas fa-user me-2"></i>{{ __('Full Name') }}
                                </label>
                                <x-input-error :messages="$errors->get('name')" class="text-danger small mt-1" />
                            </div>

                            <!-- Email Address -->
                            <div class="form-floating fade-in-up delay-3">
                                <input type="email" name="email" id="email" class="form-control" 
                                    placeholder="name@example.com" value="{{ old('email') }}" 
                                    required autocomplete="username">
                                <label for="email">
                                    <i class="fas fa-envelope me-2"></i>{{ __('Email Address') }}
                                </label>
                                <x-input-error :messages="$errors->get('email')" class="text-danger small mt-1" />
                            </div>

                            <!-- Password -->
                            <div class="form-floating fade-in-up delay-4">
                                <input type="password" name="password" id="password" class="form-control" 
                                    placeholder="Password" required autocomplete="new-password" onkeyup="checkPasswordStrength()">
                                <label for="password">
                                    <i class="fas fa-lock me-2"></i>{{ __('Password') }}
                                </label>
                                <div class="password-strength" id="passwordStrength"></div>
                                <div class="password-tips" id="passwordTips">Password should be at least 8 characters</div>
                                <x-input-error :messages="$errors->get('password')" class="text-danger small mt-1" />
                            </div>

                            <!-- Confirm Password -->
                            <div class="form-floating fade-in-up delay-5">
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" 
                                    placeholder="Confirm Password" required autocomplete="new-password">
                                <label for="password_confirmation">
                                    <i class="fas fa-check-circle me-2"></i>{{ __('Confirm Password') }}
                                </label>
                                <x-input-error :messages="$errors->get('password_confirmation')" class="text-danger small mt-1" />
                            </div>
                            
                            <div class="form-check mb-3 fade-in-up delay-6">
                                <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#" class="login-link">Terms of Service</a> and <a href="#" class="login-link">Privacy Policy</a>
                                </label>
                            </div>

                            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between mt-4 fade-in-up delay-6">
                                <a class="login-link mb-3 mb-md-0" href="{{ route('login') }}">
                                    <i class="fas fa-sign-in-alt me-1"></i>{{ __('Already have an account?') }}
                                </a>

                                <button type="submit" class="btn btn-register">
                                    <i class="fas fa-user-plus me-2"></i>{{ __('Create Account') }}
                                </button>
                            </div>
                        </form>
                        
                    </div>
                    
                    <div class="card-footer register-footer text-center py-4 fade-in-up delay-6">
                        <div class="small">
                            <p class="mb-0">By creating an account, you agree to our Terms of Service and Privacy Policy.</p>
                        </div>
                    </div>
                </div>
                
                <div class="text-center text-white-50 mt-4 small fade-in-up delay-6">
                    &copy; 2025 Your Company. All rights reserved.
                </div>
            </div>
        </div>
    </div>

    <!-- Tambahkan link ke Font Awesome jika belum ada -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    
    <!-- Script untuk password strength -->
    <script>
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthBar = document.getElementById('passwordStrength');
            const tips = document.getElementById('passwordTips');
            
            // Reset
            strengthBar.className = 'password-strength';
            
            if (password.length === 0) {
                tips.textContent = 'Password should be at least 8 characters';
                return;
            }
            
            // Check strength
            let strength = 0;
            const patterns = [
                /[A-Z]/, // uppercase
                /[a-z]/, // lowercase
                /[0-9]/, // numbers
                /[^A-Za-z0-9]/ // special characters
            ];
            
            // Add points for length
            if (password.length >= 8) strength += 1;
            if (password.length >= 12) strength += 1;
            
            // Add points for patterns
            patterns.forEach(pattern => {
                if (pattern.test(password)) strength += 1;
            });
            
            // Update UI based on strength
            if (password.length < 8) {
                strengthBar.classList.add('strength-weak');
                tips.textContent = 'Too short! Add more characters';
            } else if (strength < 4) {
                strengthBar.classList.add('strength-weak');
                tips.textContent = 'Weak! Add numbers and special characters';
            } else if (strength < 6) {
                strengthBar.classList.add('strength-medium');
                tips.textContent = 'Medium! Try adding special characters';
            } else {
                strengthBar.classList.add('strength-strong');
                tips.textContent = 'Strong password!';
            }
        }
    </script>
</x-public-layout>