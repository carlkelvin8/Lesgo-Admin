<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@lesgo.ph'],
            [
                'name' => 'System Admin',
                'phone_number' => '09000000001',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'staff@lesgo.ph'],
            [
                'name' => 'Staff User',
                'phone_number' => '09000000002',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'email_verified_at' => now(),
            ]
        );

        User::factory()->customer()->count(20)->create();
        User::factory()->driver()->count(10)->create();
        User::factory()->partner()->count(5)->create();
    }
}
