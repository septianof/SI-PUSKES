<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'no_rm',
        'nik',
        'nama',
        'alamat',
        'tgl_lahir',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tgl_lahir' => 'date',
    ];

    /**
     * Get all kunjungans for this pasien.
     */
    public function kunjungans()
    {
        return $this->hasMany(Kunjungan::class, 'pasien_id');
    }
}
