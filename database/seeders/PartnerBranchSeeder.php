<?php

namespace Database\Seeders;

use App\Models\Partner;
use App\Models\PartnerBranch;
use Illuminate\Database\Seeder;

class PartnerBranchSeeder extends Seeder
{
    public function run(): void
    {
        $partners = Partner::all();

        $branches = [
            ['name' => 'Main Branch', 'city' => 'Manila', 'latitude' => 14.5995, 'longitude' => 120.9842, 'is_primary' => true],
            ['name' => 'Makati Branch', 'city' => 'Makati', 'latitude' => 14.5547, 'longitude' => 121.0244, 'is_primary' => false],
            ['name' => 'Quezon City Branch', 'city' => 'Quezon City', 'latitude' => 14.6760, 'longitude' => 121.0437, 'is_primary' => false],
            ['name' => 'BGC Branch', 'city' => 'Taguig', 'latitude' => 14.5176, 'longitude' => 121.0509, 'is_primary' => false],
        ];

        foreach ($partners as $partner) {
            foreach ($branches as $index => $branchData) {
                PartnerBranch::create([
                    'partner_id' => $partner->id,
                    'name' => $partner->name . ' - ' . $branchData['name'],
                    'phone_number' => '+63 2 8' . rand(100, 999) . ' ' . rand(1000, 9999),
                    'address_line1' => rand(100, 999) . ' ' . ['Ayala Ave', 'EDSA', 'Ortigas Ave', 'Bonifacio Dr'][array_rand(['Ayala Ave', 'EDSA', 'Ortigas Ave', 'Bonifacio Dr'])],
                    'address_line2' => 'Building ' . chr(65 + rand(0, 5)),
                    'city' => $branchData['city'],
                    'region' => 'Metro Manila',
                    'country' => 'Philippines',
                    'postal_code' => '1' . rand(100, 999),
                    'latitude' => $branchData['latitude'],
                    'longitude' => $branchData['longitude'],
                    'is_primary' => $branchData['is_primary'],
                    'opening_hours' => [
                        'monday' => '08:00-18:00',
                        'tuesday' => '08:00-18:00',
                        'wednesday' => '08:00-18:00',
                        'thursday' => '08:00-18:00',
                        'friday' => '08:00-18:00',
                        'saturday' => '09:00-15:00',
                        'sunday' => 'Closed',
                    ],
                ]);

                // Only create 2-3 branches per partner
                if ($index >= rand(1, 2)) {
                    break;
                }
            }
        }
    }
}
