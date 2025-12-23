<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeoFeature extends Model
{
    protected $table = 'geo_features';
    
    protected $fillable = [
        'nama',
        'deskripsi',
        'kategori_id',
        'user_id',
        'geometry_type',
        'coordinates',
        'properties'
    ];
    
    protected $casts = [
        'coordinates' => 'array',
        'properties' => 'array'
    ];
    
    // Geometry types constants
    const TYPE_MARKER = 'marker';
    const TYPE_POLYGON = 'polygon';
    const TYPE_POLYLINE = 'polyline';
    const TYPE_CIRCLE = 'circle';
    
    /**
     * Get the user that owns the geo feature.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Scope a query to only include markers.
     */
    public function scopeMarkers($query)
    {
        return $query->where('geometry_type', self::TYPE_MARKER);
    }
    
    /**
     * Scope a query to only include polygons.
     */
    public function scopePolygons($query)
    {
        return $query->where('geometry_type', self::TYPE_POLYGON);
    }
    
    /**
     * Scope a query to only include polylines.
     */
    public function scopePolylines($query)
    {
        return $query->where('geometry_type', self::TYPE_POLYLINE);
    }
    
    /**
     * Scope a query to only include circles.
     */
    public function scopeCircles($query)
    {
        return $query->where('geometry_type', self::TYPE_CIRCLE);
    }
}
