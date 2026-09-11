<?php

namespace App\Http\Middleware;

use Filament\Http\Middleware\Authenticate as FilamentAuthenticate;

/**
 * Authenticate - Middleware custom untuk redirect halaman login Filament.
 * Menggantikan redirect default Filament ke /login (tanpa prefix panel).
 */
class Authenticate extends FilamentAuthenticate
{
    /**
     * Mendapatkan URL redirect untuk user yang belum login.
     * Mengarahkan ke /login (halaman login custom).
     *
     * @param mixed $request Request HTTP.
     * @return string URL redirect.
     */
    protected function redirectTo($request): ?string
    {
        return '/login';
    }
}
