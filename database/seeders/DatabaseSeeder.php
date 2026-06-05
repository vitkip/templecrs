<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@temple.org',
        ]);

        // Seed departments first, then personnel
        $this->call([
            DepartmentSeeder::class,
            PersonnelSeeder::class,
            SettingsSeeder::class,
        ]);
    }
}
