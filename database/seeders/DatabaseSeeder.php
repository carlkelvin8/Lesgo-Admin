<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            PartnerSeeder::class,
            AddressSeeder::class,
            CustomerProfileSeeder::class,
            PartnerBranchSeeder::class,
            ServiceSeeder::class,
            DriverProfileSeeder::class,
            VehicleSeeder::class,
            OrderSeeder::class,
            PaymentSeeder::class,
            WalletSeeder::class,
        ]);
    }
}
