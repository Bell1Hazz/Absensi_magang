<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Mentor
        User::create([
            'name' => 'Admin Mentor',
            'email' => 'mentor@example.com',
            'password' => Hash::make('password'), // Manual hash
            'role' => 'mentor',
            'shift' => 'pagi',
            'lokasi' => 'KANTOR',
        ]);

        // Magang
        User::create([
            'name' => 'Abel Hazza Fredella',
            'email' => 'abel@example.com',
            'password' => Hash::make('password'), // Manual hash
            'role' => 'magang',
            'shift' => 'pagi',
            'lokasi' => 'KANTOR',
        ]);

        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => Hash::make('password'), // Manual hash
            'role' => 'magang',
            'shift' => 'sore',
            'lokasi' => 'KANTOR',
        ]);

        User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@example.com',
            'password' => Hash::make('password'), // Manual hash
            'role' => 'magang',
            'shift' => 'pagi',
            'lokasi' => 'KANTOR',
        ]);
    }
}