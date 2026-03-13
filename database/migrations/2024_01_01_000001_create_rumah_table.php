<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::create('rumah', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('lokasi');
            $table->integer('harga');
            $table->integer('luas_tanah');
            $table->integer('luas_bangunan');
            $table->integer('kamar_tidur');
            $table->integer('kamar_mandi');
            $table->string('tipe'); // rumah, apartemen, ruko, dll
            $table->string('foto')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rumah');
    }
};
