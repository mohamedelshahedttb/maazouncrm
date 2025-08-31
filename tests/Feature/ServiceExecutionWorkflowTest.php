<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\ServiceExecutionStep;
use App\Models\ExecutionProgress;
use App\Models\Task;
use App\Models\Partner;
use App\Services\ServiceExecutionService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServiceExecutionWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $client;
    protected $service;
    protected $appointment;
    protected $partner;
    protected $executionService;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test user
        $this->user = User::factory()->create();
        
        // Create test client
        $this->client = Client::create([
            'name' => 'Test Client',
            'phone' => '0501234567',
            'email' => 'test@example.com',
            'status' => Client::STATUS_NEW,
            'is_active' => true,
        ]);
        
        // Create test partner
        $this->partner = Partner::create([
            'name' => 'Test Partner',
            'license_number' => 'TEST001',
            'service_scope' => 'Marriage, Divorce',
            'phone' => '0501234568',
            'email' => 'partner@example.com',
            'commission_rate' => 10.00,
            'status' => Partner::STATUS_ACTIVE,
            'is_active' => true,
        ]);
        
        // Create test service
        $this->service = Service::create([
            'name' => 'Test Marriage Service',
            'category' => Service::CATEGORY_MARRIAGE,
            'description' => 'Test marriage service description',
            'price' => 500.00,
            'currency' => 'EGP',
            'duration_minutes' => 120,
            'is_active' => true,
        ]);
        
        // Create test appointment
        $this->appointment = Appointment::create([
            'client_id' => $this->client->id,
            'service_id' => $this->service->id,
            'assigned_to' => $this->user->id,
            'primary_partner_id' => $this->partner->id,
            'appointment_date' => now()->addDay(),
            'status' => Appointment::STATUS_CONFIRMED,
            'execution_status' => Appointment::EXECUTION_STATUS_SCHEDULED,
            'is_active' => true,
        ]);
        
        $this->executionService = new ServiceExecutionService();
    }

    /** @test */
    public function it_can_create_execution_steps_for_service()
    {
        // Create execution steps
        $step1 = ServiceExecutionStep::create([
            'service_id' => $this->service->id,
            'step_order' => 1,
            'step_name' => 'Preparation Step',
            'step_description' => 'First preparation step',
            'estimated_duration_minutes' => 30,
            'required_resources' => 'partner',
            'step_type' => ServiceExecutionStep::TYPE_PREPARATION,
            'is_required' => true,
            'is_active' => true,
        ]);

        $step2 = ServiceExecutionStep::create([
            'service_id' => $this->service->id,
            'step_order' => 2,
            'step_name' => 'Execution Step',
            'step_description' => 'Main execution step',
            'estimated_duration_minutes' => 60,
            'required_resources' => 'partner,supplier',
            'step_type' => ServiceExecutionStep::TYPE_EXECUTION,
            'is_required' => true,
            'is_active' => true,
        ]);

        // Assert steps were created
        $this->assertDatabaseHas('service_execution_steps', [
            'id' => $step1->id,
            'service_id' => $this->service->id,
            'step_order' => 1,
        ]);

        $this->assertDatabaseHas('service_execution_steps', [
            'id' => $step2->id,
            'service_id' => $this->service->id,
            'step_order' => 2,
        ]);

        // Assert service has execution steps
        $this->assertTrue($this->service->hasExecutionSteps());
        $this->assertEquals(2, $this->service->execution_steps_count);
    }

    /** @test */
    public function it_can_initialize_execution_workflow_for_appointment()
    {
        // Create execution steps first
        $this->createTestExecutionSteps();

        // Initialize execution workflow
        $result = $this->executionService->initializeExecution($this->appointment);

        // Assert workflow was initialized
        $this->assertTrue($result);

        // Assert execution progress records were created
        $this->assertDatabaseHas('execution_progress', [
            'appointment_id' => $this->appointment->id,
            'status' => ExecutionProgress::STATUS_PENDING,
        ]);

        // Assert tasks were generated
        $this->assertDatabaseHas('tasks', [
            'appointment_id' => $this->appointment->id,
            'title' => 'Preparation Step',
        ]);

        // Assert appointment execution status was updated
        $this->appointment->refresh();
        $this->assertEquals(Appointment::EXECUTION_STATUS_SCHEDULED, $this->appointment->execution_status);
    }

    /** @test */
    public function it_can_start_execution_of_appointment()
    {
        // Create execution steps and initialize workflow
        $this->createTestExecutionSteps();
        $this->executionService->initializeExecution($this->appointment);

        // Start execution
        $result = $this->executionService->startExecution($this->appointment);

        // Assert execution was started
        $this->assertTrue($result);

        // Assert appointment status was updated
        $this->appointment->refresh();
        $this->assertEquals(Appointment::EXECUTION_STATUS_IN_EXECUTION, $this->appointment->execution_status);
        $this->assertEquals(Appointment::STATUS_IN_PROGRESS, $this->appointment->status);

        // Assert first step was started
        $firstStep = $this->appointment->executionProgress()
            ->where('status', ExecutionProgress::STATUS_IN_PROGRESS)
            ->first();
        
        $this->assertNotNull($firstStep);
        $this->assertNotNull($firstStep->started_at);

        // Assert related task was started
        if ($firstStep->task) {
            $this->assertEquals(Task::STATUS_IN_PROGRESS, $firstStep->task->status);
        }
    }

    /** @test */
    public function it_can_complete_execution_step()
    {
        // Create execution steps and initialize workflow
        $this->createTestExecutionSteps();
        $this->executionService->initializeExecution($this->appointment);
        $this->executionService->startExecution($this->appointment);

        // Get first execution step
        $firstStep = $this->appointment->executionProgress()
            ->where('status', ExecutionProgress::STATUS_IN_PROGRESS)
            ->first();

        // Complete the step
        $result = $this->executionService->completeExecutionStep($firstStep);

        // Assert step was completed
        $this->assertTrue($result);

        // Assert step status was updated
        $firstStep->refresh();
        $this->assertEquals(ExecutionProgress::STATUS_COMPLETED, $firstStep->status);
        $this->assertNotNull($firstStep->completed_at);

        // Assert related task was completed
        if ($firstStep->task) {
            $this->assertEquals(Task::STATUS_COMPLETED, $firstStep->task->status);
        }

        // Assert next step was started
        $nextStep = $this->appointment->executionProgress()
            ->where('status', ExecutionProgress::STATUS_IN_PROGRESS)
            ->first();
        
        $this->assertNotNull($nextStep);
    }

    /** @test */
    public function it_can_get_execution_progress_summary()
    {
        // Create execution steps and initialize workflow
        $this->createTestExecutionSteps();
        $this->executionService->initializeExecution($this->appointment);

        // Get progress summary
        $summary = $this->executionService->getExecutionProgressSummary($this->appointment);

        // Assert summary structure
        $this->assertArrayHasKey('total_steps', $summary);
        $this->assertArrayHasKey('completed_steps', $summary);
        $this->assertArrayHasKey('in_progress_steps', $summary);
        $this->assertArrayHasKey('pending_steps', $summary);
        $this->assertArrayHasKey('progress_percentage', $summary);
        $this->assertArrayHasKey('steps', $summary);

        // Assert initial values
        $this->assertEquals(2, $summary['total_steps']);
        $this->assertEquals(0, $summary['completed_steps']);
        $this->assertEquals(0, $summary['in_progress_steps']);
        $this->assertEquals(2, $summary['pending_steps']);
        $this->assertEquals(0, $summary['progress_percentage']);

        // Assert steps details
        $this->assertCount(2, $summary['steps']);
        $this->assertEquals('Preparation Step', $summary['steps'][0]['step_name']);
        $this->assertEquals('Execution Step', $summary['steps'][1]['step_name']);
    }

    /** @test */
    public function it_can_block_execution_step()
    {
        // Create execution steps and initialize workflow
        $this->createTestExecutionSteps();
        $this->executionService->initializeExecution($this->appointment);

        // Get first execution step
        $firstStep = $this->appointment->executionProgress()
            ->where('status', ExecutionProgress::STATUS_PENDING)
            ->first();

        // Block the step
        $blockReason = 'Waiting for client documents';
        $result = $this->executionService->blockExecutionStep($firstStep, $blockReason);

        // Assert step was blocked
        $this->assertTrue($result);

        // Assert step status was updated
        $firstStep->refresh();
        $this->assertEquals(ExecutionProgress::STATUS_BLOCKED, $firstStep->status);
        $this->assertEquals($blockReason, $firstStep->blocking_reason);
    }

    /** @test */
    public function it_can_complete_full_execution_workflow()
    {
        // Create execution steps and initialize workflow
        $this->createTestExecutionSteps();
        $this->executionService->initializeExecution($this->appointment);
        $this->executionService->startExecution($this->appointment);

        // Complete all steps sequentially
        $executionSteps = $this->appointment->executionProgress()
            ->orderBy('execution_step_id')
            ->get();

        // Complete first step
        $firstStep = $executionSteps->where('status', ExecutionProgress::STATUS_IN_PROGRESS)->first();
        if ($firstStep) {
            $this->executionService->completeExecutionStep($firstStep);
        }

        // Complete second step (should now be in progress)
        $secondStep = $this->appointment->executionProgress()
            ->where('status', ExecutionProgress::STATUS_IN_PROGRESS)
            ->first();
        if ($secondStep) {
            $this->executionService->completeExecutionStep($secondStep);
        }

        // Assert all steps are completed
        $this->appointment->refresh();
        $this->assertEquals(Appointment::EXECUTION_STATUS_COMPLETED, $this->appointment->execution_status);
        $this->assertEquals(Appointment::STATUS_COMPLETED, $this->appointment->status);

        // Assert progress summary shows 100% completion
        $summary = $this->executionService->getExecutionProgressSummary($this->appointment);
        $this->assertEquals(100, $summary['progress_percentage']);
        $this->assertEquals(2, $summary['completed_steps']);
    }

    /** @test */
    public function it_can_handle_task_priority_and_phases()
    {
        // Create execution steps and initialize workflow
        $this->createTestExecutionSteps();
        $this->executionService->initializeExecution($this->appointment);

        // Get generated tasks
        $tasks = $this->appointment->tasks;

        // Assert task priorities are set correctly
        $preparationTask = $tasks->where('execution_phase', Task::PHASE_PREPARATION)->first();
        $executionTask = $tasks->where('execution_phase', Task::PHASE_EXECUTION)->first();

        $this->assertNotNull($preparationTask);
        $this->assertNotNull($executionTask);

        // Assert priorities are set based on phase
        $this->assertEquals(Task::PRIORITY_MEDIUM, $preparationTask->priority);
        $this->assertEquals(Task::PRIORITY_HIGH, $executionTask->priority);
    }

    /** @test */
    public function it_can_handle_task_dependencies()
    {
        // Create execution steps with dependencies
        $step1 = ServiceExecutionStep::create([
            'service_id' => $this->service->id,
            'step_order' => 1,
            'step_name' => 'Step 1',
            'step_type' => ServiceExecutionStep::TYPE_PREPARATION,
            'is_required' => true,
            'is_active' => true,
        ]);

        $step2 = ServiceExecutionStep::create([
            'service_id' => $this->service->id,
            'step_order' => 2,
            'step_name' => 'Step 2',
            'step_type' => ServiceExecutionStep::TYPE_EXECUTION,
            'dependencies' => '1',
            'is_required' => true,
            'is_active' => true,
        ]);

        // Initialize workflow
        $this->executionService->initializeExecution($this->appointment);

        // Get tasks
        $tasks = $this->appointment->tasks;
        $task1 = $tasks->where('execution_step_id', $step1->id)->first();
        $task2 = $tasks->where('execution_step_id', $step2->id)->first();

        // Assert dependencies are set
        $this->assertEmpty($task1->prerequisites_array);
        $this->assertContains('1', $task2->prerequisites_array);
    }

    /**
     * Create test execution steps for testing
     */
    protected function createTestExecutionSteps(): void
    {
        ServiceExecutionStep::create([
            'service_id' => $this->service->id,
            'step_order' => 1,
            'step_name' => 'Preparation Step',
            'step_description' => 'First preparation step',
            'estimated_duration_minutes' => 30,
            'required_resources' => 'partner',
            'step_type' => ServiceExecutionStep::TYPE_PREPARATION,
            'is_required' => true,
            'is_active' => true,
        ]);

        ServiceExecutionStep::create([
            'service_id' => $this->service->id,
            'step_order' => 2,
            'step_name' => 'Execution Step',
            'step_description' => 'Main execution step',
            'estimated_duration_minutes' => 60,
            'required_resources' => 'partner,supplier',
            'step_type' => ServiceExecutionStep::TYPE_EXECUTION,
            'is_required' => true,
            'is_active' => true,
        ]);
    }
}
