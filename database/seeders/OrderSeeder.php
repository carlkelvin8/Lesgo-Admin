<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Partner;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $services = Service::all();
        $partners = Partner::all();

        if ($customers->isEmpty() || $services->isEmpty()) {
            return;
        }

        for ($i = 0; $i < 30; $i++) {
            Order::factory()->create([
                'customer_id' => $customers->random()->id,
                'service_id' => $services->random()->id,
                'partner_id' => $partners->isNotEmpty() ? $partners->random()->id : null,
            ]);
        }
    }
}
