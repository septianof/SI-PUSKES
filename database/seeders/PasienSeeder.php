<?php

namespace Database\Seeders;

use App\Models\Pasien;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PasienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Indonesian locale
        $yearMonth = now()->format('Ym'); // Format: YYYYMM (e.g., 202601)

        // Generate 20 pasien dengan data yang realistis
        for ($i = 1; $i <= 20; $i++) {
            Pasien::create([
                'no_rm' => $yearMonth . '-' . str_pad($i, 3, '0', STR_PAD_LEFT), // Format: 202601-001, 202601-002, etc.
                'nik' => $faker->numerify('################'), // 16 digit NIK
                'nama' => $faker->name(),
                'alamat' => $faker->address(),
                'tgl_lahir' => $faker->dateTimeBetween('-70 years', '-1 year')->format('Y-m-d'),
            ]);
        }
    }
}
