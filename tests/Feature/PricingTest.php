<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Area;
use App\Models\Service;
use App\Models\ServiceRatePolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PricingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    public function test_pricing_endpoint_calculates_correct_total()
    {
        $service = Service::factory()->create(['is_active' => true]);
        $area = Area::factory()->create([
            'transportation_fee' => 200.00,
            'mahr_percentage' => 2.5, // 2.5%
            'is_active' => true,
        ]);
        // Policy: for 1,000 - 5,000 => 1,800
        ServiceRatePolicy::factory()->create([
            'service_id' => $service->id,
            'mahr_min' => 1000,
            'mahr_max' => 5000,
            'fixed_fee' => 1800.00,
            'is_active' => true,
        ]);

        $mahr = 2000; // 2.5% = 50
        // total = transportation (200) + percentage (50) + fixed fee (1800) = 2050
        $response = $this->post(route('pricing.calculate'), [
            'service_id' => $service->id,
            'area_id' => $area->id,
            'mahr' => $mahr,
        ]);

        $response->assertOk();
        $response->assertJson(['price' => 2050.00]);
    }
}


