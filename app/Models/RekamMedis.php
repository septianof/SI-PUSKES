<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekamMedis extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rekam_medis';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'kunjungan_id',
        'dokter_id',
        'tgl_periksa',
        'keluhan',
        'diagnosa',
        'tanda_vital',
        'tindakan',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tgl_periksa' => 'datetime',
    ];

    /**
     * Get the kunjungan that owns this rekam medis.
     */
    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class, 'kunjungan_id');
    }

    /**
     * Get the dokter (user) that created this rekam medis.
     */
    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }

    /**
     * Get the resep for this rekam medis.
     */
    public function resep()
    {
        return $this->hasOne(Resep::class, 'rekam_medis_id');
    }
}
