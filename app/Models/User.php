<?php

namespace App\Models;

use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
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
}
