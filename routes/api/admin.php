<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin API Routes
|--------------------------------------------------------------------------
| Endpoint API untuk keperluan manajemen/dashboard Admin.
*/

Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Api\Admin\DashboardApiController::class, 'index']);
    
    Route::get('/users', [\App\Http\Controllers\Api\Admin\UserApiController::class, 'index']);
    Route::put('/users/{id}/role', [\App\Http\Controllers\Api\Admin\UserApiController::class, 'updateRole']);
    Route::delete('/users/{id}', [\App\Http\Controllers\Api\Admin\UserApiController::class, 'destroy']);
    
    Route::post('/rumah', [\App\Http\Controllers\Api\Admin\RumahApiController::class, 'store']);
    Route::post('/rumah/{id}', [\App\Http\Controllers\Api\Admin\RumahApiController::class, 'update']);
    Route::delete('/rumah/{id}', [\App\Http\Controllers\Api\Admin\RumahApiController::class, 'destroy']);
});
