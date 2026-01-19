<div class="space-y-6">
    {{-- Header Button Back --}}
    <div>
        <a href="{{ route('dokter.antrean') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Antrean
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Left Column: Patient Info & History --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Patient Info Card --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Data Pasien</h3>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Nama Lengkap</p>
                        <p class="font-semibold text-gray-800">{{ $pasien->nama }}</p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase">No. RM</p>
                            <p class="font-mono text-sm font-medium text-blue-600">{{ $pasien->no_rm }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">NIK</p>
                            <p class="text-sm text-gray-700">{{ $pasien->nik }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Tgl Lahir / Umur</p>
                            <p class="text-sm text-gray-700">
                                {{ \Carbon\Carbon::parse($pasien->tgl_lahir)->format('d-m-Y') }} 
                                <span class="text-gray-400">({{ $this->umur }})</span>
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Jaminan</p>
                            @if($pasien->no_bpjs)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                    BPJS
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                    UMUM
                                </span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 uppercase">Alamat</p>
                        <p class="text-sm text-gray-700">{{ $pasien->alamat }}</p>
                    </div>
                </div>
            </div>

            {{-- Visit History Card --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Riwayat Kunjungan</h3>
                
                @if(count($riwayatKunjungan) > 0)
                    <div class="space-y-4">
                        @foreach($riwayatKunjungan as $riwayat)
                            <div class="border-l-2 border-blue-200 pl-3 pb-3 relative">
                                <div class="absolute -left-[5px] top-0 w-2 h-2 rounded-full bg-blue-400"></div>
                                <p class="text-xs text-gray-500 mb-1">{{ $riwayat->tgl_kunjungan->format('d M Y') }} - {{ $riwayat->poli->nama_poli }}</p>
                                <p class="text-sm font-medium text-gray-800">{{ $riwayat->rekamMedis->diagnosa ?? '-' }}</p>
                                <p class="text-xs text-gray-600 mt-1 line-clamp-2">"{{ $riwayat->rekamMedis->keluhan ?? '-' }}"</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 italic">Belum ada riwayat kunjungan.</p>
                @endif
            </div>
        </div>

        {{-- Right Column: Examination Form --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <div class="flex justify-between items-center border-b pb-4 mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Pemeriksaan Medis</h2>
                        <p class="text-sm text-gray-500">Isi rekam medis pasien dengan lengkap.</p>
                    </div>
                    <div class="text-right">
                        <span class="block text-xs text-gray-500">Tanggal Periksa</span>
                        <span class="block text-sm font-medium text-gray-800">{{ now()->format('d F Y') }}</span>
                    </div>
                </div>

                {{-- Alert Success --}}
                @if (session()->has('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                        <p class="font-bold">Sukses!</p>
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                <form wire:submit.prevent="simpanRekamMedis" class="space-y-6">
                    {{-- 1. Tanda Vital --}}
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 uppercase mb-3 flex items-center">
                            <span class="bg-blue-100 text-blue-600 p-1 rounded mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </span>
                            1. Tanda Vital
                        </h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-lg">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Tensi (mmHg)</label>
                                <input wire:model="tensi" type="text" placeholder="120/80" 
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm">
                                @error('tensi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Suhu (°C)</label>
                                <input wire:model="suhu" type="number" step="0.1" placeholder="36.5" 
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm">
                                @error('suhu') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Berat (kg)</label>
                                <input wire:model="bb" type="number" step="0.1" placeholder="60" 
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm">
                                @error('bb') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Tinggi (cm)</label>
                                <input wire:model="tb" type="number" step="1" placeholder="170" 
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm">
                                @error('tb') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- 2. Anamnesa & Diagnosa --}}
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 uppercase mb-3 flex items-center">
                            <span class="bg-blue-100 text-blue-600 p-1 rounded mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </span>
                            2. Pemeriksaan & Diagnosa
                        </h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Anamnesa / Keluhan</label>
                                <textarea wire:model="keluhan" rows="3" 
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm"
                                    placeholder="Deskripsikan keluhan pasien..."></textarea>
                                @error('keluhan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Diagnosa (ICD-10)</label>
                                <div class="relative">
                                    <input wire:model="diagnosa" type="text" 
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm pl-10"
                                        placeholder="Contoh: A01.0 - Typhoid fever">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                </div>
                                @error('diagnosa') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tindakan / Catatan Tambahan</label>
                                <textarea wire:model="tindakan" rows="2" 
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm"
                                    placeholder="Tindakan yang dilakukan..."></textarea>
                                @error('tindakan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- 3. Resep Obat --}}
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 uppercase mb-3 flex items-center">
                            <span class="bg-blue-100 text-blue-600 p-1 rounded mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                </svg>
                            </span>
                            3. Resep Obat
                        </h4>
                        
                        <div class="bg-gray-50 p-4 rounded-lg space-y-4">
                            {{-- Form Tambah Obat --}}
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                                <div class="md:col-span-5">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Nama Obat</label>
                                    <select wire:model="selectedObat" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm">
                                        <option value="">-- Pilih Obat --</option>
                                        @foreach($obatOptions as $obat)
                                            <option value="{{ $obat['id'] }}">{{ $obat['nama_obat'] }} (Stok: {{ $obat['stok'] }} {{ strtoupper($obat['jenis']) }})</option>
                                        @endforeach
                                    </select>
                                    @error('selectedObat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Jml</label>
                                    <input wire:model="jumlah" type="number" min="1" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm">
                                    @error('jumlah') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Dosis (ex: 3x1)</label>
                                    <input wire:model="dosis" type="text" placeholder="3x1 Sesudah makan" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm">
                                    @error('dosis') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="md:col-span-2">
                                    <button wire:click.prevent="addObat" type="button" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Tambah
                                    </button>
                                </div>
                            </div>

                            @if (session()->has('obat_added'))
                                <div class="text-green-600 text-xs italic">{{ session('obat_added') }}</div>
                            @endif

                            {{-- Tabel Resep Sementara --}}
                            @if(count($resepList) > 0)
                                <div class="mt-4 border rounded-md overflow-hidden bg-white">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Obat</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jml</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosis</th>
                                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($resepList as $index => $item)
                                                <tr>
                                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $item['nama_obat'] }}</td>
                                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $item['jumlah'] }}</td>
                                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $item['dosis'] }}</td>
                                                    <td class="px-4 py-2 text-right text-sm font-medium">
                                                        <button wire:click.prevent="removeObat({{ $index }})" type="button" class="text-red-600 hover:text-red-900">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4 bg-white border border-dashed border-gray-300 rounded-md">
                                    <p class="text-sm text-gray-500">Belum ada obat dalam resep.</p>
                                    <p class="text-xs text-gray-400 mt-1">Jika tidak ada obat, pasien akan diarahkan langsung ke pembayaran.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex justify-end pt-4 border-t border-gray-100">
                        <a href="{{ route('dokter.antrean') }}" class="mr-3 flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Batal
                        </a>
                        <button wire:click.prevent="savePemeriksaan" type="button" class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 flex items-center">
                            <svg wire:loading.remove wire:target="savePemeriksaan" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <svg wire:loading wire:target="savePemeriksaan" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Selesaikan Pemeriksaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
