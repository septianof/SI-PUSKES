<?php

namespace App\Livewire\Farmasi;

use App\Models\Kunjungan;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Antrean Resep - SI PUSKES')]
class AntreanResep extends Component
{
    /**
     * Get antrean resep (kunjungan dengan status 'obat')
     */
    public function getAntreanResepProperty()
    {
        return Kunjungan::with([
            'pasien',
            'poli',
            'rekamMedis.resep.detailReseps.obat',
        ])
            ->where('status', 'obat')
            ->orderBy('tgl_kunjungan', 'desc')
            ->get();
    }

    /**
     * Get jumlah item obat dari resep
     */
    public function getJumlahItem($kunjungan)
    {
        $resep = $kunjungan->rekamMedis?->resep;
        if (! $resep) {
            return 0;
        }

        return $resep->detailReseps->count();
    }

    /**
     * Navigate to proses resep page
     */
    public function prosesResep($kunjunganId)
    {
        return $this->redirect(route('farmasi.proses', ['kunjungan' => $kunjunganId]), navigate: true);
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.farmasi.antrean-resep');
    }
}
