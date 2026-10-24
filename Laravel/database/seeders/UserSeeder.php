<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Адміністратор', 'email' => 'admin@hostel.test', 'password' => 'admin123', 'role' => User::ROLE_ADMIN],
            ['name' => 'Менеджер', 'email' => 'manager@hostel.test', 'password' => 'manager123', 'role' => User::ROLE_MANAGER],
            ['name' => 'Клієнт', 'email' => 'client@hostel.test', 'password' => 'client123', 'role' => User::ROLE_CLIENT],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => $user['password'],
                    'role' => $user['role'],
                ],
            );
        }
    }
}
