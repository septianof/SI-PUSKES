<?php

namespace App\Livewire\Dokter;

use Livewire\Component;
use App\Models\Kunjungan;
use App\Models\RekamMedis;
use App\Models\Obat;
use App\Models\Resep;
use App\Models\DetailResep;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeriksaPasien extends Component
{
    // Kunjungan & Pasien Data
    public $kunjungan;
    public $pasien;
    public $poli;
    
    // Riwayat Kunjungan
    public $riwayatKunjungan = [];
    
    // Form Input Properties
    public $tensi = '';
    public $bb = '';
    public $tb = '';
    public $suhu = '';
    public $keluhan = '';
    public $diagnosa = '';
    public $tindakan = '';
    
    // Prescription Cart Properties
    public $resepList = []; // Array of selected medicines
    public $selectedObat = ''; // Currently selected medicine ID
    public $jumlah = 1; // Quantity
    public $dosis = ''; // Dosage (e.g., "3x1")
    public $obatOptions = []; // Available medicines from database

    /**
     * Mount component with kunjungan ID
     */
    public function mount($kunjungan)
    {
        // Load kunjungan dengan relasi
        $this->kunjungan = Kunjungan::with(['pasien', 'poli'])
            ->findOrFail($kunjungan);
        
        // Authorization: Pastikan kunjungan milik poli dokter yang login
        if ($this->kunjungan->poli_id !== Auth::user()->poli_id) {
            abort(403, 'Anda tidak memiliki akses ke kunjungan ini.');
        }
        
        // Load pasien & poli
        $this->pasien = $this->kunjungan->pasien;
        $this->poli = $this->kunjungan->poli;
        
        // Pre-fill keluhan dari keluhan awal kunjungan
        $this->keluhan = $this->kunjungan->keluhan_awal ?? '';
        
        // Load riwayat kunjungan pasien (kunjungan sebelumnya yang sudah selesai)
        $this->loadRiwayatKunjungan();
        
        // Load available medicines for prescription
        $this->loadObatOptions();
    }

    /**
     * Load riwayat kunjungan pasien sebelumnya
     */
    public function loadRiwayatKunjungan()
    {
        $this->riwayatKunjungan = Kunjungan::where('pasien_id', $this->pasien->id)
            ->where('id', '!=', $this->kunjungan->id) // Exclude current visit
            ->whereIn('status', ['selesai']) // Only completed visits
            ->with(['rekamMedis', 'poli'])
            ->orderBy('tgl_kunjungan', 'desc')
            ->limit(5) // Last 5 visits
            ->get();
    }

    /**
     * Calculate patient age from birth date
     */
    public function getUmurProperty()
    {
        if (!$this->pasien || !$this->pasien->tgl_lahir) {
            return '-';
        }
        
        $birthDate = \Carbon\Carbon::parse($this->pasien->tgl_lahir);
        $age = $birthDate->age;
        
        return $age . ' tahun';
    }

    /**
     * Format tanda vital for storage
     * Returns JSON string: {"tensi":"120/80","bb":"60","tb":"170","suhu":"36.5"}
     */
    public function getTandaVitalFormatted()
    {
        return json_encode([
            'tensi' => $this->tensi,
            'bb' => $this->bb,
            'tb' => $this->tb,
            'suhu' => $this->suhu,
        ]);
    }

    /**
     * Load available medicines from database (stok > 0)
     */
    public function loadObatOptions()
    {
        $this->obatOptions = Obat::where('stok', '>', 0)
            ->orderBy('nama_obat', 'asc')
            ->get(['id', 'nama_obat', 'stok', 'jenis'])
            ->toArray();
    }

    /**
     * Add medicine to prescription cart
     */
    public function addObat()
    {
        // Validation
        $this->validate([
            'selectedObat' => 'required|exists:obats,id',
            'jumlah' => 'required|integer|min:1',
            'dosis' => 'required|string|max:50',
        ], [
            'selectedObat.required' => 'Pilih obat terlebih dahulu',
            'selectedObat.exists' => 'Obat tidak ditemukan',
            'jumlah.required' => 'Jumlah harus diisi',
            'jumlah.min' => 'Jumlah minimal 1',
            'dosis.required' => 'Dosis harus diisi',
        ]);

        // Get medicine data
        $obat = Obat::find($this->selectedObat);
        
        // Check if medicine already in cart
        $existingIndex = collect($this->resepList)->search(function($item) use ($obat) {
            return $item['obat_id'] == $obat->id;
        });
        
        if ($existingIndex !== false) {
            // Update existing item
            $this->resepList[$existingIndex]['jumlah'] += $this->jumlah;
        } else {
            // Add new item to cart
            $this->resepList[] = [
                'obat_id' => $obat->id,
                'nama_obat' => $obat->nama_obat,
                'jumlah' => $this->jumlah,
                'dosis' => $this->dosis,
            ];
        }
        
        // Reset form
        $this->selectedObat = '';
        $this->jumlah = 1;
        $this->dosis = '';
        
        session()->flash('obat_added', 'Obat berhasil ditambahkan ke resep');
    }

    /**
     * Remove medicine from prescription cart
     */
    public function removeObat($index)
    {
        if (isset($this->resepList[$index])) {
            unset($this->resepList[$index]);
            $this->resepList = array_values($this->resepList); // Reindex array
        }
    }

    /**
     * Final save with DB transaction
     * - Save rekam medis
     * - Save resep & detail_reseps (if any)
     * - Update kunjungan status from 'menunggu' to 'bayar' (Opsi A Flow)
     */
    public function savePemeriksaan()
    {
        // Validation
        $this->validate([
            'keluhan' => 'required|string',
            'diagnosa' => 'required|string|max:255',
            'tindakan' => 'nullable|string',
            'tensi' => 'nullable|string|max:20',
            'bb' => 'nullable|numeric',
            'tb' => 'nullable|numeric',
            'suhu' => 'nullable|numeric',
        ]);

        try {
            DB::transaction(function () {
                // 1. Save Rekam Medis
                $rekamMedis = RekamMedis::updateOrCreate(
                    ['kunjungan_id' => $this->kunjungan->id],
                    [
                        'dokter_id' => Auth::id(),
                        'tgl_periksa' => now(),
                        'keluhan' => $this->keluhan,
                        'diagnosa' => $this->diagnosa,
                        'tindakan' => $this->tindakan,
                        'tanda_vital' => $this->getTandaVitalFormatted(),
                    ]
                );

                // 2. Handle Prescription (if any)
                if (count($this->resepList) > 0) {
                    // Create Resep
                    $resep = Resep::create([
                        'rekam_medis_id' => $rekamMedis->id,
                        'tgl_resep' => now(),
                        'status' => 'menunggu', // For pharmacy processing later
                        'catatan' => null,
                    ]);

                    // Create Detail Reseps
                    foreach ($this->resepList as $item) {
                        DetailResep::create([
                            'resep_id' => $resep->id,
                            'obat_id' => $item['obat_id'],
                            'jumlah' => $item['jumlah'],
                            'dosis' => $item['dosis'],
                        ]);
                    }
                }

                // 3. Update status to 'bayar' for ALL patients (Opsi A Flow)
                // Payment happens first, then pharmacy (if prescription exists)
                $this->kunjungan->update(['status' => 'bayar']);
                
                $statusMessage = 'Pemeriksaan selesai. Pasien diarahkan ke Kasir untuk pembayaran.';
                
                session()->flash('success', $statusMessage);
            });

            // Redirect back to queue dashboard
            return redirect()->route('dokter.antrean');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Render the component view
     */
    public function render()
    {
        return view('livewire.dokter.periksa-pasien')
            ->layout('components.layouts.app')
            ->title('Pemeriksaan Pasien - ' . ($this->pasien->nama ?? ''));
    }
}
