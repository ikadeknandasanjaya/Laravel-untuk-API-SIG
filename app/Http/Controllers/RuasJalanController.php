<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GisApiService;

class RuasJalanController extends Controller
{
    protected GisApiService $apiService;
    protected Request $request;

    public function __construct(GisApiService $apiService, Request $request)
    {
        $this->apiService = $apiService;
        $this->request = $request;
        // Inject request ke GisApiService agar bisa baca Authorization header
        $this->apiService = new GisApiService($request);
    }

    public function dashboard()
    {
        $userResponse = $this->apiService->getUser();
        $user = $userResponse['data'] ?? null;

        // Ambil semua ruas jalan untuk ditampilkan di peta
        $ruasJalanResponse = $this->apiService->getAllRuasJalan();
        $ruasJalan = $ruasJalanResponse['ruasjalan'] ?? [];

        return view('dashboard', compact('user', 'ruasJalan'));
    }

     /**
     * Display list of all ruas jalan
     */
    public function index()
    {
        // Ambil semua ruas jalan dari API
        $response = $this->apiService->getAllRuasJalan();
        $ruasJalan = $response['ruasjalan'] ?? [];

        // Ambil master data untuk filter
        $eksisting = $this->apiService->getMasterEksisting()['ekspisal am'] ?? [];
        $jenisJalan = $this->apiService->getMasterJenisJalan()['jpisal'] ?? [];
        $kondisi = $this->apiService->getMasterKondisi()['kondpisal'] ?? [];

        return view('ruasjalan.index', compact('ruasJalan', 'eksisting', 'jenisJalan', 'kondisi'));
    }

    /**
     * Show form for creating new ruas jalan
     */
    public function create()
    {
        // Ambil semua master data untuk dropdown
        $regions = $this->apiService->getAllRegion()['provinsi'] ?? [];
        $eksisting = $this->apiService->getMasterEksisting()['ekspisal'] ?? [];
        $jenisJalan = $this->apiService->getMasterJenisJalan()['jpisal'] ?? [];
        $kondisi = $this->apiService->getMasterKondisi()['kondpisal'] ?? [];

        return view('ruasjalan.create', compact('regions', 'eksisting', 'jenisJalan', 'kondisi'));
    }

    /**
     * Store a newly created ruas jalan
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'paths' => 'required|string',          // Encoded polyline dari map
            'desa_id' => 'required|integer',
            'kode_ruas' => 'required|string|max:10',
            'nama_ruas' => 'required|string|max:255',
            'panjang' => 'required|numeric|min:0',
            'lebar' => 'required|numeric|min:0',
            'eksisting_id' => 'required|integer',
            'kondisi_id' => 'required|integer',
            'jenisjalan_id' => 'required|integer',
            'keterangan' => 'nullable|string',
        ]);

        // Kirim ke API
        $response = $this->apiService->createRuasJalan($validated);

        if (isset($response['status']) && $response['status'] === true) {
            return redirect()->route('ruasjalan.index')
                ->with('success', 'Ruas jalan berhasil ditambahkan!');
        }

        return back()
            ->withInput()
            ->withErrors(['error' => $response['message'] ?? 'Gagal menambah ruas jalan']);
    }

    /**
     * Display specific ruas jalan
     */
    public function show(int $id)
    {
        $response = $this->apiService->getRuasJalan($id);
        $ruasJalan = $response['ruasjalan'] ?? null;

        if (!$ruasJalan) {
            abort(404, 'Ruas jalan tidak ditemukan');
        }

        return view('ruasjalan.show', compact('ruasJalan'));
    }

    /**
     * Show form for editing ruas jalan
     */
    public function edit(int $id)
    {
        // Ambil data ruas jalan
        $response = $this->apiService->getRuasJalan($id);
        $ruasJalan = $response['ruasjalan'] ?? null;

        if (!$ruasJalan) {
            abort(404, 'Ruas jalan tidak ditemukan');
        }

        // Ambil master data untuk dropdown
        $regions = $this->apiService->getAllRegion()['provinsi'] ?? [];
        $eksisting = $this->apiService->getMasterEksisting()['ekspisal'] ?? [];
        $jenisJalan = $this->apiService->getMasterJenisJalan()['jpisal'] ?? [];
        $kondisi = $this->apiService->getMasterKondisi()['kondpisal'] ?? [];

        return view('ruasjalan.edit', compact('ruasJalan', 'regions', 'eksisting', 'jenisJalan', 'kondisi'));
    }

    /**
     * Update specific ruas jalan
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'paths' => 'required|string',
            'desa_id' => 'required|integer',
            'kode_ruas' => 'required|string|max:10',
            'nama_ruas' => 'required|string|max:255',
            'panjang' => 'required|numeric|min:0',
            'lebar' => 'required|numeric|min:0',
            'eksisting_id' => 'required|integer',
            'kondisi_id' => 'required|integer',
            'jenisjalan_id' => 'required|integer',
            'keterangan' => 'nullable|string',
        ]);

        $response = $this->apiService->updateRuasJalan($id, $validated);

        if (isset($response['status']) && $response['status'] === true) {
            return redirect()->route('ruasjalan.index')
                ->with('success', 'Ruas jalan berhasil diupdate!');
        }

        return back()
            ->withInput()
            ->withErrors(['error' => $response['message'] ?? 'Gagal mengupdate ruas jalan']);
    }

    /**
     * Delete specific ruas jalan
     */
    public function destroy(int $id)
    {
        $response = $this->apiService->deleteRuasJalan($id);

        if (isset($response['status']) && $response['status'] === true) {
            return redirect()->route('ruasjalan.index')
                ->with('success', 'Ruas jalan berhasil dihapus!');
        }

        return back()->withErrors(['error' => $response['message'] ?? 'Gagal menghapus ruas jalan']);
    }

    // ========================================
    // API ENDPOINTS untuk AJAX (dropdown cascading)
    // ========================================

    /**
     * Get kabupaten by provinsi ID (untuk AJAX)
     */
    public function getKabupaten(int $provinsiId)
    {
        $response = $this->apiService->getKabupatenByProvinsi($provinsiId);
        return response()->json($response);
    }

    /**
     * Get kecamatan by kabupaten ID (untuk AJAX)
     */
    public function getKecamatan(int $kabupatenId)
    {
        $response = $this->apiService->getKecamatanByKabupaten($kabupatenId);
        return response()->json($response);
    }

    /**
     * Get desa by kecamatan ID (untuk AJAX)
     */
    public function getDesa(int $kecamatanId)
    {
        $response = $this->apiService->getDesaByKecamatan($kecamatanId);
        return response()->json($response);
    }

    // ========================================
    // API METHODS untuk Vue.js
    // ========================================

    /**
     * Dashboard API endpoint
     */
    public function dashboardApi()
    {
        $userResponse = $this->apiService->getUser();
        $user = $userResponse['data'] ?? null;

        $ruasJalanResponse = $this->apiService->getAllRuasJalan();
        $ruasJalan = $ruasJalanResponse['ruasjalan'] ?? [];

        return response()->json([
            'user' => $user,
            'ruasjalan' => $ruasJalan,
        ]);
    }

    /**
     * Index API endpoint
     */
    public function indexApi()
    {
        $response = $this->apiService->getAllRuasJalan();
        return response()->json($response);
    }

    /**
     * Show API endpoint
     */
    public function showApi(int $id)
    {
        $response = $this->apiService->getRuasJalan($id);
        return response()->json($response);
    }

    /**
     * Store API endpoint
     */
    public function storeApi(Request $request)
    {
        $validated = $request->validate([
            'paths' => 'required|string',
            'desa_id' => 'required|integer',
            'kode_ruas' => 'required|string|max:10',
            'nama_ruas' => 'required|string|max:255',
            'panjang' => 'required|numeric|min:0',
            'lebar' => 'required|numeric|min:0',
            'eksisting_id' => 'required|integer',
            'kondisi_id' => 'required|integer',
            'jenisjalan_id' => 'required|integer',
            'keterangan' => 'nullable|string',
        ]);

        $response = $this->apiService->createRuasJalan($validated);
        return response()->json($response);
    }

    /**
     * Update API endpoint
     */
    public function updateApi(Request $request, int $id)
    {
        $validated = $request->validate([
            'paths' => 'required|string',
            'desa_id' => 'required|integer',
            'kode_ruas' => 'required|string|max:10',
            'nama_ruas' => 'required|string|max:255',
            'panjang' => 'required|numeric|min:0',
            'lebar' => 'required|numeric|min:0',
            'eksisting_id' => 'required|integer',
            'kondisi_id' => 'required|integer',
            'jenisjalan_id' => 'required|integer',
            'keterangan' => 'nullable|string',
        ]);

        $response = $this->apiService->updateRuasJalan($id, $validated);
        return response()->json($response);
    }

    /**
     * Destroy API endpoint
     */
    public function destroyApi(int $id)
    {
        $response = $this->apiService->deleteRuasJalan($id);
        return response()->json($response);
    }
}