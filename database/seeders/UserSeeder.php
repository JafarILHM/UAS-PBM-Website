<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'), // password terenkripsi
            'role' => 'admin', // optional, sesuaikan dengan field role kamu
        ]);

        // Kamu bisa buat user tambahan di sini
        User::create([
            'name' => 'Operator',
            'email' => 'operator@example.com',
            'password' => Hash::make('operator123'),
            'role' => 'operator',
        ]);
    }
}
