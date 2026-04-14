<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    protected $connection = 'mongodb';

    public function up(): void
    {
        // MongoDB akan membuat collection otomatis saat insert pertama.
    }

    public function down(): void
    {
        \Illuminate\Support\Facades\Schema::connection('mongodb')->dropIfExists('password_reset_tokens');
    }
};
