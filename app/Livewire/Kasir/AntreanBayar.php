<?php

namespace App\Livewire\Kasir;

use App\Models\Kunjungan;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Antrean Pembayaran - SI PUSKES')]
class AntreanBayar extends Component
{
    /**
     * Render the kasir antrean bayar page.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        // Query kunjungan dengan status 'bayar'
        // Eager load relasi pasien dan poli untuk menghindari N+1 query
        // Urutkan berdasarkan tgl_kunjungan (oldest first)
        $antreanList = Kunjungan::with(['pasien', 'poli'])
            ->where('status', 'bayar')
            ->orderBy('tgl_kunjungan', 'asc')
            ->get();

        return view('livewire.kasir.antrean-bayar', [
            'antreanList' => $antreanList,
        ]);
    }

    /**
     * Get status penjamin (Umum/BPJS) berdasarkan no_bpjs pasien.
     *
     * @param \App\Models\Pasien $pasien
     * @return string
     */
    public function getStatusPenjamin($pasien)
    {
        return !empty($pasien->no_bpjs) ? 'BPJS' : 'Umum';
    }

    /**
     * Redirect ke halaman proses bayar.
     *
     * @param int $kunjunganId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function prosesBayar($kunjunganId)
    {
        return redirect()->route('kasir.proses', ['kunjungan' => $kunjunganId]);
    }
}
