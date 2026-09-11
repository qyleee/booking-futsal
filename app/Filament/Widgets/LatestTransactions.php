<?php

namespace App\Filament\Widgets;

use App\Models\Transaksi;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

/**
 * LatestTransactions - Widget tabel yang menampilkan transaksi terbaru.
 * Menampilkan maksimal 3 transaksi terakhir beserta info user, lapangan, total, dan status.
 * Hanya bisa dilihat oleh admin.
 */
class LatestTransactions extends TableWidget
{
    /** Lebar widget (1 kolom). */
    protected int|string|array $columnSpan = 1;

    /**
     * Mengonfigurasi tabel widget untuk menampilkan transaksi terbaru.
     * Query: ambil 3 transaksi terbaru beserta relasi user dan lapangan.
     * Kolom: pelanggan, lapangan, total harga, dan status pembayaran.
     *
     * @param Table $table Tabel Filament.
     * @return Table Tabel yang sudah dikonfigurasi.
     */
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaksi::query()
                    ->with(['booking.user', 'booking.lapangan'])
                    ->latest('created_at')
                    ->limit(3),
            )
            ->heading('Transaksi Terbaru')
            ->description('Maksimal tiga transaksi terakhir')
            ->columns([
                TextColumn::make('booking.user.name')
                    ->label('Pelanggan')
                    ->limit(18),

                TextColumn::make('booking.lapangan.nama_lapangan')
                    ->label('Lapangan')
                    ->limit(18),

                TextColumn::make('booking.total_harga')
                    ->label('Total')
                    ->money('IDR', locale: 'id'),

                TextColumn::make('status_pembayaran')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->color(fn (string $state): string => match ($state) {
                        'lunas' => 'success',
                        'menunggu' => 'warning',
                        'ditolak' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->paginated(false);
    }

    /**
     * Menentukan apakah widget ini bisa dilihat.
     * Hanya admin yang bisa melihat transaksi terbaru.
     *
     * @return bool True jika admin.
     */
    public static function canView(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }
}
