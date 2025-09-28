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

class ClientCreateFormTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test data
        $this->user = User::factory()->create();
        $this->service = Service::factory()->create(['is_active' => true]);
        $this->source = ClientSource::factory()->create(['is_active' => true]);
        $this->product = Product::factory()->create(['is_active' => true, 'status' => 'active']);
        $this->area = Area::factory()->create(['is_active' => true]);
    }

    /** @test */
    public function test_create_client_form_displays_correctly()
    {
        $response = $this->actingAs($this->user)->get('/clients/create');
        
        $response->assertStatus(200);
        $response->assertSee('إضافة عميل جديد');
        $response->assertSee('اكسسوارات العقد');
        $response->assertSee('نسبة الخصم');
        $response->assertSee('السعر النهائي');
        $response->assertSee('حالة العميل');
    }

    /** @test */
    public function test_date_fields_accept_dd_mm_yyyy_format()
    {
        $clientData = [
            'name' => 'أحمد محمد',
            'phone' => '01234567890',
            'status' => 'new',
            'event_date' => '25/12/2024',
            'next_follow_up_date' => '30/12/2024',
            'coupon_arrival_date' => '28/12/2024',
            'document_receipt_date' => '31/12/2024',
        ];

        $response = $this->actingAs($this->user)->post('/clients', $clientData);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('clients', [
            'name' => 'أحمد محمد',
            'event_date' => '2024-12-25',
            'next_follow_up_date' => '2024-12-30',
            'coupon_arrival_date' => '2024-12-28',
            'document_receipt_date' => '2024-12-31',
        ]);
    }

    /** @test */
    public function test_phone_and_groom_name_are_optional()
    {
        $clientData = [
            'name' => 'أحمد محمد',
            'status' => 'new',
            // phone and groom_name are not provided
        ];

        $response = $this->actingAs($this->user)->post('/clients', $clientData);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('clients', [
            'name' => 'أحمد محمد',
            'phone' => null,
            'groom_name' => null,
        ]);
    }

    /** @test */
    public function test_accessories_field_accepts_multiple_products()
    {
        $product2 = Product::factory()->create(['is_active' => true, 'status' => 'active']);
        
        $clientData = [
            'name' => 'أحمد محمد',
            'phone' => '01234567890',
            'status' => 'new',
            'accessories' => [$this->product->id, $product2->id],
        ];

        $response = $this->actingAs($this->user)->post('/clients', $clientData);
        
        $response->assertRedirect();
        $client = \App\Models\Client::where('name', 'أحمد محمد')->first();
        $this->assertEquals([$this->product->id, $product2->id], $client->accessories);
    }

    /** @test */
    public function test_discount_calculation_percentage()
    {
        $clientData = [
            'name' => 'أحمد محمد',
            'phone' => '01234567890',
            'status' => 'new',
            'service_id' => $this->service->id,
            'discount_type' => 'percentage',
            'discount_value' => 10,
        ];

        $response = $this->actingAs($this->user)->post('/clients', $clientData);
        
        $response->assertRedirect();
        $client = \App\Models\Client::where('name', 'أحمد محمد')->first();
        $this->assertEquals('percentage', $client->discount_type);
        $this->assertEquals(10, $client->discount_value);
    }

    /** @test */
    public function test_discount_calculation_fixed_amount()
    {
        $clientData = [
            'name' => 'أحمد محمد',
            'phone' => '01234567890',
            'status' => 'new',
            'service_id' => $this->service->id,
            'discount_type' => 'fixed_amount',
            'discount_value' => 50,
        ];

        $response = $this->actingAs($this->user)->post('/clients', $clientData);
        
        $response->assertRedirect();
        $client = \App\Models\Client::where('name', 'أحمد محمد')->first();
        $this->assertEquals('fixed_amount', $client->discount_type);
        $this->assertEquals(50, $client->discount_value);
    }

    /** @test */
    public function test_client_status_field_is_saved()
    {
        $clientData = [
            'name' => 'أحمد محمد',
            'phone' => '01234567890',
            'status' => 'new',
            'client_status' => 'in_progress',
        ];

        $response = $this->actingAs($this->user)->post('/clients', $clientData);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('clients', [
            'name' => 'أحمد محمد',
            'client_status' => 'in_progress',
        ]);
    }

    /** @test */
    public function test_contract_cost_field_is_removed()
    {
        $response = $this->actingAs($this->user)->get('/clients/create');
        
        $response->assertStatus(200);
        $response->assertDontSee('تكلفة العقد');
    }

    /** @test */
    public function test_pricing_section_order()
    {
        $response = $this->actingAs($this->user)->get('/clients/create');
        
        $response->assertStatus(200);
        
        // Check that area selection comes before calculated price
        $content = $response->getContent();
        $areaPosition = strpos($content, 'المنطقة (لحساب السعر)');
        $pricePosition = strpos($content, 'سعر الخدمة (محسوب تلقائياً)');
        
        $this->assertLessThan($pricePosition, $areaPosition);
    }

    /** @test */
    public function test_final_price_display_is_readonly()
    {
        $response = $this->actingAs($this->user)->get('/clients/create');
        
        $response->assertStatus(200);
        $response->assertSee('السعر النهائي');
        $response->assertSee('readonly', false); // Check for readonly attribute
    }

    /** @test */
    public function test_form_validation_rules()
    {
        $response = $this->actingAs($this->user)->post('/clients', []);
        
        $response->assertSessionHasErrors(['name', 'status']);
        $response->assertSessionDoesntHaveErrors(['phone', 'groom_name']);
    }

    /** @test */
    public function test_accessories_validation()
    {
        $clientData = [
            'name' => 'أحمد محمد',
            'status' => 'new',
            'accessories' => [99999], // Non-existent product ID
        ];

        $response = $this->actingAs($this->user)->post('/clients', $clientData);
        
        $response->assertSessionHasErrors(['accessories.0']);
    }
}
