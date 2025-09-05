<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'status', 'phone', 'address',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isOwner()
    {
        return $this->role === 'owner';
    }

    public function isDirector()
    {
        return $this->role === 'director';
    }

    public function isInvestor()
    {
        return $this->role === 'investor';
    }
   
    public function hasRole(string $role): bool
    {
    return $this->role === $role;
    }


    public function isActive()
    {
        return $this->status === 'active';
    }

    public function investor()
    {
        return $this->hasOne(Investor::class);
    }
}
