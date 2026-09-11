<?php

namespace App\Filament\Resources\Lapangans\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

/**
 * LapanganForm - Mengonfigurasi form untuk create/edit lapangan.
 * Berisi field: nama_lapangan, harga_per_jam, dan image (upload gambar).
 */
class LapanganForm
{
    /**
     * Mengonfigurasi semua komponen form lapangan.
     *
     * @param Schema $schema Schema Filament.
     * @return Schema Schema yang sudah dikonfigurasi dengan semua field.
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_lapangan')
                    ->required(),

                TextInput::make('harga_per_jam')
                    ->numeric()
                    ->required(),

                // Upload gambar lapangan ke storage/public/lapangan
                FileUpload::make('image')
                    ->image()
                    ->directory('lapangan')
                    ->disk('public'),
            ]);
    }
}
