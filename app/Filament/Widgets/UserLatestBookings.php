<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

/**
 * UserLatestBookings - Widget tabel yang menampilkan booking terbaru milik user.
 * Menampilkan maksimal 3 booking terakhir beserta info lapangan, tanggal, jam, total, dan status.
 * Hanya bisa dilihat oleh user (bukan admin).
 */
class UserLatestBookings extends TableWidget
{
    /** Lebar widget (full width). */
    protected int|string|array $columnSpan = 'full';

    /**
     * Mengonfigurasi tabel widget untuk menampilkan booking terbaru user.
     * Query: ambil 3 booking terbaru milik user yang sedang login beserta relasi lapangan.
     * Kolom: lapangan, tanggal, jam, total harga, dan status pemesanan.
     *
     * @param Table $table Tabel Filament.
     * @return Table Tabel yang sudah dikonfigurasi.
     */
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Booking::query()
                    ->with('lapangan')
                    ->where('user_id', auth()->id())
                    ->latest('created_at')
                    ->limit(3),
            )
            ->heading('Status Pemesanan Terakhir')
            ->description('Maksimal tiga booking terakhir')
            ->columns([
                TextColumn::make('lapangan.nama_lapangan')
                    ->label('Lapangan'),

                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y'),

                TextColumn::make('jam_mulai')
                    ->label('Jam')
                    ->formatStateUsing(fn ($state, Booking $record): string => "{$record->jam_mulai} - {$record->jam_selesai}"),

                TextColumn::make('total_harga')
                    ->label('Total')
                    ->money('IDR', locale: 'id'),

                TextColumn::make('status')
                    ->label('Status Pemesanan')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->color(fn (string $state): string => match ($state) {
                        'menunggu' => 'warning',
                        'dikonfirmasi' => 'success',
                        'selesai' => 'gray',
                        'dibatalkan' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->paginated(false);
    }

    /**
     * Menentukan apakah widget ini bisa dilihat.
     * Hanya user (bukan admin) yang bisa melihat booking terakhir mereka.
     *
     * @return bool True jika user biasa.
     */
    public static function canView(): bool
    {
        return auth()->check() && auth()->user()->role === 'user';
    }
}
