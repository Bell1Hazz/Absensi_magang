<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $role;
    
    public function __construct($role = null)
    {
        $this->role = $role;
    }
    
    public function collection()
    {
        $query = User::query();
        
        if ($this->role) {
            $query->where('role', $this->role);
        }
        
        return $query->orderBy('created_at', 'desc')->get();
    }
    
    public function headings(): array
    {
        return [
            'No',
            'Nama Lengkap',
            'Email',
            'Role',
            'Shift',
            'Lokasi',
            'Tanggal Daftar',
            'Status Foto Profil'
        ];
    }
    
    public function map($user): array
    {
        static $no = 0;
        $no++;
        
        return [
            $no,
            $user->name,
            $user->email,
            ucfirst($user->role),
            ucfirst($user->shift),
            $user->lokasi,
            $user->created_at->format('d M Y'),
            $user->hasProfilePhoto() ? 'Ada' : 'Tidak Ada'
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}