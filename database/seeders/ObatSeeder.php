<?php

namespace Database\Seeders;

use App\Models\Obat;
use Illuminate\Database\Seeder;

class ObatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $obats = [
            // Obat Umum (General Medicines)
            ['nama_obat' => 'Paracetamol 500mg', 'jenis' => 'Tablet', 'stok' => 150, 'harga' => 5000],
            ['nama_obat' => 'Amoxicillin 500mg', 'jenis' => 'Kapsul', 'stok' => 80, 'harga' => 8000],
            ['nama_obat' => 'Ibuprofen 400mg', 'jenis' => 'Tablet', 'stok' => 90, 'harga' => 6000],
            ['nama_obat' => 'CTM (Chlorpheniramine Maleate)', 'jenis' => 'Tablet', 'stok' => 120, 'harga' => 4000],
            ['nama_obat' => 'Vitamin C 1000mg', 'jenis' => 'Tablet', 'stok' => 200, 'harga' => 2000],
            
            // Obat Batuk & Flu
            ['nama_obat' => 'OBH Combi Batuk', 'jenis' => 'Sirup', 'stok' => 45, 'harga' => 15000],
            ['nama_obat' => 'Ambroxol 30mg', 'jenis' => 'Tablet', 'stok' => 100, 'harga' => 7000],
            ['nama_obat' => 'Dextromethorphan', 'jenis' => 'Sirup', 'stok' => 35, 'harga' => 18000],
            
            // Obat Pencernaan
            ['nama_obat' => 'Antasida Tablet', 'jenis' => 'Tablet', 'stok' => 100, 'harga' => 3000],
            ['nama_obat' => 'Omeprazole 20mg', 'jenis' => 'Kapsul', 'stok' => 70, 'harga' => 12000],
            ['nama_obat' => 'Loperamide 2mg', 'jenis' => 'Tablet', 'stok' => 50, 'harga' => 5000],
            ['nama_obat' => 'Domperidone 10mg', 'jenis' => 'Tablet', 'stok' => 60, 'harga' => 8000],
            
            // Antibiotik
            ['nama_obat' => 'Ciprofloxacin 500mg', 'jenis' => 'Tablet', 'stok' => 40, 'harga' => 15000],
            ['nama_obat' => 'Azithromycin 500mg', 'jenis' => 'Tablet', 'stok' => 30, 'harga' => 20000],
            ['nama_obat' => 'Cefixime 100mg', 'jenis' => 'Kapsul', 'stok' => 55, 'harga' => 18000],
            
            // Obat Luar (Topical)
            ['nama_obat' => 'Salep 2-4', 'jenis' => 'Salep', 'stok' => 8, 'harga' => 12000],
            ['nama_obat' => 'Betadine Solution 100ml', 'jenis' => 'Tetes', 'stok' => 5, 'harga' => 18000],
            ['nama_obat' => 'Hydrocortisone Cream', 'jenis' => 'Salep', 'stok' => 25, 'harga' => 15000],
            ['nama_obat' => 'Gentamicin Cream', 'jenis' => 'Salep', 'stok' => 30, 'harga' => 10000],
            
            // Obat Kardiovaskular
            ['nama_obat' => 'Amlodipine 5mg', 'jenis' => 'Tablet', 'stok' => 85, 'harga' => 5000],
            ['nama_obat' => 'Captopril 25mg', 'jenis' => 'Tablet', 'stok' => 90, 'harga' => 4000],
            ['nama_obat' => 'Simvastatin 10mg', 'jenis' => 'Tablet', 'stok' => 65, 'harga' => 8000],
            
            // Obat Diabetes
            ['nama_obat' => 'Metformin 500mg', 'jenis' => 'Tablet', 'stok' => 100, 'harga' => 6000],
            ['nama_obat' => 'Glimepiride 2mg', 'jenis' => 'Tablet', 'stok' => 70, 'harga' => 10000],
            
            // Multivitamin & Suplemen
            ['nama_obat' => 'Multivitamin Dewasa', 'jenis' => 'Tablet', 'stok' => 150, 'harga' => 3000],
            ['nama_obat' => 'Vitamin B Complex', 'jenis' => 'Tablet', 'stok' => 120, 'harga' => 4000],
            ['nama_obat' => 'Kalsium Laktat', 'jenis' => 'Tablet', 'stok' => 80, 'harga' => 5000],
            
            // Obat Lain-lain
            ['nama_obat' => 'Antimo', 'jenis' => 'Tablet', 'stok' => 60, 'harga' => 7000],
            ['nama_obat' => 'Asam Mefenamat 500mg', 'jenis' => 'Tablet', 'stok' => 95, 'harga' => 7000],
            ['nama_obat' => 'Cetirizine 10mg', 'jenis' => 'Tablet', 'stok' => 110, 'harga' => 5000],
        ];

        foreach ($obats as $obat) {
            Obat::create($obat);
        }
    }
}
