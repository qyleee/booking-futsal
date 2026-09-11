<?php

namespace App\Filament\Resources\Bookings;

use App\Filament\Resources\Bookings\Pages\CreateBooking;
use App\Filament\Resources\Bookings\Pages\EditBooking;
use App\Filament\Resources\Bookings\Pages\ListBookings;
use App\Filament\Resources\Bookings\Schemas\BookingForm;
use App\Filament\Resources\Bookings\Tables\BookingsTable;
use App\Models\Booking;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * BookingResource - Resource Filament untuk CRUD data Booking.
 * Menyediakan halaman list, create, dan edit booking.
 * Admin bisa melihat semua booking, user hanya bisa melihat booking miliknya sendiri.
 */
class BookingResource extends Resource
{
    /** Model yang digunakan oleh resource ini. */
    protected static ?string $model = Booking::class;

    /** Ikon navigasi di sidebar (kalender). */
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    /** Kolom yang ditampilkan sebagai judul record. */
    protected static ?string $recordTitleAttribute = 'status';

    /** Label navigasi di sidebar. */
    protected static ?string $navigationLabel = 'Booking';
    protected static ?string $pluralModelLabel = 'Booking';
    protected static ?string $modelLabel = 'Booking';

    /**
     * Query Eloquent untuk resource ini.
     * User biasa hanya bisa melihat booking miliknya sendiri.
     * Admin bisa melihat semua booking.
     *
     * @return Builder Query builder.
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->check() && auth()->user()->role !== 'admin') {
            $query->where('user_id', auth()->id());
        }

        return $query;
    }

    /**
     * Menentukan apakah user boleh mengedit booking.
     * - Admin bisa edit jika status bukan dikonfirmasi/selesai/dibatalkan.
     * - User bisa edit hanya jika status menunggu dan itu booking miliknya.
     *
     * @param mixed $record Record booking.
     * @return bool True jika boleh edit.
     */
    public static function canEdit($record): bool
    {
        if (! auth()->check()) {
            return false;
        }

        // Admin tidak bisa edit booking, hanya bisa lihat
        if (auth()->user()?->role === 'admin') {
            return false;
        }

        // User hanya bisa edit jika status menunggu dan itu booking miliknya
        return $record->user_id === auth()->id()
            && $record->status === 'menunggu';
    }

    /**
     * Menentukan apakah user boleh menghapus booking.
     * Hanya admin yang boleh menghapus.
     *
     * @param mixed $record Record booking.
     * @return bool True jika boleh hapus.
     */
    public static function canDelete($record): bool
    {
        return auth()->user()?->role === 'admin';
    }

    /**
     * Mengonfigurasi form untuk create/edit booking.
     *
     * @param Schema $schema Schema Filament.
     * @return Schema Schema yang sudah dikonfigurasi.
     */
    public static function form(Schema $schema): Schema
    {
        return BookingForm::configure($schema);
    }

    /**
     * Mengonfigurasi tabel untuk daftar booking.
     *
     * @param Table $table Tabel Filament.
     * @return Table Tabel yang sudah dikonfigurasi.
     */
    public static function table(Table $table): Table
    {
        return BookingsTable::configure($table);
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
     * - index: daftar booking.
     * - create: buat booking baru.
     * - edit: edit booking yang sudah ada.
     *
     * @return array<string, string> Mapping rute halaman.
     */
    public static function getPages(): array
    {
        return [
            'index' => ListBookings::route('/'),
            'create' => CreateBooking::route('/create'),
            'edit' => EditBooking::route('/{record}/edit'),
        ];
    }
}
