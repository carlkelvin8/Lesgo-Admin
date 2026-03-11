<?php

namespace Database\Seeders;

use App\Models\DriverProfile;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Database\Seeder;

class DriverProfileSeeder extends Seeder
{
    public function run(): void
    {
        $drivers = User::where('role', 'driver')->get();
        $partners = Partner::where('status', 'active')->get();

        foreach ($drivers as $index => $driver) {
            DriverProfile::create([
                'user_id' => $driver->id,
                'partner_id' => $partners[$index % $partners->count()]->id,
                'status' => ['active', 'active', 'active', 'inactive', 'pending'][array_rand(['active', 'active', 'active', 'inactive', 'pending'])],
                'rating' => rand(35, 50) / 10, // 3.5 to 5.0
                'total_trips' => rand(10, 500),
                'license_number' => 'DL-' . rand(1000000, 9999999),
                'license_expiry_date' => now()->addYears(rand(1, 5)),
                'id_document_path' => 'documents/driver-' . $driver->id . '-id.pdf',
                'last_latitude' => 14.5995 + (rand(-100, 100) / 1000),
                'last_longitude' => 120.9842 + (rand(-100, 100) / 1000),
            ]);
        }
    }
}
