<?php

namespace App\Livewire\Laporan;

use App\Models\Kunjungan;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Laporan Kunjungan Poli - SI PUSKES')]
class LaporanKunjungan extends Component
{
    public $tglMulai;

    public $tglAkhir;

    public $dataKunjungan = [];

    public $showResults = false;

    /**
     * Initialize with default date range (last 30 days)
     */
    public function mount()
    {
        $this->tglAkhir = now()->format('Y-m-d');
        $this->tglMulai = now()->subDays(30)->format('Y-m-d');
    }

    /**
     * Validate date range
     */
    public function rules()
    {
        return [
            'tglMulai' => 'required|date',
            'tglAkhir' => 'required|date|after_or_equal:tglMulai',
        ];
    }

    public function messages()
    {
        return [
            'tglAkhir.after_or_equal' => 'Tanggal akhir harus lebih besar atau sama dengan tanggal mulai.',
        ];
    }

    /**
     * Generate laporan kunjungan
     */
    public function generateLaporan()
    {
        $this->validate();

        // Query kunjungan dengan eager loading poli
        $kunjungans = Kunjungan::with('poli')
            ->whereBetween('tgl_kunjungan', [$this->tglMulai.' 00:00:00', $this->tglAkhir.' 23:59:59'])
            ->get();

        // Group by poli_id dan hitung statistik
        $this->dataKunjungan = $kunjungans->groupBy('poli_id')
            ->map(function ($items, $poliId) {
                $poli = $items->first()->poli;

                return [
                    'poli_id' => $poli->id,
                    'nama_poli' => $poli->nama_poli,
                    'total_kunjungan' => $items->count(),
                    'pasien_umum' => $items->where('metode_bayar', 'Umum')->count(),
                    'pasien_bpjs' => $items->where('metode_bayar', 'BPJS')->count(),
                ];
            })
            ->sortByDesc('total_kunjungan')
            ->values()
            ->toArray();

        $this->showResults = true;

        \Log::info('Laporan Kunjungan Generated', [
            'periode' => $this->tglMulai.' - '.$this->tglAkhir,
            'total_poli' => count($this->dataKunjungan),
            'total_kunjungan' => collect($this->dataKunjungan)->sum('total_kunjungan'),
        ]);
    }

    /**
     * Get total summary
     */
    public function getTotalSummaryProperty()
    {
        $data = collect($this->dataKunjungan);

        return [
            'total_kunjungan' => $data->sum('total_kunjungan'),
            'total_umum' => $data->sum('pasien_umum'),
            'total_bpjs' => $data->sum('pasien_bpjs'),
        ];
    }

    /**
     * Reset form
     */
    public function resetForm()
    {
        $this->tglAkhir = now()->format('Y-m-d');
        $this->tglMulai = now()->subDays(30)->format('Y-m-d');
        $this->dataKunjungan = [];
        $this->showResults = false;
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.laporan.laporan-kunjungan');
    }
}
