<?php

namespace App\Http\Controllers\Auth;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * LoginController - Menangani proses login user.
 * Halaman login diakses di `/login` (tanpa prefix panel).
 * Setelah login, redirect berdasarkan role: admin ke /admin, user ke /user.
 */
class LoginController
{
    /**
     * Menampilkan halaman form login.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return Auth::user()->role === 'admin'
                ? redirect('/admin')
                : redirect('/user');
        }

        return view('auth.login');
    }

    /**
     * Memproses data login.
     * Memvalidasi email & password, mencoba autentikasi,
     * lalu redirect berdasarkan role user.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return Auth::user()->role === 'admin'
                ? redirect('/admin')
                : redirect('/user');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }
}
