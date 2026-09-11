<?php

namespace App\Filament\Resources\Transaksis\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

/**
 * TransaksiForm - Mengonfigurasi form untuk create transaksi.
 * Berisi field: booking_id, metode_pembayaran, bukti_pembayaran, dan status_pembayaran.
 * User hanya bisa melihat booking miliknya sendiri.
 * Status pembayaran hanya bisa diubah oleh admin.
 */
class TransaksiForm
{
    /**
     * Mengonfigurasi semua komponen form transaksi.
     * - booking_id: dipilih dari booking yang belum punya transaksi.
     * - metode_pembayaran: transfer_bank, ewallet, qris, cash.
     * - bukti_pembayaran: upload gambar bukti.
     * - status_pembayaran: menunggu/lunas/ditolak, hanya admin yang bisa mengubah.
     *
     * @param Schema $schema Schema Filament.
     * @return Schema Schema yang sudah dikonfigurasi dengan semua field.
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
               // Pilih booking - user hanya melihat booking miliknya, admin melihat semua
               Select::make('booking_id')
                    ->relationship('booking', 'id', function (Builder $query): void {
                        if (auth()->user()?->role !== 'admin') {
                            $query->where('user_id', auth()->id());
                        }
                    })
                    ->default(request()->get('booking_id'))
                    ->disabled(fn (string $operation): bool => $operation === 'edit' || request()->has('booking_id'))
                    ->dehydrated()
                    ->required(),

                // Pilih metode pembayaran
                Select::make('metode_pembayaran')
                    ->options([
                        'transfer_bank' => 'Transfer Bank',
                        'ewallet' => 'E-Wallet',
                        'qris' => 'QRIS',
                        'cash' => 'Cash',
                    ])
                    ->required(fn (string $operation): bool => $operation === 'create'),

                // Upload bukti pembayaran
                FileUpload::make('bukti_pembayaran')
                    ->directory('bukti-pembayaran')
                    ->image(),

                // Status pembayaran - hanya admin yang bisa mengubah
                Select::make('status_pembayaran')
                    ->options([
                        'menunggu' => 'Menunggu',
                        'lunas' => 'Lunas',
                        'ditolak' => 'Ditolak',
                    ])
                    ->default('menunggu')
                    ->disabled(fn () => auth()->user()?->role !== 'admin')
                    ->dehydrated()
                    ->required(),
            ]);
    }
}
