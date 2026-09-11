<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\BookingChart;
use App\Filament\Widgets\LatestTransactions;
use App\Filament\Widgets\PaymentOverview;
use App\Filament\Widgets\UserExpenseOverview;
use App\Filament\Widgets\UserLatestBookings;
use App\Filament\Widgets\WelcomeAdminWidget;
use Filament\Pages\Dashboard as BaseDashboard;

/**
 * Dashboard - Halaman dashboard utama di Filament.
 * Menampilkan widget-widget sesuai role user:
 * - Admin: WelcomeAdminWidget, PaymentOverview, BookingChart, LatestTransactions.
 * - User: UserExpenseOverview, UserLatestBookings.
 * Menggunakan 2 kolom layout.
 */
class Dashboard extends BaseDashboard
{
    /**
     * Mendapatkan daftar widget yang ditampilkan di dashboard.
     * Widget yang tidak sesuai role akan otomatis disembunyikan oleh method canView() di masing-masing widget.
     *
     * @return array Daftar class widget.
     */
    public function getWidgets(): array
    {
        return [
            WelcomeAdminWidget::class,
            PaymentOverview::class,
            UserExpenseOverview::class,
            UserLatestBookings::class,
            BookingChart::class,
            LatestTransactions::class,
        ];
    }

    /**
     * Mendapatkan jumlah kolom layout dashboard.
     *
     * @return int|array Jumlah kolom (2).
     */
    public function getColumns(): int|array
    {
        return 2;
    }
}
