<div class="space-y-6">
    {{-- Header Section --}}
    <div class="flex justify-between items-center bg-white p-4 rounded-lg shadow-sm border border-gray-100">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Antrean Poli {{ $poli->nama_poli ?? '-' }}</h1>
            <p class="text-gray-500 text-sm mt-1">Daftar pasien menunggu pemeriksaan</p>
        </div>
        <div class="flex items-center space-x-3">
            <button wire:click="refreshAntrean" class="flex items-center space-x-2 text-gray-600 hover:text-blue-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span>Refresh Data</span>
            </button>
        </div>
    </div>

    {{-- Stats Cards Removed as requested --}}


    {{-- Queue Table --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-500 tracking-wider">
                        <th class="px-6 py-4 font-semibold">No. RM</th>
                        <th class="px-6 py-4 font-semibold">Tgl & Jam</th>
                        <th class="px-6 py-4 font-semibold">Nama Pasien</th>
                        <th class="px-6 py-4 font-semibold">Keluhan Awal</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($kunjungans as $kunjungan)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-mono text-sm font-medium text-blue-600 bg-blue-50 px-2 py-1 rounded">
                                    {{ $kunjungan->pasien->no_rm }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $kunjungan->tgl_kunjungan->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $kunjungan->tgl_kunjungan->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $kunjungan->pasien->nama }}</div>
                                <div class="text-xs text-gray-500">{{ $kunjungan->pasien->nik }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-700 max-w-xs truncate" title="{{ $kunjungan->keluhan_awal }}">
                                    {{ $kunjungan->keluhan_awal ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    {{ ucfirst($kunjungan->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button 
                                    wire:click="periksaPasien({{ $kunjungan->id }})"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                    Periksa
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-base font-medium">Tidak ada antrean saat ini</p>
                                    <p class="text-sm text-gray-400">Silakan refresh halaman secara berkala</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(count($kunjungans) > 0)
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                <span class="text-xs text-gray-500">Menampilkan {{ count($kunjungans) }} pasien menunggu</span>
            </div>
        @endif
    </div>
    
    {{-- Flash Message Helper (if needed) --}}
    @if (session()->has('info'))
        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded shadow-sm" role="alert">
            <p class="font-bold">Info</p>
            <p>{{ session('info') }}</p>
        </div>
    @endif
</div>
