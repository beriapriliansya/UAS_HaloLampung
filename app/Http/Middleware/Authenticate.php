<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route; 

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (! $request->expectsJson()) {
            // Cek apakah route yang sedang diakses adalah route admin
            // Kita bisa cek prefix URL atau nama route
            if ($request->is('admin/*') || Route::is('admin.*')) {
                 // Jika mencoba akses halaman admin tanpa login, redirect ke login admin
                 return route('admin.login');
            } else {
                 // Jika mencoba akses halaman publik tanpa login, redirect ke login publik
                 return route('login');
            }
        }
        return null; // Untuk request API/JSON, kembalikan null agar response 401 dikirim
    }
}
