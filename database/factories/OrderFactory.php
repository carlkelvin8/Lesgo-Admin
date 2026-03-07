<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $status = fake()->randomElement(['pending', 'accepted', 'picked_up', 'completed', 'cancelled']);
        $estimatedFare = fake()->randomFloat(2, 80, 500);
        $actualFare = $status === 'completed' ? $estimatedFare * fake()->randomFloat(2, 0.9, 1.1) : null;

        return [
            'status' => $status,
            'payment_method' => fake()->randomElement(['cash', 'gcash', 'maya', 'card']),
            'payment_status' => $status === 'completed' ? 'paid' : 'pending',
            'estimated_distance_m' => fake()->numberBetween(500, 50000),
            'actual_distance_m' => $status === 'completed' ? fake()->numberBetween(500, 50000) : null,
            'estimated_fare' => $estimatedFare,
            'actual_fare' => $actualFare,
            'partner_share' => $actualFare ? $actualFare * 0.80 : null,
            'driver_share' => $actualFare ? $actualFare * 0.15 : null,
            'platform_fee' => $actualFare ? $actualFare * 0.05 : null,
            'scheduled_at' => fake()->dateTimeBetween('-30 days', '+7 days'),
            'completed_at' => $status === 'completed' ? fake()->dateTimeBetween('-30 days', 'now') : null,
            'cancelled_at' => $status === 'cancelled' ? fake()->dateTimeBetween('-30 days', 'now') : null,
        ];
    }
}
