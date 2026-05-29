<?php

namespace App\Models;

use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Sanctum\NewAccessToken;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'role',
        'otp_code',        
        'otp_expires_at',  
        'is_verified',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'otp_expires_at'    => 'datetime',
        'is_verified'       => 'boolean',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Override createToken to use our MongoDB-compatible NewAccessToken
     * instead of the default Laravel\Sanctum\NewAccessToken which
     * type-hints Laravel\Sanctum\PersonalAccessToken in its constructor.
     */
    public function createToken(string $name, array $abilities = ['*'], DateTimeInterface $expiresAt = null)
    {
        $plainTextToken = $this->generateTokenString();

        $token = $this->tokens()->create([
            'name' => $name,
            'token' => hash('sha256', $plainTextToken),
            'abilities' => $abilities,
            'expires_at' => $expiresAt,
        ]);

        return new NewAccessToken($token, $token->getKey().'|'.$plainTextToken);
    }

    /**
     * Favorit user — disimpan sebagai array of rumah_id di dokumen user,
     * atau pakai embedded document. Di sini kita pakai relasi manual via
     * Rumah::whereIn karena MongoDB tidak punya pivot table.
     */
    public function favorits()
    {
        return $this->belongsToMany(Rumah::class, null, 'user_ids', 'rumah_ids')
                    ->withTimestamps();
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
