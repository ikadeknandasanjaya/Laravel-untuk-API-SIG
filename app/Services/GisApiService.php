<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class GisApiService {
    protected string $baseUrl = 'https://gisapis.manpits.xyz/api';
    protected ?Request $request = null;

    public function __construct(?Request $request = null) {
        $this->request = $request;
    }

    protected function getToken(): ?string {
        // Token diambil dari Authorization header yang dikirim frontend
        // Frontend akan inject remote_token sebagai Bearer token
        // jadi kita cukup extract dari header
        if ($this->request) {
            $authHeader = $this->request->header('Authorization');
            if ($authHeader && str_starts_with($authHeader, 'Bearer ')) {
                $token = substr($authHeader, 7);
                \Log::info('GisApiService: Using token from Authorization header', [
                    'token' => substr($token, 0, 20) . '...'
                ]);
                return $token;
            }
        }
        
        \Log::warning('GisApiService: No token found in Authorization header');
        return '';
    }

    public function setToken(string $token): void {
        Session::put('api_token', $token);
    }

    public function clearToken(): void {
        Session::forget('api_token');
    }

    public function isAuthenticated(): bool {
        return !empty($this->getToken());
    }

    protected function get(string $endpoint) : array{
        $response = Http::withToken($this->getToken())
            ->get("{$this->baseUrl}{$endpoint}");
        return $response->json();
    }

    protected function post(string $endpoint, array $data = []) : array{
        $response = Http::withToken($this->getToken())
            -> asForm() 
            ->post("{$this->baseUrl}{$endpoint}", $data);
        return $response->json();
    }

    protected function put(string $endpoint, array $data = []) : array{
        $response = Http::withToken($this->getToken())
            -> asForm() 
            ->put("{$this->baseUrl}{$endpoint}", $data);
        return $response->json();
    }

    protected function delete(string $endpoint) : array{
        $response = Http::withToken($this->getToken())
            ->delete("{$this->baseUrl}{$endpoint}");
        return $response->json();
    }


     // ========================================
    // AUTHENTICATION METHODS
    // ========================================

      /**
     * Register new user
     * 
     * @param string $name
     * @param string $email
     * @param string $password
     * @return array
     */

    public function register(string $name, string $email, string $password): array {
        $response = Http::asForm()->post("{$this->baseUrl}/register", [
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        return $response;
    }

    /**
     * Login user
     * 
     * @param string $email
     * @param string $password
     * @return array
     */

    public function login(string $email, string $password): array {
        $response = Http::asForm()->post("{$this->baseUrl}/login", [
            'email' => $email,
            'password' => $password,
        ]);

        $data = $response->json();

        if (isset($data['meta']['token'])) {
            $this->setToken($data['meta']['token']);
        }

        return $data;

    }

       /**
     * Logout user
     * 
     * @return array
     */

    public function logout(): array {
        $response = $this->post('/logout');

        $this->clearToken();

        return $response;
    }
    
     /**
     * Get current user profile
     * 
     * @return array
     */
    
    public function getUser(): array {
        return $this->get('/user');
    }

     // ========================================
    // REGION METHODS (Provinsi, Kabupaten, Kecamatan, Desa)
    // ========================================

    /**
     * Get all regions (provinsi)
     * 
     * @return array
     */

    public function getAllRegion(): array {
        return $this->get('/mregion');
    }


    /**
     * Get provinsi by ID
     * 
     * @param int $id
     * @return array
     */
    public function getProvinsi(int $id): array
    {
        return $this->get("/provinsi/{$id}");
    }

    /**
     * Get kabupaten by provinsi ID
     * 
     * @param int $provinsiId
     * @return array
     */
    public function getKabupatenByProvinsi(int $provinsiId): array
    {
        return $this->get("/kabupaten/{$provinsiId}");
    }

    /**
     * Get kecamatan by kabupaten ID
     * 
     * @param int $kabupatenId
     * @return array
     */
    public function getKecamatanByKabupaten(int $kabupatenId): array
    {
        return $this->get("/kecamatan/{$kabupatenId}");
    }

    /**
     * Get desa by kecamatan ID
     * 
     * @param int $kecamatanId
     * @return array
     */
    public function getDesaByKecamatan(int $kecamatanId): array
    {
        return $this->get("/desa/{$kecamatanId}");
    }

    /**
     * Get kecamatan by desa ID (reverse lookup)
     * 
     * @param int $desaId
     * @return array
     */
    public function getKecamatanByDesaId(int $desaId): array
    {
        return $this->get("/kecamatanbydesaid/{$desaId}");
    }

    /**
     * Get kabupaten by kecamatan ID (reverse lookup)
     * 
     * @param int $kecamatanId
     * @return array
     */
    public function getKabupatenByKecamatanId(int $kecamatanId): array
    {
        return $this->get("/kabupatenbykecamatanid/{$kecamatanId}");
    }

    /**
     * Get provinsi by kabupaten ID (reverse lookup)
     * 
     * @param int $kabupatenId
     * @return array
     */
    public function getProvinsiByKabupatenId(int $kabupatenId): array
    {
        return $this->get("/provinsibykabupatenid/{$kabupatenId}");
    }

    // ========================================
    // MASTER DATA METHODS
    // ========================================

    /**
     * Get master eksisting jalan
     * Contoh: Aspal, Beton, Kerikil, Tanah, dll
     * 
     * @return array
     */
    public function getMasterEksisting(): array
    {
        return $this->get('/meksisting');
    }

    /**
     * Get master jenis jalan
     * Contoh: Jalan Desa, Jalan Kabupaten, Jalan Provinsi, dll
     * 
     * @return array
     */
    public function getMasterJenisJalan(): array
    {
        return $this->get('/mjenisjalan');
    }

    /**
     * Get master kondisi jalan
     * Contoh: Baik, Sedang, Rusak, Rusak Berat
     * 
     * @return array
     */
    public function getMasterKondisi(): array
    {
        return $this->get('/mkondisi');
    }

    // ========================================
    // RUAS JALAN METHODS (CRUD)
    // ========================================

    /**
     * Get all ruas jalan
     * 
     * @return array
     */
    public function getAllRuasJalan(): array
    {
        return $this->get('/ruasjalan');
    }

    /**
     * Get ruas jalan by ID
     * 
     * @param int $id
     * @return array
     */
    public function getRuasJalan(int $id): array
    {
        return $this->get("/ruasjalan/{$id}");
    }

    /**
     * Create new ruas jalan
     * 
     * @param array $data
     * @return array
     * 
     * Required fields:
     * - paths: string (encoded polyline)
     * - desa_id: int
     * - kode_ruas: string
     * - nama_ruas: string
     * - panjang: float (meter)
     * - lebar: float (meter)
     * - eksisting_id: int
     * - kondisi_id: int
     * - jenisjalan_id: int
     * - keterangan: string
     */
    public function createRuasJalan(array $data): array
    {
        return $this->post('/ruasjalan', $data);
    }

    /**
     * Update ruas jalan by ID
     * 
     * @param int $id
     * @param array $data
     * @return array
     */
    public function updateRuasJalan(int $id, array $data): array
    {
        return $this->put("/ruasjalan/{$id}", $data);
    }

    /**
     * Delete ruas jalan by ID
     * 
     * @param int $id
     * @return array
     */
    public function deleteRuasJalan(int $id): array
    {
        return $this->delete("/ruasjalan/{$id}");
    }

 }
