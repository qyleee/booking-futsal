<?php

namespace App\Filament\Resources\Bookings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

/**
 * BookingsTable - Mengonfigurasi tabel untuk daftar booking di Filament.
 * Menampilkan kolom: user, lapangan, tanggal, jam, total harga, status, dan timestamps.
 * Menyediakan filter berdasarkan status dan aksi edit/bulk delete.
 */
class BookingsTable
{
    /**
     * Mengonfigurasi tabel booking dengan kolom, filter, dan aksi.
     * - Edit hanya tersedia untuk admin (jika status memungkinkan) atau user pemilik (jika menunggu).
     * - Bulk delete hanya tersedia untuk admin.
     *
     * @param Table $table Tabel Filament.
     * @return Table Tabel yang sudah dikonfigurasi.
     */
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('User'),

                TextColumn::make('lapangan.nama_lapangan')
                    ->label('Lapangan'),

                TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                TextColumn::make('jam_mulai')
                    ->time()
                    ->sortable(),
                TextColumn::make('jam_selesai')
                    ->time()
                    ->sortable(),
                TextColumn::make('total_harga')
                    ->numeric()
                    ->sortable(),
                // Kolom status dengan badge warna: kuning=menunggu, hijau=dikonfirmasi, merah=dibatalkan, abu-abu=selesai
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                      'menunggu' => 'warning',
                      'dikonfirmasi' => 'success',
                      'dibatalkan' => 'danger',
                      'selesai' => 'gray',
                    default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
                    ->filters([
               SelectFilter::make('status')
                    ->options([
                       'menunggu' => 'Menunggu',
                       'dikonfirmasi' => 'Dikonfirmasi',
                       'selesai' => 'Selesai',
                       'dibatalkan' => 'Dibatalkan',
        ]),
])
            ->recordActions([
    EditAction::make()
        ->visible(fn ($record): bool =>
            auth()->user()?->role !== 'admin' &&
            $record->user_id === auth()->id() &&
            $record->status === 'menunggu'
        ),
       ])
            ->toolbarActions(
             auth()->user()?->role === 'admin'
        ? [
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ]
        : []
);
    }
}
