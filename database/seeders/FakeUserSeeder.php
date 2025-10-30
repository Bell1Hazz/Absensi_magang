<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class FakeUserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Indonesian locale
        
        // Generate 50 user magang fake
        for ($i = 1; $i <= 50; $i++) {
            $name = $faker->name;
            $email = strtolower(str_replace(' ', '.', $name)) . $i . '@magang.com';
            
            User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'magang',
                'shift' => $faker->randomElement(['pagi', 'sore']),
                'lokasi' => $faker->randomElement(['KANTOR', 'REMOTE', 'CABANG SOLO', 'CABANG SEMARANG']),
                'created_at' => $faker->dateTimeBetween('-3 months', 'now'),
                'updated_at' => now()
            ]);
        }
        
        // Generate 5 mentor
        for ($i = 1; $i <= 5; $i++) {
            $name = $faker->name;
            $email = 'mentor' . $i . '@company.com';
            
            User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'mentor',
                'shift' => 'pagi',
                'lokasi' => 'KANTOR',
                'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
                'updated_at' => now()
            ]);
        }
    }
}