<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Air Hotel',
            'email' => 'admin@airhotel.com',
            'password' => Hash::make('password123'),
            'phone' => '081234567890',
            'role' => 'admin',
        ]);

        // User biasa untuk testing
        User::create([
            'name' => 'User Biasa',
            'email' => 'user@airhotel.com',
            'password' => Hash::make('password123'),
            'phone' => '081234567891',
            'role' => 'user',
        ]);
    }
}