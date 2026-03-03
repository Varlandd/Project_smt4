<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RumahController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ── Landing Page ──
Route::get('/', function () {
    return view('pages.landing');
})->name('home');

// ── Form Pencarian Rumah ──
Route::post('/cari-rumah', [RumahController::class, 'search'])->name('rumah.search');
