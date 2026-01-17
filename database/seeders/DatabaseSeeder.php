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
        // Menambahkan 5 user dummy statis untuk testing
        // Password default: password123
        
        // 1. Admin
        User::create([
            'username' => 'admin',
            'password' => Hash::make('password123'),
            'nama_lengkap' => 'Administrator',
            'email' => 'admin@sipuskes.com',
            'role' => 'admin',
        ]);

        // 2. Pendaftaran
        User::create([
            'username' => 'pendaftaran',
            'password' => Hash::make('password123'),
            'nama_lengkap' => 'Petugas Pendaftaran',
            'email' => 'pendaftaran@sipuskes.com',
            'role' => 'pendaftaran',
        ]);

        // 3. Dokter
        User::create([
            'username' => 'dokter1',
            'password' => Hash::make('password123'),
            'nama_lengkap' => 'Dr. Ahmad Hidayat',
            'email' => 'dokter1@sipuskes.com',
            'role' => 'dokter',
        ]);

        // 4. Apoteker
        User::create([
            'username' => 'apoteker',
            'password' => Hash::make('password123'),
            'nama_lengkap' => 'Apt. Siti Nurjanah',
            'email' => 'apoteker@sipuskes.com',
            'role' => 'apoteker',
        ]);

        // 5. Kepala Puskesmas
        User::create([
            'username' => 'kepala',
            'password' => Hash::make('password123'),
            'nama_lengkap' => 'Dr. Budi Santoso, M.Kes',
            'email' => 'kepala@sipuskes.com',
            'role' => 'kepala_puskesmas',
        ]);
    }
}
