<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fasilitas extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'fasilitas';

    protected $fillable = ['nama'];

    public function rumah()
    {
        return $this->belongsToMany(Rumah::class, null, 'fasilitas_ids', 'rumah_ids')
                    ->withTimestamps();
    }
}
