<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    protected $fillable = [
        'user_id', 'tanggal', 'jam_masuk', 'jam_pulang', 'status', 'keterangan',
        'latitude_masuk', 'longitude_masuk', 'latitude_pulang', 'longitude_pulang',
        'lokasi_masuk', 'lokasi_pulang'
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date'
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // GPS Calculation dengan koordinat yang BENAR
    public static function calculateDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 6371000; // meters

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon/2) * sin($dLon/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance = $earthRadius * $c;

        return round($distance, 2);
    }

    // Lokasi detection dengan koordinat YANG BENAR
    public static function determineLocation($latitude, $longitude): string
    {
        // KOORDINAT KANTOR YANG BENAR
        $officeLatitude = -7.845969257804157;
        $officeLongitude = 110.36214269617226;
        $maxDistance = 1000; // 1 km

        $distance = self::calculateDistance(
            (float) $latitude, 
            (float) $longitude,
            $officeLatitude, 
            $officeLongitude
        );

        \Log::info('GPS Check (CORRECT COORDINATES):', [
            'user_coordinates' => "$latitude, $longitude",
            'office_coordinates' => "$officeLatitude, $officeLongitude",
            'distance_meters' => $distance,
            'max_distance' => $maxDistance,
            'result' => $distance <= $maxDistance ? 'KANTOR' : 'DILUAR KANTOR'
        ]);

        return $distance <= $maxDistance ? 'KANTOR' : 'DILUAR KANTOR';
    }
}