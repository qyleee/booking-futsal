<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * UserExpenseOverview - Widget statistik total pengeluaran user yang sedang login.
 * Menampilkan total Rupiah dari booking yang sudah dibayar lunas oleh user ini.
 * Hanya bisa dilihat oleh user (bukan admin).
 */
class UserExpenseOverview extends BaseWidget
{
    /** Lebar widget (full width). */
    protected int|string|array $columnSpan = 'full';

    /** Jumlah kolom statistik (1 kartu). */
    protected int|array|null $columns = 1;

    /**
     * Mendapatkan data statistik pengeluaran user.
     * Menjumlahkan total_harga dari booking milik user ini yang sudah lunas.
     *
     * @return array Daftar objek Stat.
     */
    protected function getStats(): array
    {
        $expenses = Booking::query()
            ->where('user_id', auth()->id())
            ->whereHas(
                'transaksi',
                fn ($query) => $query->where('status_pembayaran', 'lunas'),
            )
            ->sum('total_harga');

        return [
            Stat::make(
                'Pengeluaran Saya',
                'Rp '.number_format($expenses, 0, ',', '.'),
            )
                ->description('Total booking yang sudah dibayar')
                ->icon('heroicon-o-wallet')
                ->color('warning'),
        ];
    }

    /**
     * Menentukan apakah widget ini bisa dilihat.
     * Hanya user (bukan admin) yang bisa melihat pengeluaran mereka.
     *
     * @return bool True jika user biasa.
     */
    public static function canView(): bool
    {
        return auth()->check() && auth()->user()->role === 'user';
    }
}
