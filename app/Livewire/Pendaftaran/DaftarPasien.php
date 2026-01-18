<?php

namespace App\Livewire\Pendaftaran;

use App\Models\Pasien;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Pendaftaran Pasien - SI PUSKES')]
class DaftarPasien extends Component
{
    // Properties untuk Search
    public $search = '';
    public $searchResults = [];

    // Properties untuk Selected Pasien
    public $selectedPasienId = null;
    public $selectedPasien = null;

    // Properties untuk Form Registrasi Pasien Baru
    public $showModalPasienBaru = false;
    public $nik = '';
    public $nama = '';
    public $tgl_lahir = '';
    public $alamat = '';
    public $no_bpjs = '';

    // Validation Messages
    protected $messages = [
        'nik.required' => 'NIK wajib diisi.',
        'nik.digits' => 'NIK harus 16 digit.',
        'nik.unique' => 'NIK sudah terdaftar.',
        'nama.required' => 'Nama wajib diisi.',
        'nama.min' => 'Nama minimal 3 karakter.',
        'tgl_lahir.required' => 'Tanggal lahir wajib diisi.',
        'tgl_lahir.date' => 'Format tanggal tidak valid.',
        'tgl_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
        'alamat.required' => 'Alamat wajib diisi.',
        'alamat.min' => 'Alamat minimal 10 karakter.',
        'no_bpjs.digits' => 'No BPJS harus 13 digit.',
    ];

    /**
     * Perform search ketika user mengetik
     */
    public function updatedSearch()
    {
        if (strlen($this->search) >= 3) {
            $this->performSearch();
        } else {
            $this->searchResults = [];
        }
    }

    /**
     * Cari pasien berdasarkan NIK atau Nama
     */
    private function performSearch()
    {
        $this->searchResults = Pasien::query()
            ->where(function ($query) {
                $query->where('nik', 'like', '%' . $this->search . '%')
                    ->orWhere('nama', 'like', '%' . $this->search . '%')
                    ->orWhere('no_rm', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Pilih pasien dari hasil pencarian
     */
    public function selectPasien($pasienId)
    {
        $this->selectedPasienId = $pasienId;
        $this->selectedPasien = Pasien::find($pasienId);
        
        // Clear search
        $this->search = '';
        $this->searchResults = [];

        // Dispatch event untuk komponen lain (nanti untuk Form Kunjungan)
        $this->dispatch('pasien-selected', pasienId: $pasienId);
    }

    /**
     * Clear selected pasien
     */
    public function clearSelectedPasien()
    {
        $this->selectedPasienId = null;
        $this->selectedPasien = null;
        $this->dispatch('pasien-cleared');
    }

    /**
     * Open Modal untuk Pasien Baru
     */
    public function openModalPasienBaru()
    {
        $this->resetFormPasienBaru();
        $this->showModalPasienBaru = true;
    }

    /**
     * Close Modal Pasien Baru
     */
    public function closeModalPasienBaru()
    {
        $this->showModalPasienBaru = false;
        $this->resetFormPasienBaru();
    }

    /**
     * Store Pasien Baru
     */
    public function storePasienBaru()
    {
        // Validation Rules
        $rules = [
            'nik' => 'required|digits:16|unique:pasiens,nik',
            'nama' => 'required|min:3',
            'tgl_lahir' => 'required|date|before:today',
            'alamat' => 'required|min:10',
        ];

        // No BPJS optional, tapi jika diisi harus 13 digit
        if (!empty($this->no_bpjs)) {
            $rules['no_bpjs'] = 'digits:13';
        }

        // Validate Input
        $validated = $this->validate($rules, $this->messages);

        try {
            // Generate No RM otomatis
            $noRm = $this->generateNoRm();

            // Create New Pasien
            $pasien = Pasien::create([
                'no_rm' => $noRm,
                'nik' => $this->nik,
                'nama' => $this->nama,
                'tgl_lahir' => $this->tgl_lahir,
                'alamat' => $this->alamat,
                'no_bpjs' => $this->no_bpjs ?: null,
            ]);

            // Auto-select pasien yang baru dibuat
            $this->selectPasien($pasien->id);

            // Close Modal
            $this->showModalPasienBaru = false;
            $this->resetFormPasienBaru();

            session()->flash('success', 'Pasien baru berhasil didaftarkan dengan No RM: ' . $noRm);

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Generate No RM otomatis dengan format YYYYMM-XXX
     * 
     * @return string
     */
    private function generateNoRm()
    {
        // Format: YYYYMM-XXX (Contoh: 202601-001)
        $yearMonth = now()->format('Ym'); // Contoh: 202601

        // Cari pasien terakhir di bulan ini
        $lastPasien = Pasien::query()
            ->where('no_rm', 'like', $yearMonth . '-%')
            ->orderBy('no_rm', 'desc')
            ->first();

        if ($lastPasien) {
            // Ambil nomor urut terakhir dan increment
            $lastNumber = (int) substr($lastPasien->no_rm, -3); // Ambil 3 digit terakhir
            $newNumber = $lastNumber + 1;
        } else {
            // Jika belum ada pasien bulan ini, mulai dari 001
            $newNumber = 1;
        }

        // Format nomor urut dengan leading zeros (3 digit)
        $formattedNumber = str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        return $yearMonth . '-' . $formattedNumber;
    }

    /**
     * Reset Form Pasien Baru
     */
    private function resetFormPasienBaru()
    {
        $this->nik = '';
        $this->nama = '';
        $this->tgl_lahir = '';
        $this->alamat = '';
        $this->no_bpjs = '';
        $this->resetValidation();
    }

    /**
     * Render Component
     */
    public function render()
    {
        return view('livewire.pendaftaran.daftar-pasien');
    }
}
