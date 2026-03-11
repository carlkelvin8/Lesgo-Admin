<?php

namespace Database\Seeders;

use App\Models\Partner;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $partners = Partner::where('status', 'active')->get();

        $services = [
            [
                'code' => 'STANDARD',
                'name' => 'Standard Delivery',
                'description' => 'Regular delivery service for everyday needs',
                'base_fare' => 50.00,
                'per_km_rate' => 15.00,
                'per_minute_rate' => 2.00,
                'minimum_fare' => 80.00,
            ],
            [
                'code' => 'EXPRESS',
                'name' => 'Express Delivery',
                'description' => 'Fast delivery for urgent packages',
                'base_fare' => 80.00,
                'per_km_rate' => 20.00,
                'per_minute_rate' => 3.00,
                'minimum_fare' => 120.00,
            ],
            [
                'code' => 'PREMIUM',
                'name' => 'Premium Service',
                'description' => 'Priority handling with insurance',
                'base_fare' => 120.00,
                'per_km_rate' => 25.00,
                'per_minute_rate' => 4.00,
                'minimum_fare' => 180.00,
            ],
            [
                'code' => 'SAMEDAY',
                'name' => 'Same Day Delivery',
                'description' => 'Guaranteed same-day delivery',
                'base_fare' => 100.00,
                'per_km_rate' => 22.00,
                'per_minute_rate' => 3.50,
                'minimum_fare' => 150.00,
            ],
            [
                'code' => 'BULK',
                'name' => 'Bulk Delivery',
                'description' => 'For multiple packages or large items',
                'base_fare' => 150.00,
                'per_km_rate' => 18.00,
                'per_minute_rate' => 2.50,
                'minimum_fare' => 200.00,
            ],
        ];

        foreach ($partners as $partner) {
            foreach ($services as $serviceData) {
                Service::create([
                    'partner_id' => $partner->id,
                    'code' => $partner->slug . '-' . $serviceData['code'],
                    'name' => $serviceData['name'],
                    'description' => $serviceData['description'],
                    'base_fare' => $serviceData['base_fare'],
                    'per_km_rate' => $serviceData['per_km_rate'],
                    'per_minute_rate' => $serviceData['per_minute_rate'],
                    'minimum_fare' => $serviceData['minimum_fare'],
                    'is_active' => rand(0, 10) > 1, // 90% active
                ]);
            }
        }
    }
}
