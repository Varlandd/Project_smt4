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
        Route::middleware('admin')->prefix('admin')->group(function () {
            Route::get('/dashboard', function () {
                    return view('admin.pages.dashboard');
                }
                )->name('admin.dashboard');

                Route::get('/rumah', function () {
                    return view('admin.pages.rumah');
                }
                )->name('admin.rumah');

                Route::get('/users', function () {
                    return view('admin.pages.users');
                }
                )->name('admin.users');

                Route::get('/statistik', function () {
                    return view('admin.pages.statistik');
                }
                )->name('admin.statistik');
            }
            );        });
