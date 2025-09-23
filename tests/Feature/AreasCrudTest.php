<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Area;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AreasCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    public function test_can_list_and_create_edit_delete_area()
    {
        // Index
        $this->get(route('areas.index'))->assertOk();

        // Create
        $data = [
            'name' => 'المعادي',
            'transportation_fee' => 150.50,
            'mahr_percentage' => 2.5,
            'is_active' => true,
        ];
        $this->post(route('areas.store'), $data)->assertRedirect(route('areas.index'));
        $this->assertDatabaseHas('areas', ['name' => 'المعادي']);

        $area = Area::first();

        // Edit
        $update = [
            'name' => 'المعادي الجديدة',
            'transportation_fee' => 200,
            'mahr_percentage' => null,
            'is_active' => false,
        ];
        $this->put(route('areas.update', $area), $update)->assertRedirect(route('areas.index'));
        $this->assertDatabaseHas('areas', ['name' => 'المعادي الجديدة', 'is_active' => false]);

        // Delete
        $this->delete(route('areas.destroy', $area))->assertRedirect(route('areas.index'));
        $this->assertDatabaseMissing('areas', ['id' => $area->id]);
    }
}


