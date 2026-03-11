<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();

        $addresses = [
            ['label' => 'Home', 'address_line1' => '123 Rizal Street', 'city' => 'Manila', 'latitude' => 14.5995, 'longitude' => 120.9842],
            ['label' => 'Office', 'address_line1' => '456 Ayala Avenue', 'city' => 'Makati', 'latitude' => 14.5547, 'longitude' => 121.0244],
            ['label' => 'Home', 'address_line1' => '789 Bonifacio Drive', 'city' => 'Quezon City', 'latitude' => 14.6760, 'longitude' => 121.0437],
            ['label' => 'Work', 'address_line1' => '321 Ortigas Avenue', 'city' => 'Pasig', 'latitude' => 14.5832, 'longitude' => 121.0610],
            ['label' => 'Home', 'address_line1' => '654 Taft Avenue', 'city' => 'Manila', 'latitude' => 14.5547, 'longitude' => 120.9937],
        ];

        foreach ($customers as $customer) {
            $addressData = $addresses[array_rand($addresses)];
            
            Address::create([
                'user_id' => $customer->id,
                'label' => $addressData['label'],
                'contact_name' => $customer->name,
                'contact_phone' => $customer->phone_number,
                'address_line1' => $addressData['address_line1'],
                'address_line2' => 'Unit ' . rand(100, 999),
                'city' => $addressData['city'],
                'region' => 'Metro Manila',
                'country' => 'Philippines',
                'postal_code' => '1' . rand(100, 999),
                'latitude' => $addressData['latitude'],
                'longitude' => $addressData['longitude'],
                'is_default' => true,
            ]);

            // Add a second address for some customers
            if (rand(0, 1)) {
                $secondAddress = $addresses[array_rand($addresses)];
                Address::create([
                    'user_id' => $customer->id,
                    'label' => $secondAddress['label'] === 'Home' ? 'Office' : 'Home',
                    'contact_name' => $customer->name,
                    'contact_phone' => $customer->phone_number,
                    'address_line1' => $secondAddress['address_line1'],
                    'address_line2' => 'Floor ' . rand(1, 20),
                    'city' => $secondAddress['city'],
                    'region' => 'Metro Manila',
                    'country' => 'Philippines',
                    'postal_code' => '1' . rand(100, 999),
                    'latitude' => $secondAddress['latitude'],
                    'longitude' => $secondAddress['longitude'],
                    'is_default' => false,
                ]);
            }
        }
    }
}
