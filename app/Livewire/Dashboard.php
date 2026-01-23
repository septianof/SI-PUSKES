<?php

namespace App\Livewire;

use App\Models\Kunjungan;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Pembayaran;
use App\Models\Poli;
use App\Models\Resep;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Dashboard - SI PUSKES')]
class Dashboard extends Component
{
    public $stats = [];

    public $chartPeriod = '7days'; // Default 7 hari

    public $chartData = [];

    public function mount()
    {
        $user = Auth::user();
        $role = $user->role;

        $this->stats = $this->getStatsByRole($role);
        $this->calculateChartData();
    }

    /**
     * Calculate chart data based on selected period
     */
    public function calculateChartData()
    {
        if ($this->chartPeriod === '7days') {
            // 7 hari terakhir
            $this->chartData = $this->getLastSevenDaysData();
        } else {
            // 30 hari terakhir (1 bulan)
            $this->chartData = $this->getLastMonthData();
        }
    }

    /**
     * Get last 7 days visit data
     */
    private function getLastSevenDaysData()
    {
        $data = [];
        $days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = Kunjungan::whereDate('tgl_kunjungan', $date)->count();
            
            $data[] = [
                'label' => $days[$date->dayOfWeek],
                'value' => $count,
                'date' => $date->format('d M'),
            ];
        }

        return $data;
    }

    /**
     * Get last 30 days visit data (grouped by week)
     */
    private function getLastMonthData()
    {
        $data = [];
        
        // Group 30 hari menjadi 4 minggu
        for ($week = 3; $week >= 0; $week--) {
            $startDate = now()->subWeeks($week + 1)->startOfWeek();
            $endDate = now()->subWeeks($week)->endOfWeek();
            
            $count = Kunjungan::whereBetween('tgl_kunjungan', [$startDate, $endDate])->count();
            
            $data[] = [
                'label' => 'W' . (4 - $week), // W1, W2, W3, W4
                'value' => $count,
                'date' => $startDate->format('d M') . ' - ' . $endDate->format('d M'),
            ];
        }

        return $data;
    }


    private function getStatsByRole($role)
    {        
        // Default stats structure       
        switch ($role) {
            case 'admin':
                return [
                    [
                        'label' => 'Total User',
                        'value' => User::count(),
                        'icon' => 'users',
                        'color' => 'blue',
                        'subtext' => 'User aktif sistem',
                    ],
                    [
                        'label' => 'Total Poli',
                        'value' => Poli::count(),
                        'icon' => 'building-office',
                        'color' => 'green',
                        'subtext' => 'Poliklinik tersedia',
                    ],
                    [
                        'label' => 'Total Obat',
                        'value' => Obat::count(),
                        'icon' => 'beaker',
                        'color' => 'yellow',
                        'subtext' => 'Jenis obat terdaftar',
                    ],
                ];

            case 'pendaftaran':
                return [
                    [
                        'label' => 'Pasien Baru',
                        'value' => Pasien::whereDate('created_at', today())->count(),
                        'icon' => 'user-plus',
                        'color' => 'green',
                        'subtext' => 'Hari ini',
                    ],
                    [
                        'label' => 'Selesai',
                        'value' => Kunjungan::where('status', 'selesai')->whereDate('tgl_kunjungan', today())->count(),
                        'icon' => 'check-circle',
                        'color' => 'purple',
                        'subtext' => 'Hari ini',
                    ],
                    [
                        'label' => 'Antrean Bayar',
                        'value' => Kunjungan::where('status', 'bayar')->count(),
                        'icon' => 'user-group',
                        'color' => 'blue',
                        'subtext' => 'Perlu diproses',
                    ],
                ];

            case 'dokter':
                $user = Auth::user();
                $poliId = $user->poli_id;

                return [
                    [
                        'label' => 'Pasien Menunggu',
                        'value' => $poliId ? Kunjungan::where('poli_id', $poliId)
                            ->where('status', 'menunggu')
                            ->count() : 0,
                        'icon' => 'stethoscope',
                        'color' => 'blue',
                        'subtext' => 'Di Poli Anda',
                    ],
                    [
                        'label' => 'Selesai Periksa',
                        'value' => $poliId ? Kunjungan::where('poli_id', $poliId)
                            ->whereIn('status', ['bayar', 'obat', 'selesai'])
                            ->whereDate('tgl_kunjungan', today())
                            ->count() : 0,
                        'icon' => 'clipboard-document-check',
                        'color' => 'green',
                        'subtext' => 'Hari ini',
                    ],
                    [
                        'label' => 'Total Pasien',
                        'value' => $poliId ? Kunjungan::where('poli_id', $poliId)
                            ->whereDate('tgl_kunjungan', today())
                            ->count() : 0,
                        'icon' => 'users',
                        'color' => 'yellow',
                        'subtext' => 'Hari ini',
                    ],
                ];

            case 'apoteker':
                return [
                    [
                        'label' => 'Resep Masuk',
                        'value' => Kunjungan::where('status', 'obat')->count(),
                        'icon' => 'document-text',
                        'color' => 'red',
                        'subtext' => 'Perlu disiapkan',
                    ],
                    [
                        'label' => 'Obat Stok Menipis',
                        'value' => Obat::where('stok', '<', 10)->count(),
                        'icon' => 'exclamation-triangle',
                        'color' => 'yellow',
                        'subtext' => 'Stok < 10',
                    ],
                    [
                        'label' => 'Resep Selesai',
                        'value' => Resep::where('status', 'selesai')->whereDate('updated_at', today())->count(),
                        'icon' => 'check-badge',
                        'color' => 'green',
                        'subtext' => 'Hari ini',
                    ],
                ];

            default: // kepala_puskesmas & fallback
                $totalKunjunganHariIni = Kunjungan::whereDate('tgl_kunjungan', today())->count();
                $pasienBpjsHariIni = Kunjungan::where('metode_bayar', 'BPJS')->whereDate('tgl_kunjungan', today())->count();
                $pendapatanHariIni = Pembayaran::whereDate('tgl_bayar', today())->sum('total_biaya');

                return [
                    [
                        'label' => 'Total Kunjungan',
                        'value' => $totalKunjunganHariIni,
                        'icon' => 'chart-bar',
                        'color' => 'blue',
                        'subtext' => 'Hari ini',
                    ],
                    [
                        'label' => 'Pasien BPJS',
                        'value' => $pasienBpjsHariIni,
                        'icon' => 'credit-card',
                        'color' => 'green',
                        'subtext' => 'Hari ini',
                    ],
                    [
                        'label' => 'Pendapatan',
                        'value' => 'Rp ' . number_format($pendapatanHariIni, 0, ',', '.'),
                        'icon' => 'currency-dollar',
                        'color' => 'purple',
                        'subtext' => 'Hari ini',
                    ],
                ];
        }
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
