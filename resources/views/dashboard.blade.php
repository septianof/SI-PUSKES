<x-layouts.app>
    <x-slot:title>
        Dashboard - SI PUSKES
    </x-slot:title>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-bold mb-2">Selamat Datang, {{ Auth::user()->nama_lengkap ?? Auth::user()->username }}!</h2>
                    <p class="text-gray-600">
                        Anda login sebagai <span class="font-semibold px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-sm uppercase">{{ Auth::user()->role }}</span>
                    </p>
                </div>
            </div>

            <!-- Stats Grid (Placeholder untuk Tahap selanjutnya) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Example Stat Card 1 -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Pasien Hari Ini</p>
                            <p class="text-2xl font-bold text-gray-900">0</p>
                        </div>
                    </div>
                </div>

                <!-- Example Stat Card 2 -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Antrean Menunggu</p>
                            <p class="text-2xl font-bold text-gray-900">0</p>
                        </div>
                    </div>
                </div>

                <!-- Example Stat Card 3 -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-500 mr-4">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Poli Aktif</p>
                            <p class="text-2xl font-bold text-gray-900">2</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                     <h3 class="text-lg font-semibold mb-4">Akses Cepat</h3>
                     <div class="flex gap-4">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Logout
                            </button>
                        </form>
                     </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
