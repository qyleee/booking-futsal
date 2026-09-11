<?php

use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

// Route halaman utama (landing page) - menampilkan daftar lapangan
Route::get('/', LandingController::class)->name('landing');

// Redirect /login ke panel user login
Route::redirect('/login', '/user/login')->name('login');

// Redirect /register ke panel user registrasi
Route::redirect('/register', '/user/register')->name('register');

// Redirect /dashboard ke panel user
Route::redirect('/dashboard', '/user');
