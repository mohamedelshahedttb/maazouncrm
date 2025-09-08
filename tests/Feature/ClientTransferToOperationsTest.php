<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Client;
use App\Models\Task;
use App\Models\User;
use App\Models\Service;
use App\Models\ClientSource;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClientTransferToOperationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_transfer_client_to_operations()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        
        $service = Service::factory()->create();
        $source = ClientSource::factory()->create();
        
        $client = Client::factory()->create([
            'name' => 'أحمد محمد',
            'phone' => '0501234567',
            'email' => 'ahmed@example.com',
            'address' => 'الرياض، حي النرجس',
            'geographical_area' => 'المنطقة الوسطى',
            'governorate' => 'الرياض',
            'area' => 'حي النرجس',
            'google_maps_link' => 'https://maps.google.com/test',
            'relationship_status' => 'والد',
            'call_result' => 'interested',
            'service_id' => $service->id,
            'source_id' => $source->id,
            'status' => 'new',
            'document_status' => 'pending',
        ]);

        $response = $this->post(route('clients.transfer-to-operations', $client));

        $response->assertRedirect(route('clients.show', $client));
        $response->assertSessionHas('success');

        // Check that a task was created
        $this->assertDatabaseCount('tasks', 1);
        
        $task = Task::first();
        $this->assertEquals('مهمة جديدة - ' . $client->name, $task->title);
        $this->assertEquals('pending', $task->status);
        $this->assertEquals('new', $task->tag);
        $this->assertEquals('medium', $task->priority);
        $this->assertEquals('preparation', $task->execution_phase);
        $this->assertEquals($client->address, $task->location);
        $this->assertEquals($user->id, $task->assigned_to);

        // Check that the task description contains client information
        $this->assertStringContainsString('أحمد محمد', $task->description);
        $this->assertStringContainsString('0501234567', $task->description);
        $this->assertStringContainsString('ahmed@example.com', $task->description);
        $this->assertStringContainsString('الرياض، حي النرجس', $task->description);
        $this->assertStringContainsString('المنطقة الوسطى', $task->description);
        $this->assertStringContainsString('الرياض', $task->description);
        $this->assertStringContainsString('حي النرجس', $task->description);
        $this->assertStringContainsString('https://maps.google.com/test', $task->description);
        $this->assertStringContainsString('والد', $task->description);
        $this->assertStringContainsString('مهتم', $task->description);

        // Check that client status was updated
        $client->refresh();
        $this->assertEquals('in_progress', $client->status);
        $this->assertEquals('under_review', $client->document_status);
    }

    public function test_transfer_to_operations_requires_authentication()
    {
        $client = Client::factory()->create();
        
        $response = $this->post(route('clients.transfer-to-operations', $client));
        
        $response->assertRedirect(route('login'));
    }

    public function test_transfer_to_operations_creates_task_with_correct_due_date()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        
        $client = Client::factory()->create();

        $response = $this->post(route('clients.transfer-to-operations', $client));

        $task = Task::first();
        $expectedDueDate = now()->addDays(3);
        
        // Check that the due date is approximately 3 days from now (within 1 minute tolerance)
        $this->assertTrue($task->due_date->diffInMinutes($expectedDueDate) < 1);
    }

    public function test_transfer_to_operations_includes_service_information()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        
        $service = Service::factory()->create(['name' => 'خدمة الزواج الكاملة']);
        $client = Client::factory()->create(['service_id' => $service->id]);

        $response = $this->post(route('clients.transfer-to-operations', $client));

        $task = Task::first();
        $this->assertStringContainsString('خدمة الزواج الكاملة', $task->description);
    }

    public function test_transfer_to_operations_handles_missing_optional_fields()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        
        $client = Client::factory()->create([
            'name' => 'أحمد محمد',
            'phone' => '0501234567',
            'email' => null,
            'address' => null,
            'geographical_area' => null,
            'governorate' => null,
            'area' => null,
            'google_maps_link' => null,
            'relationship_status' => null,
            'call_result' => null,
            'service_id' => null,
            'notes' => null,
        ]);

        $response = $this->post(route('clients.transfer-to-operations', $client));

        $task = Task::first();
        $this->assertStringContainsString('أحمد محمد', $task->description);
        $this->assertStringContainsString('0501234567', $task->description);
        // Should not contain null values
        $this->assertStringNotContainsString('null', $task->description);
    }
}


