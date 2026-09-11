<?php

namespace App\Auth\Http\Responses;

use Filament\Auth\Http\Responses\Contracts\LogoutResponse as Responsable;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

/**
 * LogoutResponse - Menangani respons redirect setelah user logout.
 * Mengimplementasikan kontrak LogoutResponse Filament.
 * Setelah logout, user akan dialihkan ke halaman login.
 */
class LogoutResponse implements Responsable
{
    /**
     * Mengubah response menjadi redirect ke halaman login.
     *
     * @param mixed $request Request HTTP.
     * @return RedirectResponse|Redirector Redirect ke /login.
     */
    public function toResponse($request): RedirectResponse | Redirector
    {
        return redirect()->to('/login');
    }
}
