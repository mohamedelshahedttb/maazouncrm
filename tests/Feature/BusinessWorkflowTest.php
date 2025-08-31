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
use App\Models\Supplier;
use App\Models\Product;
use App\Models\ClientOrder;
use App\Services\ServiceExecutionService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BusinessWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $client;
    protected $marriageService;
    protected $partner;
    protected $supplier;
    protected $executionService;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test user
        $this->user = User::factory()->create();
        
        // Create test client
        $this->client = Client::create([
            'name' => 'أحمد الشريف',
            'bride_name' => 'فاطمة السيد',
            'guardian_name' => 'السيد محمد',
            'phone' => '0501234567',
            'email' => 'ahmed@example.com',
            'address' => 'الرياض، المملكة العربية السعودية',
            'status' => Client::STATUS_NEW,
            'is_active' => true,
        ]);
        
        // Create test partner
        $this->partner = Partner::create([
            'name' => 'مكتب المحامي أحمد',
            'license_number' => 'LS-987654',
            'service_scope' => 'Marriage, Divorce',
            'phone' => '0501234568',
            'email' => 'partner@example.com',
            'commission_rate' => 10.00,
            'status' => Partner::STATUS_ACTIVE,
            'is_active' => true,
        ]);
        
        // Create test supplier
        $this->supplier = Supplier::create([
            'name' => 'مؤسسة الصفاء للقرطاسية',
            'contact_person' => 'أحمد سعيد',
            'phone' => '0501234569',
            'services_products' => 'دفاتر عقود, مستلزمات مكتبية',
            'rating' => 4.5,
            'status' => Supplier::STATUS_ACTIVE,
            'is_active' => true,
        ]);
        
        // Create marriage service with execution steps
        $this->marriageService = Service::create([
            'name' => 'خدمة توثيق الزواج',
            'category' => Service::CATEGORY_MARRIAGE,
            'description' => 'خدمة شاملة لتوثيق عقد الزواج',
            'price' => 800.00,
            'currency' => 'EGP',
            'duration_minutes' => 180,
            'is_active' => true,
        ]);
        
        // Create execution steps for marriage service
        $this->createMarriageServiceSteps();
        
        $this->executionService = new ServiceExecutionService();
    }

    /** @test */
    public function it_can_execute_complete_marriage_service_workflow()
    {
        // 1. Client creates appointment
        $appointment = Appointment::create([
            'client_id' => $this->client->id,
            'service_id' => $this->marriageService->id,
            'assigned_to' => $this->user->id,
            'primary_partner_id' => $this->partner->id,
            'appointment_date' => now()->addDay(),
            'status' => Appointment::STATUS_CONFIRMED,
            'is_active' => true,
        ]);

        // 2. Initialize execution workflow
        $result = $this->executionService->initializeExecution($appointment);
        $this->assertTrue($result);

        // 3. Verify execution steps were created
        $executionSteps = $appointment->executionProgress()->count();
        $this->assertEquals(7, $executionSteps); // 7 steps for marriage service

        // 4. Verify tasks were generated
        $tasks = $appointment->tasks()->count();
        $this->assertEquals(7, $tasks);

        // 5. Start execution
        $result = $this->executionService->startExecution($appointment);
        $this->assertTrue($result);

        // 6. Verify first step is in progress
        $firstStep = $appointment->executionProgress()
            ->where('status', ExecutionProgress::STATUS_IN_PROGRESS)
            ->first();
        $this->assertNotNull($firstStep);
        $this->assertEquals('استقبال العميل والتحقق من الهوية', $firstStep->executionStep->step_name);

        // 7. Complete first step
        $result = $this->executionService->completeExecutionStep($firstStep);
        $this->assertTrue($result);

        // 8. Verify second step started automatically
        $secondStep = $appointment->executionProgress()
            ->where('status', ExecutionProgress::STATUS_IN_PROGRESS)
            ->first();
        $this->assertNotNull($secondStep);
        $this->assertEquals('مراجعة المستندات المطلوبة', $secondStep->executionStep->step_name);

        // 9. Complete all remaining steps
        $this->completeAllRemainingSteps($appointment);

        // 10. Verify appointment is completed
        $appointment->refresh();
        $this->assertEquals(Appointment::EXECUTION_STATUS_COMPLETED, $appointment->execution_status);
        $this->assertEquals(Appointment::STATUS_COMPLETED, $appointment->status);

        // 11. Verify progress is 100%
        $summary = $this->executionService->getExecutionProgressSummary($appointment);
        $this->assertEquals(100, $summary['progress_percentage']);
        $this->assertEquals(7, $summary['completed_steps']);
    }

    /** @test */
    public function it_can_handle_workflow_with_blocked_steps()
    {
        // Create appointment and initialize workflow
        $appointment = Appointment::create([
            'client_id' => $this->client->id,
            'service_id' => $this->marriageService->id,
            'assigned_to' => $this->user->id,
            'primary_partner_id' => $this->partner->id,
            'appointment_date' => now()->addDay(),
            'status' => Appointment::STATUS_CONFIRMED,
            'is_active' => true,
        ]);

        $this->executionService->initializeExecution($appointment);
        $this->executionService->startExecution($appointment);

        // Block a step due to missing documents
        $firstStep = $appointment->executionProgress()
            ->where('status', ExecutionProgress::STATUS_IN_PROGRESS)
            ->first();

        $blockReason = 'العميل لم يحضر المستندات المطلوبة';
        $result = $this->executionService->blockExecutionStep($firstStep, $blockReason);
        $this->assertTrue($result);

        // Verify step is blocked
        $firstStep->refresh();
        $this->assertEquals(ExecutionProgress::STATUS_BLOCKED, $firstStep->status);
        $this->assertEquals($blockReason, $firstStep->blocking_reason);

        // Verify workflow cannot proceed
        $summary = $this->executionService->getExecutionProgressSummary($appointment);
        $this->assertEquals(0, $summary['progress_percentage']);
        $this->assertEquals(1, $summary['blocked_steps']);
    }

    /** @test */
    public function it_can_handle_resource_allocation_and_release()
    {
        // Create appointment and initialize workflow
        $appointment = Appointment::create([
            'client_id' => $this->client->id,
            'service_id' => $this->marriageService->id,
            'assigned_to' => $this->user->id,
            'primary_partner_id' => $this->partner->id,
            'appointment_date' => now()->addDay(),
            'status' => Appointment::STATUS_CONFIRMED,
            'is_active' => true,
        ]);

        $this->executionService->initializeExecution($appointment);

        // Verify resources were allocated
        $tasks = $appointment->tasks;
        $this->assertGreaterThan(0, $tasks->count());

        // Check if any task has resource allocation
        $taskWithResources = $tasks->first(function($task) {
            return $task->hasResourceAllocation();
        });

        if ($taskWithResources) {
            $this->assertTrue($taskWithResources->hasResourceAllocation());
            $this->assertGreaterThan(0, $taskWithResources->active_resource_allocations->count());
        }
    }

    /** @test */
    public function it_can_handle_task_dependencies_correctly()
    {
        // Create appointment and initialize workflow
        $appointment = Appointment::create([
            'client_id' => $this->client->id,
            'service_id' => $this->marriageService->id,
            'assigned_to' => $this->user->id,
            'primary_partner_id' => $this->partner->id,
            'appointment_date' => now()->addDay(),
            'status' => Appointment::STATUS_CONFIRMED,
            'is_active' => true,
        ]);

        $this->executionService->initializeExecution($appointment);

        // Get tasks and verify dependencies
        $tasks = $appointment->tasks()->orderBy('execution_step_id')->get();
        
        // First task should have no prerequisites
        $firstTask = $tasks->first();
        $this->assertEmpty($firstTask->prerequisites_array);

        // Second task should depend on first task
        $secondTask = $tasks->skip(1)->first();
        $this->assertContains('1', $secondTask->prerequisites_array);

        // Verify task execution order
        $this->assertEquals(1, $firstTask->execution_step_id);
        $this->assertEquals(2, $secondTask->execution_step_id);
    }

    /** @test */
    public function it_can_handle_workflow_with_multiple_phases()
    {
        // Create appointment and initialize workflow
        $appointment = Appointment::create([
            'client_id' => $this->client->id,
            'service_id' => $this->marriageService->id,
            'assigned_to' => $this->user->id,
            'primary_partner_id' => $this->partner->id,
            'appointment_date' => now()->addDay(),
            'status' => Appointment::STATUS_CONFIRMED,
            'is_active' => true,
        ]);

        $this->executionService->initializeExecution($appointment);

        // Verify tasks are created with correct phases
        $tasks = $appointment->tasks;
        
        $preparationTasks = $tasks->where('execution_phase', Task::PHASE_PREPARATION);
        $executionTasks = $tasks->where('execution_phase', Task::PHASE_EXECUTION);
        $verificationTasks = $tasks->where('execution_phase', Task::PHASE_VERIFICATION);
        $deliveryTasks = $tasks->where('execution_phase', Task::PHASE_DELIVERY);

        // Verify we have tasks in each phase
        $this->assertGreaterThan(0, $preparationTasks->count());
        $this->assertGreaterThan(0, $executionTasks->count());
        $this->assertGreaterThan(0, $verificationTasks->count());
        $this->assertGreaterThan(0, $deliveryTasks->count());

        // Verify phase progression
        $this->assertEquals(1, $preparationTasks->first()->execution_step_id);
        $this->assertEquals(3, $executionTasks->first()->execution_step_id);
        $this->assertEquals(6, $verificationTasks->first()->execution_step_id);
        $this->assertEquals(7, $deliveryTasks->first()->execution_step_id);
    }

    /** @test */
    public function it_can_handle_workflow_cost_estimation()
    {
        // Create appointment and initialize workflow
        $appointment = Appointment::create([
            'client_id' => $this->client->id,
            'service_id' => $this->marriageService->id,
            'assigned_to' => $this->user->id,
            'primary_partner_id' => $this->partner->id,
            'appointment_date' => now()->addDay(),
            'status' => Appointment::STATUS_CONFIRMED,
            'is_active' => true,
        ]);

        $this->executionService->initializeExecution($appointment);

        // Verify tasks have estimated costs
        $tasks = $appointment->tasks;
        $totalEstimatedCost = $tasks->sum('estimated_cost');
        
        $this->assertGreaterThan(0, $totalEstimatedCost);
        $this->assertEquals('EGP', $tasks->first()->cost_currency);

        // Verify cost breakdown by phase
        $preparationCost = $tasks->where('execution_phase', Task::PHASE_PREPARATION)->sum('estimated_cost');
        $executionCost = $tasks->where('execution_phase', Task::PHASE_EXECUTION)->sum('estimated_cost');
        $verificationCost = $tasks->where('execution_phase', Task::PHASE_VERIFICATION)->sum('estimated_cost');
        $deliveryCost = $tasks->where('execution_phase', Task::PHASE_DELIVERY)->sum('estimated_cost');

        $this->assertGreaterThan(0, $preparationCost);
        $this->assertGreaterThan(0, $executionCost);
        $this->assertGreaterThan(0, $verificationCost);
        $this->assertGreaterThan(0, $deliveryCost);
    }

    /** @test */
    public function it_can_handle_workflow_duration_estimation()
    {
        // Create appointment and initialize workflow
        $appointment = Appointment::create([
            'client_id' => $this->client->id,
            'service_id' => $this->marriageService->id,
            'assigned_to' => $this->user->id,
            'primary_partner_id' => $this->partner->id,
            'appointment_date' => now()->addDay(),
            'status' => Appointment::STATUS_CONFIRMED,
            'is_active' => true,
        ]);

        $this->executionService->initializeExecution($appointment);

        // Verify service has total duration estimation
        $this->assertGreaterThan(0, $this->marriageService->estimated_total_duration);
        $this->assertNotEmpty($this->marriageService->formatted_total_duration);

        // Verify individual step durations
        $executionSteps = $this->marriageService->executionSteps;
        foreach ($executionSteps as $step) {
            if ($step->estimated_duration_minutes) {
                $this->assertGreaterThan(0, $step->estimated_duration_minutes);
                $this->assertNotEmpty($step->formatted_duration);
            }
        }
    }

    /** @test */
    public function it_can_handle_workflow_with_parallel_execution_potential()
    {
        // Create appointment and initialize workflow
        $appointment = Appointment::create([
            'client_id' => $this->client->id,
            'service_id' => $this->marriageService->id,
            'assigned_to' => $this->user->id,
            'primary_partner_id' => $this->partner->id,
            'appointment_date' => now()->addDay(),
            'status' => Appointment::STATUS_CONFIRMED,
            'is_active' => true,
        ]);

        $this->executionService->initializeExecution($appointment);

        // Verify that tasks without dependencies can potentially run in parallel
        $tasks = $appointment->tasks;
        $independentTasks = $tasks->filter(function($task) {
            return empty($task->prerequisites_array);
        });

        $this->assertGreaterThan(0, $independentTasks->count());

        // Verify that dependent tasks have proper prerequisites
        $dependentTasks = $tasks->filter(function($task) {
            return !empty($task->prerequisites_array);
        });

        foreach ($dependentTasks as $task) {
            $this->assertNotEmpty($task->prerequisites_array);
        }
    }

    /**
     * Create execution steps for marriage service
     */
    protected function createMarriageServiceSteps(): void
    {
        $steps = [
            [
                'step_order' => 1,
                'step_name' => 'استقبال العميل والتحقق من الهوية',
                'step_description' => 'التحقق من هوية العميل والعروسة وولي الأمر',
                'estimated_duration_minutes' => 15,
                'required_resources' => 'partner,location',
                'dependencies' => '',
                'step_type' => ServiceExecutionStep::TYPE_PREPARATION,
                'is_required' => true,
            ],
            [
                'step_order' => 2,
                'step_name' => 'مراجعة المستندات المطلوبة',
                'step_description' => 'مراجعة شهادة الميلاد، الهوية الوطنية، شهادة الطلاق السابق إن وجدت',
                'estimated_duration_minutes' => 20,
                'required_resources' => 'partner',
                'dependencies' => '1',
                'step_type' => ServiceExecutionStep::TYPE_PREPARATION,
                'is_required' => true,
            ],
            [
                'step_order' => 3,
                'step_name' => 'إعداد عقد الزواج',
                'step_description' => 'كتابة عقد الزواج بالتفاصيل المطلوبة',
                'estimated_duration_minutes' => 30,
                'required_resources' => 'partner,supplier',
                'dependencies' => '2',
                'step_type' => ServiceExecutionStep::TYPE_EXECUTION,
                'is_required' => true,
            ],
            [
                'step_order' => 4,
                'step_name' => 'قراءة عقد الزواج',
                'step_description' => 'قراءة العقد على العميل والعروسة وولي الأمر',
                'estimated_duration_minutes' => 15,
                'required_resources' => 'partner',
                'dependencies' => '3',
                'step_type' => ServiceExecutionStep::TYPE_EXECUTION,
                'is_required' => true,
            ],
            [
                'step_order' => 5,
                'step_name' => 'التوقيع على العقد',
                'step_description' => 'توقيع جميع الأطراف على العقد',
                'estimated_duration_minutes' => 10,
                'required_resources' => 'partner',
                'dependencies' => '4',
                'step_type' => ServiceExecutionStep::TYPE_EXECUTION,
                'is_required' => true,
            ],
            [
                'step_order' => 6,
                'step_name' => 'التحقق من صحة التوقيعات',
                'step_description' => 'التحقق من صحة جميع التوقيعات والمعلومات',
                'estimated_duration_minutes' => 10,
                'required_resources' => 'partner',
                'dependencies' => '5',
                'step_type' => ServiceExecutionStep::TYPE_VERIFICATION,
                'is_required' => true,
            ],
            [
                'step_order' => 7,
                'step_name' => 'تسليم نسخة العقد للعميل',
                'step_description' => 'تسليم نسخة موقعة من العقد للعميل',
                'estimated_duration_minutes' => 5,
                'required_resources' => 'partner',
                'dependencies' => '6',
                'step_type' => ServiceExecutionStep::TYPE_DELIVERY,
                'is_required' => true,
            ],
        ];

        foreach ($steps as $stepData) {
            ServiceExecutionStep::create(array_merge($stepData, [
                'service_id' => $this->marriageService->id,
                'is_active' => true,
            ]));
        }
    }

    /**
     * Complete all remaining execution steps
     */
    protected function completeAllRemainingSteps(Appointment $appointment): void
    {
        $remainingSteps = $appointment->executionProgress()
            ->whereIn('status', [ExecutionProgress::STATUS_PENDING, ExecutionProgress::STATUS_IN_PROGRESS])
            ->orderBy('execution_step_id')
            ->get();

        foreach ($remainingSteps as $step) {
            $this->executionService->completeExecutionStep($step);
        }
    }
}
