<?php

namespace App\Providers\Filament;

use App\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Http\Middleware\EnsureCorrectPanel;

/**
 * AdminPanelProvider - Konfigurasi panel admin Filament.
 * Panel ini hanya bisa diakses oleh user dengan role 'admin'.
 * Path: /admin. Brand: "Booking Lapangan Futsal".
 * Login ditangani oleh controller custom di /login.
 * Menggunakan warna tema Emerald (hijau).
 */
class AdminPanelProvider extends PanelProvider
{
    /**
     * Mengonfigurasi panel admin Filament.
     * - ID: admin, Path: /admin.
     * - Brand name: "Booking Lapangan Futsal".
     * - Login DIHAPUS (ditangani controller custom di /login).
     * - Warna tema: Emerald.
     * - Auto-discover resources, pages, dan widgets dari direktori Filament.
     * - Middleware: session, auth, CSRF, dan EnsureCorrectPanel.
     * - Custom Authenticate middleware redirect ke /login.
     *
     * @param Panel $panel Panel Filament.
     * @return Panel Panel yang sudah dikonfigurasi.
     */
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->assets([])
            ->brandName('Booking Lapangan Futsal')
            ->homeUrl('/admin')
            ->colors([
                'primary' => Color::Emerald,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                \App\Filament\Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->renderHook(
    PanelsRenderHook::HEAD_END,
    fn (): HtmlString => new HtmlString(
        '<style>
            .fi-ta-page-checkbox,
            .fi-ta-group-checkbox {
                display: none !important;
            }
            .fi-wi-chart > .fi-section,
            .fi-wi-table > .fi-section {
                border: 0 !important;
                background: transparent !important;
                box-shadow: none !important;
            }
            .fi-wi-chart > .fi-section > .fi-section-content-ctn,
            .fi-wi-table > .fi-section > .fi-section-content-ctn {
                padding-inline: 0 !important;
            }
            .fi-wi-chart .fi-section-header,
            .fi-wi-table .fi-section-header {
                padding-inline: 0 !important;
            }
            .fi-wi-chart .fi-section-content,
            .fi-wi-chart .fi-wi-chart-frame {
                overflow: visible !important;
            }
            .fi-section {
                transition: transform 0.25s ease, box-shadow 0.25s ease !important;
            }
            .fi-section:hover {
                transform: translateY(-2px) !important;
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06) !important;
            }
            .fi-stat-widget .fi-section {
                transition: transform 0.25s ease, box-shadow 0.25s ease !important;
                border-radius: 1rem !important;
            }
            .fi-stat-widget .fi-section:hover {
                transform: translateY(-3px) !important;
                box-shadow: 0 12px 28px rgba(5, 150, 105, 0.12) !important;
            }
            .fi-stat-widget .fi-stat-card {
                transition: background 0.2s ease !important;
                border-radius: 0.75rem !important;
            }
            .fi-stat-widget .fi-stat-card:hover {
                background: rgba(5, 150, 105, 0.03) !important;
            }
            .fi-wi-table .fi-section {
                border-radius: 1rem !important;
            }
            .fi-wi-table tr {
                transition: background 0.15s ease !important;
            }
            .fi-wi-table tbody tr:hover {
                background: rgba(5, 150, 105, 0.04) !important;
            }
            .fi-wi-chart .fi-section {
                border-radius: 1rem !important;
            }
            .fi-sidebar-nav-item {
                transition: background 0.15s ease, color 0.15s ease !important;
                border-radius: 0.5rem !important;
            }
            .fi-sidebar-nav-item:hover {
                background: rgba(5, 150, 105, 0.08) !important;
            }
            .fi-topbar-item-acct {
                transition: background 0.15s ease !important;
            }
            .fi-topbar-item-acct:hover {
                background: rgba(0, 0, 0, 0.04) !important;
            }
        </style>',
    ),
)
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                EnsureCorrectPanel::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
