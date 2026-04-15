<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat akun admin default
        User::firstOrCreate(
            ['email' => 'admin@bpstrade.id'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
            ]
        );

        // Buat akun user biasa (opsional, untuk testing)
        User::firstOrCreate(
            ['email' => 'user@bpstrade.id'],
            [
                'name'     => 'Test User',
                'password' => Hash::make('user123'),
                'role'     => 'user',
            ]
        );
    }
}
