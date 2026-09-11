<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

// Route halaman utama (landing page) - menampilkan daftar lapangan
Route::get('/', LandingController::class)->name('landing');

// Route login - halaman login untuk semua role (user & admin)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// Route register - halaman registrasi user baru
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

// Redirect /dashboard ke panel user
Route::redirect('/dashboard', '/user');
