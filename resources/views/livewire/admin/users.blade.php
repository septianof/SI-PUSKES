<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

    <!-- Page Header -->
    <div class="sm:flex sm:justify-between sm:items-center mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl md:text-3xl text-gray-800 font-bold">Manajemen User</h1>
        </div>
        <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
            <!-- Add User Button -->
            <button wire:click="create" class="btn bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded shadow flex items-center">
                <svg class="w-4 h-4 fill-current opacity-50 shrink-0 mr-2" viewBox="0 0 16 16">
                    <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                </svg>
                <span>Tambah User</span>
            </button>
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

    <!-- Table & Filters Actions -->
    <div class="bg-white shadow-lg rounded-sm border border-gray-200">
        <header class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
            <h2 class="font-semibold text-gray-800">Daftar User <span class="text-gray-400 font-medium">({{ $users->total() }})</span></h2>
            
            <!-- Search -->
            <div class="relative max-w-xs w-full">
                <label for="action-search" class="sr-only">Search</label>
                <input wire:model.live.debounce.300ms="search" id="action-search" class="form-input pl-9 focus:border-indigo-300 w-full rounded-md border-gray-300 shadow-sm" type="text" placeholder="Cari nama, username, role..." />
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
        </header>

        <div class="p-3">
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead class="text-xs font-semibold uppercase text-gray-500 bg-gray-50 border-t border-b border-gray-200">
                        <tr>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap text-left w-12">
                                <div class="font-semibold text-left">No</div>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap text-left">
                                <div class="font-semibold text-left">Nama Lengkap</div>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap text-left">
                                <div class="font-semibold text-left">Username</div>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap text-left">
                                <div class="font-semibold text-left">Role</div>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap text-center">
                                <div class="font-semibold">Aksi</div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($users as $index => $user)
                        <tr>
                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <div class="text-left">{{ $users->firstItem() + $index }}</div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <div class="font-medium text-gray-800">{{ $user->nama_lengkap }}</div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <div class="text-left text-gray-600">{{ $user->username }}</div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <div class="text-left">
                                    @php
                                        $colors = [
                                            'admin' => 'bg-red-100 text-red-600',
                                            'pendaftaran' => 'bg-green-100 text-green-600',
                                            'dokter' => 'bg-blue-100 text-blue-600',
                                            'apoteker' => 'bg-yellow-100 text-yellow-600',
                                            'kepala Puskesmas' => 'bg-purple-100 text-purple-600',
                                        ];
                                        $colorClass = $colors[$user->role] ?? 'bg-gray-100 text-gray-600';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize {{ $colorClass }}">
                                        {{ $user->role }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                <div class="space-x-1 text-center">
                                    <button wire:click="edit({{ $user->id }})" class="text-slate-400 hover:text-slate-500 rounded-full">
                                        <span class="sr-only">Edit</span>
                                        <svg class="w-8 h-8 fill-current" viewBox="0 0 32 32">
                                            <path d="M19.7 8.3c-.4-.4-1-.4-1.4 0l-10 10c-.2.2-.3.4-.3.7v4c0 .6.4 1 1 1h4c.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4l-4-4zM12.6 22H10v-2.6l6-6 2.6 2.6-6 6zm7.4-7.4L17.4 12l1.6-1.6 2.6 2.6-1.6 1.6z" />
                                        </svg>
                                    </button>
                                    
                                    <button 
                                        wire:click="delete({{ $user->id }})" 
                                        wire:confirm="Apakah Anda yakin ingin menghapus user '{{ $user->nama_lengkap }}'? Tindakan ini tidak dapat dibatalkan."
                                        class="text-rose-500 hover:text-rose-600 rounded-full"
                                    >
                                        <span class="sr-only">Delete</span>
                                        <svg class="w-8 h-8 fill-current" viewBox="0 0 32 32">
                                            <path d="M13 15h2v6h-2zM17 15h2v6h-2z" />
                                            <path d="M20 9c0-.6-.4-1-1-1h-6c-.6 0-1 .4-1 1v2H8v2h1v10c0 .6.4 1 1 1h12c0 .6-.4 1-1 1V13h1v-2h-4V9zm-6 1h4v1h-4v-1zm7 3v9H11v-9h10z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-2 first:pl-5 last:pr-5 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    <span>Tidak ada data user yang ditemukan.</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 px-2">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div
        x-data="{ show: @entangle('showModal') }"
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
                    {{ $isEditMode ? 'Edit User' : 'Tambah User Baru' }}
                </h3>
                <button class="p-1 ml-auto bg-transparent border-0 text-gray-500 hover:text-gray-800 float-right text-3xl leading-none font-semibold outline-none focus:outline-none" @click="show = false">
                    <span class="bg-transparent text-gray-500 hover:text-gray-800 h-6 w-6 text-2xl block outline-none focus:outline-none">
                        ×
                    </span>
                </button>
            </div>

            <!-- Body -->
            <div class="relative p-6 flex-auto">
                <form wire:submit.prevent="store">
                    <div class="grid gap-6">
                        <!-- Nama Lengkap -->
                        <div>
                            <label for="nama_lengkap" class="block mb-2 text-sm font-medium text-gray-900">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="nama_lengkap" id="nama_lengkap" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Contoh: Dr. Budi Santoso">
                            @error('nama_lengkap') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Username -->
                        <div>
                            <label for="username" class="block mb-2 text-sm font-medium text-gray-900">Username <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="username" id="username" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="username_login">
                            @error('username') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Role -->
                        <div>
                            <label for="role" class="block mb-2 text-sm font-medium text-gray-900">Role <span class="text-red-500">*</span></label>
                            <select wire:model="role" id="role" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="">-- Pilih Role --</option>
                                @foreach($roleOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('role') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900">
                                Password
                                @if(!$isEditMode) <span class="text-red-500">*</span> @endif
                            </label>
                            <input type="password" wire:model="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="{{ $isEditMode ? 'Kosongkan jika tidak ingin mengubah password' : 'Minimal 6 karakter' }}">
                            @if($isEditMode)
                                <p class="mt-1 text-xs text-gray-500">* Kosongkan jika tidak ingin mengubah password.</p>
                            @endif
                            @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-end p-6 border-t border-solid border-slate-200 rounded-b">
                <button 
                    class="text-gray-500 background-transparent font-bold uppercase px-6 py-2 text-sm outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150 hover:text-gray-700" 
                    type="button" 
                    wire:click="closeModal"
                >
                    Batal
                </button>
                <button 
                    class="bg-blue-600 text-white active:bg-blue-700 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" 
                    type="button" 
                    wire:click="store"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove wire:target="store">{{ $isEditMode ? 'Update User' : 'Simpan User' }}</span>
                    <span wire:loading wire:target="store">Menyimpan...</span>
                </button>
            </div>
        </div>
    </div>

</div>
