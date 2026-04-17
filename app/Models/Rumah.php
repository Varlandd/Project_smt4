<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class Rumah extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'rumah';

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
        'fasilitas',            // embedded array of nama fasilitas
        'fasilitas_ids',        // array of ObjectId fasilitas
        'favorited_user_ids',   // array of user _id yang memfavoritkan
    ];

    protected $casts = [
        'harga'               => 'integer',
        'luas_tanah'          => 'integer',
        'luas_bangunan'       => 'integer',
        'kamar_tidur'         => 'integer',
        'kamar_mandi'         => 'integer',
        'fasilitas'           => 'array',
        'fasilitas_ids'       => 'array',
        'favorited_user_ids'  => 'array',
    ];

    /**
     * Relasi ke user yang memfavoritkan.
     */
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, null, 'rumah_ids', 'user_ids')
                    ->withTimestamps();
    }

    /**
     * Hitung jumlah favorit dari array embedded.
     */
    public function getFavoritedByCountAttribute(): int
    {
        return count($this->favorited_user_ids ?? []);
    }

    /**
     * Override insertAndSetId to fix MongoDB Eloquent Builder bug in mongodb/laravel-mongodb v4.0
     * where parent::insertGetId resolves to __call, which ignores the inserted ID and returns the Builder.
     */
    protected function insertAndSetId(EloquentBuilder $query, $attributes)
    {
        $id = $query->toBase()->insertGetId($attributes, $keyName = $this->getKeyName());

        $this->setAttribute($keyName, $id);
    }
}
