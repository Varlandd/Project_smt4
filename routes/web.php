<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RumahController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// Landing Page
Route::get('/', [RumahController::class, 'index'])->name('home');

// Form Pencarian Rumah
Route::post('/cari-rumah', [RumahController::class, 'search'])->name('rumah.search');

// Auth Guest
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/verify-otp', [AuthController::class, 'showOtp'])->name('otp.verify');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('otp.resend');
});

// Lupa Password
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])
        ->name('password.request');

    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])
        ->name('password.email');

    Route::get('/verify-reset-otp', [ResetPasswordController::class, 'showOtpForm'])
        ->name('password.otp.form');

    Route::post('/verify-reset-otp', [ResetPasswordController::class, 'verifyOtp'])
        ->name('password.otp.verify');

    Route::get('/reset-password', [ResetPasswordController::class, 'showResetForm'])
        ->name('password.reset.form');

    Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'])
        ->name('password.update');
});

// Auth Logged In
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [UserDashboardController::class, 'profile'])->name('profile');

    Route::get('/profile/info', [UserDashboardController::class, 'profileInfo'])->name('profile.info');
    Route::get('/profile/edit', [UserDashboardController::class, 'profileEdit'])->name('profile.edit');
    Route::put('/profile/update', [UserDashboardController::class, 'profileUpdate'])->name('profile.update');
    Route::get('/profile/security', [UserDashboardController::class, 'profileSecurity'])->name('profile.security');
    Route::put('/profile/password', [UserDashboardController::class, 'updatePassword'])->name('profile.password.update');
    Route::get('/profile/orders', [UserDashboardController::class, 'profileOrders'])->name('profile.orders');

    Route::get('/properti', [UserDashboardController::class, 'browse'])->name('properti.browse');
    Route::get('/properti/{id}', [UserDashboardController::class, 'show'])->name('properti.show');

    Route::post('/properti/{id}/favorit', [UserDashboardController::class, 'toggleFavorit'])->name('properti.favorit');
    Route::get('/favorit', [UserDashboardController::class, 'favorit'])->name('favorit.index');

    Route::get('/prediksi', [UserDashboardController::class, 'prediksi'])->name('prediksi');
    Route::get('/rekomendasi', [UserDashboardController::class, 'rekomendasi'])->name('rekomendasi');
    Route::get('/rekomendasi/wizard', [UserDashboardController::class, 'wizard'])->name('rekomendasi.wizard');
    Route::get('/rekomendasi-finansial', [UserDashboardController::class, 'rekomendasiFinansial'])->name('rekomendasi.finansial');
    Route::post('/rekomendasi-finansial/hitung', [UserDashboardController::class, 'hitungFinansial'])->name('rekomendasi.finansial.hitung');
    Route::get('/bandingkan', [UserDashboardController::class, 'bandingkan'])->name('bandingkan');

    Route::post('/kontak', [RumahController::class, 'contact'])->name('kontak.send');

    Route::post('/ml/predict', [UserDashboardController::class, 'mlPredict'])->name('ml.predict');
    Route::post('/ml/recommend', [UserDashboardController::class, 'mlRecommend'])->name('ml.recommend');

    Route::get('/ml-test', [UserDashboardController::class, 'mlTestPage'])->name('ml.test');
    Route::post('/ml-test/predict', [UserDashboardController::class, 'mlTestPredict'])->name('ml.test.predict');
    Route::post('/ml-test/check', [UserDashboardController::class, 'mlTestCheck'])->name('ml.test.check');

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

        Route::get('/api-monitoring', [\App\Http\Controllers\Admin\ApiMonitoringController::class, 'index'])->name('api-monitoring');
        Route::post('/api-monitoring/test', [\App\Http\Controllers\Admin\ApiMonitoringController::class, 'testEndpoint'])->name('api-monitoring.test');
    });
});