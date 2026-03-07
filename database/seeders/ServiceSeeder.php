<?php

namespace Database\Seeders;

use App\Models\Partner;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $baseServices = [
            [
                'code' => 'MOTOR_STD',
                'name' => 'Motorcycle Standard',
                'description' => 'Standard motorcycle delivery for small to medium packages.',
                'base_fare' => 49.00,
                'per_km_rate' => 10.00,
                'per_minute_rate' => 1.50,
                'minimum_fare' => 79.00,
                'is_active' => true,
            ],
            [
                'code' => 'CAR_STD',
                'name' => 'Car Standard',
                'description' => 'Standard car delivery for medium to large packages.',
                'base_fare' => 99.00,
                'per_km_rate' => 18.00,
                'per_minute_rate' => 2.00,
                'minimum_fare' => 149.00,
                'is_active' => true,
            ],
            [
                'code' => 'VAN_STD',
                'name' => 'Van Delivery',
                'description' => 'Van delivery for large and bulky items.',
                'base_fare' => 199.00,
                'per_km_rate' => 25.00,
                'per_minute_rate' => 3.00,
                'minimum_fare' => 299.00,
                'is_active' => true,
            ],
        ];

        foreach ($baseServices as $service) {
            Service::firstOrCreate(['code' => $service['code']], $service);
        }

        $partners = Partner::all();
        foreach ($partners as $partner) {
            Service::factory()->count(2)->create(['partner_id' => $partner->id]);
        }
    }
}
