<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wisata extends Model
{
    protected $fillable = [
        'nama',
        'alamat', 
        'lat',
        'lng',
        'kategori',
        'user_id',
        'geometry_type',
        'coordinates'
    ];
    
    protected $casts = [
        'lat' => 'decimal:7',
        'lng' => 'decimal:7',
        'coordinates' => 'array'
    ];
    
    /**
     * Get the user that owns the wisata.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
