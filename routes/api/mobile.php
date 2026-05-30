<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RumahApiController;
use App\Http\Controllers\Api\KalkulatorController;
use App\Http\Controllers\Api\FavoritController;
use App\Http\Controllers\Api\StatsController;
use App\Http\Controllers\Api\LokasiController;
use App\Http\Controllers\Api\FasilitasController;
use App\Http\Controllers\Api\ProfileApiController;
use App\Http\Controllers\Api\AnalitikApiController;
use App\Http\Controllers\Api\ImageProxyController;
use App\Http\Controllers\Api\RekomendasiFinansialController;

/*
|--------------------------------------------------------------------------
| Mobile API Routes
|--------------------------------------------------------------------------
| Endpoint API untuk aplikasi mobile (Flutter).
*/

// ── Public Routes (tanpa auth) ──
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/stats', [StatsController::class, 'index']);
Route::get('/lokasi', [LokasiController::class, 'index']);
Route::get('/fasilitas', [FasilitasController::class, 'index']);

// Image Proxy (untuk Flutter Web CORS)
Route::get('/image-proxy', [ImageProxyController::class, 'show']);

// Rumah (public - bisa dilihat tanpa login)
Route::get('/rumah', [RumahApiController::class, 'index']);
Route::get('/rumah/{id}', [RumahApiController::class, 'show']);

// Analitik - Prediksi & Rekomendasi (public)
Route::post('/predict', [AnalitikApiController::class, 'predict']);
Route::post('/recommend', [AnalitikApiController::class, 'recommend']);
Route::post('/kalkulator', [KalkulatorController::class, 'hitung']);
Route::post('/rekomendasi-finansial', [RekomendasiFinansialController::class, 'hitung']);

// ── Protected Routes (butuh Sanctum token) ──
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);

    // Rumah (protected actions)
    Route::post('/rumah/search', [RumahApiController::class, 'search']);

    // Favorit
    Route::get('/favorit', [FavoritController::class, 'index']);
    Route::post('/favorit/{rumahId}', [FavoritController::class, 'store']);
    Route::delete('/favorit/{rumahId}', [FavoritController::class, 'destroy']);

    // Profil User
    Route::get('/user', [ProfileApiController::class, 'show']);
    Route::put('/user/profile', [ProfileApiController::class, 'updateProfile']);
    Route::put('/user/password', [ProfileApiController::class, 'updatePassword']);


});
