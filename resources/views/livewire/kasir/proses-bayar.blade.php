<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Proses Pembayaran') }}
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

                    {{-- Invoice Rincian Biaya --}}
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Rincian Biaya</h3>
                        
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Biaya</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                {{-- Biaya Pendaftaran --}}
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        Biaya Pendaftaran/Tindakan ({{ $kunjungan->poli->nama_poli }})
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900 text-right">
                                        Rp {{ number_format($this->biayaPendaftaran, 0, ',', '.') }}
                                    </td>
                                </tr>

                                {{-- Biaya Obat (jika ada) --}}
                                @if($this->detailObat->isNotEmpty())
                                    @foreach($this->detailObat as $item)
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-gray-600">
                                                • {{ $item['nama_obat'] }} ({{ $item['jumlah'] }}x @ Rp {{ number_format($item['harga_satuan'], 0, ',', '.') }})
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-600 text-right">
                                                Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-500 italic">
                                            • Tidak ada resep obat
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500 text-right">
                                            Rp 0
                                        </td>
                                    </tr>
                                @endif

                                {{-- Grand Total --}}
                                <tr class="bg-gray-50 font-semibold">
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        TOTAL PEMBAYARAN
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900 text-right">
                                        Rp {{ number_format($this->grandTotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Form Pembayaran --}}
                    <div class="mt-6 border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Pembayaran Tunai</h3>
                        
                        <form wire:submit.prevent="finalisasiBayar">
                            <div class="mb-4">
                                <label for="bayarTunai" class="block text-sm font-medium text-gray-700 mb-2">
                                    Jumlah Uang Dibayar
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                    </div>
                                    <input 
                                        type="number" 
                                        wire:model.live="bayarTunai"
                                        id="bayarTunai"
                                        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-12 pr-12 sm:text-sm border-gray-300 rounded-md"
                                        placeholder="0"
                                        min="0"
                                        step="1000"
                                    >
                                </div>
                                @error('bayarTunai') 
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Display Kembalian --}}
                            @if($bayarTunai > 0)
                                <div class="mb-4 p-4 bg-blue-50 rounded-lg">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-700">Kembalian:</span>
                                        <span class="text-lg font-bold text-blue-600">
                                            Rp {{ number_format($this->kembalian, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            @endif

                            {{-- Action Buttons --}}
                            <div class="flex justify-between items-center">
                                <a 
                                    href="{{ route('kasir') }}" 
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    Batal
                                </a>
                                <button 
                                    type="submit"
                                    class="inline-flex items-center px-6 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    Bayar & Cetak
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
