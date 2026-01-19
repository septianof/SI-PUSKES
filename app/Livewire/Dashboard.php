<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
// Import models lain jika sudah ada (Kunjungan, Poli, Obat, dll)

#[Layout('components.layouts.app')]
#[Title('Dashboard - SI PUSKES')]
class Dashboard extends Component
{
    public $stats = [];

    public function mount()
    {
        $user = Auth::user();
        $role = $user->role;

        $this->stats = $this->getStatsByRole($role);
    }

    private function getStatsByRole($role)
    {
        // Default stats structure
        // [Label, Value, Icon (SVG path or component key), Color Class, Subtext]
        
        // Note: Karena tabel transaksi belum diisi, kita gunakan query count() real yang akan return 0.
        // Jika model belum ada, kita return 0 hardcoded dulu untuk menghindari error.

        switch ($role) {
            case 'admin':
                return [
                    [
                        'label' => 'Total User',
                        'value' => User::count(),
                        'icon' => 'users',
                        'color' => 'blue',
                        'subtext' => 'User aktif sistem'
                    ],
                    [
                        'label' => 'Total Poli',
                        'value' => 0, // Poli::count() placeholder
                        'icon' => 'building-office',
                        'color' => 'green',
                        'subtext' => 'Poliklinik tersedia'
                    ],
                    [
                        'label' => 'Total Obat',
                        'value' => 0, // Obat::count() placeholder
                        'icon' => 'beaker',
                        'color' => 'yellow',
                        'subtext' => 'Jenis obat terdaftar'
                    ]
                ];

            case 'pendaftaran':
                return [
                    [
                        'label' => 'Antrean Menunggu',
                        'value' => 0, // Kunjungan::where('status', 'waiting')->count()
                        'icon' => 'user-group',
                        'color' => 'blue',
                        'subtext' => 'Perlu diproses'
                    ],
                    [
                        'label' => 'Pasien Baru',
                        'value' => 0, // Pasien::whereDate('created_at', today())->count()
                        'icon' => 'user-plus',
                        'color' => 'green',
                        'subtext' => 'Hari ini'
                    ],
                    [
                        'label' => 'Selesai',
                        'value' => 0, // Kunjungan::where('status', 'finished')->count()
                        'icon' => 'check-circle',
                        'color' => 'purple',
                        'subtext' => 'Hari ini'
                    ]
                ];

            case 'dokter':
                $user = Auth::user();
                $poliId = $user->poli_id;
                
                return [
                    [
                        'label' => 'Pasien Menunggu',
                        'value' => $poliId ? \App\Models\Kunjungan::where('poli_id', $poliId)
                                                ->where('status', 'menunggu')
                                                ->count() : 0,
                        'icon' => 'stethoscope',
                        'color' => 'blue',
                        'subtext' => 'Di Poli Anda'
                    ],
                    [
                        'label' => 'Selesai Periksa',
                        'value' => 0, // TODO: Will be implemented when rekam medis module is done
                        'icon' => 'clipboard-document-check',
                        'color' => 'green',
                        'subtext' => 'Hari ini'
                    ],
                    [
                        'label' => 'Total Pasien',
                        'value' => $poliId ? \App\Models\Kunjungan::where('poli_id', $poliId)
                                                ->whereDate('tgl_kunjungan', today())
                                                ->count() : 0,
                        'icon' => 'users',
                        'color' => 'yellow',
                        'subtext' => 'Hari ini'
                    ]
                ];

            case 'apoteker':
                return [
                    [
                        'label' => 'Resep Masuk',
                        'value' => 0, // Resep::where('status', 'waiting')->count()
                        'icon' => 'document-text',
                        'color' => 'red', // Merah biar notice
                        'subtext' => 'Perlu disiapkan'
                    ],
                    [
                        'label' => 'Obat Stok Menipis',
                        'value' => 0, // Obat::where('stok', '<', 10)->count()
                        'icon' => 'exclamation-triangle',
                        'color' => 'yellow',
                        'subtext' => 'Stok < 10'
                    ],
                    [
                        'label' => 'Resep Selesai',
                        'value' => 0,
                        'icon' => 'check-badge',
                        'color' => 'green',
                        'subtext' => 'Hari ini'
                    ]
                ];

            default: // kepala_puskesmas & fallback
                return [
                    [
                        'label' => 'Total Kunjungan',
                        'value' => 150, // Placeholder angka yang user suka
                        'icon' => 'chart-bar',
                        'color' => 'blue',
                        'subtext' => '+12% dari kemarin'
                    ],
                    [
                        'label' => 'Pasien BPJS',
                        'value' => 85,
                        'icon' => 'credit-card',
                        'color' => 'green',
                        'subtext' => '56% dari total'
                    ],
                    [
                        'label' => 'Pendapatan',
                        'value' => 'Rp 0',
                        'icon' => 'currency-dollar',
                        'color' => 'purple',
                        'subtext' => 'Estimasi hari ini'
                    ]
                ];
        }
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
