<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kunjungans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
            $table->foreignId('poli_id')->constrained('polis')->onDelete('cascade');
            $table->dateTime('tgl_kunjungan');
            $table->enum('status', ['menunggu', 'bayar', 'obat', 'selesai'])->default('menunggu');
            $table->enum('metode_bayar', ['Umum', 'BPJS'])->default('Umum');
            $table->string('no_bpjs', 13)->nullable();
            $table->text('keluhan_awal')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kunjungans');
    }
};
