<?php

namespace App\Filament\Resources\Lapangans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * LapangansTable - Mengonfigurasi tabel untuk daftar lapangan di Filament.
 * Menampilkan kolom: nama_lapangan (searchable), harga_per_jam, dan created_at.
 * Menyediakan aksi edit per baris dan bulk delete.
 */
class LapangansTable
{
    /**
     * Mengonfigurasi tabel lapangan dengan kolom dan aksi.
     * Nama lapangan bisa dicari (searchable), harga bisa diurutkan (sortable).
     *
     * @param Table $table Tabel Filament.
     * @return Table Tabel yang sudah dikonfigurasi.
     */
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
    TextColumn::make('nama_lapangan')
        ->searchable(),

    TextColumn::make('harga_per_jam')
        ->numeric()
        ->sortable(),

    TextColumn::make('created_at')
        ->dateTime()
        ->sortable()
        ->toggleable(isToggledHiddenByDefault: true),
])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
