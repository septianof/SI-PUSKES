<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Password default untuk semua user: password123
        $defaultPassword = Hash::make('password123');

        // 1. Admin
        User::create([
            'username' => 'admin',
            'password' => $defaultPassword,
            'nama_lengkap' => 'Administrator',
            'role' => 'admin',
        ]);

        // 2. Pendaftaran
        User::create([
            'username' => 'pendaftaran',
            'password' => $defaultPassword,
            'nama_lengkap' => 'Petugas Pendaftaran',
            'role' => 'pendaftaran',
        ]);

        // 3. Apoteker
        User::create([
            'username' => 'apoteker',
            'password' => $defaultPassword,
            'nama_lengkap' => 'Apt. Siti Nurjanah, S.Farm',
            'role' => 'apoteker',
        ]);

        // 4. Kepala Puskesmas
        User::create([
            'username' => 'kepala',
            'password' => $defaultPassword,
            'nama_lengkap' => 'Dr. Budi Santoso, M.Kes',
            'role' => 'kepala Puskesmas',
        ]);

        // 5-9. Dokter untuk setiap Poli (5 dokter)
        $dokters = [
            [
                'username' => 'dokter_umum',
                'nama_lengkap' => 'Dr. Ahmad Hidayat, Sp.PD',
                'poli_id' => 1, // Poli Umum
            ],
            [
                'username' => 'dokter_gigi',
                'nama_lengkap' => 'Dr. Siti Rahmawati, Sp.KG',
                'poli_id' => 2, // Poli Gigi
            ],
            [
                'username' => 'dokter_kia',
                'nama_lengkap' => 'Dr. Dewi Lestari, Sp.OG',
                'poli_id' => 3, // Poli KIA
            ],
            [
                'username' => 'dokter_lansia',
                'nama_lengkap' => 'Dr. Bambang Wijaya, Sp.PD',
                'poli_id' => 4, // Poli Lansia
            ],
            [
                'username' => 'dokter_tb',
                'nama_lengkap' => 'Dr. Rina Puspitasari, Sp.P',
                'poli_id' => 5, // Poli TB
            ],
        ];

        foreach ($dokters as $dokter) {
            User::create([
                'username' => $dokter['username'],
                'password' => $defaultPassword,
                'nama_lengkap' => $dokter['nama_lengkap'],
                'role' => 'dokter',
                'poli_id' => $dokter['poli_id'],
            ]);
        }
    }
}
