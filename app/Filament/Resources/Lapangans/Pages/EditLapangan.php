<?php

namespace App\Filament\Resources\Lapangans\Pages;

use App\Filament\Resources\Lapangans\LapanganResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

/**
 * EditLapangan - Halaman edit data lapangan di Filament.
 * Menyediakan form untuk memperbarui nama, harga, dan gambar lapangan.
 * Menyediakan tombol hapus di header untuk admin.
 */
class EditLapangan extends EditRecord
{
    /** Resource yang terkait dengan halaman ini. */
    protected static string $resource = LapanganResource::class;

    /**
     * Action header yang tersedia di halaman edit.
     * Menyediakan tombol hapus untuk menghapus lapangan.
     *
     * @return array Daftar action header.
     */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
