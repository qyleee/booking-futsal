<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;

/**
 * BookingChart - Widget grafik pie chart untuk statistik booking.
 * Menampilkan distribusi booking berdasarkan status: dikonfirmasi, menunggu, dibatalkan.
 * Hanya bisa dilihat oleh admin.
 */
class BookingChart extends ChartWidget
{
    /** Lebar widget (1 kolom). */
    protected int|string|array $columnSpan = 1;

    /** Judul widget. */
    protected ?string $heading = 'Statistik Booking';

    /** Tinggi maksimum chart. */
    protected ?string $maxHeight = '180px';

    /**
     * Mendapatkan data untuk pie chart.
     * Menghitung jumlah booking per status: dikonfirmasi, menunggu, dibatalkan.
     * Label ditampilkan dengan format "Status (jumlah)".
     *
     * @return array Data chart dalam format Chart.js.
     */
    protected function getData(): array
    {
        $counts = [
            'Dikonfirmasi' => Booking::where('status', 'dikonfirmasi')->count(),
            'Menunggu' => Booking::where('status', 'menunggu')->count(),
            'Dibatalkan' => Booking::where('status', 'dibatalkan')->count(),
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Booking',
                    'data' => array_values($counts),
                    'backgroundColor' => [
                        '#22c55e',
                        '#f59e0b',
                        '#ef4444',
                    ],
                    'borderWidth' => 2,
                    'borderColor' => '#ffffff',
                ],
            ],
            'labels' => array_map(
                fn (string $label, int $count): string => "{$label} ({$count})",
                array_keys($counts),
                array_values($counts),
            ),
        ];
    }

    /**
     * Tipe chart yang digunakan.
     *
     * @return string Tipe pie chart.
     */
    protected function getType(): string
    {
        return 'pie';
    }

    /**
     * Opsi konfigurasi chart (responsive, legend, tooltip).
     * Tooltip menampilkan persentase dari total booking.
     *
     * @return array|RawJs|null Opsi chart.
     */
    protected function getOptions(): array|RawJs|null
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'layout' => [
                'padding' => 18,
            ],
            'plugins' => [
                'legend' => [
                    'position' => 'right',
                    'labels' => [
                        'usePointStyle' => true,
                        'boxWidth' => 10,
                        'padding' => 14,
                    ],
                ],
                'tooltip' => [
                    'displayColors' => true,
                    'callbacks' => [
                        'label' => RawJs::make(
                            '(context) => {
                                const values = context.dataset.data;
                                const total = values.reduce((sum, value) => sum + Number(value), 0);
                                const count = Number(context.parsed);
                                const percentage = total > 0 ? ((count / total) * 100).toFixed(1) : "0.0";

                                return " " + context.label + ": " + count + " booking (" + percentage + "%)";
                            }',
                        ),
                    ],
                ],
            ],
        ];
    }

    /**
     * Menentukan apakah widget ini bisa dilihat.
     * Hanya admin yang bisa melihat chart booking.
     *
     * @return bool True jika admin.
     */
    public static function canView(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }
}
