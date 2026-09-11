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
 * - Halaman login/register tidak dialihkan agar proses autentikasi tetap jalan.
 */
class EnsureCorrectPanel
{
    /**
     * Menangani request dan memeriksa apakah user mengakses panel yang benar.
     * Jika admin mengakses panel user (bukan halaman auth), redirect ke admin.
     * Jika user biasa mengakses panel admin, redirect ke user panel.
     *
     * @param Request $request Request HTTP yang masuk.
     * @param Closure $next Fungsi middleware selanjutnya.
     * @return Response Response HTTP.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->path();
        $isAuthPage = str_ends_with($path, '/login') || str_ends_with($path, '/register');

        if (Auth::check() && Auth::user()->role === 'admin') {
            if (str_starts_with($path, 'user') && !$isAuthPage) {
                return redirect('/admin');
            }
        }

        if (Auth::check() && Auth::user()->role === 'user') {
            if (str_starts_with($path, 'admin')) {
                return redirect('/user');
            }
        }

        return $next($request);
    }
}
