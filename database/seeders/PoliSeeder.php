<?php

namespace Database\Seeders;

use App\Models\Poli;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PoliSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $polis = [
            [
                'nama_poli' => 'Poli Umum',
                'lokasi' => 'Gedung A, Lantai 1',
                'tarif_daftar' => 15000,
            ],
            [
                'nama_poli' => 'Poli Gigi',
                'lokasi' => 'Gedung B, Lantai 1',
                'tarif_daftar' => 25000,
            ],
            [
                'nama_poli' => 'Poli KIA (Kesehatan Ibu dan Anak)',
                'lokasi' => 'Gedung A, Lantai 2',
                'tarif_daftar' => 20000,
            ],
            [
                'nama_poli' => 'Poli Lansia',
                'lokasi' => 'Gedung C, Lantai 1',
                'tarif_daftar' => 15000,
            ],
            [
                'nama_poli' => 'Poli TB (Tuberkulosis)',
                'lokasi' => 'Gedung D, Lantai 1',
                'tarif_daftar' => 10000,
            ],
        ];

        foreach ($polis as $poli) {
            Poli::create($poli);
        }
    }
}
