<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'shift',
        'lokasi'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // Laravel 11 akan auto-hash
        ];
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    public function izin()
    {
        return $this->hasMany(Izin::class);
    }

    public function isMentor(): bool
    {
        return $this->role === 'mentor';
    }

    public function isMagang(): bool
    {
        return $this->role === 'magang';
    }
}