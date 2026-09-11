<?php

namespace App\Filament\Resources\Lapangans;

use App\Filament\Resources\Lapangans\Pages\CreateLapangan;
use App\Filament\Resources\Lapangans\Pages\EditLapangan;
use App\Filament\Resources\Lapangans\Pages\ListLapangans;
use App\Filament\Resources\Lapangans\Schemas\LapanganForm;
use App\Filament\Resources\Lapangans\Tables\LapangansTable;
use App\Models\Lapangan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

/**
 * LapanganResource - Resource Filament untuk CRUD data Lapangan futsal.
 * Hanya bisa diakses oleh admin. Menyediakan halaman list, create, dan edit lapangan.
 */
class LapanganResource extends Resource
{
    /** Model yang digunakan oleh resource ini. */
    protected static ?string $model = Lapangan::class;

    /**
     * Menentukan apakah user boleh mengakses resource ini.
     * Hanya admin yang boleh mengelola data lapangan.
     *
     * @return bool True jika admin, false jika bukan.
     */
    public static function canAccess(): bool
    {
    return auth()->user()->role === 'admin';
    }

    /** Ikon navigasi di sidebar (gedung). */
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice;

    /** Kolom yang ditampilkan sebagai judul record. */
    protected static ?string $recordTitleAttribute = 'nama_lapangan';

    /** Label navigasi di sidebar. */
    protected static ?string $navigationLabel = 'Lapangan';
    protected static ?string $pluralModelLabel = 'Lapangan';
    protected static ?string $modelLabel = 'Lapangan';

    /**
     * Mengonfigurasi form untuk create/edit lapangan.
     *
     * @param Schema $schema Schema Filament.
     * @return Schema Schema yang sudah dikonfigurasi.
     */
    public static function form(Schema $schema): Schema
    {
        return LapanganForm::configure($schema);
    }

    /**
     * Mengonfigurasi tabel untuk daftar lapangan.
     *
     * @param Table $table Tabel Filament.
     * @return Table Tabel yang sudah dikonfigurasi.
     */
    public static function table(Table $table): Table
    {
        return LapangansTable::configure($table);
    }

    /**
     * Mendapatkan relasi yang ditampilkan di form/detail.
     *
     * @return array Daftar relasi.
     */
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    /**
     * Mendapatkan rute halaman-halaman resource ini.
     * - index: daftar lapangan.
     * - create: tambah lapangan baru.
     * - edit: edit lapangan yang sudah ada.
     *
     * @return array<string, string> Mapping rute halaman.
     */
    public static function getPages(): array
    {
        return [
            'index' => ListLapangans::route('/'),
            'create' => CreateLapangan::route('/create'),
            'edit' => EditLapangan::route('/{record}/edit'),
        ];
    }

    
}
