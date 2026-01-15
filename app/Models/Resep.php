<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resep extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'rekam_medis_id',
        'tgl_resep',
        'status',
        'catatan',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tgl_resep' => 'datetime',
    ];

    /**
     * Get the rekam medis that owns this resep.
     */
    public function rekamMedis()
    {
        return $this->belongsTo(RekamMedis::class, 'rekam_medis_id');
    }

    /**
     * Get all detail reseps for this resep.
     */
    public function detailReseps()
    {
        return $this->hasMany(DetailResep::class, 'resep_id');
    }

    /**
     * Get all obats through detail reseps (many-to-many).
     */
    public function obats()
    {
        return $this->belongsToMany(Obat::class, 'detail_reseps', 'resep_id', 'obat_id')
            ->withPivot('jumlah', 'dosis')
            ->withTimestamps();
    }
}
