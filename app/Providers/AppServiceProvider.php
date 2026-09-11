<?php

namespace App\Providers;

use App\Auth\Http\Responses\LogoutResponse;
use Filament\Auth\Http\Responses\Contracts\LogoutResponse as LogoutResponseContract;
use Illuminate\Support\ServiceProvider;

/**
 * AppServiceProvider - Service provider utama aplikasi.
 * Mengikat implementasi LogoutResponse custom ke kontrak Filament.
 * Setelah logout, user akan dialihkan ke /login.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Mendaftarkan layanan ke container.
     * Mengikat kontrak LogoutResponse Filament ke implementasi custom.
     */
    public function register(): void
    {
        $this->app->bind(LogoutResponseContract::class, LogoutResponse::class);
    }

    /**
     * Boot service provider.
     * Tidak ada konfigurasi boot saat ini.
     */
    public function boot(): void
    {
        //
    }
}
