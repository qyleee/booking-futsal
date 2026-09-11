<?php

namespace App\Filament\Resources\Transaksis;

use App\Filament\Resources\Transaksis\Pages\CreateTransaksi;
use App\Filament\Resources\Transaksis\Pages\ListTransaksis;
use App\Filament\Resources\Transaksis\Schemas\TransaksiForm;
use App\Filament\Resources\Transaksis\Tables\TransaksisTable;
use App\Models\Transaksi;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * TransaksiResource - Resource Filament untuk data Transaksi (pembayaran).
 * Admin bisa melihat semua transaksi, user hanya melihat transaksi miliknya.
 * Admin bisa create/delete, tapi semua user tidak bisa edit transaksi.
 */
class TransaksiResource extends Resource
{
    /** Model yang digunakan oleh resource ini. */
    protected static ?string $model = Transaksi::class;

    /** Ikon navigasi di sidebar (kartu kredit). */
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    /** Kolom yang ditampilkan sebagai judul record. */
    protected static ?string $recordTitleAttribute = 'Transaksi';

    /** Label navigasi di sidebar. */
    protected static ?string $navigationLabel = 'Transaksi';
    protected static ?string $pluralModelLabel = 'Transaksi';
    protected static ?string $modelLabel = 'Transaksi';

    /**
     * Mengonfigurasi form untuk create transaksi.
     *
     * @param Schema $schema Schema Filament.
     * @return Schema Schema yang sudah dikonfigurasi.
     */
    public static function form(Schema $schema): Schema
    {
        return TransaksiForm::configure($schema);
    }

    /**
     * Mengonfigurasi tabel untuk daftar transaksi.
     *
     * @param Table $table Tabel Filament.
     * @return Table Tabel yang sudah dikonfigurasi.
     */
    public static function table(Table $table): Table
    {
        return TransaksisTable::configure($table);
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
     * - index: daftar transaksi.
     * - create: buat transaksi baru.
     *
     * @return array<string, string> Mapping rute halaman.
     */
    public static function getPages(): array
    {
        return [
            'index' => ListTransaksis::route('/'),
            'create' => CreateTransaksi::route('/create'),
        ];
    }

    /**
     * Query Eloquent untuk resource ini.
     * Admin bisa melihat semua transaksi.
     * User hanya bisa melihat transaksi yang terkait dengan booking miliknya.
     *
     * @return Builder Query builder.
     */
    public static function getEloquentQuery(): Builder
    {
        if (auth()->user()->role === 'admin') {
            return parent::getEloquentQuery();
        }

        return parent::getEloquentQuery()
            ->whereHas('booking', function ($query) {
                $query->where('user_id', auth()->id());
            });
    }

    /**
     * Menentukan apakah user boleh membuat transaksi baru.
     * Hanya admin yang boleh.
     *
     * @return bool True jika admin.
     */
    public static function canCreate(): bool
    {
        return auth()->user()->role === 'admin';
    }

    /**
     * Menentukan apakah user boleh mengedit transaksi.
     * Tidak ada user yang boleh mengedit transaksi (selalu false).
     *
     * @param Model $record Record transaksi.
     * @return bool Selalu false.
     */
    public static function canEdit(Model $record): bool
    {
        return false;
    }

    /**
     * Menentukan apakah user boleh menghapus transaksi.
     * Hanya admin yang boleh.
     *
     * @param Model $record Record transaksi.
     * @return bool True jika admin.
     */
    public static function canDelete(Model $record): bool
    {
        return auth()->user()->role === 'admin';
    }

    /**
     * Menentukan apakah user boleh menghapus beberapa transaksi sekaligus.
     * Hanya admin yang boleh.
     *
     * @return bool True jika admin.
     */
    public static function canDeleteAny(): bool
    {
        return auth()->user()->role === 'admin';
    }
}
