<?php

namespace App\Livewire\Laporan;

use App\Models\DetailResep;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Laporan Pemakaian Obat - SI PUSKES')]
class LaporanObat extends Component
{
    public $tglMulai;

    public $tglAkhir;

    public $dataObat = [];

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
     * Generate laporan pemakaian obat
     */
    public function generateLaporan()
    {
        $this->validate();

        // Query detail resep dengan filter kunjungan dalam periode
        $detailReseps = DetailResep::with(['obat', 'resep.rekamMedis.kunjungan'])
            ->whereHas('resep.rekamMedis.kunjungan', function ($query) {
                $query->whereBetween('tgl_kunjungan', [$this->tglMulai.' 00:00:00', $this->tglAkhir.' 23:59:59']);
            })
            ->get();

        // Group by obat_id dan aggregate
        $this->dataObat = $detailReseps->groupBy('obat_id')
            ->map(function ($items) {
                $obat = $items->first()->obat;
                $totalJumlah = $items->sum('jumlah');

                return [
                    'obat_id' => $obat->id,
                    'nama_obat' => $obat->nama_obat,
                    'jenis' => $obat->jenis,
                    'total_digunakan' => $totalJumlah,
                    'stok_tersisa' => $obat->stok,
                    'frekuensi_resep' => $items->count(),
                    'is_low_stock' => $obat->stok < 10, // Flag untuk stok rendah
                ];
            })
            ->sortByDesc('total_digunakan')
            ->values()
            ->take(20) // Top 20 obat
            ->toArray();

        $this->showResults = true;

        \Log::info('Laporan Obat Generated', [
            'periode' => $this->tglMulai.' - '.$this->tglAkhir,
            'total_obat' => count($this->dataObat),
            'total_digunakan' => collect($this->dataObat)->sum('total_digunakan'),
        ]);
    }

    /**
     * Get obat dengan stok rendah
     */
    public function getLowStockObatProperty()
    {
        return collect($this->dataObat)->where('is_low_stock', true);
    }

    /**
     * Get total summary
     */
    public function getTotalSummaryProperty()
    {
        $data = collect($this->dataObat);

        return [
            'total_jenis_obat' => $data->count(),
            'total_digunakan' => $data->sum('total_digunakan'),
            'total_frekuensi' => $data->sum('frekuensi_resep'),
        ];
    }

    /**
     * Reset form
     */
    public function resetForm()
    {
        $this->tglAkhir = now()->format('Y-m-d');
        $this->tglMulai = now()->subDays(30)->format('Y-m-d');
        $this->dataObat = [];
        $this->showResults = false;
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.laporan.laporan-obat');
    }
}
