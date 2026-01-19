<?php

namespace App\Livewire\Kasir;

use App\Models\Kunjungan;
use App\Models\Pembayaran;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Proses Pembayaran - SI PUSKES')]
class ProsesBayar extends Component
{
    public $kunjungan;
    public $bayarTunai = 0;

    /**
     * Mount component with kunjungan
     */
    public function mount($kunjungan)
    {
        // Load kunjungan with all necessary relations
        $this->kunjungan = Kunjungan::with([
            'pasien',
            'poli',
            'rekamMedis.resep.detailReseps.obat'
        ])->findOrFail($kunjungan);

        // Verify status is 'bayar'
        if ($this->kunjungan->status !== 'bayar') {
            session()->flash('error', 'Kunjungan ini tidak dalam status pembayaran.');
            return redirect()->route('kasir');
        }

        // PASTIKAN BUKAN PASIEN BPJS (double check security)
        if ($this->kunjungan->metode_bayar === 'BPJS') {
            session()->flash('error', 'Pasien BPJS tidak perlu melalui kasir.');
            return redirect()->route('kasir');
        }
    }

    /**
     * Computed property: Biaya Pendaftaran dari Poli
     */
    public function getBiayaPendaftaranProperty()
    {
        return $this->kunjungan->poli->tarif_daftar;
    }

    /**
     * Computed property: Biaya Obat (sum dari detail resep)
     */
    public function getBiayaObatProperty()
    {
        $resep = $this->kunjungan->rekamMedis?->resep;
        if (!$resep) return 0;

        return $resep->detailReseps->sum(function($detail) {
            return $detail->jumlah * $detail->obat->harga;
        });
    }

    /**
     * Computed property: Grand Total
     */
    public function getGrandTotalProperty()
    {
        return $this->biayaPendaftaran + $this->biayaObat;
    }

    /**
     * Computed property: Kembalian
     */
    public function getKembalianProperty()
    {
        return max(0, $this->bayarTunai - $this->grandTotal);
    }

    /**
     * Get detail obat dari resep untuk ditampilkan
     */
    public function getDetailObatProperty()
    {
        $resep = $this->kunjungan->rekamMedis?->resep;
        if (!$resep) return [];

        return $resep->detailReseps->map(function($detail) {
            return [
                'nama_obat' => $detail->obat->nama_obat,
                'jumlah' => $detail->jumlah,
                'dosis' => $detail->dosis,
                'harga_satuan' => $detail->obat->harga,
                'subtotal' => $detail->jumlah * $detail->obat->harga,
            ];
        });
    }

    /**
     * Finalisasi Pembayaran (untuk pasien Umum)
     */
    public function finalisasiBayar()
    {
        // Validation
        $this->validate([
            'bayarTunai' => [
                'required',
                'numeric',
                'min:' . $this->grandTotal
            ],
        ], [
            'bayarTunai.required' => 'Jumlah bayar harus diisi',
            'bayarTunai.numeric' => 'Jumlah bayar harus berupa angka',
            'bayarTunai.min' => 'Uang tidak cukup. Minimal: Rp ' . number_format($this->grandTotal, 0, ',', '.'),
        ]);

        try {
            // 1. Create Pembayaran record
            Pembayaran::create([
                'kunjungan_id' => $this->kunjungan->id,
                'tgl_bayar' => now(),
                'total_biaya' => $this->grandTotal,
                'metode_bayar' => 'Tunai',
                'status' => 'Lunas',
            ]);

            // 2. Update kunjungan status
            // Jika ada resep → 'obat' (ke farmasi), jika tidak → 'selesai'
            $hasResep = $this->kunjungan->rekamMedis?->resep !== null;
            $newStatus = $hasResep ? 'obat' : 'selesai';
            
            $this->kunjungan->update(['status' => $newStatus]);

            // Success message
            $message = 'Pembayaran berhasil! ';
            $message .= $hasResep 
                ? 'Pasien diarahkan ke Farmasi untuk pengambilan obat.'
                : 'Pasien sudah dapat pulang.';

            session()->flash('success', $message);

            return redirect()->route('kasir');

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Render component view
     */
    public function render()
    {
        return view('livewire.kasir.proses-bayar');
    }
}
