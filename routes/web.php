<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RumahController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserDashboardController;

/* |-------------------------------------------------------------------------- | Web Routes |-------------------------------------------------------------------------- */

// ── Landing Page ──
Route::get('/', [RumahController::class, 'index'])->name('home');

// ── Form Pencarian Rumah ──
Route::post('/cari-rumah', [RumahController::class, 'search'])->name('rumah.search');

// ── Auth (Guest only) ──
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// ── Auth (Logged in) ──
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ── User Dashboard ──
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // ── Browse Properti ──
    Route::get('/properti', [UserDashboardController::class, 'browse'])->name('properti.browse');
    Route::get('/properti/{id}', [UserDashboardController::class, 'show'])->name('properti.show');

    // ── Favorit ──
    Route::post('/properti/{id}/favorit', [UserDashboardController::class, 'toggleFavorit'])->name('properti.favorit');
    Route::get('/favorit', [UserDashboardController::class, 'favorit'])->name('favorit.index');

    // ── Fitur User ──
    Route::get('/prediksi', [UserDashboardController::class, 'prediksi'])->name('prediksi');
    Route::get('/rekomendasi', [UserDashboardController::class, 'rekomendasi'])->name('rekomendasi');
    Route::get('/rekomendasi/wizard', [UserDashboardController::class, 'wizard'])->name('rekomendasi.wizard');
    Route::get('/bandingkan', [UserDashboardController::class, 'bandingkan'])->name('bandingkan');

    // ── Form Pencarian Rumah ──
    Route::post('/cari-rumah', [RumahController::class, 'search'])->name('rumah.search');
    Route::post('/kontak', [RumahController::class, 'contact'])->name('kontak.send');

    // ── ML Service Routes (User-accessible) ──
    Route::post('/ml/predict', [UserDashboardController::class, 'mlPredict'])->name('ml.predict');
    Route::post('/ml/recommend', [UserDashboardController::class, 'mlRecommend'])->name('ml.recommend');

    // ── Admin only ──
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::get('rumah/export', [\App\Http\Controllers\Admin\RumahController::class, 'export'])->name('rumah.export');
        Route::post('rumah/import', [\App\Http\Controllers\Admin\RumahController::class, 'import'])->name('rumah.import');
        Route::delete('rumah/bulk-delete', [\App\Http\Controllers\Admin\RumahController::class, 'bulkDestroy'])->name('rumah.bulk-delete');
        Route::resource('rumah', \App\Http\Controllers\Admin\RumahController::class);
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        Route::resource('pesan', \App\Http\Controllers\Admin\PesanController::class)->only(['index', 'show', 'destroy']);

        Route::get('/statistik', [\App\Http\Controllers\Admin\StatistikController::class, 'index'])->name('statistik');
        Route::get('/logs', [\App\Http\Controllers\Admin\LogController::class, 'index'])->name('logs');

        Route::get('/analitik', [\App\Http\Controllers\Admin\AnalitikController::class, 'index'])->name('analitik');
        Route::post('/analitik/predict', [\App\Http\Controllers\Admin\AnalitikController::class, 'predict'])->name('predict');
    });
});
