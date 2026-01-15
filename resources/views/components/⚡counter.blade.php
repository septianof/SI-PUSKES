<?php

use Livewire\Component;

new class extends Component
{
    public $count = 0;

    public function increment()
    {
        $this->count++;
    }

    public function decrement()
    {
        $this->count--;
    }
};
?>

<div class="flex flex-col items-center justify-center min-h-screen bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Livewire Counter Demo</h2>
        
        <div class="bg-gradient-to-r from-blue-100 to-purple-100 rounded-xl p-6 mb-6">
            <p class="text-sm text-gray-600 mb-2 text-center">Current Count</p>
            <p class="text-6xl font-bold text-center bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                {{ $count }}
            </p>
        </div>

        <div class="flex gap-4">
            <button 
                wire:click="decrement"
                class="flex-1 bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition duration-200 ease-in-out"
            >
                <span class="text-2xl">−</span> Decrement
            </button>
            
            <button 
                wire:click="increment"
                class="flex-1 bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition duration-200 ease-in-out"
            >
                <span class="text-2xl">+</span> Increment
            </button>
        </div>

        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <p class="text-xs text-gray-600 text-center">
                ✅ <span class="font-semibold">Tailwind CSS</span>: Styling aktif<br>
                ⚡ <span class="font-semibold">Livewire</span>: Interaktivitas berjalan
            </p>
        </div>
    </div>
</div>