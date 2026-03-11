<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\CustomerProfile;
use App\Models\User;
use Illuminate\Database\Seeder;

class CustomerProfileSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();

        foreach ($customers as $customer) {
            $defaultAddress = Address::where('user_id', $customer->id)
                ->where('is_default', true)
                ->first();

            CustomerProfile::create([
                'user_id' => $customer->id,
                'date_of_birth' => now()->subYears(rand(20, 60))->subDays(rand(0, 365)),
                'gender' => ['male', 'female'][array_rand(['male', 'female'])],
                'default_address_id' => $defaultAddress?->id,
            ]);
        }
    }
}
