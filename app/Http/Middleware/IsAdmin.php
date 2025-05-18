<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // Import Auth

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan user login DAN is_admin = true
        if (!Auth::check() || !Auth::user()->is_admin) {
            // Jika bukan admin, redirect atau beri error
            // Pilihan: redirect ke home, halaman login, atau 403 Forbidden
            // return redirect('/');
            abort(403, 'Unauthorized action.');
        }
        return $next($request);
    }
}