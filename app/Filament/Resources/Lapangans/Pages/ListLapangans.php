<?php

namespace App\Filament\Resources\Lapangans\Pages;

use App\Filament\Resources\Lapangans\LapanganResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

/**
 * ListLapangans - Halaman daftar lapangan di Filament.
 * Menampilkan semua data lapangan yang tersedia.
 * Menyediakan tombol "Create" di header untuk menambah lapangan baru.
 */
class ListLapangans extends ListRecords
{
    /** Resource yang terkait dengan halaman ini. */
    protected static string $resource = LapanganResource::class;

    /**
     * Action header yang tersedia di halaman daftar.
     * Menyediakan tombol "Create" untuk menambah lapangan baru.
     *
     * @return array Daftar action header.
     */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
