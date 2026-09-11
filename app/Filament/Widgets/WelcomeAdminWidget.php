<?php

namespace App\Filament\Widgets;

use Filament\Widgets\AccountWidget;

/**
 * WelcomeAdminWidget - Widget sambutan di dashboard admin.
 * Menampilkan informasi akun user yang sedang login.
 * Digunakan sebagai widget pembuka di panel admin.
 */
class WelcomeAdminWidget extends AccountWidget
{
    /** Lebar widget (full width). */
    protected int|string|array $columnSpan = 'full';
}
