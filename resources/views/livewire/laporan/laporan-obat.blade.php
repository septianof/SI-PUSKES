<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Pemakaian Obat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Filter Section --}}
                    <div class="mb-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Filter Periode</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div>
                                <label for="tglMulai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                                <input type="date" wire:model="tglMulai" id="tglMulai" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('tglMulai') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="tglAkhir" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                                <input type="date" wire:model="tglAkhir" id="tglAkhir" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('tglAkhir') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="flex gap-2">
                                <button wire:click="generateLaporan" wire:loading.attr="disabled" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50">
                                    <span wire:loading.remove wire:target="generateLaporan">Tampilkan Laporan</span>
                                    <span wire:loading wire:target="generateLaporan">Memuat...</span>
                                </button>
                                <button wire:click="resetForm" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                                    Reset
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Data Table --}}
                    @if($showResults)
                        <div class="overflow-x-auto">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-bold text-gray-800">
                                    Periode: {{ \Carbon\Carbon::parse($tglMulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($tglAkhir)->format('d M Y') }}
                                </h3>
                                <div class="text-sm text-gray-500">
                                    Total Penggunaan: <span class="font-bold text-gray-900">{{ $this->totalSummary['total_digunakan'] }} unit</span>
                                </div>
                            </div>
                            
                            {{-- Warning Low Stock --}}
                            @php $lowStockCount = collect($dataObat)->where('is_low_stock', true)->count(); @endphp
                            @if($lowStockCount > 0)
                                <div class="mb-4 p-4 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 text-sm">
                                    <span class="font-bold">Perhatian:</span> Terdapat {{ $lowStockCount }} jenis obat dengan stok menipis (di bawah 10 unit).
                                </div>
                            @endif

                            <table class="min-w-full divide-y divide-gray-200 border">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Obat</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total Digunakan</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Frekuensi Resep</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Tersisa</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($dataObat as $item)
                                        <tr class="{{ $item['is_low_stock'] ? 'bg-red-50' : '' }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->iteration }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item['nama_obat'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item['jenis'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-bold text-indigo-600">{{ $item['total_digunakan'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">{{ $item['frekuensi_resep'] }}x</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                @if($item['is_low_stock'])
                                                    <span class="px-2 py-1 text-xs font-bold text-red-700 bg-red-100 rounded-full">
                                                        {{ $item['stok_tersisa'] }} (Menipis)
                                                    </span>
                                                @else
                                                    <span class="text-gray-900">{{ $item['stok_tersisa'] }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-10 text-center text-gray-500 italic">
                                                Tidak ada data pemakaian obat pada periode ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12 text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                            </svg>
                            <p class="mt-2 text-lg font-medium">Silakan pilih periode tanggal untuk menampilkan laporan.</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
