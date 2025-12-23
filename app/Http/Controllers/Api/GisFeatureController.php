<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GisFeature;
use Illuminate\Http\Request;

class GisFeatureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = GisFeature::query();
        
        // Filter berdasarkan tipe
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }
        
        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }
        
        $features = $query->orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'data' => $features
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe' => 'required|in:polyline,polygon,marker',
            'kategori' => 'required|in:jalan,market,area,lainnya',
            'coordinates' => 'required|array',
            'style' => 'nullable|array'
        ], [
            'nama.required' => 'Nama fitur wajib diisi.',
            'nama.max' => 'Nama fitur maksimal 255 karakter.',
            'tipe.required' => 'Tipe fitur wajib dipilih.',
            'tipe.in' => 'Tipe fitur tidak valid.',
            'kategori.required' => 'Kategori wajib dipilih.',
            'kategori.in' => 'Kategori tidak valid.',
            'coordinates.required' => 'Koordinat wajib diisi.',
            'coordinates.array' => 'Format koordinat tidak valid.',
        ]);
        
        $feature = GisFeature::create($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Fitur GIS berhasil disimpan',
            'data' => $feature
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $feature = GisFeature::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $feature
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $feature = GisFeature::findOrFail($id);
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe' => 'required|in:polyline,polygon,marker',
            'kategori' => 'required|in:jalan,market,area,lainnya',
            'coordinates' => 'required|array',
            'style' => 'nullable|array'
        ], [
            'nama.required' => 'Nama fitur wajib diisi.',
            'nama.max' => 'Nama fitur maksimal 255 karakter.',
            'tipe.required' => 'Tipe fitur wajib dipilih.',
            'tipe.in' => 'Tipe fitur tidak valid.',
            'kategori.required' => 'Kategori wajib dipilih.',
            'kategori.in' => 'Kategori tidak valid.',
            'coordinates.required' => 'Koordinat wajib diisi.',
            'coordinates.array' => 'Format koordinat tidak valid.',
        ]);
        
        $feature->update($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Fitur GIS berhasil diupdate',
            'data' => $feature
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $feature = GisFeature::findOrFail($id);
        $feature->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Fitur GIS berhasil dihapus'
        ]);
    }
}
