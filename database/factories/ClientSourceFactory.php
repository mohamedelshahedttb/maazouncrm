<?php

namespace Database\Factories;

use App\Models\ClientSource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClientSource>
 */
class ClientSourceFactory extends Factory
{
    protected $model = ClientSource::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['الموقع الإلكتروني', 'وسائل التواصل الاجتماعي', 'الإعلانات', 'التوصية', 'المعرض', 'الهاتف المباشر']),
            'type' => $this->faker->randomElement(['online', 'offline', 'referral']),
            'description' => $this->faker->optional()->paragraph(),
            'is_active' => $this->faker->boolean(90),
        ];
    }
}