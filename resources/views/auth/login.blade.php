<x-public-layout>
    <!-- CSS untuk animasi dan styling tambahan -->
    <style>
        body {
            background: white;
            min-height: 100vh;
        }

        .login-container {
            padding-top: 7%;
            padding-bottom: 7%;
        }

        .login-card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
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
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .login-icon {
            font-size: 28px;
            height: 60px;
            width: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.1);
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

        .btn-login {
            background: linear-gradient(45deg, #3a7bd5, #00d2ff);
            border: none;
            border-radius: 30px;
            padding: 12px 30px;
            font-weight: 600;
            letter-spacing: 0.5px;
            box-shadow: 0 5px 15px rgba(58, 123, 213, 0.3);
            transition: all 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(58, 123, 213, 0.4);
        }

        .login-footer {
            background-color: #f8f9fa;
            border-top: none;
        }

        .register-link {
            color: #3a7bd5;
            font-weight: 500;
            transition: all 0.3s;
        }

        .register-link:hover {
            color: #00d2ff;
            text-decoration: none;
        }

        .forgot-link {
            color: #6c757d;
            transition: all 0.3s;
        }

        .forgot-link:hover {
            color: #3a7bd5;
            text-decoration: none;
        }

        .login-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0;
        }

        .login-subtitle {
            font-size: 0.9rem;
            opacity: 0.8;
            margin-top: 5px;
        }

        .form-check-input:checked {
            background-color: #3a7bd5;
            border-color: #3a7bd5;
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
    </style>

    <div class="container login-container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="text-center text-grey mb-4 fade-in-up">
                    <h1 class="display-5 fw-bold">Welcome Back</h1>
                    <p class="lead">Sign in to continue to your dashboard</p>
                </div>

                <div class="login-card card border-0 fade-in-up delay-1">
                    <div class="card-header text-center text-white">
                        <div class="login-icon">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <h3 class="login-title">Account Login</h3>
                        <p class="login-subtitle">Access your account</p>
                    </div>

                    <div class="card-body p-4 p-lg-5">
                        <!-- Session Status -->
                        <x-auth-session-status class="alert alert-info mb-4" :status="session('status')" />

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email Address -->
                            <div class="form-floating fade-in-up delay-2">
                                <input type="email" name="email" id="email" class="form-control"
                                    placeholder="name@example.com" value="{{ old('email') }}" required autofocus
                                    autocomplete="username">
                                <label for="email">
                                    <i class="fas fa-envelope me-2"></i>{{ __('Email Address') }}
                                </label>
                                <x-input-error :messages="$errors->get('email')" class="text-danger small mt-1" />
                            </div>

                            <!-- Password -->
                            <div class="form-floating fade-in-up delay-3">
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="Password" required autocomplete="current-password">
                                <label for="password">
                                    <i class="fas fa-lock me-2"></i>{{ __('Password') }}
                                </label>
                                <x-input-error :messages="$errors->get('password')" class="text-danger small mt-1" />
                            </div>

                            <!-- Remember Me -->
                            <div class="form-check mb-3 fade-in-up delay-4">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                                <label class="form-check-label" for="remember_me">
                                    {{ __('Remember me') }}
                                </label>
                            </div>

                            <div class="d-flex justify-content-center align-items-center mt-4 fade-in-up delay-5">
                                <button type="submit" class="btn btn-login">
                                    <i class="fas fa-sign-in-alt me-2"></i>{{ __('Sign In') }}
                                </button>
                            </div>

                        </form>


                    </div>

                    <div class="card-footer login-footer text-center py-4 fade-in-up delay-5">
                        <div>
                            <span class="text-muted">Don't have an account?</span>
                            <a href="{{ route('register') }}" class="register-link ms-1">
                                <i class="fas fa-user-plus me-1"></i>Create Account
                            </a>
                        </div>
                    </div>
                </div>

                <div class="text-center text-white-50 mt-4 small fade-in-up delay-5">
                    &copy; 2025 Your Company. All rights reserved.
                </div>
            </div>
        </div>
    </div>

    <!-- Tambahkan link ke Font Awesome jika belum ada -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
</x-public-layout>
