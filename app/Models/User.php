<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'shift',
        'lokasi',
        'profile_photo'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
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

    // PERBAIKAN: Get profile photo URL dengan multiple fallback
    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->profile_photo) {
            // Method 1: Check storage disk
            if (Storage::disk('public')->exists($this->profile_photo)) {
                $url = asset('storage/' . $this->profile_photo);
                \Log::info('Photo URL (storage):', ['url' => $url, 'user_id' => $this->id]);
                return $url . '?v=' . time();
            }
            
            // Method 2: Check public uploads folder (alternative method)
            if (file_exists(public_path($this->profile_photo))) {
                $url = asset($this->profile_photo);
                \Log::info('Photo URL (public):', ['url' => $url, 'user_id' => $this->id]);
                return $url . '?v=' . time();
            }
            
            // Method 3: Check public/uploads/profile_photos (backup method)
            $backupPath = 'uploads/profile_photos/' . basename($this->profile_photo);
            if (file_exists(public_path($backupPath))) {
                $url = asset($backupPath);
                \Log::info('Photo URL (backup):', ['url' => $url, 'user_id' => $this->id]);
                return $url . '?v=' . time();
            }
            
            \Log::warning('Photo not found in any location:', [
                'user_id' => $this->id,
                'profile_photo' => $this->profile_photo,
                'storage_exists' => Storage::disk('public')->exists($this->profile_photo),
                'public_exists' => file_exists(public_path($this->profile_photo))
            ]);
        }
        
        // Default avatar
        return $this->getDefaultAvatarUrl();
    }

    // Get default avatar URL
    public function getDefaultAvatarUrl(): string
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=5e72e4&color=fff&size=200&font-size=0.5&rounded=true';
    }

    // Check if user has profile photo
    public function hasProfilePhoto(): bool
    {
        if (!$this->profile_photo) {
            return false;
        }
        
        // Check multiple locations
        return Storage::disk('public')->exists($this->profile_photo) || 
               file_exists(public_path($this->profile_photo)) ||
               file_exists(public_path('uploads/profile_photos/' . basename($this->profile_photo)));
    }

    // Get initials
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        $initials = '';
        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        return substr($initials, 0, 2);
    }
}