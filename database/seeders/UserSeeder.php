<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan tabel kosong dulu agar tidak duplikat saat dijalankan ulang
        // User::truncate(); // Hati-hati, ini menghapus semua user!

        // Akun untuk Kepala Gudang (Admin)
        User::create([
            'name' => 'Kepala Gudang',
            'email' => 'admin@gudang.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Akun untuk Staff Biasa
        User::create([
            'name' => 'Staff Operator',
            'email' => 'staff@gudang.com',
            'password' => Hash::make('staff123'),
            'role' => 'staff',
        ]);
    }
}
