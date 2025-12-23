<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GisFeature extends Model
{
    protected $fillable = [
        'nama',
        'deskripsi',
        'tipe', // 'polyline', 'polygon', 'marker'
        'kategori', // 'jalan', 'market', 'area'
        'coordinates', // JSON untuk menyimpan koordinat
        'style', // JSON untuk style (warna, dll)
        'user_id'
    ];
    
    protected $casts = [
        'coordinates' => 'array',
        'style' => 'array'
    ];
    
    /**
     * Get the user that owns the GIS feature.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
