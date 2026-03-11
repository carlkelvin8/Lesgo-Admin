<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User (already created, but ensure it exists)
        User::firstOrCreate(
            ['email' => 'admin@lesgo.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone_number' => '+63 917 123 4567',
                'email_verified_at' => now(),
            ]
        );

        // Staff Users
        User::create([
            'name' => 'Maria Santos',
            'email' => 'maria.santos@lesgo.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'phone_number' => '+63 917 234 5678',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Juan Dela Cruz',
            'email' => 'juan.delacruz@lesgo.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'phone_number' => '+63 917 345 6789',
            'email_verified_at' => now(),
        ]);

        // Partner Users
        $partner1 = User::create([
            'name' => 'FastMove Logistics',
            'email' => 'contact@fastmove.com',
            'password' => Hash::make('password'),
            'role' => 'partner',
            'phone_number' => '+63 917 456 7890',
            'email_verified_at' => now(),
        ]);

        $partner2 = User::create([
            'name' => 'QuickDeliver Express',
            'email' => 'info@quickdeliver.com',
            'password' => Hash::make('password'),
            'role' => 'partner',
            'phone_number' => '+63 917 567 8901',
            'email_verified_at' => now(),
        ]);

        // Driver Users
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => "Driver {$i}",
                'email' => "driver{$i}@lesgo.com",
                'password' => Hash::make('password'),
                'role' => 'driver',
                'phone_number' => '+63 917 ' . str_pad($i * 111, 7, '0', STR_PAD_LEFT),
                'email_verified_at' => now(),
            ]);
        }

        // Customer Users
        $customerNames = [
            'Pedro Reyes', 'Ana Garcia', 'Carlos Mendoza', 'Sofia Torres',
            'Miguel Ramos', 'Isabella Cruz', 'Diego Flores', 'Valentina Lopez',
            'Gabriel Santos', 'Camila Rivera', 'Lucas Morales', 'Emma Diaz',
            'Mateo Hernandez', 'Olivia Castro', 'Sebastian Ortiz'
        ];

        foreach ($customerNames as $index => $name) {
            User::create([
                'name' => $name,
                'email' => strtolower(str_replace(' ', '.', $name)) . '@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone_number' => '+63 917 ' . str_pad(($index + 1) * 222, 7, '0', STR_PAD_LEFT),
                'email_verified_at' => now(),
            ]);
        }
    }
}
