<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poli extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'nama_poli',
        'lokasi',
        'tarif_daftar',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tarif_daftar' => 'decimal:2',
    ];

    /**
     * Get all kunjungans for this poli.
     */
    public function kunjungans()
    {
        return $this->hasMany(Kunjungan::class, 'poli_id');
    }
}
