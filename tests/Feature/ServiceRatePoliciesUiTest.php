<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Service;
use App\Models\ServiceRatePolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServiceRatePoliciesUiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    public function test_can_add_and_update_rate_policies_from_service_edit()
    {
        $service = Service::factory()->create([
            'is_active' => true,
            'category' => 'marriage',
            'currency' => 'EGP',
            'price' => 0,
        ]);

        // Add a policy via update
        $payload = [
            'name' => $service->name,
            'category' => 'marriage',
            'description' => $service->description,
            'price' => 0,
            'currency' => 'EGP',
            'duration_minutes' => $service->duration_minutes,
            'requirements' => $service->requirements,
            'notes' => $service->notes,
            'is_active' => 1,
            'rate_policies' => [
                [
                    'mahr_min' => 1000,
                    'mahr_max' => 5000,
                    'fixed_fee' => 1800,
                    'is_active' => 1,
                ]
            ],
        ];

        $this->put(route('services.update', $service), $payload)->assertRedirect(route('services.index'));
        $this->assertDatabaseHas('service_rate_policies', [
            'service_id' => $service->id,
            'mahr_min' => 1000,
            'mahr_max' => 5000,
            'fixed_fee' => 1800,
        ]);

        $policy = ServiceRatePolicy::first();

        // Update the policy via edit form
        $payload['rate_policies'] = [[
            'id' => $policy->id,
            'mahr_min' => 2000,
            'mahr_max' => 6000,
            'fixed_fee' => 1900,
            'is_active' => 1,
        ]];
        $this->put(route('services.update', $service), $payload)->assertRedirect(route('services.index'));
        $this->assertDatabaseHas('service_rate_policies', [
            'id' => $policy->id,
            'mahr_min' => 2000,
            'mahr_max' => 6000,
            'fixed_fee' => 1900,
        ]);
    }
}


