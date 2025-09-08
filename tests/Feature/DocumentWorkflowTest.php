<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Client;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DocumentWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_sales_agent_can_change_client_to_potential_client()
    {
        $client = Client::factory()->create([
            'status' => 'new',
            'document_status' => 'pending'
        ]);

        $response = $this->put(route('clients.update', $client), [
            'name' => $client->name,
            'phone' => $client->phone,
            'status' => 'in_progress',
            'call_result' => 'potential_client',
            'document_status' => 'under_review',
            'is_active' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'call_result' => 'potential_client',
            'document_status' => 'under_review',
        ]);
    }

    public function test_operation_employee_can_reject_documents()
    {
        $client = Client::factory()->create([
            'document_status' => 'under_review'
        ]);

        $response = $this->put(route('clients.update', $client), [
            'name' => $client->name,
            'phone' => $client->phone,
            'status' => $client->status,
            'document_status' => 'incomplete',
            'document_rejection_reason' => 'الاوراق غير مكتملة - يرجى إحضار الهوية الوطنية',
            'is_active' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'document_status' => 'incomplete',
            'document_rejection_reason' => 'الاوراق غير مكتملة - يرجى إحضار الهوية الوطنية',
        ]);
    }

    public function test_operation_employee_can_approve_documents()
    {
        $client = Client::factory()->create([
            'document_status' => 'under_review'
        ]);

        $response = $this->put(route('clients.update', $client), [
            'name' => $client->name,
            'phone' => $client->phone,
            'status' => $client->status,
            'document_status' => 'approved',
            'is_active' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'document_status' => 'approved',
        ]);
    }

    public function test_operation_employee_can_assign_partner()
    {
        $client = Client::factory()->create([
            'document_status' => 'approved'
        ]);
        $partner = Partner::factory()->create();

        $response = $this->put(route('clients.update', $client), [
            'name' => $client->name,
            'phone' => $client->phone,
            'status' => $client->status,
            'document_status' => $client->document_status,
            'assigned_partner_id' => $partner->id,
            'job_date' => '2024-12-25',
            'job_time' => '10:00:00',
            'job_number' => 'JOB001',
            'coupon_number' => 'COUPON001',
            'is_active' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'assigned_partner_id' => $partner->id,
            'job_date' => '2024-12-25',
            'job_time' => '10:00:00',
            'job_number' => 'JOB001',
            'coupon_number' => 'COUPON001',
        ]);
    }

    public function test_operation_employee_can_schedule_final_document_delivery()
    {
        $client = Client::factory()->create([
            'document_status' => 'approved',
            'assigned_partner_id' => Partner::factory()->create()->id,
        ]);

        $response = $this->put(route('clients.update', $client), [
            'name' => $client->name,
            'phone' => $client->phone,
            'status' => $client->status,
            'document_status' => $client->document_status,
            'assigned_partner_id' => $client->assigned_partner_id,
            'final_document_delivery_date' => '2024-12-30',
            'final_document_notification_sent' => true,
            'is_active' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'final_document_delivery_date' => '2024-12-30',
            'final_document_notification_sent' => true,
        ]);
    }

    public function test_document_workflow_states()
    {
        $client = Client::factory()->create();

        // Test pending state
        $client->update(['document_status' => 'pending']);
        $this->assertEquals('في الانتظار', $client->document_status_label);
        $this->assertEquals('yellow', $client->document_status_color);

        // Test under review state
        $client->update(['document_status' => 'under_review']);
        $this->assertEquals('قيد المراجعة', $client->document_status_label);
        $this->assertEquals('blue', $client->document_status_color);

        // Test approved state
        $client->update(['document_status' => 'approved']);
        $this->assertEquals('موافق عليه', $client->document_status_label);
        $this->assertEquals('green', $client->document_status_color);

        // Test incomplete state
        $client->update(['document_status' => 'incomplete']);
        $this->assertEquals('الاوراق غير مكتملة', $client->document_status_label);
        $this->assertEquals('red', $client->document_status_color);
    }

    public function test_client_can_be_transferred_to_operations()
    {
        $client = Client::factory()->create([
            'call_result' => 'potential_client',
            'document_status' => 'pending'
        ]);

        // Simulate sales agent changing status to potential client
        $client->update([
            'document_status' => 'under_review'
        ]);

        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'call_result' => 'potential_client',
            'document_status' => 'under_review',
        ]);
    }
}


