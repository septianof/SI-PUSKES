<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SI-PUSKES') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Livewire Styles -->
        @livewireStyles

        <style>
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="font-sans antialiased" x-data="{ sidebarOpen: false }">
        <div class="min-h-screen bg-gray-100 flex">
            <!-- Mobile Sidebar Overlay -->
            <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-40 lg:hidden" style="display: none;"></div>

            <!-- Sidebar -->
            <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed z-50 inset-y-0 left-0 w-64 bg-white shadow-lg overflow-y-auto transition-transform duration-300 transform lg:translate-x-0 lg:static lg:inset-auto">
                <div class="flex items-center justify-center mt-8">
                    <div class="flex items-center">
                        <span class="text-gray-800 text-2xl font-bold">SI-PUSKES</span>
                    </div>
                </div>

                <nav class="mt-10 px-4 space-y-2">
                    <!-- Dashboard (Available for all or specific roles if needed) -->
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-md {{ request()->routeIs('dashboard') ? 'bg-gray-200 font-semibold' : '' }}">
                        <span class="font-medium">Dashboard</span>
                    </a>

                    <!-- Admin Menu -->
                    @if(Auth::check() && Auth::user()->role === 'admin')
                        <div class="pt-4 pb-2">
                            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Admin</p>
                        </div>
                        <a href="{{ route('users') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-md {{ request()->routeIs('users') ? 'bg-gray-200 font-semibold' : '' }}">
                            <span class="font-medium">Kelola Pengguna</span>
                        </a>
                        <a href="{{ route('polis') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-md {{ request()->routeIs('polis') ? 'bg-gray-200 font-semibold' : '' }}">
                            <span class="font-medium">Kelola Poliklinik</span>
                        </a>
                        <a href="{{ route('obats') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-md {{ request()->routeIs('obats') ? 'bg-gray-200 font-semibold' : '' }}">
                            <span class="font-medium">Kelola Obat</span>
                        </a>
                    @endif

                    <!-- Petugas Pendaftaran Menu -->
                    @if(Auth::check() && Auth::user()->role === 'pendaftaran')
                        <div class="pt-4 pb-2">
                            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Petugas</p>
                        </div>
                        <a href="{{ route('pendaftaran') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-md {{ request()->routeIs('pendaftaran') ? 'bg-gray-200 font-semibold' : '' }}">
                            <span class="font-medium">Pendaftaran</span>
                        </a>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-md {{ request()->routeIs('pembayaran.*') ? 'bg-gray-200' : '' }}">
                            <span class="font-medium">Pembayaran</span>
                        </a>
                    @endif

                    <!-- Dokter Menu -->
                    @if(Auth::check() && Auth::user()->role === 'dokter')
                        <div class="pt-4 pb-2">
                            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Dokter</p>
                        </div>
                        <a href="{{ route('dokter.antrean') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-md {{ request()->routeIs('dokter.antrean') ? 'bg-gray-200 font-semibold' : '' }}">
                            <span class="font-medium">Antrean Poli</span>
                        </a>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-md {{ request()->routeIs('dokter.riwayat') ? 'bg-gray-200 font-semibold' : '' }}">
                            <span class="font-medium">Riwayat Rekam Medis</span>
                        </a>
                    @endif

                    <!-- Apoteker Menu -->
                    @if(Auth::check() && Auth::user()->role === 'apoteker')
                        <div class="pt-4 pb-2">
                            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Farmasi</p>
                        </div>
                        <a href="{{ route('obats') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-md {{ request()->routeIs('obats') ? 'bg-gray-200 font-semibold' : '' }}">
                            <span class="font-medium">Kelola Obat</span>
                        </a>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-md {{ request()->routeIs('farmasi.*') ? 'bg-gray-200' : '' }}">
                            <span class="font-medium">Resep Masuk</span>
                        </a>
                    @endif

                     <!-- Kepala Puskesmas Menu -->
                     @if(Auth::check() && Auth::user()->role === 'kepala Puskesmas')
                        <div class="pt-4 pb-2">
                            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Kepala Puskesmas</p>
                        </div>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-md {{ request()->routeIs('laporan.*') ? 'bg-gray-200' : '' }}">
                            <span class="font-medium">Laporan</span>
                        </a>
                    @endif
                </nav>
                
                 <!-- Logout - Mobile Sidebar View (Optional position) -->
                 <div class="absolute bottom-0 w-full p-4 border-t lg:hidden">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center w-full px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-700 rounded-md">
                            <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Logout
                        </button>
                    </form>
                 </div>
            </div>

            <!-- Main Content Wrapper -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Header -->
                <header class="flex justify-between items-center py-4 px-6 bg-white shadow-sm border-b border-gray-100">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none lg:hidden">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- User Profile -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center focus:outline-none hover:bg-gray-50 p-2 rounded-md transition-colors">
                                <span class="mr-2 text-sm font-semibold text-gray-800">{{ Auth::user()->username ?? 'Guest' }}</span>
                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full uppercase">{{ Auth::user()->role ?? 'Guest' }}</span>
                                <svg class="ml-2 h-4 w-4 text-gray-600 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div 
                                x-show="open" 
                                @click.away="open = false" 
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 ring-1 ring-black ring-opacity-5" 
                                x-cloak
                            >
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-sm text-gray-500">Login sebagai:</p>
                                    <p class="text-sm font-semibold text-gray-800 truncate">{{ Auth::user()->nama_lengkap ?? Auth::user()->username }}</p>
                                </div>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-red-600">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>
        
        <!-- Livewire Scripts -->
        @livewireScripts
    </body>
</html>
