<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        $services = [
            ['code' => 'MOTOR', 'name' => 'Motorcycle Delivery'],
            ['code' => 'CAR', 'name' => 'Car Delivery'],
            ['code' => 'VAN', 'name' => 'Van Delivery'],
            ['code' => 'TRUCK', 'name' => 'Truck Delivery'],
            ['code' => 'BIKE', 'name' => 'Bicycle Courier'],
        ];
        $service = fake()->unique()->randomElement($services);
        return [
            'code' => $service['code'] . '_' . fake()->unique()->randomNumber(3),
            'name' => $service['name'],
            'description' => fake()->sentence(),
            'base_fare' => fake()->randomFloat(2, 30, 100),
            'per_km_rate' => fake()->randomFloat(2, 5, 20),
            'per_minute_rate' => fake()->randomFloat(2, 1, 5),
            'minimum_fare' => fake()->randomFloat(2, 50, 150),
            'is_active' => fake()->boolean(80),
        ];
    }
}
