<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <h2 class="text-2xl font-bold text-gray-800">
            Riwayat Rekam Medis
        </h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-[calc(100vh-12rem)]">
        
        {{-- Panel Kiri: Pencarian & List Pasien --}}
        <div class="lg:col-span-1 bg-white rounded-xl shadow-sm border border-gray-200 flex flex-col h-full overflow-hidden">
            {{-- Search Header --}}
            <div class="p-4 border-b border-gray-100 bg-gray-50">
                <label class="block text-xs font-medium text-gray-500 uppercase mb-2">Cari Pasien</label>
                <div class="relative">
                    <input 
                        wire:model.live.debounce.300ms="searchQuery" 
                        type="search" 
                        placeholder="Nama atau No. RM..." 
                        class="w-full pl-10 pr-4 py-2 border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- List Results --}}
            <div class="flex-1 overflow-y-auto p-2 space-y-2">
                @forelse($patientResults as $pasien)
                    <button 
                        wire:click="selectPasien({{ $pasien->id }})" 
                        class="w-full text-left p-3 rounded-lg transition-colors duration-150 group {{ $selectedPasien && $selectedPasien->id === $pasien->id ? 'bg-blue-50 border-blue-200 border' : 'hover:bg-gray-50 border border-transparent' }}"
                    >
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-semibold text-sm {{ $selectedPasien && $selectedPasien->id === $pasien->id ? 'text-blue-700' : 'text-gray-800' }}">
                                    {{ $pasien->nama }}
                                </h4>
                                <p class="text-xs text-gray-500 mt-1">RM: {{ $pasien->no_rm }}</p>
                            </div>
                            <span class="text-xs text-gray-400">
                                {{ \Carbon\Carbon::parse($pasien->tgl_lahir)->age }} th
                            </span>
                        </div>
                    </button>
                @empty
                    <div class="text-center py-8 text-gray-400 text-sm px-4">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <p class="font-medium text-gray-600">Belum Ada Pasien</p>
                        <p class="text-xs mt-1">Belum ada pasien yang pernah berobat di poli Anda.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Panel Kanan: Detail Riwayat --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 h-full overflow-hidden flex flex-col">
            @if($selectedPasien)
                {{-- Detail Pasien Header --}}
                <div class="p-6 border-b border-gray-100 bg-blue-50">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">{{ $selectedPasien->nama }}</h3>
                            <div class="flex items-center text-sm text-gray-600 mt-2 space-x-4">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    {{ $selectedPasien->no_rm }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    {{ \Carbon\Carbon::parse($selectedPasien->tgl_lahir)->isoFormat('D MMMM Y') }} ({{ $this->umur }})
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    {{ Str::limit($selectedPasien->alamat, 40) }}
                                </span>
                            </div>
                        </div>
                        <div class="bg-white px-3 py-1 rounded-full text-xs font-semibold text-blue-600 border border-blue-200">
                            {{ ucfirst($selectedPasien->jenis_kelamin) }}
                        </div>
                    </div>
                </div>

                {{-- Timeline Content --}}
                <div class="flex-1 overflow-y-auto p-6 bg-gray-50">
                    <div class="max-w-3xl mx-auto">
                        @forelse($rekamMedisList as $rm)
                            <div class="relative pl-8 pb-8 last:pb-0">
                                {{-- Timeline Line --}}
                                <div class="absolute left-0 top-0 bottom-0 w-px bg-gray-300"></div>
                                {{-- Timeline Dot --}}
                                <div class="absolute left-[-4px] top-2 w-2.5 h-2.5 rounded-full bg-blue-500 ring-4 ring-blue-100"></div>

                                {{-- Date Header --}}
                                <div class="mb-2 flex items-center text-sm font-medium text-gray-500">
                                    <span>{{ \Carbon\Carbon::parse($rm->tgl_periksa)->isoFormat('dddd, D MMMM Y - HH:mm') }}</span>
                                    <span class="mx-2">•</span>
                                    <span class="text-blue-600">{{ $rm->kunjungan->poli->nama_poli ?? '-' }}</span>
                                    <span class="mx-2">•</span>
                                    <span class="text-gray-900 font-semibold">{{ $rm->dokter->name ?? 'Dokter' }}</span>
                                </div>

                                {{-- Record Card --}}
                                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                                    {{-- Tanda Vital Grid --}}
                                    @php $vital = $this->parseTandaVital($rm->tanda_vital); @endphp
                                    @if($vital)
                                        <div class="grid grid-cols-4 gap-4 p-4 bg-gray-50 border-b border-gray-100 text-center">
                                            <div>
                                                <div class="text-xs text-gray-500 uppercase">Tensi</div>
                                                <div class="font-semibold text-gray-900">{{ $vital['tensi'] ?? '-' }}</div>
                                            </div>
                                            <div>
                                                <div class="text-xs text-gray-500 uppercase">BB</div>
                                                <div class="font-semibold text-gray-900">{{ $vital['bb'] ?? '-' }} kg</div>
                                            </div>
                                            <div>
                                                <div class="text-xs text-gray-500 uppercase">TB</div>
                                                <div class="font-semibold text-gray-900">{{ $vital['tb'] ?? '-' }} cm</div>
                                            </div>
                                            <div>
                                                <div class="text-xs text-gray-500 uppercase">Suhu</div>
                                                <div class="font-semibold text-gray-900">{{ $vital['suhu'] ?? '-' }}°C</div>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Diagnosa & Keluhan --}}
                                    <div class="p-4 space-y-4">
                                        <div>
                                            <h5 class="text-xs font-bold text-gray-500 uppercase mb-1">Diagnosa</h5>
                                            <p class="text-gray-900 font-medium">{{ $rm->diagnosa }}</p>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <h5 class="text-xs font-bold text-gray-500 uppercase mb-1">Keluhan / Anamnesa</h5>
                                                <p class="text-sm text-gray-700 leading-relaxed">{{ $rm->keluhan }}</p>
                                            </div>
                                            @if($rm->tindakan)
                                            <div>
                                                <h5 class="text-xs font-bold text-gray-500 uppercase mb-1">Tindakan</h5>
                                                <p class="text-sm text-gray-700 leading-relaxed">{{ $rm->tindakan }}</p>
                                            </div>
                                            @endif
                                        </div>

                                        {{-- Resep Obat --}}
                                        @if($rm->resep && $rm->resep->detailReseps->count() > 0)
                                            <div class="mt-4 pt-4 border-t border-dashed border-gray-200">
                                                <h5 class="text-xs font-bold text-gray-500 uppercase mb-2 flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                                                    Resep Obat
                                                </h5>
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($rm->resep->detailReseps as $detail)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                            {{ $detail->obat->nama_obat }} ({{ $detail->jumlah }}) - {{ $detail->dosis }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center py-12 text-center">
                                <div class="bg-gray-100 p-4 rounded-full mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900">Belum Ada Riwayat</h3>
                                <p class="text-sm text-gray-500 mt-1">Pasien ini belum memiliki catatan rekam medis.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            @else
                {{-- Empty State (No Patient Selected) --}}
                <div class="flex-1 flex flex-col items-center justify-center p-8 text-center bg-gray-50">
                    <div class="w-32 h-32 bg-blue-50 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-16 h-16 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Pilih Pasien</h3>
                    <p class="text-gray-500 max-w-sm">
                        Cari nama atau nomor rekam medis pasien di panel kiri untuk melihat riwayat kesehatan lengkap mereka.
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
