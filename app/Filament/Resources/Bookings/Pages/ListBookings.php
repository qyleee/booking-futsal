<?php

namespace App\Filament\Resources\Bookings\Pages;

use App\Filament\Resources\Bookings\BookingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

/**
 * ListBookings - Halaman daftar booking di Filament.
 * Menampilkan semua booking sesuai hak akses user (admin lihat semua, user lihat miliknya).
 * Menyediakan tombol "Create" di header untuk membuat booking baru.
 */
class ListBookings extends ListRecords
{
    /** Resource yang terkait dengan halaman ini. */
    protected static string $resource = BookingResource::class;

    /**
     * Action header yang tersedia di halaman daftar.
     * Menyediakan tombol "Create" untuk membuat booking baru.
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
