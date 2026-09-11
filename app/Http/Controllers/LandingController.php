<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;

/**
 * Controller LandingController - Menangani halaman utama (landing page).
 * Menampilkan daftar semua lapangan futsal yang tersedia kepada pengunjung.
 * Menggunakan invokable controller (satu method __invoke).
 */
class LandingController
{
    /**
     * Menampilkan halaman landing dengan semua data lapangan.
     * Mengambil semua data lapangan dari database dan mengirimkannya ke view 'landing'.
     *
     * @return \Illuminate\View\View Halaman landing page.
     */
    public function __invoke()
    {
        $lapangans = Lapangan::all();

        return view('landing', compact('lapangans'));
    }
}
