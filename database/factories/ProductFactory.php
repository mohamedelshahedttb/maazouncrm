<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'category' => $this->faker->randomElement(['accessories', 'documents', 'gifts', 'other']),
            'description' => $this->faker->sentence(),
            'supplier_id' => null,
            'purchase_price' => $this->faker->randomFloat(2, 10, 1000),
            'selling_price' => $this->faker->randomFloat(2, 20, 1500),
            'currency' => 'EGP',
            'stock_quantity' => $this->faker->numberBetween(0, 100),
            'min_stock_level' => 10,
            'sku' => $this->faker->unique()->bothify('PRD-####'),
            'status' => 'active',
            'notes' => $this->faker->optional()->sentence(),
            'is_active' => true,
        ];
    }
}
