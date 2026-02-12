<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Event;
use App\Models\Config;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed config tables first (includes Locations with their Floors)
        $this->call([
            ConfigSeeder::class,
        ]);

        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gms.church',
            'password' => Hash::make('password123'),
        ]);

        // Seed events with 1 week schedule
        $this->call([
            EventSeeder::class,
        ]);
    }
}
