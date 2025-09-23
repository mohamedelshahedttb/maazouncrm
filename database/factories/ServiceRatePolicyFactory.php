<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceRatePolicy>
 */
class ServiceRatePolicyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $min = $this->faker->randomElement([0, 1000, 5000, 10000]);
        $max = $min + $this->faker->randomElement([999, 4000, 5000]);
        return [
            'service_id' => \App\Models\Service::factory(),
            'mahr_min' => $min,
            'mahr_max' => $max,
            'fixed_fee' => $this->faker->randomFloat(2, 500, 5000),
            'is_active' => true,
        ];
    }
}
