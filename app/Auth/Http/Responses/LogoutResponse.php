<?php

namespace App\Auth\Http\Responses;

use Filament\Auth\Http\Responses\Contracts\LogoutResponse as Responsable;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

/**
 * LogoutResponse - Menangani respons redirect setelah user logout.
 * Setelah logout, user akan dialihkan ke halaman utama (landing page).
 */
class LogoutResponse implements Responsable
{
    /**
     * Mengubah response menjadi redirect ke halaman utama (landing page).
     *
     * @param mixed $request Request HTTP.
     * @return RedirectResponse|Redirector Redirect ke landing page.
     */
    public function toResponse($request): RedirectResponse | Redirector
    {
        return redirect()->to('/');
    }
}
