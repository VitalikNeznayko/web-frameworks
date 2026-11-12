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
        $users = [
            ['name' => 'Client', 'email' => 'client@hostel.com', 'password' => 'client123', 'role' => User::ROLE_CLIENT],
            ['name' => 'Manager', 'email' => 'manager@hostel.com', 'password' => 'manager123', 'role' => User::ROLE_MANAGER],
            ['name' => 'Admin', 'email' => 'admin@hostel.com', 'password' => 'admin123', 'role' => User::ROLE_ADMIN],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(['email' => $user['email']], $user);
        }
    }
}
