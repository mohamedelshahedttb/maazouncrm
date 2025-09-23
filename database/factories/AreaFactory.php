<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Area>
 */
class AreaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->city(),
            'transportation_fee' => $this->faker->randomFloat(2, 0, 500),
            'mahr_percentage' => $this->faker->randomElement([null, $this->faker->randomFloat(2, 0, 10)]),
            'is_active' => true,
        ];
    }
}
