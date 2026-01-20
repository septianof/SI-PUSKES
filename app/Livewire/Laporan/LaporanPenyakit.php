<?php

namespace App\Livewire\Laporan;

use App\Models\RekamMedis;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.app')]
#[Title('Laporan 10 Penyakit Terbanyak - SI PUSKES')]
class LaporanPenyakit extends Component
{
    public $tglMulai;
    public $tglAkhir;
    public $dataPenyakit = [];
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
     * Generate laporan penyakit terbanyak
     */
    public function generateLaporan()
    {
        $this->validate();

        // Query untuk mendapatkan top 10 diagnosis
        $results = RekamMedis::whereBetween('tgl_periksa', [$this->tglMulai . ' 00:00:00', $this->tglAkhir . ' 23:59:59'])
            ->whereNotNull('diagnosa')
            ->where('diagnosa', '!=', '')
            ->select('diagnosa', DB::raw('count(*) as jumlah'))
            ->groupBy('diagnosa')
            ->orderByDesc('jumlah')
            ->limit(10)
            ->get();

        // Calculate total untuk percentage
        $totalKasus = $results->sum('jumlah');

        // Transform dengan ranking dan percentage
        $this->dataPenyakit = $results->map(function($item, $index) use ($totalKasus) {
            return [
                'ranking' => $index + 1,
                'diagnosa' => $item->diagnosa,
                'jumlah_kasus' => $item->jumlah,
                'persentase' => $totalKasus > 0 ? round(($item->jumlah / $totalKasus) * 100, 2) : 0,
            ];
        })->toArray();

        $this->showResults = true;

        \Log::info('Laporan Penyakit Generated', [
            'periode' => $this->tglMulai . ' - ' . $this->tglAkhir,
            'total_unique_diseases' => count($this->dataPenyakit),
            'total_kasus' => $totalKasus
        ]);
    }

    /**
     * Get total kasus
     */
    public function getTotalKasusProperty()
    {
        return collect($this->dataPenyakit)->sum('jumlah_kasus');
    }

    /**
     * Reset form
     */
    public function resetForm()
    {
        $this->tglAkhir = now()->format('Y-m-d');
        $this->tglMulai = now()->subDays(30)->format('Y-m-d');
        $this->dataPenyakit = [];
        $this->showResults = false;
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.laporan.laporan-penyakit');
    }
}
