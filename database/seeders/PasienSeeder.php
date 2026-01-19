<?php

namespace Database\Seeders;

use App\Models\Pasien;
use App\Models\Kunjungan;
use Illuminate\Database\Seeder;

class PasienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dummy Pasien 1 - Untuk Poli Umum
        $pasien1 = Pasien::create([
            'no_rm' => 'RM-2026-001',
            'nik' => '3201012001900001',
            'nama' => 'Budi Santoso',
            'alamat' => 'Jl. Merdeka No. 123, Jakarta',
            'tgl_lahir' => '1990-01-20',
            'no_bpjs' => '0001234567890',
        ]);

        // Dummy Pasien 2 - Untuk Poli Umum
        $pasien2 = Pasien::create([
            'no_rm' => 'RM-2026-002',
            'nik' => '3201012002910002',
            'nama' => 'Siti Aisyah',
            'alamat' => 'Jl. Sudirman No. 45, Jakarta',
            'tgl_lahir' => '1991-02-15',
            'no_bpjs' => null, // Pasien Umum
        ]);

        // Dummy Pasien 3 - Untuk Poli Gigi
        $pasien3 = Pasien::create([
            'no_rm' => 'RM-2026-003',
            'nik' => '3201012003920003',
            'nama' => 'Ahmad Dahlan',
            'alamat' => 'Jl. Gatot Subroto No. 78, Jakarta',
            'tgl_lahir' => '1992-03-10',
            'no_bpjs' => '0009876543210',
        ]);

        // Dummy Pasien 4 - Untuk Poli Gigi
        $pasien4 = Pasien::create([
            'no_rm' => 'RM-2026-004',
            'nik' => '3201012004930004',
            'nama' => 'Dewi Lestari',
            'alamat' => 'Jl. Thamrin No. 90, Jakarta',
            'tgl_lahir' => '1993-04-25',
            'no_bpjs' => null, // Pasien Umum
        ]);

        // === DUMMY KUNJUNGAN STATUS MENUNGGU ===

        // Kunjungan 1 - Poli Umum (ID: 1) - Status Menunggu
        Kunjungan::create([
            'pasien_id' => $pasien1->id,
            'poli_id' => 1, // Poli Umum
            'tgl_kunjungan' => now(),
            'status' => 'menunggu',
            'keluhan_awal' => 'Demam dan batuk sejak 3 hari',
        ]);

        // Kunjungan 2 - Poli Umum (ID: 1) - Status Menunggu
        Kunjungan::create([
            'pasien_id' => $pasien2->id,
            'poli_id' => 1, // Poli Umum
            'tgl_kunjungan' => now(),
            'status' => 'menunggu',
            'keluhan_awal' => 'Sakit kepala dan mual',
        ]);

        // Kunjungan 3 - Poli Gigi (ID: 2) - Status Menunggu
        Kunjungan::create([
            'pasien_id' => $pasien3->id,
            'poli_id' => 2, // Poli Gigi
            'tgl_kunjungan' => now(),
            'status' => 'menunggu',
            'keluhan_awal' => 'Gigi berlubang dan sakit',
        ]);

        // Kunjungan 4 - Poli Gigi (ID: 2) - Status Menunggu
        Kunjungan::create([
            'pasien_id' => $pasien4->id,
            'poli_id' => 2, // Poli Gigi
            'tgl_kunjungan' => now(),
            'status' => 'menunggu',
            'keluhan_awal' => 'Pembersihan karang gigi',
        ]);
    }
}

