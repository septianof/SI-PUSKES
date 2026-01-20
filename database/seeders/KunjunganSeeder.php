<?php

namespace Database\Seeders;

use App\Models\DetailResep;
use App\Models\Kunjungan;
use App\Models\Pembayaran;
use App\Models\RekamMedis;
use App\Models\Resep;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class KunjunganSeeder extends Seeder
{
    private $faker;

    private $diagnosaList = [
        'ISPA (Infeksi Saluran Pernapasan Akut)',
        'Hipertensi',
        'Diabetes Mellitus',
        'Gastritis',
        'Dermatitis',
        'Asma',
        'Diare',
        'Demam Tifoid',
        'Flu',
        'Sakit Gigi',
        'Rematik',
        'Migrain',
    ];

    public function __construct()
    {
        $this->faker = Faker::create('id_ID');
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ADJUSTED Distribution (40 total):
        // Active visits (max 15 unique patients due to validation):
        // - 5 menunggu (3 Umum + 2 BPJS)
        // - 3 bayar (hanya Umum)
        // - 7 obat (4 Umum + 3 BPJS)
        // Completed visits (can reuse patients):
        // - 25 selesai (13 Umum + 12 BPJS) - including repeat patients

        $pasienIds = range(1, 20); // 20 pasien
        shuffle($pasienIds); // Randomize

        $usedPasienIds = []; // Track used pasien for active visits
        $today = now();

        // 1. STATUS: MENUNGGU (5 kunjungan - 3 Umum + 2 BPJS)
        for ($i = 0; $i < 5; $i++) {
            $pasienId = array_shift($pasienIds);
            $usedPasienIds[] = $pasienId;

            $this->createKunjungan([
                'pasien_id' => $pasienId,
                'poli_id' => rand(1, 5),
                'tgl_kunjungan' => $today->copy()->subHours(rand(1, 5)),
                'status' => 'menunggu',
                'metode_bayar' => $i < 3 ? 'Umum' : 'BPJS',
                'no_bpjs' => $i < 3 ? null : $this->generateNoBpjs(),
            ]);
        }

        // 2. STATUS: BAYAR (3 kunjungan - hanya Umum)
        for ($i = 0; $i < 3; $i++) {
            $pasienId = array_shift($pasienIds);
            $usedPasienIds[] = $pasienId;

            $kunjungan = $this->createKunjungan([
                'pasien_id' => $pasienId,
                'poli_id' => rand(1, 5),
                'tgl_kunjungan' => $today->copy()->subHours(rand(1, 3)),
                'status' => 'bayar',
                'metode_bayar' => 'Umum',
            ]);

            // Add RekamMedis + optional Resep
            $this->createRekamMedis($kunjungan, rand(0, 1) == 1);
        }

        // 3. STATUS: OBAT (7 kunjungan - 4 Umum + 3 BPJS)
        for ($i = 0; $i < 7; $i++) {
            $pasienId = array_shift($pasienIds);
            $usedPasienIds[] = $pasienId;

            $kunjungan = $this->createKunjungan([
                'pasien_id' => $pasienId,
                'poli_id' => rand(1, 5),
                'tgl_kunjungan' => $today->copy()->subHours(rand(6, 12)),
                'status' => 'obat',
                'metode_bayar' => $i < 4 ? 'Umum' : 'BPJS',
                'no_bpjs' => $i < 4 ? null : $this->generateNoBpjs(),
            ]);

            // Add RekamMedis + Resep (wajib untuk status obat)
            $this->createRekamMedis($kunjungan, true);

            // Add Pembayaran jika Umum
            if ($i < 4) {
                $this->createPembayaran($kunjungan);
            }
        }

        // 4. STATUS: SELESAI (25 kunjungan - 13 Umum + 12 BPJS)
        // Can reuse ALL pasien (including those with active visits) because these are COMPLETED visits from the past
        $allPasienIds = range(1, 20);
        shuffle($allPasienIds);

        for ($i = 0; $i < 25; $i++) {
            // Cycle through all patients to allow multiple completed visits
            $pasienId = $allPasienIds[$i % 20];
            $metodeBayar = $i < 13 ? 'Umum' : 'BPJS';

            $kunjungan = $this->createKunjungan([
                'pasien_id' => $pasienId,
                'poli_id' => rand(1, 5),
                'tgl_kunjungan' => $today->copy()->subDays(rand(1, 30)),
                'status' => 'selesai',
                'metode_bayar' => $metodeBayar,
                'no_bpjs' => $metodeBayar === 'BPJS' ? $this->generateNoBpjs() : null,
            ]);

            // Add RekamMedis + Resep (70% chance)
            $withResep = rand(1, 10) <= 7;
            $this->createRekamMedis($kunjungan, $withResep);

            // Add Pembayaran jika Umum
            if ($metodeBayar === 'Umum') {
                $this->createPembayaran($kunjungan);
            }
        }
    }

    /**
     * Create Kunjungan
     */
    private function createKunjungan(array $data): Kunjungan
    {
        return Kunjungan::create(array_merge([
            'keluhan_awal' => $this->faker->sentence(rand(8, 15)),
        ], $data));
    }

    /**
     * Create RekamMedis + optional Resep
     */
    private function createRekamMedis(Kunjungan $kunjungan, bool $withResep = false): RekamMedis
    {
        // Get dokter_id from kunjungan's poli
        $dokterId = \App\Models\User::where('role', 'dokter')
            ->where('poli_id', $kunjungan->poli_id)
            ->first()->id;

        $rekamMedis = RekamMedis::create([
            'kunjungan_id' => $kunjungan->id,
            'dokter_id' => $dokterId,
            'tgl_periksa' => $kunjungan->tgl_kunjungan,
            'keluhan' => $kunjungan->keluhan_awal,
            'diagnosa' => $this->diagnosaList[array_rand($this->diagnosaList)],
            'tanda_vital' => $this->generateTandaVital(),
            'tindakan' => $this->faker->sentence(rand(5, 10)),
        ]);

        if ($withResep) {
            $this->createResep($rekamMedis);
        }

        return $rekamMedis;
    }

    /**
     * Create Resep + DetailResep (2-4 obat)
     */
    private function createResep(RekamMedis $rekamMedis): Resep
    {
        $resep = Resep::create([
            'rekam_medis_id' => $rekamMedis->id,
            'tgl_resep' => $rekamMedis->tgl_periksa,
            'status' => $rekamMedis->kunjungan->status === 'selesai' ? 'selesai' : 'menunggu',
        ]);

        // Add 2-4 obat
        $jumlahObat = rand(2, 4);
        $obatIds = range(1, 30); // 30 obat available
        shuffle($obatIds);
        $selectedObatIds = array_slice($obatIds, 0, $jumlahObat);

        foreach ($selectedObatIds as $obatId) {
            DetailResep::create([
                'resep_id' => $resep->id,
                'obat_id' => $obatId,
                'jumlah' => rand(5, 20),
                'dosis' => $this->generateDosis(),
            ]);
        }

        return $resep;
    }

    /**
     * Create Pembayaran
     */
    private function createPembayaran(Kunjungan $kunjungan): Pembayaran
    {
        // Calculate total based on poli tarif + obat (if any)
        $tarifPendaftaran = $kunjungan->poli->tarif_daftar;

        // Get obat cost if resep exists
        $biayaObat = 0;
        if ($kunjungan->rekamMedis && $kunjungan->rekamMedis->resep) {
            foreach ($kunjungan->rekamMedis->resep->detailReseps as $detail) {
                $biayaObat += $detail->obat->harga * $detail->jumlah;
            }
        }

        $totalBayar = $tarifPendaftaran + $biayaObat;

        return Pembayaran::create([
            'kunjungan_id' => $kunjungan->id,
            'tgl_bayar' => $kunjungan->tgl_kunjungan->copy()->addMinutes(rand(30, 120)),
            'total_biaya' => $totalBayar,
            'metode_bayar' => $this->faker->randomElement(['cash', 'transfer', 'debit']),
            'status' => 'lunas',
        ]);
    }

    /**
     * Generate No BPJS (13 digits)
     */
    private function generateNoBpjs(): string
    {
        return $this->faker->numerify('#############');
    }

    /**
     * Generate Dosis
     */
    private function generateDosis(): string
    {
        $dosisTemplates = [
            '3 x 1 sehari',
            '2 x 1 sehari',
            '1 x 1 sehari',
            '3 x 1 sehari sesudah makan',
            '2 x 1 sehari sebelum makan',
            '1 x 1 sehari malam hari',
        ];

        return $dosisTemplates[array_rand($dosisTemplates)];
    }

    /**
     * Generate Tanda Vital (realistic)
     */
    private function generateTandaVital(): string
    {
        $tensi = rand(100, 140).'/'.rand(60, 90);
        $nadi = rand(60, 100);
        $suhu = number_format(rand(360, 380) / 10, 1);
        $respirasi = rand(16, 24);

        return "TD: {$tensi} mmHg, Nadi: {$nadi}x/menit, Suhu: {$suhu}°C, RR: {$respirasi}x/menit";
    }
}
