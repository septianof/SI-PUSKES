<?php

namespace App\Livewire\Dokter;

use Livewire\Component;
use App\Models\Pasien;
use App\Models\RekamMedis;
use Illuminate\Support\Facades\Auth;

class RiwayatPasien extends Component
{
    // Search & Results
    public $searchQuery = '';
    public $patientResults = [];
    
    // Selected Patient & Medical Records
    public $selectedPasien = null;
    public $rekamMedisList = [];

    /**
     * Mount component and load initial patient list
     * Show all patients who have visited this doctor's poli
     */
    public function mount()
    {
        $this->loadInitialPatients();
    }

    /**
     * Load all patients who have visited this doctor's poli
     */
    public function loadInitialPatients()
    {
        $userPoliId = Auth::user()->poli_id;
        
        // Get all patients who have ever visited this poli
        $this->patientResults = Pasien::whereHas('kunjungans', function($query) use ($userPoliId) {
                $query->where('poli_id', $userPoliId);
            })
            ->orderBy('nama', 'asc')
            ->get();
    }

    /**
     * Auto-trigger when search query changes (wire:model.live)
     */
    public function updatedSearchQuery()
    {
        if (strlen($this->searchQuery) < 2) {
            // If search is cleared or too short, show all patients from poli
            $this->loadInitialPatients();
        } else {
            // Filter the list
            $this->searchPatients();
        }
    }

    /**
     * Search/filter patients by name or No. RM
     */
    public function searchPatients()
    {
        $userPoliId = Auth::user()->poli_id;
        
        $this->patientResults = Pasien::whereHas('kunjungans', function($query) use ($userPoliId) {
                $query->where('poli_id', $userPoliId);
            })
            ->where(function($query) {
                $query->where('nama', 'like', '%' . $this->searchQuery . '%')
                      ->orWhere('no_rm', 'like', '%' . $this->searchQuery . '%');
            })
            ->orderBy('nama', 'asc')
            ->limit(50)
            ->get();
    }

    /**
     * Select a patient and load their medical record history
     */
    public function selectPasien($pasienId)
    {
        // Load selected patient
        $this->selectedPasien = Pasien::find($pasienId);
        
        if ($this->selectedPasien) {
            $this->loadRekamMedis();
        }
    }

    /**
     * Load medical records for selected patient with eager loading
     * Ordered by most recent first (descending)
     */
    public function loadRekamMedis()
    {
        $this->rekamMedisList = RekamMedis::whereHas('kunjungan', function($query) {
                $query->where('pasien_id', $this->selectedPasien->id);
            })
            ->with([
                'kunjungan.poli',
                'dokter',
                'resep.detailReseps.obat'
            ])
            ->orderBy('tgl_periksa', 'desc')
            ->get();
    }

    /**
     * Helper: Calculate patient age
     */
    public function getUmurProperty()
    {
        if (!$this->selectedPasien || !$this->selectedPasien->tgl_lahir) {
            return '-';
        }
        
        $birthDate = \Carbon\Carbon::parse($this->selectedPasien->tgl_lahir);
        $age = $birthDate->age;
        
        return $age . ' tahun';
    }

    /**
     * Helper: Parse tanda vital JSON to array
     */
    public function parseTandaVital($tandaVitalJson)
    {
        if (!$tandaVitalJson) {
            return null;
        }
        
        return json_decode($tandaVitalJson, true);
    }

    /**
     * Render the component view
     */
    public function render()
    {
        return view('livewire.dokter.riwayat-pasien')
            ->layout('components.layouts.app')
            ->title('Riwayat Rekam Medis Pasien');
    }
}
