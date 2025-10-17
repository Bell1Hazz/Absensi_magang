<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Absensi;
use Carbon\Carbon;
use Faker\Factory as Faker;

class FakeAbsensiSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        
        // Get all magang users
        $magangUsers = User::where('role', 'magang')->get();
        
        // Generate absensi untuk 30 hari terakhir
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();
        
        foreach ($magangUsers as $user) {
            $currentDate = $startDate->copy();
            
            while ($currentDate <= $endDate) {
                // Skip weekend (opsional)
                if ($currentDate->isWeekend()) {
                    $currentDate->addDay();
                    continue;
                }
                
                // 85% chance user hadir
                if ($faker->boolean(85)) {
                    // Generate jam masuk (07:30 - 09:00)
                    $jamMasuk = $faker->time('H:i:s', '09:00');
                    
                    // Status berdasarkan jam masuk
                    $status = Carbon::createFromFormat('H:i:s', $jamMasuk)->format('H:i') > '08:00' ? 'terlambat' : 'hadir';
                    
                    // Generate jam pulang (16:00 - 18:00) - 90% chance ada jam pulang
                    $jamPulang = $faker->boolean(90) ? $faker->time('H:i:s', '18:00') : null;
                    
                    // GPS coordinates (random dalam radius kantor atau diluar)
                    $isInOffice = $faker->boolean(80); // 80% di kantor
                    
                    if ($isInOffice) {
                        // Koordinat dalam radius kantor
                        $latMasuk = $faker->latitude(-7.846, -7.844);
                        $lonMasuk = $faker->longitude(110.360, 110.363);
                        $lokasiMasuk = 'KANTOR';
                    } else {
                        // Koordinat diluar kantor
                        $latMasuk = $faker->latitude(-7.9, -7.7);
                        $lonMasuk = $faker->longitude(110.2, 110.5);
                        $lokasiMasuk = 'DILUAR KANTOR';
                    }
                    
                    // Koordinat pulang (bisa beda)
                    $latPulang = $jamPulang ? $faker->latitude(-7.85, -7.84) : null;
                    $lonPulang = $jamPulang ? $faker->longitude(110.35, 110.37) : null;
                    $lokasiPulang = $jamPulang ? ($faker->boolean(75) ? 'KANTOR' : 'DILUAR KANTOR') : null;
                    
                    Absensi::create([
                        'user_id' => $user->id,
                        'tanggal' => $currentDate->format('Y-m-d'),
                        'jam_masuk' => $jamMasuk,
                        'jam_pulang' => $jamPulang,
                        'status' => $status,
                        'latitude_masuk' => $latMasuk,
                        'longitude_masuk' => $lonMasuk,
                        'latitude_pulang' => $latPulang,
                        'longitude_pulang' => $lonPulang,
                        'lokasi_masuk' => $lokasiMasuk,
                        'lokasi_pulang' => $lokasiPulang,
                        'created_at' => $currentDate,
                        'updated_at' => $currentDate
                    ]);
                }
                // 15% tidak hadir (tidak ada record)
                
                $currentDate->addDay();
            }
        }
    }
}