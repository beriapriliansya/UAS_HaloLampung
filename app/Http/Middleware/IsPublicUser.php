<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import Auth
use Symfony\Component\HttpFoundation\Response;

class IsPublicUser
{
    /**
     * Handle an incoming request.
     * Memastikan user yang login BUKAN admin.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user login DAN BUKAN admin
        if (Auth::check() && !Auth::user()->is_admin) {
            // Jika user login dan bukan admin, izinkan akses
            return $next($request);
        }

        // Jika user adalah admin atau belum login (meskipun sudah dicek auth sebelumnya),
        // kembalikan error atau redirect.
        // Redirect ke home lebih baik daripada 403 untuk admin.
        // Jika belum login, middleware auth seharusnya sudah handle.
        if(Auth::check() && Auth::user()->is_admin) {
            // Jika admin mencoba akses fitur user publik, redirect ke admin dashboard
             return redirect()->route('admin.dashboard')->with('warning', 'Admin tidak dapat mengakses fitur ini.');
        }

         // Fallback jika belum login (seharusnya tidak terjadi jika middleware auth diterapkan duluan)
        return redirect()->route('login');

    }
}