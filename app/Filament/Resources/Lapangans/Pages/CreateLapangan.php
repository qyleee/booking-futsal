<?php

namespace App\Filament\Resources\Lapangans\Pages;

use App\Filament\Resources\Lapangans\LapanganResource;
use Filament\Resources\Pages\CreateRecord;

/**
 * CreateLapangan - Halaman pembuatan lapangan baru di Filament.
 * Menyediakan form untuk mengisi nama lapangan, harga per jam, dan gambar.
 * Setelah berhasil, redirect ke halaman daftar lapangan.
 */
class CreateLapangan extends CreateRecord
{
    /** Resource yang terkait dengan halaman ini. */
    protected static string $resource = LapanganResource::class;

    /**
     * URL redirect setelah lapangan berhasil dibuat.
     *
     * @return string URL halaman daftar lapangan.
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
