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
        'foto'                => 'array',
    ];

     public function getFotoAttribute($value)
    {
        // Jika value bukan array, jadikan array
        $fotos = is_array($value) ? $value : ($value ? [$value] : []);
        
        // Bersihkan setiap URL
        return array_map([$this, 'cleanImageUrl'], $fotos);
    }
    
    /**
     * Mutator: Pastikan foto selalu disimpan sebagai array
     * Dipanggil otomatis saat set $rumah->foto = ...
     */
    public function setFotoAttribute($value)
    {
        // Jika value string dengan koma, explode
        if (is_string($value) && str_contains($value, ',')) {
            // Coba split dengan regex untuk URL
            preg_match_all('/https?:\/\/[^\s,]+/', $value, $matches);
            $value = $matches[0] ?? [];
        }
        
        // Jika value string biasa, jadikan array
        if (is_string($value)) {
            $value = [$value];
        }
        
        $this->attributes['foto'] = is_array($value) ? $value : [];
    }
    
    /**
     * Helper: Extract URL asli dari format Next.js
     * https://rumah123.com/portal-img/_next/image/?url=ENCODED_URL&w=1920&q=75
     * menjadi https://picture.rumah123.com/r123-images/...jpg
     */
    private function cleanImageUrl($url)
    {
        // Jika bukan URL valid, return as is
        if (!$url || !is_string($url)) {
            return $url;
        }
        
        // Jika bukan format Next.js, return as is
        if (strpos($url, '_next/image') === false) {
            return $url;
        }
        
        // Ekstrak parameter 'url' dari query string
        $parsed = parse_url($url);
        if (isset($parsed['query'])) {
            parse_str($parsed['query'], $params);
            if (isset($params['url'])) {
                return urldecode($params['url']);
            }
        }
        
        return $url;
    }
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
