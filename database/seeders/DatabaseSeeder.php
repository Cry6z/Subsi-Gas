<?php

namespace Database\Seeders;

use App\Models\Location;
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
        $adminEmail = env('ADMIN_EMAIL', 'admin@example.com');

        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => $adminEmail,
            'role' => 'admin',
        ]);

        $distributors = User::factory(3)->create([
            'role' => 'distributor',
            'created_by_admin_id' => $admin->id,
            'is_active' => true,
        ]);

        foreach ($distributors as $distributor) {
            Location::factory(3)->create([
                'distributor_id' => $distributor->id,
            ]);
        }
    }
}
