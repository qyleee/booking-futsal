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
 * UserPanelProvider - Konfigurasi panel user Filament.
 * Panel ini bisa diakses oleh user dengan role 'user' atau 'admin'.
 * Path: /user. Brand: "FutsalBook".
 * Login dan registrasi ditangani oleh controller custom di /login dan /register.
 * Menggunakan warna tema Emerald (hijau).
 */
class UserPanelProvider extends PanelProvider
{
    /**
     * Mengonfigurasi panel user Filament.
     * - ID: user, Path: /user.
     * - Brand name: "FutsalBook".
     * - Login dan registrasi DIHAPUS (ditangani controller custom).
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
            ->id('user')
            ->path('user')
            ->brandName('FutsalBook')
            ->homeUrl('/user')
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
                        .fi-wi-table tr {
                            transition: background 0.15s ease !important;
                        }
                        .fi-wi-table tbody tr:hover {
                            background: rgba(5, 150, 105, 0.04) !important;
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
