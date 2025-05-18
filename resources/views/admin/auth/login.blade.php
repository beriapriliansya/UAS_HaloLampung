{{-- resources/views/admin/auth/login.blade.php --}}
<x-admin-guest-layout>
    <!-- Session Status (jika ada, misal setelah reset password berhasil) -->
    @if (session('status'))
        <div class="alert alert-success mb-4" role="alert">
            {{ session('status') }}
        </div>
    @endif

    {{-- Pesan error umum dari controller (misal 'Akses ditolak') --}}
    @if ($errors->has('email') && $errors->first('email') === 'Akses ditolak. Admin harus login melalui halaman login admin.')
        <div class="alert alert-danger mb-4" role="alert">
            {{ $errors->first('email') }}
        </div>
    @endif


    <form method="POST" action="{{ route('admin.login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input id="email" class="form-control @error('email', 'login') is-invalid @enderror"
                   type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            {{-- Error spesifik untuk kredensial salah (bukan yang 'Akses ditolak') --}}
            @error('email', 'login')
                @if ($message !== 'Akses ditolak. Admin harus login melalui halaman login admin.')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @endif
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">{{ __('Password') }}</label>
            <input id="password" class="form-control @error('password', 'login') is-invalid @enderror"
                   type="password" name="password" required autocomplete="current-password">
            @error('password', 'login')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="mb-3 form-check">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
            <label class="form-check-label" for="remember_me">{{ __('Remember me') }}</label>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg">
                {{ __('Log in') }}
            </button>
        </div>
    </form>
</x-admin-guest-layout>