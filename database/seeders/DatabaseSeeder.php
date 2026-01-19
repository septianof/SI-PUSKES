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
        // PENTING: Seed Poli dan Obat DULU sebelum users (karena dokter butuh poli_id)
        $this->call([
            PoliSeeder::class,
            ObatSeeder::class,
        ]);

        // Menambahkan 6 user dummy statis untuk testing
        // Password default: password123
        
        // 1. Admin
        User::create([
            'username' => 'admin',
            'password' => Hash::make('password123'),
            'nama_lengkap' => 'Administrator',
            'role' => 'admin',
        ]);

        // 2. Pendaftaran
        User::create([
            'username' => 'pendaftaran',
            'password' => Hash::make('password123'),
            'nama_lengkap' => 'Petugas Pendaftaran',
            'role' => 'pendaftaran',
        ]);

        // 3. Dokter Poli Umum
        User::create([
            'username' => 'dokter1',
            'password' => Hash::make('password123'),
            'nama_lengkap' => 'Dr. Ahmad Hidayat',
            'role' => 'dokter',
            'poli_id' => 1, // Poli Umum
        ]);

        // 3b. Dokter Poli Gigi
        User::create([
            'username' => 'dokter2',
            'password' => Hash::make('password123'),
            'nama_lengkap' => 'Dr. Siti Rahmawati',
            'role' => 'dokter',
            'poli_id' => 2, // Poli Gigi
        ]);

        // 4. Apoteker
        User::create([
            'username' => 'apoteker',
            'password' => Hash::make('password123'),
            'nama_lengkap' => 'Apt. Siti Nurjanah',
            'role' => 'apoteker',
        ]);

        // 5. Kepala Puskesmas
        User::create([
            'username' => 'kepala',
            'password' => Hash::make('password123'),
            'nama_lengkap' => 'Dr. Budi Santoso, M.Kes',
            'role' => 'kepala Puskesmas',
        ]);

        // Seed data dummy pasien dan kunjungan untuk testing
        $this->call([
            PasienSeeder::class,
        ]);
    }
}

