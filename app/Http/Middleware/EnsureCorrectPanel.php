<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware EnsureCorrectPanel - Mengarahkan user ke panel yang sesuai berdasarkan role.
 * - Admin yang mengakses /user akan dialihkan ke /admin.
 * - User biasa yang mengakses /admin akan dialihkan ke /user.
 * - Halaman login (/login) dan register (/register) tidak dialihkan.
 * - Landing page (/) tidak dialihkan.
 */
class EnsureCorrectPanel
{
    /**
     * Menangani request dan memeriksa apakah user mengakses panel yang benar.
     *
     * @param Request $request Request HTTP yang masuk.
     * @param Closure $next Fungsi middleware selanjutnya.
     * @return Response Response HTTP.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->path();

        // Halaman yang tidak perlu di-redirect (login, register, landing page)
        $skipPaths = ['login', 'register'];

        if (in_array($path, $skipPaths)) {
            return $next($request);
        }

        // Admin yang mengakses panel user (bukan halaman auth) -> redirect ke admin
        if (Auth::check() && Auth::user()->role === 'admin') {
            if (str_starts_with($path, 'user')) {
                return redirect('/admin');
            }
        }

        // User biasa yang mengakses panel admin -> redirect ke user
        if (Auth::check() && Auth::user()->role === 'user') {
            if (str_starts_with($path, 'admin')) {
                return redirect('/user');
            }
        }

        return $next($request);
    }
}
