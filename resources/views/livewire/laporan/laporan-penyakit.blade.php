<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan 10 Penyakit Terbanyak') }}
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
                                    Total Diagnosis: <span class="font-bold text-gray-900">{{ $this->totalKasus }}</span>
                                </div>
                            </div>

                            <table class="min-w-full divide-y divide-gray-200 border">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Rank</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diagnosa Penyakit</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Jumlah Kasus</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-40">Persentase</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($dataPenyakit as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold {{ $item['ranking'] <= 3 ? 'text-indigo-600' : 'text-gray-500' }}">
                                                #{{ $item['ranking'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item['diagnosa'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-900">{{ $item['jumlah_kasus'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                <div class="flex items-center justify-center">
                                                    <span class="mr-2 text-gray-700">{{ $item['persentase'] }}%</span>
                                                    <div class="w-16 bg-gray-200 rounded-full h-1.5 dark:bg-gray-200">
                                                        <div class="bg-indigo-600 h-1.5 rounded-full" style="width: {{ $item['persentase'] }}%"></div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-10 text-center text-gray-500 italic">
                                                Tidak ada data penyakit pada periode ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12 text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            <p class="mt-2 text-lg font-medium">Silakan pilih periode tanggal untuk menampilkan laporan.</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
