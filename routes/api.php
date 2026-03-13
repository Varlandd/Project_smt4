<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RumahApiController;
use App\Http\Controllers\Api\KalkulatorController;
use App\Http\Controllers\Api\FavoritController;
use App\Http\Controllers\Api\StatsController;
use App\Http\Controllers\Api\LokasiController;
use App\Http\Controllers\Api\FasilitasController;

/* |-------------------------------------------------------------------------- | API Routes |-------------------------------------------------------------------------- | | Endpoint API untuk Flutter rumah_mobile. | Base URL: http://10.0.2.2:8000/api (emulator) |           http://192.168.x.x:8000/api (hp fisik) | */

// ── Public Routes (tanpa auth) ──
Route::post('/register', [AuthController::class , 'register']);
Route::post('/login', [AuthController::class , 'login']);

Route::get('/stats', [StatsController::class , 'index']);
Route::get('/lokasi', [LokasiController::class , 'index']);
Route::get('/fasilitas', [FasilitasController::class , 'index']);

// ── Protected Routes (butuh Sanctum token) ──
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class , 'logout']);

    // Rumah
    Route::get('/rumah', [RumahApiController::class , 'index']);
    Route::get('/rumah/{id}', [RumahApiController::class , 'show']);
    Route::post('/rumah/search', [RumahApiController::class , 'search']);

    // Kalkulator KPR
    Route::post('/kalkulator', [KalkulatorController::class , 'hitung']);

    // Favorit
    Route::get('/favorit', [FavoritController::class , 'index']);
    Route::post('/favorit/{rumahId}', [FavoritController::class , 'store']);
    Route::delete('/favorit/{rumahId}', [FavoritController::class , 'destroy']);
});
