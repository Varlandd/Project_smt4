<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    protected $connection = 'mongodb';

    public function up(): void
    {
        // MongoDB akan membuat collection 'fasilitas' otomatis saat insert pertama.
    }

    public function down(): void
    {
        \Illuminate\Support\Facades\Schema::connection('mongodb')->dropIfExists('fasilitas');
    }
};
