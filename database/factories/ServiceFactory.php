<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    protected $model = Service::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['عقد زواج', 'عقد طلاق', 'تصديق مستندات', 'عقد وصية', 'عقد وكالة']),
            'category' => $this->faker->randomElement(['زواج', 'طلاق', 'تصديق', 'وصية', 'وكالة']),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 100, 5000),
            'currency' => 'SAR',
            'duration_minutes' => $this->faker->numberBetween(30, 480),
            'requirements' => $this->faker->optional()->paragraph(),
            'notes' => $this->faker->optional()->paragraph(),
            'is_active' => $this->faker->boolean(90),
        ];
    }
}