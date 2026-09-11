<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
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
 * Menggunakan warna tema Emerald (hijau).
 * Menyertakan custom CSS untuk animasi, hover effects, dan styling auth pages.
 */
class AdminPanelProvider extends PanelProvider
{
    /**
     * Mengonfigurasi panel admin Filament.
     * - ID: admin, Path: /admin.
     * - Brand name: "Booking Lapangan Futsal".
     * - Warna tema: Emerald.
     * - Auto-discover resources, pages, dan widgets dari direktori Filament.
     * - Middleware: session, auth, CSRF, dan EnsureCorrectPanel (redirect otomatis).
     * - Custom CSS via render hook: animasi hover, styling login/register, gradient background.
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
        '            <style>
            /* Sembunyikan checkbox di tabel */
            .fi-ta-page-checkbox,
            .fi-ta-group-checkbox {
                display: none !important;
            }

            /* Hapus border/background default pada widget chart dan table */
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

            /* Efek hover pada widget dashboard */
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

            /* Efek hover pada sidebar nav */
            .fi-sidebar-nav-item {
                transition: background 0.15s ease, color 0.15s ease !important;
                border-radius: 0.5rem !important;
            }

            .fi-sidebar-nav-item:hover {
                background: rgba(5, 150, 105, 0.08) !important;
            }

            /* Efek hover pada topbar user menu */
            .fi-topbar-item-acct {
                transition: background 0.15s ease !important;
            }

            .fi-topbar-item-acct:hover {
                background: rgba(0, 0, 0, 0.04) !important;
            }

            /* Styling halaman login/register */
            .fi-simple-layout {
                background: linear-gradient(135deg, #065f46 0%, #047857 50%, #059669 100%) !important;
                min-height: 100vh;
                position: relative;
                overflow: hidden;
            }

            .fi-simple-layout::before {
                content: "";
                position: absolute;
                top: -50%;
                right: -30%;
                width: 600px;
                height: 600px;
                background: rgba(255, 255, 255, 0.05);
                border-radius: 50%;
            }

            .fi-simple-layout::after {
                content: "";
                position: absolute;
                bottom: -40%;
                left: -20%;
                width: 500px;
                height: 500px;
                background: rgba(255, 255, 255, 0.03);
                border-radius: 50%;
            }

            .fi-simple-main {
                background: rgba(255, 255, 255, 0.95) !important;
                backdrop-filter: blur(20px);
                border-radius: 1.5rem !important;
                box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(255, 255, 255, 0.1) !important;
                padding: 2.5rem !important;
                position: relative;
                z-index: 1;
                animation: slideUp 0.6s ease;
            }

            @keyframes slideUp {
                from { opacity: 0; transform: translateY(30px); }
                to { opacity: 1; transform: translateY(0); }
            }

            .fi-simple-header svg,
            .fi-simple-header img {
                filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
                transition: transform 0.3s ease;
            }

            .fi-simple-header svg:hover,
            .fi-simple-header img:hover {
                transform: scale(1.05);
            }

            .fi-simple-header-heading {
                font-weight: 800 !important;
                font-size: 1.75rem !important;
                color: #111827 !important;
                letter-spacing: -0.025em;
            }

            .fi-simple-header-subheading {
                color: #6b7280 !important;
                font-size: 0.95rem !important;
                margin-top: 0.5rem;
            }

            .fi-input {
                border-radius: 0.75rem !important;
                border: 1.5px solid #e5e7eb !important;
                padding: 0.75rem 1rem !important;
                font-size: 0.95rem !important;
                transition: all 0.2s ease !important;
                background: #f9fafb !important;
            }

            .fi-input:focus {
                border-color: #059669 !important;
                box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.15) !important;
                background: #fff !important;
            }

            .fi-fo-field-wrp label,
            .fi-input-label {
                font-weight: 600 !important;
                color: #374151 !important;
                font-size: 0.875rem !important;
                margin-bottom: 0.375rem !important;
            }

            .fi-btn-primary {
                background: linear-gradient(135deg, #059669, #047857) !important;
                border: none !important;
                border-radius: 0.75rem !important;
                padding: 0.75rem 1.5rem !important;
                font-weight: 700 !important;
                font-size: 0.95rem !important;
                box-shadow: 0 4px 14px rgba(5, 150, 105, 0.35) !important;
                transition: all 0.3s ease !important;
                position: relative;
                overflow: hidden;
            }

            .fi-btn-primary::before {
                content: "";
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
                transition: left 0.5s ease;
            }

            .fi-btn-primary:hover::before {
                left: 100%;
            }

            .fi-btn-primary:hover {
                transform: translateY(-2px) !important;
                box-shadow: 0 8px 25px rgba(5, 150, 105, 0.45) !important;
            }

            .fi-simple-page a {
                color: #059669 !important;
                font-weight: 600 !important;
                transition: color 0.2s ease;
            }

            .fi-simple-page a:hover {
                color: #047857 !important;
                text-decoration: underline !important;
            }

            .fi-checkbox-input {
                border-radius: 0.375rem !important;
                border: 1.5px solid #d1d5db !important;
                transition: all 0.2s ease !important;
            }

            .fi-checkbox-input:checked {
                background-color: #059669 !important;
                border-color: #059669 !important;
            }

            .fi-fo-field-wrp-invalid .fi-input {
                border-color: #ef4444 !important;
            }

            .fi-fo-field-wrp-invalid .fi-input:focus {
                box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15) !important;
            }

            .fi-fo-field-wrp-alert {
                border-radius: 0.75rem !important;
                border: 1px solid #d1fae5 !important;
                background: #ecfdf5 !important;
                color: #065f46 !important;
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
