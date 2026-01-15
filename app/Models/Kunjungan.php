<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'pasien_id',
        'poli_id',
        'tgl_kunjungan',
        'status',
        'keluhan_awal',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tgl_kunjungan' => 'datetime',
    ];

    /**
     * Get the pasien that owns this kunjungan.
     */
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }

    /**
     * Get the poli for this kunjungan.
     */
    public function poli()
    {
        return $this->belongsTo(Poli::class, 'poli_id');
    }

    /**
     * Get the rekam medis for this kunjungan.
     */
    public function rekamMedis()
    {
        return $this->hasOne(RekamMedis::class, 'kunjungan_id');
    }

    /**
     * Get the pembayaran for this kunjungan.
     */
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'kunjungan_id');
    }

    /**
     * Get the klaim BPJS for this kunjungan.
     */
    public function klaimBpjs()
    {
        return $this->hasOne(KlaimBpjs::class, 'kunjungan_id');
    }
}
