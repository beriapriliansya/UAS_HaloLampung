<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();

                // Jika user adalah admin, redirect ke admin dashboard
                if ($user && $user->is_admin) {
                     // Periksa apakah request saat ini adalah untuk halaman auth admin
                     // Jika ya, redirect ke admin dashboard. Jika tidak (misal akses /login publik), tetap redirect ke admin dashboard.
                     return redirect()->route('admin.dashboard');
                } else {
                    // Jika user adalah user biasa, redirect ke home publik
                    return redirect(RouteServiceProvider::HOME); // Default HOME ('/')
                }
            }
        }

        return $next($request);
    }
}