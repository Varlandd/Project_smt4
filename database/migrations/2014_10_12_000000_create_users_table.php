<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    protected $connection = 'mongodb';

    /**
     * Run the migrations.
     * MongoDB bersifat schemaless, collection akan dibuat otomatis
     * saat data pertama kali di-insert.
     */
    public function up(): void
    {
        // Collection 'users' dibuat otomatis oleh MongoDB saat insert pertama.
        // Index pada 'email' akan dibuat saat seeder dijalankan jika diperlukan.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\Schema::connection('mongodb')->dropIfExists('users');
    }
};
