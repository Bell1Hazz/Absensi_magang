<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Mentor
        User::create([
            'name' => 'Admin 2',
            'email' => 'mentor2@example.com',
            'password' => 'password',
            'role' => 'mentor',
            'shift' => 'pagi',
            'lokasi' => 'KANTOR',
        ]);

        // Magang
        
        User::create([
            'name' => 'Benny Putra',
            'email' => 'Benny@example.com',
            'password' => 'password',
            'role' => 'magang',
            'shift' => 'sore',
            'lokasi' => 'KANTOR',
        ]);
    }
    
}