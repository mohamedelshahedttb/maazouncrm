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
        // Create admin user with full permissions
        $this->call([
            AdminUserSeeder::class,
            ClientSourceSeeder::class,
            RequiredDocumentsSeeder::class,
            ServiceExecutionStepsSeeder::class,
        ]);

        // Create additional test users if needed
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'staff',
            'is_active' => true,
        ]);
    }
}
