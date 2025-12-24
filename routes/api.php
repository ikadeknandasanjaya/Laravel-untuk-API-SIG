<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeoFeatureController;
use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Local Auth Routes
Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('/logout', [ApiAuthController::class, 'logout'])->middleware('auth:sanctum');

// Protected User Route
Route::middleware('auth:sanctum')->get('/user', [ApiAuthController::class, 'user']);

// API Lokasi Sederhana
Route::get('/lokasi', function (Request $req) {
    return response()->json([
        ["id"=>1,"nama"=>"Gedung A","lat"=>-8.65,"lng"=>115.22],
        ["id"=>2,"nama"=>"Perpustakaan","lat"=>-8.64,"lng"=>115.23],
    ]);
});

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    // Categories CRUD by type
    Route::prefix('categories/{type}')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::get('/{id}', [CategoryController::class, 'show']);
        Route::put('/{id}', [CategoryController::class, 'update']);
        Route::delete('/{id}', [CategoryController::class, 'destroy']);
    });
});

// API Geo Features - CRUD untuk semua jenis fitur geografis
Route::prefix('geo-features')->group(function () {
    // Get all features or filter by type
    Route::get('/', [GeoFeatureController::class, 'index']);
    
    // Get by specific geometry type
    Route::get('/markers', [GeoFeatureController::class, 'markers']);
    Route::get('/polygons', [GeoFeatureController::class, 'polygons']);
    Route::get('/polylines', [GeoFeatureController::class, 'polylines']);
    Route::get('/circles', [GeoFeatureController::class, 'circles']);
    
    // CRUD operations
    Route::post('/', [GeoFeatureController::class, 'store']);
    Route::get('/{id}', [GeoFeatureController::class, 'show']);
    Route::put('/{id}', [GeoFeatureController::class, 'update']);
    Route::delete('/{id}', [GeoFeatureController::class, 'destroy']);
});

