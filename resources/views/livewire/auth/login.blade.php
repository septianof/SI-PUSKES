<div class="w-full max-w-md bg-white shadow-xl rounded-2xl overflow-hidden">
    <!-- Header / Logo -->
    <div class="bg-blue-600 p-8 text-center text-white">
        <div class="flex justify-center mb-4">
            <!-- Icon Puskesmas (SVG Custom) -->
            <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
        </div>
        <h1 class="text-2xl font-bold uppercase tracking-wider">SIPUSKES</h1>
        <p class="text-blue-100 text-sm mt-1">Sistem Informasi Puskesmas</p>
    </div>

    <!-- Login Form -->
    <div class="p-8">
        <h2 class="text-xl font-semibold text-gray-800 text-center mb-6">Masuk ke Sistem</h2>

        <form wire:submit="login" class="space-y-5">
            <!-- Alert Error General -->
            @if ($errors->has('username') && !$errors->has('password'))
                 <!-- Jika error spesifik username saja -->
            @endif

            <!-- Username Input -->
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <input 
                        wire:model="username" 
                        id="username" 
                        type="text" 
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('username') border-red-500 ring-red-100 @enderror" 
                        placeholder="Contoh: admin"
                        required 
                        autofocus
                    >
                </div>
                @error('username') 
                    <p class="mt-1 text-sm text-red-600 animate-pulse">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Password Input -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input 
                        wire:model="password" 
                        id="password" 
                        type="password" 
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('password') border-red-500 ring-red-100 @enderror" 
                        placeholder="••••••••"
                        required
                    >
                </div>
                @error('password') 
                    <p class="mt-1 text-sm text-red-600 animate-pulse">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input wire:model="remember" type="checkbox" class="rounded text-blue-600 focus:ring-blue-500 border-gray-300">
                    <span class="text-gray-600">Ingat Saya</span>
                </label>
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 flex justify-center items-center"
            >
                <!-- Loading Spinner -->
                <svg wire:loading wire:target="login" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading.remove wire:target="login">MASUK SISTEM</span>
                <span wire:loading wire:target="login">Proses Authentikasi...</span>
            </button>
        </form>
    </div>
    
    <!-- Footer -->
    <div class="bg-gray-50 px-8 py-4 border-t border-gray-100">
        <p class="text-xs text-center text-gray-500">
            Lupa password? Silakan hubungi 
            <a href="#" class="text-blue-600 hover:underline font-medium">Administrator IT</a>
        </p>
    </div>
</div>
