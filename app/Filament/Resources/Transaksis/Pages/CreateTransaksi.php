<?php

namespace App\Filament\Resources\Transaksis\Pages;

use App\Filament\Resources\Transaksis\TransaksiResource;
use App\Models\Booking;
use Filament\Resources\Pages\CreateRecord;

/**
 * CreateTransaksi - Halaman pembuatan transaksi baru di Filament.
 * Memvalidasi bahwa booking belum memiliki transaksi sebelumnya
 * dan hanya admin yang boleh membuat transaksi.
 */
class CreateTransaksi extends CreateRecord
{
    /** Resource yang terkait dengan halaman ini. */
    protected static string $resource = TransaksiResource::class;

    /**
     * Memodifikasi data form sebelum disimpan.
     * Memastikan booking yang dipilih belum memiliki transaksi.
     * Jika admin tidak diizinkan atau booking sudah punya transaksi, abort 403.
     *
     * @param array $data Data dari form.
     * @return array Data yang sudah divalidasi.
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $booking = Booking::findOrFail($data['booking_id']);

        if (TransaksiResource::canCreate() === false || $booking->transaksi()->exists()) {
            abort(403);
        }

        return $data;
    }
}
