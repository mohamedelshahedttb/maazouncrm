<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Service;
use App\Models\ClientSource;
use App\Models\Product;
use App\Models\Area;

class ClientFormSubmissionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $service;
    protected $source;
    protected $product;
    protected $area;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->service = Service::factory()->create(['is_active' => true]);
        $this->source = ClientSource::factory()->create(['is_active' => true]);
        $this->product = Product::factory()->create(['is_active' => true, 'status' => 'active']);
        $this->area = Area::factory()->create(['is_active' => true]);
    }

    /**
     * @test
     */
    public function it_can_create_client_with_basic_information()
    {
        $clientData = [
            'name' => 'أحمد محمد',
            'groom_name' => 'أحمد',
            'bride_name' => 'فاطمة',
            'phone' => '01234567890',
            'client_status' => 'new',
            'event_date' => '28/09/2025',
            'mahr' => '50000',
            'area_id' => $this->area->id,
            'service_id' => $this->service->id,
            'source_id' => $this->source->id,
        ];

        $response = $this->actingAs($this->user)
            ->post(route('clients.store'), $clientData);

        $response->assertRedirect();
        $this->assertDatabaseHas('clients', [
            'name' => 'أحمد محمد',
            'groom_name' => 'أحمد',
            'bride_name' => 'فاطمة',
            'phone' => '01234567890',
            'client_status' => 'new',
        ]);
    }

    /**
     * @test
     */
    public function it_can_create_client_with_accessories()
    {
        $product2 = Product::factory()->create(['is_active' => true, 'status' => 'active']);
        
        $clientData = [
            'name' => 'محمد علي',
            'client_status' => 'new',
            'event_date' => '30/09/2025',
            'accessories' => [$this->product->id, $product2->id],
            'area_id' => $this->area->id,
            'service_id' => $this->service->id,
            'source_id' => $this->source->id,
        ];

        $response = $this->actingAs($this->user)
            ->post(route('clients.store'), $clientData);

        $response->assertRedirect();
        
        $client = \App\Models\Client::where('name', 'محمد علي')->first();
        $this->assertNotNull($client);
        $this->assertEquals([$this->product->id, $product2->id], $client->accessories);
    }

    /**
     * @test
     */
    public function it_can_create_client_with_discount_percentage()
    {
        $clientData = [
            'name' => 'سارة أحمد',
            'client_status' => 'new',
            'event_date' => '01/10/2025',
            'mahr' => '30000',
            'discount_type' => 'percentage',
            'discount_value' => '10',
            'area_id' => $this->area->id,
            'service_id' => $this->service->id,
            'source_id' => $this->source->id,
        ];

        $response = $this->actingAs($this->user)
            ->post(route('clients.store'), $clientData);

        $response->assertRedirect();
        
        $client = \App\Models\Client::where('name', 'سارة أحمد')->first();
        $this->assertNotNull($client);
        $this->assertEquals('percentage', $client->discount_type);
        $this->assertEquals(10, $client->discount_value);
    }

    /**
     * @test
     */
    public function it_can_create_client_with_delivery_man()
    {
        $clientData = [
            'name' => 'علي حسن',
            'client_status' => 'new',
            'event_date' => '02/10/2025',
            'document_receiver' => 'delivery',
            'delivery_man_name' => 'محمد الدليفري',
            'area_id' => $this->area->id,
            'service_id' => $this->service->id,
            'source_id' => $this->source->id,
        ];

        $response = $this->actingAs($this->user)
            ->post(route('clients.store'), $clientData);

        $response->assertRedirect();
        
        $client = \App\Models\Client::where('name', 'علي حسن')->first();
        $this->assertNotNull($client);
        $this->assertEquals('delivery', $client->document_receiver);
        $this->assertEquals('محمد الدليفري', $client->delivery_man_name);
    }

    /**
     * @test
     */
    public function it_can_create_client_with_client_relative()
    {
        $clientData = [
            'name' => 'نور الدين',
            'client_status' => 'new',
            'event_date' => '03/10/2025',
            'document_receiver' => 'client_relative',
            'client_relative_name' => 'أحمد العم',
            'area_id' => $this->area->id,
            'service_id' => $this->service->id,
            'source_id' => $this->source->id,
        ];

        $response = $this->actingAs($this->user)
            ->post(route('clients.store'), $clientData);

        $response->assertRedirect();
        
        $client = \App\Models\Client::where('name', 'نور الدين')->first();
        $this->assertNotNull($client);
        $this->assertEquals('client_relative', $client->document_receiver);
        $this->assertEquals('أحمد العم', $client->client_relative_name);
    }

    /**
     * @test
     */
    public function it_validates_required_fields()
    {
        $response = $this->actingAs($this->user)
            ->post(route('clients.store'), []);

        $response->assertSessionHasErrors(['name']);
    }

    /**
     * @test
     */
    public function it_validates_date_format()
    {
        $clientData = [
            'name' => 'Test Client',
            'client_status' => 'new',
            'event_date' => 'invalid-date',
            'area_id' => $this->area->id,
            'service_id' => $this->service->id,
            'source_id' => $this->source->id,
        ];

        $response = $this->actingAs($this->user)
            ->post(route('clients.store'), $clientData);

        $response->assertSessionHasErrors(['event_date']);
    }

    /**
     * @test
     */
    public function it_converts_date_format_correctly()
    {
        $clientData = [
            'name' => 'Date Test Client',
            'client_status' => 'new',
            'event_date' => '28/09/2025',
            'next_follow_up_date' => '30/09/2025',
            'area_id' => $this->area->id,
            'service_id' => $this->service->id,
            'source_id' => $this->source->id,
        ];

        $response = $this->actingAs($this->user)
            ->post(route('clients.store'), $clientData);

        $response->assertRedirect();
        
        $client = \App\Models\Client::where('name', 'Date Test Client')->first();
        $this->assertNotNull($client);
        $this->assertEquals('2025-09-28', $client->event_date->format('Y-m-d'));
        $this->assertEquals('2025-09-30', $client->next_follow_up_date->format('Y-m-d'));
    }
}
