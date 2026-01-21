<div class="max-w-xl mx-auto mt-10">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Ganti Password</h2>

            {{-- Flash Messages --}}
            @if (session()->has('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <form wire:submit="simpanPassword">
                {{-- Password Lama --}}
                <div class="mb-4">
                    <label for="password_lama" class="block text-gray-700 text-sm font-bold mb-2">Password Lama</label>
                    <input wire:model="password_lama" type="password" id="password_lama" 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password_lama') border-red-500 @enderror"
                        placeholder="Masukkan password lama Anda">
                    @error('password_lama') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>

                {{-- Password Baru --}}
                <div class="mb-4">
                    <label for="password_baru" class="block text-gray-700 text-sm font-bold mb-2">Password Baru</label>
                    <input wire:model="password_baru" type="password" id="password_baru" 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password_baru') border-red-500 @enderror"
                        placeholder="Minimal 6 karakter">
                    @error('password_baru') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div class="mb-6">
                    <label for="password_konfirmasi" class="block text-gray-700 text-sm font-bold mb-2">Konfirmasi Password Baru</label>
                    <input wire:model="password_konfirmasi" type="password" id="password_konfirmasi" 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password_konfirmasi') border-red-500 @enderror"
                        placeholder="Ulangi password baru">
                    @error('password_konfirmasi') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-end">
                    <a href="{{ route('dashboard') }}" class="inline-block align-baseline font-bold text-sm text-gray-500 hover:text-gray-800 mr-4">
                        Batal
                    </a>
                    <button type="submit" wire:loading.attr="disabled" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline disabled:opacity-50">
                        <span wire:loading.remove>Simpan Password</span>
                        <span wire:loading>Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
