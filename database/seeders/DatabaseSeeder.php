<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@streamflix.com',
            'role' => 'admin',
        ]);

        // Create premium user
        User::factory()->create([
            'name' => 'Premium User',
            'email' => 'premium@streamflix.com',
            'role' => 'premium',
        ]);

        // Create basic user
        User::factory()->create([
            'name' => 'Basic User',
            'email' => 'user@streamflix.com',
            'role' => 'basic',
        ]);

        // Seed platform data
        $this->call([
            GenreSeeder::class,
            MovieSeeder::class,
            AdvertisementSeeder::class,
        ]);
    }
}
