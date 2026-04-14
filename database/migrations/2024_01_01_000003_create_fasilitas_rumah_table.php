<?php

use Illuminate\Database\Migrations\Migration;

/**
 * Di MongoDB, relasi many-to-many antara Fasilitas dan Rumah ditangani
 * secara otomatis oleh mongodb/laravel-mongodb menggunakan array of ObjectId
 * di dalam dokumen masing-masing. Tidak perlu pivot table terpisah.
 */
return new class extends Migration
{
    protected $connection = 'mongodb';

    public function up(): void
    {
        // MongoDB tidak memerlukan pivot table.
        // Relasi belongsToMany dihandle dengan embedded array di kedua koleksi.
    }

    public function down(): void
    {
        // Nothing to drop
    }
};
