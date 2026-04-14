<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    protected $connection = 'mongodb';

    public function up(): void
    {
        // MongoDB akan membuat collection otomatis saat insert pertama.
        // Sanctum token disimpan di koleksi personal_access_tokens.
    }

    public function down(): void
    {
        \Illuminate\Support\Facades\Schema::connection('mongodb')->dropIfExists('personal_access_tokens');
    }
};
