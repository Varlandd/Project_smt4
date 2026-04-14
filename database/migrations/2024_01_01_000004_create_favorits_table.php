<?php

use Illuminate\Database\Migrations\Migration;

/**
 * Di MongoDB, favorit disimpan sebagai embedded array 'favorited_user_ids'
 * di dokumen rumah. Tidak perlu tabel relasi terpisah.
 */
return new class extends Migration
{
    protected $connection = 'mongodb';

    public function up(): void
    {
        // MongoDB tidak memerlukan tabel favorits terpisah.
        // Favorit disimpan sebagai array of user ObjectId di koleksi rumah.
    }

    public function down(): void
    {
        // Nothing to drop
    }
};
