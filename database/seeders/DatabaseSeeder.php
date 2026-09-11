<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * DatabaseSeeder - Seeder utama untuk mengisi database awal.
 * Membuat satu user test default dengan role user.
 */
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Menjalankan seeder untuk mengisi database.
     * Membuat user test dengan email test@example.com.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
