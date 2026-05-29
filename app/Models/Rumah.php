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
    'nama', 'lokasi', 'kota', 'area', 'posisi_kota',
    'harga',                  // ← bersih tanpa (rp)
    'luas_tanah',             // ← bersih tanpa (m2)
    'luas_bangunan',          // ← bersih tanpa (m2)
    'kamar_tidur', 'kamar_mandi',
    'harga_per_m2_tanah',     // ← bersih tanpa (rp)
    'harga_per_m2_bangunan',  // ← bersih tanpa (rp)
    'cluster_harga', 'kategori_harga',
    'tipe', 'foto', 'deskripsi',
    'fasilitas', 'fasilitas_ids', 'favorited_user_ids',
    'latitude', 'longitude',
];

protected $casts = [
    'harga'                 => 'integer',
    'luas_tanah'            => 'integer',
    'luas_bangunan'         => 'integer',
    'kamar_tidur'           => 'integer',
    'kamar_mandi'           => 'integer',
    'harga_per_m2_tanah'    => 'float',
    'harga_per_m2_bangunan' => 'float',
    'cluster_harga'         => 'integer',
    'latitude'              => 'float',
    'longitude'             => 'float',
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
    $value = array_map('trim', explode(',', $value));
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
        $favs = $this->favorited_user_ids;
        return is_array($favs) ? count($favs) : 0;
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
