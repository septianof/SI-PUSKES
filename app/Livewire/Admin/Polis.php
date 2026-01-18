<?php

namespace App\Livewire\Admin;

use App\Models\Poli;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Kelola Poliklinik - SI PUSKES')]
class Polis extends Component
{
    use WithPagination;

    // Properties untuk Form Input
    public $nama_poli = '';
    public $lokasi = '';
    public $tarif_daftar = '';

    // Properties untuk State Management
    public $poliId = null;
    public $isEditMode = false;
    public $showModal = false;

    // Search & Filter
    public $search = '';
    public $perPage = 10;

    // Validation Messages
    protected $messages = [
        'nama_poli.required' => 'Nama poli wajib diisi.',
        'nama_poli.min' => 'Nama poli minimal 3 karakter.',
        'lokasi.required' => 'Lokasi wajib diisi.',
        'tarif_daftar.required' => 'Tarif pendaftaran wajib diisi.',
        'tarif_daftar.numeric' => 'Tarif harus berupa angka.',
        'tarif_daftar.min' => 'Tarif minimal 0.',
    ];

    /**
     * Reset pagination ketika search berubah
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Render Component dengan Data Polis
     */
    public function render()
    {
        $polis = Poli::query()
            ->when($this->search, function ($query) {
                $query->where('nama_poli', 'like', '%' . $this->search . '%')
                    ->orWhere('lokasi', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.polis', [
            'polis' => $polis,
        ]);
    }

    /**
     * Open Modal untuk Create Poli Baru
     */
    public function create()
    {
        $this->resetForm();
        $this->isEditMode = false;
        $this->showModal = true;
    }

    /**
     * Open Modal untuk Edit Poli
     */
    public function edit($id)
    {
        $poli = Poli::findOrFail($id);
        
        $this->poliId = $poli->id;
        $this->nama_poli = $poli->nama_poli;
        $this->lokasi = $poli->lokasi;
        $this->tarif_daftar = $poli->tarif_daftar;
        
        $this->isEditMode = true;
        $this->showModal = true;
    }

    /**
     * Store New Poli atau Update Existing Poli
     */
    public function store()
    {
        // Validation Rules
        $validated = $this->validate([
            'nama_poli' => 'required|min:3',
            'lokasi' => 'required',
            'tarif_daftar' => 'required|numeric|min:0',
        ], $this->messages);

        try {
            if ($this->isEditMode) {
                // Update Existing Poli
                $poli = Poli::findOrFail($this->poliId);
                
                $poli->nama_poli = $this->nama_poli;
                $poli->lokasi = $this->lokasi;
                $poli->tarif_daftar = $this->tarif_daftar;
                
                $poli->save();
                
                session()->flash('success', 'Data poli berhasil diupdate.');
            } else {
                // Create New Poli
                Poli::create([
                    'nama_poli' => $this->nama_poli,
                    'lokasi' => $this->lokasi,
                    'tarif_daftar' => $this->tarif_daftar,
                ]);
                
                session()->flash('success', 'Data poli baru berhasil ditambahkan.');
            }

            // Reset Form & Close Modal
            $this->resetForm();
            $this->showModal = false;

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Delete Poli dengan ID tertentu
     */
    public function delete($id)
    {
        try {
            $poli = Poli::findOrFail($id);
            
            // Cek apakah poli masih digunakan di kunjungan
            if ($poli->kunjungans()->count() > 0) {
                session()->flash('error', 'Poli tidak dapat dihapus karena masih memiliki data kunjungan.');
                return;
            }
            
            $poli->delete();
            session()->flash('success', 'Data poli berhasil dihapus.');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Close Modal
     */
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    /**
     * Reset Form Properties
     */
    private function resetForm()
    {
        $this->poliId = null;
        $this->nama_poli = '';
        $this->lokasi = '';
        $this->tarif_daftar = '';
        $this->isEditMode = false;
        $this->resetValidation();
    }
}
