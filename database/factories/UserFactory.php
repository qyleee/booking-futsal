<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * UserFactory - Factory untuk membuat data user palsu (dummy).
 * Digunakan untuk testing dan pengembangan.
 *
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * Password default yang digunakan oleh factory.
     * Disimpan statis agar konsisten antar pembuatan user.
     */
    protected static ?string $password;

    /**
     * Mendapatkan state default model user.
     * - name: nama acak.
     * - email: email unik.
     * - email_verified_at: sudah terverifikasi.
     * - password: 'password' (hashed).
     * - remember_token: token acak 10 karakter.
     *
     * @return array<string, mixed> Data user default.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Menandai user sebagai belum terverifikasi email-nya.
     * Mengatur email_verified_at menjadi null.
     *
     * @return static Factory dengan state unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
