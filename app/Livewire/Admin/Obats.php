<?php

namespace App\Livewire\Admin;

use App\Models\Obat;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Kelola Obat - SI PUSKES')]
class Obats extends Component
{
    use WithPagination;

    // Properties untuk Form Input
    public $nama_obat = '';
    public $jenis = '';
    public $stok = '';
    public $harga = '';

    // Properties untuk State Management
    public $obatId = null;
    public $isEditMode = false;
    public $showModal = false;

    // Search & Filter
    public $search = '';
    public $perPage = 10;

    // Jenis Obat Options
    public $jenisOptions = [
        'Tablet' => 'Tablet',
        'Sirup' => 'Sirup',
        'Kapsul' => 'Kapsul',
        'Salep' => 'Salep',
        'Injeksi' => 'Injeksi',
        'Tetes' => 'Tetes',
        'Suppositoria' => 'Suppositoria',
    ];

    // Validation Messages
    protected $messages = [
        'nama_obat.required' => 'Nama obat wajib diisi.',
        'nama_obat.min' => 'Nama obat minimal 3 karakter.',
        'jenis.required' => 'Jenis obat wajib dipilih.',
        'stok.required' => 'Stok wajib diisi.',
        'stok.integer' => 'Stok harus berupa angka bulat.',
        'stok.min' => 'Stok minimal 0.',
        'harga.required' => 'Harga wajib diisi.',
        'harga.numeric' => 'Harga harus berupa angka.',
        'harga.min' => 'Harga minimal 0.',
    ];

    /**
     * Reset pagination ketika search berubah
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Render Component dengan Data Obats
     */
    public function render()
    {
        $obats = Obat::query()
            ->when($this->search, function ($query) {
                $query->where('nama_obat', 'like', '%' . $this->search . '%')
                    ->orWhere('jenis', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.obats', [
            'obats' => $obats,
        ]);
    }

    /**
     * Open Modal untuk Create Obat Baru
     */
    public function create()
    {
        $this->resetForm();
        $this->isEditMode = false;
        $this->showModal = true;
    }

    /**
     * Open Modal untuk Edit Obat
     */
    public function edit($id)
    {
        $obat = Obat::findOrFail($id);
        
        $this->obatId = $obat->id;
        $this->nama_obat = $obat->nama_obat;
        $this->jenis = $obat->jenis;
        $this->stok = $obat->stok;
        $this->harga = $obat->harga;
        
        $this->isEditMode = true;
        $this->showModal = true;
    }

    /**
     * Store New Obat atau Update Existing Obat
     */
    public function store()
    {
        // Validation Rules
        $validated = $this->validate([
            'nama_obat' => 'required|min:3',
            'jenis' => 'required',
            'stok' => 'required|integer|min:0',
            'harga' => 'required|numeric|min:0',
        ], $this->messages);

        try {
            if ($this->isEditMode) {
                // Update Existing Obat
                $obat = Obat::findOrFail($this->obatId);
                
                $obat->nama_obat = $this->nama_obat;
                $obat->jenis = $this->jenis;
                $obat->stok = $this->stok;
                $obat->harga = $this->harga;
                
                $obat->save();
                
                session()->flash('success', 'Data obat berhasil diupdate.');
            } else {
                // Create New Obat
                Obat::create([
                    'nama_obat' => $this->nama_obat,
                    'jenis' => $this->jenis,
                    'stok' => $this->stok,
                    'harga' => $this->harga,
                ]);
                
                session()->flash('success', 'Data obat baru berhasil ditambahkan.');
            }

            // Reset Form & Close Modal
            $this->resetForm();
            $this->showModal = false;

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Delete Obat dengan ID tertentu
     */
    public function delete($id)
    {
        try {
            $obat = Obat::findOrFail($id);
            
            // Cek apakah obat masih digunakan di resep
            if ($obat->detailReseps()->count() > 0) {
                session()->flash('error', 'Obat tidak dapat dihapus karena masih digunakan dalam resep.');
                return;
            }
            
            $obat->delete();
            session()->flash('success', 'Data obat berhasil dihapus.');
            
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
        $this->obatId = null;
        $this->nama_obat = '';
        $this->jenis = '';
        $this->stok = '';
        $this->harga = '';
        $this->isEditMode = false;
        $this->resetValidation();
    }
}
