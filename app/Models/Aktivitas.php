<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Aktivitas extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'aktivitas';

    protected $fillable = [
        'user_id',
        'user_name',
        'aksi',        // 'CREATE', 'UPDATE', 'DELETE'
        'tipe_objek',  // 'Rumah', 'User', 'Fasilitas'
        'deskripsi',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
