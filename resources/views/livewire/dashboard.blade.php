<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- 1. Header & Welcome Message -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Dashboard Ringkasan</h2>
                <p class="text-gray-600">Selamat datang kembali, <span class="font-semibold">{{ Auth::user()->nama_lengkap ?? Auth::user()->username }}</span>!</p>
            </div>
            <div>
                <span class="px-4 py-2 rounded-full bg-blue-600 text-white text-sm font-semibold shadow-sm uppercase">
                    {{ Auth::user()->role }}
                </span>
            </div>
        </div>

        <!-- 2. Dynamic Statistic Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            @foreach($stats as $stat)
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6 flex items-center justify-between hover:shadow-md transition-shadow">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">{{ $stat['label'] }}</p>
                        <h3 class="text-3xl font-bold text-gray-900">{{ $stat['value'] }}</h3>
                        <p class="text-xs text-{{ $stat['color'] }}-600 mt-2 flex items-center font-medium">
                            {{ $stat['subtext'] }}
                        </p>
                    </div>
                    <div class="p-4 bg-{{ $stat['color'] }}-50 rounded-full text-{{ $stat['color'] }}-600">
                        <!-- Icon Dynamic Rendering -->
                        @if($stat['icon'] === 'users')
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        @elseif($stat['icon'] === 'building-office')
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        @elseif($stat['icon'] === 'beaker')
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        @elseif($stat['icon'] === 'user-group')
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        @elseif($stat['icon'] === 'user-plus')
                             <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        @elseif($stat['icon'] === 'check-circle')
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        @elseif($stat['icon'] === 'stethoscope')
                             <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg> <!-- Note: pakai heart sementara sbg stethoscope alt -->
                        @elseif($stat['icon'] === 'document-text')
                             <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        @elseif($stat['icon'] === 'exclamation-triangle')
                             <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        @else
                            <!-- Default fallback icon (Chart Bar) -->
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- 3. Grafik Kunjungan (Visual Placeholder CSS Only) - General for All Roles -->
        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Grafik Aktivitas Mingguan</h3>
                <select class="text-sm border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <option>7 Hari Terakhir</option>
                    <option>Bulan Ini</option>
                </select>
            </div>
            <div class="p-8">
                <!-- Simple CSS Bar Chart -->
                <div class="flex items-end justify-between h-64 w-full gap-4 text-center">
                    @foreach(['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $day)
                        @php $height = rand(20, 90); @endphp
                        <div class="flex-1 flex flex-col justify-end gap-2 group">
                            <div class="text-xs text-gray-500 opacity-0 group-hover:opacity-100 transition-opacity font-bold">{{ $height }}</div>
                            <div class="bg-blue-100 hover:bg-blue-500 transition-colors rounded-t-lg w-full" style="height: {{ $height }}%;"></div>
                            <div class="text-xs text-gray-500">{{ $day }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- 4. Quick Actions -->
        <div class="mt-8 text-right">
             <p class="text-xs text-gray-400">Data diperbarui otomatis</p>
        </div>

    </div>
</div>
