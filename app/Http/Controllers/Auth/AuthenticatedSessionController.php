<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException; 

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // 1. Coba autentikasi seperti biasa
        // Blok try-catch ini penting untuk menangani kegagalan autentikasi standar
        try {
            $request->authenticate();
        } catch (ValidationException $e) {
            // Jika kredensial salah, kembalikan error standar
            return back()->withErrors([
                'email' => trans('auth.failed'),
            ])->onlyInput('email');
        }

        // 2. Dapatkan user yang baru saja berhasil diautentikasi
        $user = Auth::user();

        // 3. PERIKSA APAKAH USER ADALAH ADMIN
        if ($user && $user->is_admin) {
            // 4. JIKA ADMIN, segera logout lagi dan kembalikan error
            Auth::guard('web')->logout(); // Logout paksa

            // Invalidate session yang mungkin baru dibuat oleh authenticate()
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Kembalikan ke form login publik dengan pesan error spesifik
            // Menggunakan withErrors agar bisa ditangkap @error('email') di view
            return back()->withErrors([
                'email' => 'Akses ditolak. Admin harus login melalui halaman login admin.',
            ])->onlyInput('email'); // Agar email tetap terisi
        }

        // 5. Jika BUKAN ADMIN, lanjutkan proses login seperti biasa
        $request->session()->regenerate();

        // Redirect ke intended location atau HOME (untuk user publik)
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
