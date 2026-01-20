<?php

namespace App\Livewire\Pendaftaran;

use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\Poli;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

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

    // Properties untuk Form Kunjungan
    public $poli_id = '';

    public $metode_bayar = 'Umum';

    public $keluhan_awal = '';

    // Data untuk Dropdown
    public $poliList = [];

    // Status kunjungan berhasil
    public $kunjunganBerhasil = false;

    public $noAntrean = null;

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
        'poli_id.required' => 'Poli wajib dipilih.',
        'poli_id.exists' => 'Poli yang dipilih tidak valid.',
        'metode_bayar.required' => 'Metode bayar wajib dipilih.',
        'metode_bayar.in' => 'Metode bayar harus Umum atau BPJS.',
        'keluhan_awal.required' => 'Keluhan awal wajib diisi.',
        'keluhan_awal.min' => 'Keluhan awal minimal 10 karakter.',
    ];

    /**
     * Mount: Load data Poli saat component dimulai
     */
    public function mount()
    {
        $this->loadPoliList();
    }

    /**
     * Load data Poli untuk dropdown
     */
    private function loadPoliList()
    {
        $this->poliList = Poli::orderBy('nama_poli', 'asc')->get();
    }

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
                $query->where('nik', 'like', '%'.$this->search.'%')
                    ->orWhere('nama', 'like', '%'.$this->search.'%')
                    ->orWhere('no_rm', 'like', '%'.$this->search.'%');
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

        // Reset form kunjungan ke default
        $this->metode_bayar = 'Umum';
        $this->no_bpjs = '';

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
        $this->resetFormKunjungan();
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
        // Validation Rules (NO MORE no_bpjs)
        $rules = [
            'nik' => 'required|digits:16|unique:pasiens,nik',
            'nama' => 'required|min:3',
            'tgl_lahir' => 'required|date|before:today',
            'alamat' => 'required|min:10',
        ];

        // Validate Input
        $validated = $this->validate($rules, $this->messages);

        try {
            // Generate No RM otomatis
            $noRm = $this->generateNoRm();

            // Create New Pasien (WITHOUT no_bpjs)
            $pasien = Pasien::create([
                'no_rm' => $noRm,
                'nik' => $this->nik,
                'nama' => $this->nama,
                'tgl_lahir' => $this->tgl_lahir,
                'alamat' => $this->alamat,
            ]);

            // Auto-select pasien yang baru dibuat
            $this->selectPasien($pasien->id);

            // Close Modal
            $this->showModalPasienBaru = false;
            $this->resetFormPasienBaru();

            session()->flash('success', 'Pasien baru berhasil didaftarkan dengan No RM: '.$noRm);

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: '.$e->getMessage());
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
            ->where('no_rm', 'like', $yearMonth.'-%')
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

        return $yearMonth.'-'.$formattedNumber;
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
     * Store Kunjungan Baru
     */
    public function storeKunjungan()
    {
        // Pastikan pasien sudah dipilih
        if (! $this->selectedPasienId) {
            session()->flash('error', 'Silakan pilih pasien terlebih dahulu.');

            return;
        }

        // CRITICAL: Check for active visits (prevent double registration)
        $activeVisit = Kunjungan::with('poli')
            ->where('pasien_id', $this->selectedPasienId)
            ->whereIn('status', ['menunggu', 'bayar', 'obat'])
            ->first();

        if ($activeVisit) {
            $poli = $activeVisit->poli->nama_poli;
            $statusLabel = match ($activeVisit->status) {
                'menunggu' => 'menunggu antrian',
                'bayar' => 'di kasir',
                'obat' => 'di farmasi',
                default => 'aktif'
            };

            session()->flash('error', "Pasien masih memiliki kunjungan aktif ({$statusLabel}) di {$poli}. Silakan selesaikan kunjungan tersebut terlebih dahulu.");

            return;
        }

        // Validation Rules
        $rules = [
            'poli_id' => 'required|exists:polis,id',
            'metode_bayar' => 'required|in:Umum,BPJS',
            'keluhan_awal' => 'required|min:10',
        ];

        // CONDITIONAL: Jika pilih BPJS, no_bpjs wajib diisi
        if ($this->metode_bayar === 'BPJS') {
            $rules['no_bpjs'] = 'required|digits:13';
        }

        // Validate Input
        $this->validate($rules, $this->messages);

        try {
            // Create Kunjungan dengan metode_bayar dan no_bpjs
            $kunjungan = Kunjungan::create([
                'pasien_id' => $this->selectedPasienId,
                'poli_id' => $this->poli_id,
                'tgl_kunjungan' => now(),
                'status' => 'menunggu',
                'metode_bayar' => $this->metode_bayar,
                'no_bpjs' => $this->metode_bayar === 'BPJS' ? $this->no_bpjs : null,
                'keluhan_awal' => $this->keluhan_awal,
            ]);

            // BPJS patient - No additional claims record needed

            // Set status berhasil
            $this->kunjunganBerhasil = true;
            $this->noAntrean = $kunjungan->id; // Bisa diganti dengan format nomor antrean custom

            // Flash success message
            session()->flash('success', 'Pendaftaran kunjungan berhasil! Nomor Antrean: '.$this->noAntrean);

            // Reset form kunjungan
            $this->resetFormKunjungan();

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    /**
     * Reset Form Kunjungan
     */
    private function resetFormKunjungan()
    {
        $this->poli_id = '';
        $this->metode_bayar = 'Umum';
        $this->no_bpjs = '';
        $this->keluhan_awal = '';
        $this->kunjunganBerhasil = false;
        $this->noAntrean = null;
        $this->resetValidation(['poli_id', 'metode_bayar', 'no_bpjs', 'keluhan_awal']);
    }

    /**
     * Reset untuk pendaftaran baru (clear pasien + form)
     */
    public function resetPendaftaran()
    {
        $this->clearSelectedPasien();
        $this->kunjunganBerhasil = false;
        $this->noAntrean = null;
    }

    /**
     * Render Component
     */
    public function render()
    {
        return view('livewire.pendaftaran.daftar-pasien');
    }
}
