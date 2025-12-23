<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wisata;

class WebController extends Controller
{
    public function index()
    {
        return view('map.index');
    }
    
    public function getWisataData()
    {
        $wisatas = Wisata::where('user_id', auth()->id())->get();
        return response()->json($wisatas);
    }
    
    public function storeWisata(Request $request)
    {
        // Base validation rules
        $rules = [
            'nama' => 'required|string|max:255',
            'kategori' => 'required|in:alam,budaya,belanja,jalan,market'
        ];
        
        $messages = [
            'nama.required' => 'Nama wajib diisi.',
            'nama.max' => 'Nama maksimal 255 karakter.',
            'kategori.required' => 'Kategori wajib dipilih.',
            'kategori.in' => 'Kategori tidak valid.',
        ];
        
        // Add specific validation based on geometry type
        $geometryType = $request->input('geometry_type');
        
        if ($geometryType === 'line') {
            $rules['coordinates'] = 'required|string';
            $messages['coordinates.required'] = 'Koordinat jalan wajib diisi.';
        } elseif ($geometryType === 'polygon') {
            $rules['coordinates'] = 'required|string';
            $messages['coordinates.required'] = 'Koordinat area wajib diisi.';
        } else {
            // For marker/point data
            $rules['alamat'] = 'required|string';
            $rules['lat'] = 'required|numeric';
            $rules['lng'] = 'required|numeric';
            $messages['alamat.required'] = 'Alamat wajib diisi.';
            $messages['lat.required'] = 'Latitude wajib diisi.';
            $messages['lat.numeric'] = 'Latitude harus berupa angka.';
            $messages['lng.required'] = 'Longitude wajib diisi.';
            $messages['lng.numeric'] = 'Longitude harus berupa angka.';
        }
        
        $request->validate($rules, $messages);
        
        $data = $request->all();
        $data['user_id'] = auth()->id();
        
        // Convert coordinates string to array for line/polygon
        if (isset($data['coordinates']) && is_string($data['coordinates'])) {
            $data['coordinates'] = json_decode($data['coordinates'], true);
        }
        
        $wisata = Wisata::create($data);
        return response()->json($wisata, 201);
    }
    
    public function updateWisata(Request $request, $id)
    {
        $wisata = Wisata::where('user_id', auth()->id())->findOrFail($id);
        
        // Base validation rules
        $rules = [
            'nama' => 'required|string|max:255',
            'kategori' => 'required|in:alam,budaya,belanja,jalan,market'
        ];
        
        $messages = [
            'nama.required' => 'Nama wajib diisi.',
            'nama.max' => 'Nama maksimal 255 karakter.',
            'kategori.required' => 'Kategori wajib dipilih.',
            'kategori.in' => 'Kategori tidak valid.',
        ];
        
        // Add specific validation based on geometry type
        $geometryType = $request->input('geometry_type', $wisata->geometry_type);
        
        if ($geometryType === 'line') {
            $rules['coordinates'] = 'required|string';
            $messages['coordinates.required'] = 'Koordinat jalan wajib diisi.';
        } elseif ($geometryType === 'polygon') {
            $rules['coordinates'] = 'required|string';
            $messages['coordinates.required'] = 'Koordinat area wajib diisi.';
        } else {
            // For marker/point data
            $rules['alamat'] = 'required|string';
            $rules['lat'] = 'required|numeric';
            $rules['lng'] = 'required|numeric';
            $messages['alamat.required'] = 'Alamat wajib diisi.';
            $messages['lat.required'] = 'Latitude wajib diisi.';
            $messages['lat.numeric'] = 'Latitude harus berupa angka.';
            $messages['lng.required'] = 'Longitude wajib diisi.';
            $messages['lng.numeric'] = 'Longitude harus berupa angka.';
        }
        
        $request->validate($rules, $messages);
        
        $data = $request->all();
        
        // Convert coordinates string to array for line/polygon
        if (isset($data['coordinates']) && is_string($data['coordinates'])) {
            $data['coordinates'] = json_decode($data['coordinates'], true);
        }
        
        $wisata->update($data);
        return response()->json($wisata);
    }
    
    public function deleteWisata($id)
    {
        $wisata = Wisata::where('user_id', auth()->id())->findOrFail($id);
        $wisata->delete();
        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
