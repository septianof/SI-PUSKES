<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Kunjungan Poli') }}
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
                                {{-- Placeholder for Export Button --}}
                                {{-- <button class="px-3 py-1 bg-green-600 text-white rounded text-sm">Export PDF</button> --}}
                            </div>

                            <table class="min-w-full divide-y divide-gray-200 border">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Poli</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total Kunjungan</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien Umum</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien BPJS</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($dataKunjungan as $index => $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->iteration }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item['nama_poli'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-bold text-indigo-600">{{ $item['total_kunjungan'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">{{ $item['pasien_umum'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">{{ $item['pasien_bpjs'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-10 text-center text-gray-500 italic">
                                                Tidak ada data kunjungan pada periode ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if(!empty($dataKunjungan))
                                    <tfoot class="bg-gray-100 font-semibold">
                                        <tr>
                                            <td colspan="2" class="px-6 py-3 text-right text-sm text-gray-900">Total Keseluruhan:</td>
                                            <td class="px-6 py-3 text-center text-sm text-indigo-700">{{ $this->totalSummary['total_kunjungan'] }}</td>
                                            <td class="px-6 py-3 text-center text-sm text-gray-900">{{ $this->totalSummary['total_umum'] }}</td>
                                            <td class="px-6 py-3 text-center text-sm text-gray-900">{{ $this->totalSummary['total_bpjs'] }}</td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12 text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="mt-2 text-lg font-medium">Silakan pilih periode tanggal untuk menampilkan laporan.</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
