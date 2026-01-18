<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

    <!-- Page Header -->
    <div class="sm:flex sm:justify-between sm:items-center mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl md:text-3xl text-gray-800 font-bold">Pendaftaran Pasien</h1>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="mb-4 px-4 py-3 bg-green-100 border border-green-200 text-green-700 rounded-lg flex items-center justify-between" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3 fill-current" viewBox="0 0 20 20"><path d="M0 11l2-2 5 5L18 3l2 2L7 18z"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" class="text-green-700 hover:text-green-900" @click="$el.parentElement.remove()">
                <span class="text-2xl">&times;</span>
            </button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 px-4 py-3 bg-red-100 border border-red-200 text-red-700 rounded-lg flex items-center justify-between" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3 fill-current" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/></svg>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" class="text-red-700 hover:text-red-900" @click="$el.parentElement.remove()">
                <span class="text-2xl">&times;</span>
            </button>
        </div>
    @endif

    <!-- Main Content: Split Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Search & Patient List -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Search Box -->
            <div class="bg-white shadow-lg rounded-sm border border-gray-200 p-5">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-semibold text-gray-800">Cari Pasien</h2>
                    <button wire:click="openModalPasienBaru" class="text-sm bg-indigo-500 hover:bg-indigo-600 text-white py-1 px-3 rounded shadow flex items-center">
                        <svg class="w-3 h-3 fill-current opacity-50 shrink-0 mr-1" viewBox="0 0 16 16">
                            <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                        </svg>
                        Pasien Baru
                    </button>
                </div>
                
                <div class="relative w-full">
                    <label for="search" class="sr-only">Search</label>
                    <input wire:model.live.debounce.300ms="search" id="search" class="form-input pl-9 focus:border-indigo-300 w-full rounded-md border-gray-300 shadow-sm" type="text" placeholder="NIK / Nama / No RM..." />
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Search Results -->
            @if(count($searchResults) > 0)
                <div class="bg-white shadow-lg rounded-sm border border-gray-200 overflow-hidden">
                    <ul class="divide-y divide-gray-200">
                        @foreach($searchResults as $pasien)
                            <li class="p-4 hover:bg-gray-50 transition duration-150 ease-in-out">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0 pr-4">
                                        <p class="text-sm font-medium text-indigo-600 truncate">
                                            {{ $pasien->nama }}
                                        </p>
                                        <div class="flex items-center justify-between text-xs text-gray-500 mt-1">
                                            <span>RM: <strong class="text-gray-700">{{ $pasien->no_rm }}</strong></span>
                                            <span class="truncate ml-2">NIK: {{ $pasien->nik }}</span>
                                        </div>
                                        <p class="text-xs text-gray-400 mt-1 truncate">
                                            {{ $pasien->alamat }}
                                        </p>
                                    </div>
                                    <button wire:click="selectPasien({{ $pasien->id }})" class="inline-flex items-center shadow-sm px-2.5 py-0.5 border border-gray-300 text-xs leading-5 font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50">
                                        Pilih
                                    </button>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @elseif(strlen($search) >= 3)
                <div class="bg-white shadow-lg rounded-sm border border-gray-200 p-4 text-center text-gray-500 text-sm">
                    Tidak ditemukan pasien dengan kata kunci tersebut.
                </div>
            @endif

            <!-- Selected Pasien Info Card -->
            @if($selectedPasien)
                <div class="bg-green-50 rounded-sm border border-green-200 p-4 relative">
                    <button wire:click="clearSelectedPasien" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                    <h3 class="text-sm font-semibold text-green-800 mb-2">Pasien Terpilih</h3>
                    <div class="space-y-1 text-sm text-gray-600">
                        <p><span class="font-medium text-gray-800">Nama:</span> {{ $selectedPasien->nama }}</p>
                        <p><span class="font-medium text-gray-800">No RM:</span> {{ $selectedPasien->no_rm }}</p>
                        <p><span class="font-medium text-gray-800">NIK:</span> {{ $selectedPasien->nik }}</p>
                        <p><span class="font-medium text-gray-800">Umur:</span> {{ \Carbon\Carbon::parse($selectedPasien->tgl_lahir)->age }} Tahun</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Form Kunjungan -->
        <div class="lg:col-span-2">
            @if($selectedPasien)
                <div class="bg-white shadow-lg rounded-sm border border-gray-200 p-6">
                    <!-- Header Form -->
                    <div class="border-b border-gray-100 pb-4 mb-6 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800">Form Pendaftaran Kunjungan</h2>
                        <span class="text-sm bg-blue-100 text-blue-800 py-1 px-3 rounded-full font-medium">
                            {{ now()->translatedFormat('l, d F Y') }}
                        </span>
                    </div>

                    @if($kunjunganBerhasil)
                        <!-- Success State -->
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Pendaftaran Berhasil!</h3>
                            <p class="text-gray-500 mb-6">Nomor Antrean Anda:</p>
                            
                            <div class="text-4xl font-mono font-bold text-indigo-600 mb-8 bg-indigo-50 inline-block px-8 py-4 rounded-lg border-2 border-indigo-100 dashed">
                                {{ $noAntrean }}
                            </div>

                            <div class="flex justify-center space-x-4">
                                <button wire:click="resetPendaftaran" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">
                                    Kembali ke Awal
                                </button>
                                <button type="button" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    Cetak Antrean
                                </button>
                            </div>
                        </div>
                    @else
                        <!-- Form Input -->
                        <form wire:submit.prevent="storeKunjungan">
                             <!-- Patient Summary Card (Highlight) -->
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 mb-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase">No RM</p>
                                    <p class="font-bold text-gray-800">{{ $selectedPasien->no_rm }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase">Nama Pasien</p>
                                    <p class="font-bold text-gray-800">{{ $selectedPasien->nama }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase">Jenis</p>
                                    <p class="font-bold text-gray-800">{{ $selectedPasien->no_bpjs ? 'BPJS' : 'Umum' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase">Umur</p>
                                    <p class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($selectedPasien->tgl_lahir)->age }} Th</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Pilih Poli -->
                                <div>
                                    <label for="poli_id" class="block mb-2 text-sm font-medium text-gray-900">Poli Tujuan <span class="text-red-500">*</span></label>
                                    <select wire:model="poli_id" id="poli_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                        <option value="">-- Pilih Poli --</option>
                                        @foreach($poliList as $poli)
                                            <option value="{{ $poli->id }}">{{ $poli->nama_poli }}</option>
                                        @endforeach
                                    </select>
                                    @error('poli_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <!-- Metode Bayar -->
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-900">Metode Pembayaran <span class="text-red-500">*</span></label>
                                    <div class="flex space-x-4 mt-3">
                                        <div class="flex items-center">
                                            <input wire:model.live="metode_bayar" id="bayar-umum" type="radio" value="Umum" name="metode_bayar" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                            <label for="bayar-umum" class="ml-2 text-sm font-medium text-gray-900">Umum / Tunai</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input wire:model.live="metode_bayar" id="bayar-bpjs" type="radio" value="BPJS" name="metode_bayar" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                            <label for="bayar-bpjs" class="ml-2 text-sm font-medium text-gray-900">BPJS Kesehatan</label>
                                        </div>
                                    </div>
                                    @error('metode_bayar') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    
                                    @if($metode_bayar === 'BPJS' && empty($selectedPasien->no_bpjs))
                                        <div class="mt-2 p-2 bg-red-50 text-red-700 text-xs rounded border border-red-200">
                                            ⚠️ Pasien ini tidak memiliki No BPJS. Harap update data pasien atau gunakan metode Umum.
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Keluhan Awal -->
                            <div class="mt-6">
                                <label for="keluhan_awal" class="block mb-2 text-sm font-medium text-gray-900">Keluhan Awal <span class="text-red-500">*</span></label>
                                <textarea wire:model="keluhan_awal" id="keluhan_awal" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Jelaskan keluhan utama pasien secara singkat..."></textarea>
                                @error('keluhan_awal') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="mt-8 flex justify-end">
                                <button type="submit" wire:loading.attr="disabled" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 focus:outline-none flex items-center">
                                    <span wire:loading.remove wire:target="storeKunjungan">Daftarkan Kunjungan</span>
                                    <span wire:loading wire:target="storeKunjungan">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Memproses...
                                    </span>
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-lg p-8 h-full flex flex-col items-center justify-center text-center text-gray-400">
                     <svg class="w-16 h-16 mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    <h3 class="text-xl font-medium text-gray-500">Siap Mendaftar</h3>
                    <p class="mt-2 max-w-sm mx-auto">
                        Cari pasien di sebelah kiri untuk menampilkan form pendaftaran kunjungan.
                    </p>
                </div>
            @endif
        </div>

    </div>

    <!-- Modal Form Pasien Baru -->
    <div
        x-data="{ show: @entangle('showModalPasienBaru') }"
        x-show="show"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto outline-none focus:outline-none"
    >
        <!-- Overlay -->
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" @click="show = false"></div>

        <!-- Modal Content -->
        <div class="relative w-full max-w-lg mx-auto my-6 bg-white rounded-lg shadow-xl outline-none focus:outline-none transform transition-all">
            <!-- Header -->
            <div class="flex items-start justify-between p-5 border-b border-solid border-slate-200 rounded-t">
                <h3 class="text-xl font-semibold text-gray-800">
                    Registrasi Pasien Baru
                </h3>
                <button class="p-1 ml-auto bg-transparent border-0 text-gray-500 hover:text-gray-800 float-right text-3xl leading-none font-semibold outline-none focus:outline-none" @click="show = false">
                    <span class="bg-transparent text-gray-500 hover:text-gray-800 h-6 w-6 text-2xl block outline-none focus:outline-none">
                        ×
                    </span>
                </button>
            </div>

            <!-- Body -->
            <div class="relative p-6 flex-auto">
                <form wire:submit.prevent="storePasienBaru">
                    <div class="grid gap-6">
                        <!-- NIK -->
                        <div>
                            <label for="nik" class="block mb-2 text-sm font-medium text-gray-900">NIK <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="nik" id="nik" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="16 Digit NIK" maxlength="16">
                            @error('nik') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Nama -->
                        <div>
                            <label for="nama" class="block mb-2 text-sm font-medium text-gray-900">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="nama" id="nama" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Nama sesuai KTP">
                            @error('nama') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Tgl Lahir -->
                        <div>
                            <label for="tgl_lahir" class="block mb-2 text-sm font-medium text-gray-900">Tanggal Lahir <span class="text-red-500">*</span></label>
                            <input type="date" wire:model="tgl_lahir" id="tgl_lahir" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            @error('tgl_lahir') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Alamat -->
                        <div>
                            <label for="alamat" class="block mb-2 text-sm font-medium text-gray-900">Alamat Lengkap <span class="text-red-500">*</span></label>
                            <textarea wire:model="alamat" id="alamat" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Alamat Domisili"></textarea>
                            @error('alamat') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- No BPJS -->
                        <div>
                            <label for="no_bpjs" class="block mb-2 text-sm font-medium text-gray-900">No. BPJS (Opsional)</label>
                            <input type="text" wire:model="no_bpjs" id="no_bpjs" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="13 Digit No BPJS (Jika ada)">
                            @error('no_bpjs') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-end p-6 border-t border-solid border-slate-200 rounded-b">
                <button 
                    class="text-gray-500 background-transparent font-bold uppercase px-6 py-2 text-sm outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150 hover:text-gray-700" 
                    type="button" 
                    wire:click="closeModalPasienBaru"
                >
                    Batal
                </button>
                <button 
                    class="bg-blue-600 text-white active:bg-blue-700 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" 
                    type="button" 
                    wire:click="storePasienBaru"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove wire:target="storePasienBaru">Simpan Data</span>
                    <span wire:loading wire:target="storePasienBaru">Menyimpan...</span>
                </button>
            </div>
        </div>
    </div>

</div>
