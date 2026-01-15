<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailResep extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'resep_id',
        'obat_id',
        'jumlah',
        'dosis',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'jumlah' => 'integer',
    ];

    /**
     * Get the resep that owns this detail.
     */
    public function resep()
    {
        return $this->belongsTo(Resep::class, 'resep_id');
    }

    /**
     * Get the obat for this detail.
     */
    public function obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id');
    }
}
