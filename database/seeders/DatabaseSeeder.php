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
        // User::factory(10)->create();

        User::factory()->create([
            'full_name' => 'Test User',
            'mobile_number' => '1234567890',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        // Seed categories
        $this->call(CategorySeeder::class);
    }
}
