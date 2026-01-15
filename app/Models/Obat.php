<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'nama_obat',
        'jenis',
        'stok',
        'harga',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'harga' => 'decimal:2',
        'stok' => 'integer',
    ];

    /**
     * Get all detail reseps for this obat.
     */
    public function detailReseps()
    {
        return $this->hasMany(DetailResep::class, 'obat_id');
    }

    /**
     * Get all reseps through detail reseps (many-to-many).
     */
    public function reseps()
    {
        return $this->belongsToMany(Resep::class, 'detail_reseps', 'obat_id', 'resep_id')
            ->withPivot('jumlah', 'dosis')
            ->withTimestamps();
    }
}
