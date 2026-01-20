<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // URUTAN PENTING: Seed sesuai dependency order
        $this->call([
            PoliSeeder::class,        // 1. Poli dulu (dokter butuh poli_id)
            ObatSeeder::class,        // 2. Obat (resep butuh obat_id)
            UserSeeder::class,        // 3. Users (termasuk 5 dokter per poli)
            PasienSeeder::class,      // 4. Pasien (kunjungan butuh pasien_id)
            KunjunganSeeder::class,   // 5. Kunjungan + relasi (terakhir)
        ]);
    }
}
