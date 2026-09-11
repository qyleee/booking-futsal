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
}
