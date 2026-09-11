<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * PaymentOverview - Widget statistik total pembayaran masuk (lunas).
 * Menampilkan total Rupiah dari semua booking yang sudah dibayar lunas.
 * Hanya bisa dilihat oleh admin.
 */
class PaymentOverview extends BaseWidget
{
    /** Lebar widget (full width). */
    protected int|string|array $columnSpan = 'full';

    /** Jumlah kolom statistik (1 kartu). */
    protected int|array|null $columns = 1;

    /**
     * Mendapatkan data statistik pembayaran masuk.
     * Menjumlahkan total_harga dari booking yang memiliki transaksi dengan status lunas.
     *
     * @return array Daftar objek Stat.
     */
    protected function getStats(): array
    {
        $paymentIn = Booking::whereHas(
            'transaksi',
            fn ($query) => $query->where('status_pembayaran', 'lunas'),
        )->sum('total_harga');

        return [
            Stat::make(
                'Pembayaran Masuk',
                'Rp '.number_format($paymentIn, 0, ',', '.'),
            )
                ->description('Total pembayaran lunas')
                ->icon('heroicon-o-banknotes')
                ->color('success'),
        ];
    }

    /**
     * Menentukan apakah widget ini bisa dilihat.
     * Hanya admin yang bisa melihat overview pembayaran.
     *
     * @return bool True jika admin.
     */
    public static function canView(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }
}
