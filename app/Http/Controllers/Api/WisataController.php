<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wisata;
use App\Http\Resources\WisataResource;

class WisataController extends Controller
{
    public function index(Request $request)
    {
        $q = Wisata::query();
        
        if ($request->filled('kategori')) {
            $q->where('kategori', $request->kategori);
        }
        
        if ($request->filled('q')) {
            $q->where('nama', 'like', '%'.$request->q.'%');
        }
        
        $items = $q->paginate(10);
        
        return WisataResource::collection($items);
    }
    
    public function show($id)
    {
        $wisata = Wisata::findOrFail($id);
        return new WisataResource($wisata);
    }
}
