<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RumahController;
use App\Http\Controllers\AuthController;

/* |-------------------------------------------------------------------------- | Web Routes |-------------------------------------------------------------------------- */

// ── Landing Page ──
Route::get('/', function () {
    return view('pages.landing');
})->name('home');

// ── Form Pencarian Rumah ──
Route::post('/cari-rumah', [RumahController::class , 'search'])->name('rumah.search');

// ── Auth (Guest only) ──
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class , 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class , 'login']);
    Route::get('/register', [AuthController::class , 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class , 'register']);
});

// ── Auth (Logged in) ──
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class , 'logout'])->name('logout');

    Route::get('/dashboard', function () {
            return view('pages.dashboard');
        }
        )->name('dashboard');

// ── Admin only ──
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('rumah', \App\Http\Controllers\Admin\RumahController::class);
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

        Route::get('/statistik', [\App\Http\Controllers\Admin\StatistikController::class, 'index'])->name('statistik');

        Route::get('/analitik', [\App\Http\Controllers\Admin\AnalitikController::class, 'index'])->name('analitik');
        Route::post('/analitik/predict', [\App\Http\Controllers\Admin\AnalitikController::class, 'predict'])->name('predict');
    });
});
