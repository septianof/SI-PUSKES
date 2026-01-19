<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', \App\Livewire\Dashboard::class)->name('dashboard');

    Route::post('/logout', function () {
        Illuminate\Support\Facades\Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/login');
    })->name('logout');

    // Admin Routes - User Management & Master Data
    Route::middleware('role:admin')->group(function () {
        Route::get('/users', \App\Livewire\Admin\Users::class)->name('users');
        Route::get('/polis', \App\Livewire\Admin\Polis::class)->name('polis');
    });

    // Shared Routes - Admin & Apoteker
    Route::middleware('role:admin,apoteker')->group(function () {
        Route::get('/obats', \App\Livewire\Admin\Obats::class)->name('obats');
    });

    // Pendaftaran Routes
    Route::middleware('role:pendaftaran')->group(function () {
        Route::get('/pendaftaran', \App\Livewire\Pendaftaran\DaftarPasien::class)->name('pendaftaran');
    });

    // Dokter Routes
    Route::middleware('role:dokter')->group(function () {
        Route::get('/dokter/antrean', \App\Livewire\Dokter\AntreanPoli::class)->name('dokter.antrean');
        Route::get('/dokter/periksa/{kunjungan}', \App\Livewire\Dokter\PeriksaPasien::class)->name('dokter.periksa');
        Route::get('/dokter/riwayat', \App\Livewire\Dokter\RiwayatPasien::class)->name('dokter.riwayat');
    });
});