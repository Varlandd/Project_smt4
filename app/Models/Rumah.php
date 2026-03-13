<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rumah extends Model
{
    use HasFactory;

    protected $table = 'rumah';

    protected $fillable = [
        'nama',
        'lokasi',
        'harga',
        'luas_tanah',
        'luas_bangunan',
        'kamar_tidur',
        'kamar_mandi',
        'tipe',
        'foto',
        'deskripsi',
    ];

    protected $casts = [
        'harga' => 'integer',
        'luas_tanah' => 'integer',
        'luas_bangunan' => 'integer',
        'kamar_tidur' => 'integer',
        'kamar_mandi' => 'integer',
    ];

    public function fasilitas()
    {
        return $this->belongsToMany(Fasilitas::class , 'fasilitas_rumah');
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class , 'favorits')->withTimestamps();
    }
}
