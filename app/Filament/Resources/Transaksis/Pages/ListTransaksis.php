<?php

namespace App\Filament\Resources\Transaksis\Pages;

use App\Filament\Resources\Transaksis\TransaksiResource;
use Filament\Resources\Pages\ListRecords;

/**
 * ListTransaksis - Halaman daftar transaksi di Filament.
 * Menampilkan semua transaksi sesuai hak akses user.
 * Tidak ada action header (create dilakukan dari booking).
 */
class ListTransaksis extends ListRecords
{
    /** Resource yang terkait dengan halaman ini. */
    protected static string $resource = TransaksiResource::class;

    /**
     * Action header yang tersedia di halaman daftar.
     * Tidak ada action karena pembuatan transaksi dilakukan dari halaman booking.
     *
     * @return array Kosong.
     */
    protected function getHeaderActions(): array
    {
        return [];
    }
}
