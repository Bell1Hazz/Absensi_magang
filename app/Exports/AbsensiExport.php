<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class AbsensiExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $startDate;
    protected $endDate;
    protected $userId;
    
    public function __construct($startDate = null, $endDate = null, $userId = null)
    {
        $this->startDate = $startDate ?: Carbon::now()->startOfMonth();
        $this->endDate = $endDate ?: Carbon::now()->endOfMonth();
        $this->userId = $userId;
    }
    
    public function collection()
    {
        $query = Absensi::with('user')
                       ->whereBetween('tanggal', [$this->startDate, $this->endDate]);
        
        if ($this->userId) {
            $query->where('user_id', $this->userId);
        }
        
        return $query->orderBy('tanggal', 'desc')
                    ->orderBy('user_id')
                    ->get();
    }
    
    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'Tanggal',
            'Hari',
            'Shift',
            'Jam Masuk',
            'Lokasi Masuk',
            'Jam Pulang',
            'Lokasi Pulang',
            'Status',
            'Total Jam Kerja',
            'Keterangan'
        ];
    }
    
    public function map($absensi): array
    {
        static $no = 0;
        $no++;
        
        // Hitung total jam kerja
        $totalJam = '-';
        if ($absensi->jam_masuk && $absensi->jam_pulang) {
            $masuk = Carbon::createFromFormat('H:i:s', $absensi->jam_masuk);
            $pulang = Carbon::createFromFormat('H:i:s', $absensi->jam_pulang);
            $diff = $pulang->diff($masuk);
            $totalJam = $diff->format('%H:%I');
        }
        
        return [
            $no,
            $absensi->user->name,
            $absensi->tanggal->format('d M Y'),
            $absensi->tanggal->format('l'),
            ucfirst($absensi->user->shift),
            $absensi->jam_masuk ?? '-',
            $absensi->lokasi_masuk ?? '-',
            $absensi->jam_pulang ?? '-',
            $absensi->lokasi_pulang ?? '-',
            ucfirst($absensi->status),
            $totalJam,
            $absensi->keterangan ?? '-'
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}