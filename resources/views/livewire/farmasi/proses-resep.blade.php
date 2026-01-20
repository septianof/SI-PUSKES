<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Proses Resep') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    
                    {{-- Header Info Pasien --}}
                    <div class="mb-6 bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Data Pasien</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">No. RM:</span>
                                <span class="font-medium ml-2">{{ $kunjungan->pasien->no_rm }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Nama:</span>
                                <span class="font-medium ml-2">{{ $kunjungan->pasien->nama }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Poli:</span>
                                <span class="font-medium ml-2">{{ $kunjungan->poli->nama_poli }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Tanggal Kunjungan:</span>
                                <span class="font-medium ml-2">{{ $kunjungan->tgl_kunjungan->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Detail Resep --}}
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Detail Resep</h3>
                        
                        <table class="min-w-full divide-y divide-gray-200 border rounded-lg overflow-hidden">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-10">No</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Obat</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dosis</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Jml Minta</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Stok</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($detailReseps as $index => $detail)
                                    @php
                                        $status = $this->statusStok[$detail->id] ?? 'unknown';
                                        $bgColor = match($status) {
                                            'cukup' => 'bg-green-100 text-green-800',
                                            'kurang' => 'bg-yellow-100 text-yellow-800',
                                            'habis' => 'bg-red-100 text-red-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                        $statusLabel = match($status) {
                                            'cukup' => 'Tersedia',
                                            'kurang' => 'Kurang',
                                            'habis' => 'Habis',
                                            default => 'Unknown'
                                        };
                                    @endphp
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $detail->obat->nama_obat }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $detail->dosis }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 text-center font-semibold">{{ $detail->jumlah }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500 text-center">{{ $detail->obat->stok }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $bgColor }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Warning jika stok kurang --}}
                    @if($this->adaStokKurang)
                        <div class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-400">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-bold text-yellow-800">Perhatian: Stok Tidak Mencukupi</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p class="mb-2">Beberapa obat memiliki stok yang tidak mencukupi. Ketika Anda memproses resep ini:</p>
                                        <ul class="list-disc list-inside space-y-1 ml-2">
                                            <li><strong>Stok Kurang (kuning):</strong> Sistem akan menyerahkan sebanyak stok yang tersedia dan mencatat di resep</li>
                                            <li><strong>Stok Habis (merah):</strong> Obat tidak dapat diserahkan, akan dicatat di resep</li>
                                        </ul>
                                        <p class="mt-2 text-xs italic">Catatan otomatis akan disimpan di database resep untuk koordinasi dengan dokter/pasien.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Action Button --}}
                    <div class="mt-6 border-t pt-6 flex justify-between items-center" x-data="{ isProcessing: false }">
                        <a 
                            href="{{ route('farmasi') }}" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            :class="{ 'opacity-50 pointer-events-none': isProcessing }"
                        >
                            Kembali
                        </a>

                        <button 
                            wire:click="finalisasiResep"
                            @click="isProcessing = true"
                            class="inline-flex items-center px-6 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed {{ $this->adaStokKurang ? 'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500' : 'bg-green-600 hover:bg-green-700 focus:ring-green-500' }}"
                            :disabled="isProcessing"
                        >
                            {{-- Spinner --}}
                            <svg x-show="isProcessing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="display: none;">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>

                            @if($this->allStokCukup)
                                <span x-text="isProcessing ? 'Memproses...' : 'Serahkan Obat & Selesai'"></span>
                            @else
                                <span x-text="isProcessing ? 'Memproses...' : 'Tandai Tidak Lengkap & Selesai'"></span>
                            @endif
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
