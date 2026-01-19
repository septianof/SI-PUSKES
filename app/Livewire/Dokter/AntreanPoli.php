<?php

namespace App\Livewire\Dokter;

use Livewire\Component;
use App\Models\Kunjungan;
use Illuminate\Support\Facades\Auth;

class AntreanPoli extends Component
{
    /**
     * List of kunjungans in queue for this doctor's poli
     */
    public $kunjungans = [];

    /**
     * Current poli information
     */
    public $poli;

    /**
     * Mount the component - load initial data
     */
    public function mount()
    {
        $user = Auth::user();
        
        // Load poli information
        $this->poli = $user->poli;

        // If user doesn't have assigned poli, set empty collection
        if (!$user->poli_id) {
            $this->kunjungans = collect([]);
            return;
        }

        $this->loadAntrean();
    }

    /**
     * Load antrean data from database
     * Filter by: poli_id = current user's poli_id AND status = 'menunggu'
     */
    public function loadAntrean()
    {
        $this->kunjungans = Kunjungan::where('poli_id', Auth::user()->poli_id)
            ->where('status', 'menunggu')
            ->with(['pasien', 'poli'])
            ->orderBy('tgl_kunjungan', 'asc')
            ->get();
    }

    /**
     * Refresh antrean data (can be called from view)
     */
    public function refreshAntrean()
    {
        $this->loadAntrean();
    }

    /**
     * Start examination for a patient
     * Redirects to pemeriksaan page (will be created in Tahap 6 Bagian 2)
     */
    public function periksaPasien($kunjunganId)
    {
        // TODO: Update status to 'periksa' and redirect to pemeriksaan form
        // For now, just redirect to placeholder route
        return redirect()->route('dokter.periksa', ['kunjungan' => $kunjunganId]);
    }

    /**
     * Render the component view
     */
    public function render()
    {
        return view('livewire.dokter.antrean-poli')
            ->layout('layouts.app')
            ->title('Antrean Poli - ' . ($this->poli->nama_poli ?? 'Tidak Ada Poli'));
    }
}
