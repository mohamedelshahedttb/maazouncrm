<?php

namespace Database\Factories;

use App\Models\Partner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Partner>
 */
class PartnerFactory extends Factory
{
    protected $model = Partner::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'الشيخ ' . $this->faker->name(),
            'office_name' => 'مكتب الشيخ ' . $this->faker->lastName() . ' للعقود',
            'office_address' => $this->faker->address(),
            'agent_name' => $this->faker->name(),
            'agent_phone' => '05' . $this->faker->numerify('########'),
            'agent_email' => $this->faker->unique()->safeEmail(),
            'location_number' => $this->faker->bothify('LOC###'),
            'book_number' => $this->faker->bothify('BOOK###'),
            'document_number' => $this->faker->bothify('DOC###'),
            'license_number' => $this->faker->unique()->bothify('LIC######'),
            'service_scope' => $this->faker->randomElement(['الزواج', 'الطلاق', 'التصديق على المستندات', 'الوصية', 'الزواج,الطلاق', 'الزواج,الطلاق,التصديق على المستندات']),
            'phone' => '05' . $this->faker->numerify('########'),
            'email' => $this->faker->unique()->safeEmail(),
            'address' => $this->faker->address(),
            'commission_rate' => $this->faker->randomFloat(2, 5, 25),
            'status' => $this->faker->randomElement(['active', 'inactive', 'suspended']),
            'notes' => $this->faker->optional()->paragraph(),
            'is_active' => $this->faker->boolean(90),
        ];
    }
}