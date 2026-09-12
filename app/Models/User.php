<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

/**
 * Model User - Menyimpan data pengguna aplikasi.
 * Menggunakan role-based access: admin dan user.
 * Admin bisa mengakses panel admin, user hanya bisa mengakses panel user.
 * Mengimplementasikan FilamentUser untuk integrasi akses panel Filament.
 */
#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Mengonversi tipe data kolom tertentu secara otomatis.
     * - email_verified_at dikonversi ke tipe datetime.
     * - password di-hash otomatis saat disimpan.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Menentukan apakah user boleh mengakses panel Filament tertentu.
     * - Panel 'admin': hanya bisa diakses user dengan role 'admin'.
     * - Panel 'user': bisa diakses user dengan role 'user' atau 'admin'.
     *
     * @param Panel $panel Panel Filament yang sedang diakses.
     * @return bool True jika diizinkan, false jika tidak.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->role === 'admin';
        }

        if ($panel->getId() === 'user') {
            return in_array($this->role, ['user', 'admin']);
        }

        return false;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        $name = trim($this->name ?? '?');
        $parts = array_filter(explode(' ', $name));
        $initials = strtoupper(
            mb_substr($parts[0] ?? '?', 0, 1) .
            mb_substr(end($parts) ?? '', 0, 1)
        );

        $colors = [
            '#10b981', '#3b82f6', '#8b5cf6', '#f59e0b',
            '#ef4444', '#ec4899', '#06b6d4', '#84cc16',
        ];
        $color = $colors[crc32($this->email ?? $name) % count($colors)];

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="128" height="128" viewBox="0 0 128 128">'
            . '<rect width="128" height="128" rx="64" fill="' . $color . '"/>'
            . '<text x="64" y="64" font-family="Inter,system-ui,sans-serif" font-size="48" font-weight="600" fill="#ffffff" text-anchor="middle" dominant-baseline="central">'
            . htmlspecialchars($initials)
            . '</text></svg>';

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}
