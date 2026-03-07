<?php

namespace Database\Factories;

use App\Models\Partner;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PartnerFactory extends Factory
{
    protected $model = Partner::class;

    public function definition(): array
    {
        $name = fake()->company();
        return [
            'name' => $name,
            'legal_name' => $name . ' Inc.',
            'slug' => Str::slug($name) . '-' . fake()->unique()->randomNumber(4),
            'business_type' => fake()->randomElement(['logistics', 'delivery', 'transport', 'courier']),
            'tax_id' => fake()->numerify('###-###-###-###'),
            'support_email' => fake()->companyEmail(),
            'support_phone' => fake()->numerify('09#########'),
            'status' => fake()->randomElement(['active', 'pending', 'suspended']),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'active']);
    }
}
