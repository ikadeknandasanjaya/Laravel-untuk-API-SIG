<?php

namespace App\Http\Controllers;

use App\Models\GeoFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GeoFeatureController extends Controller
{
    /**
     * Get all geo features or filter by type
     */
    public function index(Request $request)
    {
        $query = GeoFeature::with('user');
        
        // Filter by geometry type if provided
        if ($request->has('type')) {
            $query->where('geometry_type', $request->type);
        }
        
        // Filter by user if provided
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        $features = $query->latest()->get();
        
        return response()->json([
            'success' => true,
            'data' => $features
        ]);
    }
    
    /**
     * Get markers only
     */
    public function markers()
    {
        $markers = GeoFeature::markers()->with('user')->latest()->get();
        
        return response()->json([
            'success' => true,
            'data' => $markers
        ]);
    }
    
    /**
     * Get polygons only
     */
    public function polygons()
    {
        $polygons = GeoFeature::polygons()->with('user')->latest()->get();
        
        return response()->json([
            'success' => true,
            'data' => $polygons
        ]);
    }
    
    /**
     * Get polylines only
     */
    public function polylines()
    {
        $polylines = GeoFeature::polylines()->with('user')->latest()->get();
        
        return response()->json([
            'success' => true,
            'data' => $polylines
        ]);
    }
    
    /**
     * Get circles only
     */
    public function circles()
    {
        $circles = GeoFeature::circles()->with('user')->latest()->get();
        
        return response()->json([
            'success' => true,
            'data' => $circles
        ]);
    }
    
    /**
     * Store a new geo feature
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori_id' => 'nullable|integer',
            'geometry_type' => 'required|in:marker,polygon,polyline,circle',
            'coordinates' => 'required|array',
            'properties' => 'nullable|array'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $feature = GeoFeature::create([
            'user_id' => Auth::id() ?? 1, // Default to user 1 if not authenticated
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'kategori_id' => $request->kategori_id,
            'geometry_type' => $request->geometry_type,
            'coordinates' => $request->coordinates,
            'properties' => $request->properties
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Feature created successfully',
            'data' => $feature->load('user')
        ], 201);
    }
    
    /**
     * Get a single geo feature
     */
    public function show($id)
    {
        $feature = GeoFeature::with('user')->find($id);
        
        if (!$feature) {
            return response()->json([
                'success' => false,
                'message' => 'Feature not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $feature
        ]);
    }
    
    /**
     * Update a geo feature
     */
    public function update(Request $request, $id)
    {
        $feature = GeoFeature::find($id);
        
        if (!$feature) {
            return response()->json([
                'success' => false,
                'message' => 'Feature not found'
            ], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'nama' => 'sometimes|required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori_id' => 'nullable|integer',
            'geometry_type' => 'sometimes|required|in:marker,polygon,polyline,circle',
            'coordinates' => 'sometimes|required|array',
            'properties' => 'nullable|array'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $feature->update($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Feature updated successfully',
            'data' => $feature->load('user')
        ]);
    }
    
    /**
     * Delete a geo feature
     */
    public function destroy($id)
    {
        $feature = GeoFeature::find($id);
        
        if (!$feature) {
            return response()->json([
                'success' => false,
                'message' => 'Feature not found'
            ], 404);
        }
        
        $feature->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Feature deleted successfully'
        ]);
    }
}
