<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| File root ini sekarang mengumpulkan sub-route yang sudah dipisah.
| Jika ada tambahan route yang spesifik, buatkan partisi baru di 
| dalam folder "api/".
|
*/

// Memanggil API Khusus untuk Aplikasi Mobile (Flutter)
require __DIR__ . '/api/mobile.php';

// Memanggil API Khusus untuk Admin (Manajemen)
require __DIR__ . '/api/admin.php';
