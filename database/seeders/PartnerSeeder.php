<?php

namespace Database\Seeders;

use App\Models\Partner;
use App\Models\User;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        $partnerUsers = User::where('role', 'partner')->get();

        $partners = [
            [
                'name' => 'FastMove Logistics',
                'legal_name' => 'FastMove Logistics Inc.',
                'slug' => 'fastmove-logistics',
                'business_type' => 'logistics',
                'tax_id' => 'TIN-123-456-789',
                'store_address' => '123 Logistics Ave, Makati City, Metro Manila, Philippines',
                'support_phone' => '+63 2 8123 4567',
                'status' => 'active',
            ],
            [
                'name' => 'QuickDeliver Express',
                'legal_name' => 'QuickDeliver Express Corporation',
                'slug' => 'quickdeliver-express',
                'business_type' => 'delivery',
                'tax_id' => 'TIN-987-654-321',
                'store_address' => '456 Express Blvd, Quezon City, Metro Manila, Philippines',
                'support_phone' => '+63 2 8234 5678',
                'status' => 'active',
            ],
            [
                'name' => 'Metro Transport Services',
                'legal_name' => 'Metro Transport Services Ltd.',
                'slug' => 'metro-transport',
                'business_type' => 'transport',
                'tax_id' => 'TIN-456-789-123',
                'store_address' => '789 Transport St, Pasig City, Metro Manila, Philippines',
                'support_phone' => '+63 2 8345 6789',
                'status' => 'active',
            ],
            [
                'name' => 'Swift Courier',
                'legal_name' => 'Swift Courier Services Inc.',
                'slug' => 'swift-courier',
                'business_type' => 'courier',
                'tax_id' => 'TIN-789-123-456',
                'store_address' => '101 Courier Road, Taguig City, Metro Manila, Philippines',
                'support_phone' => '+63 2 8456 7890',
                'status' => 'pending',
            ],
        ];

        foreach ($partners as $index => $partnerData) {
            Partner::create(array_merge($partnerData, [
                'user_id' => $partnerUsers[$index % $partnerUsers->count()]->id ?? null,
            ]));
        }
    }
}
