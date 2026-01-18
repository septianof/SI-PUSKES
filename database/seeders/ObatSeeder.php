<?php

namespace Database\Seeders;

use App\Models\Obat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ObatSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $obats = [
            [
                'nama_obat' => 'Paracetamol 500mg',
                'jenis' => 'Tablet',
                'stok' => 150,
                'harga' => 5000,
            ],
            [
                'nama_obat' => 'Amoxicillin 500mg',
                'jenis' => 'Kapsul',
                'stok' => 80,
                'harga' => 8000,
            ],
            [
                'nama_obat' => 'OBH Combi Batuk',
                'jenis' => 'Sirup',
                'stok' => 45,
                'harga' => 15000,
            ],
            [
                'nama_obat' => 'Antasida Tablet',
                'jenis' => 'Tablet',
                'stok' => 100,
                'harga' => 3000,
            ],
            [
                'nama_obat' => 'Salep 2-4',
                'jenis' => 'Salep',
                'stok' => 8,  // Low stock
                'harga' => 12000,
            ],
            [
                'nama_obat' => 'Vitamin C 1000mg',
                'jenis' => 'Tablet',
                'stok' => 200,
                'harga' => 2000,
            ],
            [
                'nama_obat' => 'CTM (Chlorpheniramine Maleate)',
                'jenis' => 'Tablet',
                'stok' => 120,
                'harga' => 4000,
            ],
            [
                'nama_obat' => 'Betadine Solution 100ml',
                'jenis' => 'Tetes',
                'stok' => 5,  // Low stock
                'harga' => 18000,
            ],
            [
                'nama_obat' => 'Ibuprofen 400mg',
                'jenis' => 'Tablet',
                'stok' => 90,
                'harga' => 6000,
            ],
            [
                'nama_obat' => 'Antimo',
                'jenis' => 'Tablet',
                'stok' => 60,
                'harga' => 7000,
            ],
        ];

        foreach ($obats as $obat) {
            Obat::create($obat);
        }
    }
}
