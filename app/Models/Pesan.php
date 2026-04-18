<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pesan extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'pesan';

    protected $fillable = [
        'nama',
        'email',
        'subjek',
        'pesan',
        'status', // 'unread', 'read'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
