<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Client;
use App\Models\ClientSource;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClientFormFieldsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    public function test_client_create_form_has_all_required_fields()
    {
        // Create test data
        $service = Service::factory()->create(['is_active' => true]);
        $source = ClientSource::factory()->create(['is_active' => true]);

        $response = $this->get(route('clients.create'));

        $response->assertStatus(200);
        
        // Check right column fields
        $response->assertSee('اسم العميل');
        $response->assertSee('مصدر العميل');
        $response->assertSee('تاريخ العقد');
        $response->assertSee('محل إقامة الزوجة');
        $response->assertSee('مبلغ المؤخر');
        $response->assertSee('مكان العقد');
        $response->assertSee('تكلفة العقد');
        $response->assertSee('صلة قرابة الولي');
        $response->assertSee('سن الزوجة');
        $response->assertSee('موعد المتابعة');

        // Check left column fields
        $response->assertSee('رقم الواتساب');
        $response->assertSee('رقم الهاتف');
        $response->assertSee('اسم الزوج');
        $response->assertSee('اسم الزوجة');
        $response->assertSee('عنوان العقد بالتفصيل');
        $response->assertSee('رابط الموقع من خرائط جوجل');
        $response->assertSee('الوثيقة المؤقتة');
        $response->assertSee('اسم الشيخ');
        $response->assertSee('رقم الدفتر');
        $response->assertSee('رقم الوثيقة');
        $response->assertSee('تاريخ وصول القسيمة');
        $response->assertSee('تاريخ استلام الوثيقة');
        $response->assertSee('مستلم الوثيقة');
    }

    public function test_client_store_with_new_fields()
    {
        $service = Service::factory()->create(['is_active' => true]);
        $source = ClientSource::factory()->create(['is_active' => true]);

        $clientData = [
            'name' => 'أحمد محمد',
            'groom_name' => 'أحمد محمد',
            'bride_name' => 'فاطمة علي',
            'phone' => '0501234567',
            'whatsapp_number' => '0501234567',
            'source_id' => $source->id,
            'event_date' => '2025-12-25',
            'bride_id_address' => 'الرياض، حي النرجس',
            'mahr' => '50000',
            'contract_location' => 'مسجد النور',
            'contract_cost' => 1500.00,
            'contract_address' => 'شارع الملك فهد، الرياض',
            'relationship_status' => 'والد',
            'bride_age' => 25,
            'next_follow_up_date' => '2025-12-20',
            'temporary_document' => 'وثيقة مؤقتة رقم 123',
            'sheikh_name' => 'الشيخ عبدالله',
            'book_number' => 'B001',
            'document_number' => 'D001',
            'coupon_arrival_date' => '2025-12-15',
            'document_receipt_date' => '2025-12-30',
            'document_receiver' => 'delivery',
            'google_maps_link' => 'https://maps.google.com/test',
            'status' => 'new',
            'notes' => 'ملاحظات تجريبية'
        ];

        $response = $this->post(route('clients.store'), $clientData);

        if ($response->status() !== 302) {
            // If not redirecting, there are validation errors
            $this->fail('Validation failed. Status: ' . $response->status() . ', Content: ' . $response->getContent());
        }
        $response->assertRedirect();
        $this->assertDatabaseHas('clients', [
            'name' => 'أحمد محمد',
            'groom_name' => 'أحمد محمد',
            'bride_name' => 'فاطمة علي',
            'phone' => '0501234567',
            'whatsapp_number' => '0501234567',
            'contract_location' => 'مسجد النور',
            'contract_cost' => 1500.00,
            'contract_address' => 'شارع الملك فهد، الرياض',
            'temporary_document' => 'وثيقة مؤقتة رقم 123',
            'sheikh_name' => 'الشيخ عبدالله',
            'book_number' => 'B001',
            'document_number' => 'D001',
            'document_receiver' => 'delivery'
        ]);
    }

    public function test_client_update_with_new_fields()
    {
        $client = Client::factory()->create();
        $service = Service::factory()->create(['is_active' => true]);
        $source = ClientSource::factory()->create(['is_active' => true]);

        $updateData = [
            'name' => 'محمد أحمد',
            'groom_name' => 'محمد أحمد',
            'bride_name' => 'سارة محمد',
            'phone' => '0507654321',
            'whatsapp_number' => '0507654321',
            'source_id' => $source->id,
            'event_date' => '2025-12-30',
            'bride_id_address' => 'جدة، حي الروضة',
            'mahr' => '75000',
            'contract_location' => 'مسجد الرحمة',
            'contract_cost' => 2000.00,
            'contract_address' => 'شارع التحلية، جدة',
            'relationship_status' => 'أخ',
            'bride_age' => 28,
            'next_follow_up_date' => '2025-12-25',
            'temporary_document' => 'وثيقة مؤقتة رقم 456',
            'sheikh_name' => 'الشيخ محمد',
            'book_number' => 'B002',
            'document_number' => 'D002',
            'coupon_arrival_date' => '2025-12-20',
            'document_receipt_date' => '2026-01-05',
            'document_receiver' => 'client',
            'google_maps_link' => 'https://maps.google.com/test2',
            'status' => 'in_progress',
            'notes' => 'ملاحظات محدثة'
        ];

        $response = $this->put(route('clients.update', $client), $updateData);

        $response->assertRedirect();
        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'name' => 'محمد أحمد',
            'groom_name' => 'محمد أحمد',
            'bride_name' => 'سارة محمد',
            'contract_location' => 'مسجد الرحمة',
            'contract_cost' => 2000.00,
            'temporary_document' => 'وثيقة مؤقتة رقم 456',
            'sheikh_name' => 'الشيخ محمد',
            'book_number' => 'B002',
            'document_number' => 'D002',
            'document_receiver' => 'client'
        ]);
    }

    public function test_validation_rules_for_new_fields()
    {
        $invalidData = [
            'name' => '', // Required field
            'phone' => '', // Required field
            'bride_age' => 150, // Max 100
            'contract_cost' => -100, // Min 0
            'document_receiver' => 'invalid_option', // Invalid enum
            'google_maps_link' => 'not-a-url', // Invalid URL
        ];

        $response = $this->post(route('clients.store'), $invalidData);

        $response->assertSessionHasErrors([
            'name',
            'phone',
            'bride_age',
            'contract_cost',
            'document_receiver',
            'google_maps_link'
        ]);
    }
}
