<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RuasJalanController;

// ========================================
// API ROUTES
// ========================================

// These are handled by routes/api.php - Remove from here
// Route::post('/login', [AuthController::class, 'login']);
// Route::post('/register', [AuthController::class, 'register']);

// Protected API routes
Route::middleware(['api.auth'])->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard data
    Route::get('/api/dashboard', [RuasJalanController::class, 'dashboardApi'])->name('api.dashboard');
    
    // Ruas Jalan API
    Route::get('/api/ruasjalan', [RuasJalanController::class, 'indexApi'])->name('api.ruasjalan.index');
    Route::get('/api/ruasjalan/{id}', [RuasJalanController::class, 'showApi'])->name('api.ruasjalan.show');
    Route::post('/api/ruasjalan', [RuasJalanController::class, 'storeApi'])->name('api.ruasjalan.store');
    Route::put('/api/ruasjalan/{id}', [RuasJalanController::class, 'updateApi'])->name('api.ruasjalan.update');
    Route::delete('/api/ruasjalan/{id}', [RuasJalanController::class, 'destroyApi'])->name('api.ruasjalan.destroy');
    
    // Regions API
    Route::get('/api/regions', function () {
        $apiService = app(\App\Services\GisApiService::class);
        $response = $apiService->getAllRegion();
        return response()->json($response);
    });
    
    // API untuk dropdown cascading (AJAX)
    Route::get('/api/kabupaten/{provinsiId}', [RuasJalanController::class, 'getKabupaten'])->name('api.kabupaten');
    Route::get('/api/kecamatan/{kabupatenId}', [RuasJalanController::class, 'getKecamatan'])->name('api.kecamatan');
    Route::get('/api/desa/{kecamatanId}', [RuasJalanController::class, 'getDesa'])->name('api.desa');
});

// ========================================
// VUE SPA ROUTE - Catch all untuk Vue Router
// Harus di akhir, setelah semua route API
// ========================================
Route::get('/{any}', function () {
    return view('app');
})->where('any', '^(?!api).*$')->name('spa');
