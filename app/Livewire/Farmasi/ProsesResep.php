<?php

namespace App\Livewire\Farmasi;

use App\Models\Kunjungan;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Proses Resep - SI PUSKES')]
class ProsesResep extends Component
{
    public $kunjungan;
    public $resep;
    public $detailReseps;
    public $statusStok = []; // ['cukup', 'kurang', 'habis'] per detail_resep_id

    /**
     * Mount component
     */
    public function mount($kunjungan)
    {
        // Load kunjungan dengan semua relasi
        $this->kunjungan = Kunjungan::with([
            'pasien',
            'poli',
            'rekamMedis.resep.detailReseps.obat'
        ])->findOrFail($kunjungan);

        // Verify status
        if ($this->kunjungan->status !== 'obat') {
            session()->flash('error', 'Kunjungan ini tidak dalam status farmasi.');
            return redirect()->route('farmasi');
        }

        // Load resep
        $this->resep = $this->kunjungan->rekamMedis?->resep;
        
        if (!$this->resep) {
            session()->flash('error', 'Tidak ada resep untuk kunjungan ini.');
            return redirect()->route('farmasi');
        }

        $this->detailReseps = $this->resep->detailReseps;

        // Check stok availability untuk setiap obat
        $this->checkStokAvailability();
    }

    /**
     * Check stok availability untuk setiap detail resep
     */
    private function checkStokAvailability()
    {
        foreach ($this->detailReseps as $detail) {
            $stokObat = $detail->obat->stok;
            $jumlahDiminta = $detail->jumlah;

            if ($stokObat >= $jumlahDiminta) {
                $this->statusStok[$detail->id] = 'cukup';
            } elseif ($stokObat > 0) {
                $this->statusStok[$detail->id] = 'kurang';
            } else {
                $this->statusStok[$detail->id] = 'habis';
            }
        }
    }

    /**
     * Computed: Apakah semua obat stok cukup?
     */
    public function getAllStokCukupProperty()
    {
        foreach ($this->statusStok as $status) {
            if ($status !== 'cukup') {
                return false;
            }
        }
        return true;
    }

    /**
     * Computed: Apakah ada obat yang stok kurang/habis?
     */
    public function getAdaStokKurangProperty()
    {
        return !$this->allStokCukup;
    }

    /**
     * Finalisasi Resep - Serahkan Obat & Update Status
     */
    public function finalisasiResep()
    {
        \Log::info('finalisasiResep called', ['resep_id' => $this->resep->id]);

        try {
            $catatanItems = []; // Array untuk mencatat obat yang tidak diserahkan penuh

            // 1. Process setiap obat: kurangi stok & catat jika tidak lengkap
            foreach ($this->detailReseps as $detail) {
                $status = $this->statusStok[$detail->id];
                
                // Refresh data obat dari database
                $obat = $detail->obat->fresh();
                $stokTersedia = $obat->stok;
                $jumlahDiminta = $detail->jumlah;

                if ($status === 'cukup') {
                    // CASE 1: Stok CUKUP - serahkan semua
                    $obat->stok = $obat->stok - $jumlahDiminta;
                    $obat->save();
                    
                    \Log::info('Stok mencukupi, diserahkan penuh', [
                        'obat' => $obat->nama_obat,
                        'diminta' => $jumlahDiminta,
                        'diserahkan' => $jumlahDiminta,
                        'stok_sisa' => $obat->stok
                    ]);

                } elseif ($status === 'kurang') {
                    // CASE 2: Stok KURANG - serahkan sebanyak yang ada
                    $jumlahDiserahkan = $stokTersedia; // Serahkan semua yang ada
                    $obat->stok = 0; // Stok jadi habis
                    $obat->save();
                    
                    $catatanItems[] = "- {$obat->nama_obat}: Diminta {$jumlahDiminta} {$obat->jenis}, hanya diserahkan {$jumlahDiserahkan} {$obat->jenis} (stok tidak mencukupi)";
                    
                    \Log::warning('Stok kurang, diserahkan sebagian', [
                        'obat' => $obat->nama_obat,
                        'diminta' => $jumlahDiminta,
                        'diserahkan' => $jumlahDiserahkan,
                        'stok_sisa' => 0
                    ]);

                } else {
                    // CASE 3: Stok HABIS - tidak bisa serahkan sama sekali
                    $catatanItems[] = "- {$obat->nama_obat}: Diminta {$jumlahDiminta} {$obat->jenis}, TIDAK DAPAT DISERAHKAN (stok habis)";
                    
                    \Log::warning('Stok habis, tidak diserahkan', [
                        'obat' => $obat->nama_obat,
                        'diminta' => $jumlahDiminta,
                        'diserahkan' => 0
                    ]);
                }
            }

            // 2. Build catatan untuk resep
            $catatan = null;
            if (!empty($catatanItems)) {
                $catatan = "CATATAN PENYERAHAN OBAT:\n" . implode("\n", $catatanItems) . "\n\nSilakan koordinasi dengan dokter untuk obat yang tidak lengkap.";
            }

            // 3. Update status resep dengan catatan
            $this->resep->update([
                'status' => 'selesai',
                'catatan' => $catatan
            ]);

            // 4. Update status kunjungan
            $this->kunjungan->update(['status' => 'selesai']);

            \Log::info('Resep selesai diproses', [
                'kunjungan_id' => $this->kunjungan->id,
                'resep_id' => $this->resep->id,
                'ada_catatan' => !empty($catatan)
            ]);

            // Success message
            if ($this->allStokCukup) {
                session()->flash('success', 'Resep berhasil diproses. Semua obat telah diserahkan.');
            } else {
                session()->flash('warning', 'Resep diproses dengan catatan. Beberapa obat tidak dapat diserahkan lengkap. Catatan telah disimpan.');
            }

            return $this->redirect(route('farmasi'), navigate: true);

        } catch (\Exception $e) {
            \Log::error('Error in finalisasiResep', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.farmasi.proses-resep');
    }
}
