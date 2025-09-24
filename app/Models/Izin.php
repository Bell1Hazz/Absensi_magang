<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Izin extends Model
{
    use HasFactory;

    protected $table = 'izin';

    protected $fillable = [
        'user_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'alasan',
        'jenis_izin',
        'status',
        'keterangan_mentor',
        'disetujui_oleh',
        'tanggal_disetujui'
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'tanggal_disetujui' => 'datetime'
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mentor()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => '<span class="badge badge-warning">Menunggu</span>',
            'disetujui' => '<span class="badge badge-success">Disetujui</span>',
            'ditolak' => '<span class="badge badge-danger">Ditolak</span>',
            default => '<span class="badge badge-secondary">-</span>',
        };
    }

    public function getJenisIzinBadgeAttribute(): string
    {
        return match($this->jenis_izin) {
            'sakit' => '<span class="badge badge-danger">Sakit</span>',
            'izin' => '<span class="badge badge-info">Izin</span>',
            'cuti' => '<span class="badge badge-primary">Cuti</span>',
            default => '<span class="badge badge-secondary">-</span>',
        };
    }
}