<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Client;
use App\Models\Service;
use App\Models\ClientSource;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClientNewFieldsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_client_with_new_fields()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        
        $service = Service::factory()->create();
        $source = ClientSource::factory()->create();

        $clientData = [
            'name' => 'أحمد محمد',
            'bride_name' => 'فاطمة أحمد',
            'guardian_name' => 'محمد أحمد',
            'phone' => '0501234567',
            'email' => 'ahmed@example.com',
            'address' => 'الرياض، حي النرجس',
            'geographical_area' => 'المنطقة الوسطى',
            'governorate' => 'الرياض',
            'area' => 'حي النرجس',
            'google_maps_link' => 'https://maps.google.com/example',
            'relationship_status' => 'والد',
            'call_result' => 'interested',
            'next_follow_up_date' => '2024-12-15',
            'status' => 'new',
            'service_id' => $service->id,
            'source_id' => $source->id,
            'is_active' => true,
        ];

        $response = $this->post(route('clients.store'), $clientData);

        // Check if there are validation errors
        if ($response->status() !== 302) {
            $response->assertStatus(200);
            // If we get here, there are validation errors
            $this->fail('Client creation failed with status: ' . $response->status());
        }
        
        $response->assertRedirect();
        
        // Check if any client was created
        $this->assertDatabaseCount('clients', 1);
        
        // Check the specific fields
        $this->assertDatabaseHas('clients', [
            'name' => 'أحمد محمد',
        ]);
        
        $client = Client::first();
        $this->assertEquals('المنطقة الوسطى', $client->geographical_area);
        $this->assertEquals('الرياض', $client->governorate);
        $this->assertEquals('حي النرجس', $client->area);
        $this->assertEquals('interested', $client->call_result);
        $this->assertEquals('والد', $client->relationship_status);
    }

    public function test_can_update_client_with_new_fields()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        
        $client = Client::factory()->create();
        $service = Service::factory()->create();
        $source = ClientSource::factory()->create();

        $updateData = [
            'name' => $client->name,
            'phone' => $client->phone,
            'geographical_area' => 'المنطقة الشرقية',
            'governorate' => 'الدمام',
            'area' => 'حي الفيصلية',
            'google_maps_link' => 'https://maps.google.com/updated',
            'relationship_status' => 'أخ',
            'call_result' => 'potential_client',
            'next_follow_up_date' => '2024-12-20',
            'status' => 'in_progress',
            'service_id' => $service->id,
            'source_id' => $source->id,
            'is_active' => true,
        ];

        $response = $this->put(route('clients.update', $client), $updateData);

        $response->assertRedirect();
        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'geographical_area' => 'المنطقة الشرقية',
            'governorate' => 'الدمام',
            'area' => 'حي الفيصلية',
            'call_result' => 'potential_client',
            'relationship_status' => 'أخ',
        ]);
    }

    public function test_client_call_result_labels()
    {
        $client = Client::factory()->create(['call_result' => 'interested']);
        $this->assertEquals('مهتم', $client->call_result_label);

        $client->call_result = 'not_interested';
        $this->assertEquals('غير مهتم', $client->call_result_label);

        $client->call_result = 'follow_up_later';
        $this->assertEquals('متابعة لاحقا', $client->call_result_label);

        $client->call_result = 'potential_client';
        $this->assertEquals('عميل محتمل', $client->call_result_label);
    }

    public function test_client_document_status_labels()
    {
        $client = Client::factory()->create(['document_status' => 'pending']);
        $this->assertEquals('في الانتظار', $client->document_status_label);

        $client->document_status = 'under_review';
        $this->assertEquals('قيد المراجعة', $client->document_status_label);

        $client->document_status = 'approved';
        $this->assertEquals('موافق عليه', $client->document_status_label);

        $client->document_status = 'incomplete';
        $this->assertEquals('الاوراق غير مكتملة', $client->document_status_label);
    }

    public function test_client_can_be_assigned_to_partner()
    {
        $client = Client::factory()->create();
        $partner = Partner::factory()->create();

        $client->update([
            'assigned_partner_id' => $partner->id,
            'job_date' => '2024-12-25',
            'job_time' => '10:00:00',
            'job_number' => 'JOB001',
            'coupon_number' => 'COUPON001',
        ]);

        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'assigned_partner_id' => $partner->id,
            'job_date' => '2024-12-25 00:00:00',
            'job_time' => '10:00:00',
            'job_number' => 'JOB001',
            'coupon_number' => 'COUPON001',
        ]);

        $this->assertInstanceOf(Partner::class, $client->assignedPartner);
        $this->assertEquals($partner->id, $client->assignedPartner->id);
    }

    public function test_client_validation_rules()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        
        $response = $this->post(route('clients.store'), [
            'name' => 'Test Client',
            'phone' => '0501234567',
            'status' => 'new',
            'call_result' => 'invalid_result', // Invalid call result
            'next_follow_up_date' => '2020-01-01', // Past date
            'google_maps_link' => 'invalid-url', // Invalid URL
        ]);

        $response->assertSessionHasErrors(['call_result', 'google_maps_link']);
    }

    public function test_client_final_document_delivery()
    {
        $client = Client::factory()->create();

        $client->update([
            'final_document_delivery_date' => '2024-12-30',
            'final_document_notification_sent' => true,
        ]);

        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'final_document_delivery_date' => '2024-12-30 00:00:00',
            'final_document_notification_sent' => 1,
        ]);
    }
}
