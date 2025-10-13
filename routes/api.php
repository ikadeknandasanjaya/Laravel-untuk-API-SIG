<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WisataController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API Lokasi Sederhana
Route::get('/lokasi', function (Request $req) {
    return response()->json([
        ["id"=>1,"nama"=>"Gedung A","lat"=>-8.65,"lng"=>115.22],
        ["id"=>2,"nama"=>"Perpustakaan","lat"=>-8.64,"lng"=>115.23],
    ]);
});

// API Wisata
Route::get('/wisata', [WisataController::class, 'index']);
Route::get('/wisata/{id}', [WisataController::class, 'show']);
