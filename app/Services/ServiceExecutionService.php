<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\ServiceExecutionStep;
use App\Models\ExecutionProgress;
use App\Models\Task;
use App\Models\ResourceAllocation;
use App\Models\Partner;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServiceExecutionService
{
    /**
     * Initialize execution workflow for an appointment
     */
    public function initializeExecution(Appointment $appointment): bool
    {
        try {
            DB::beginTransaction();

            // Check if service has execution steps
            if (!$appointment->service->hasExecutionSteps()) {
                Log::warning("Service {$appointment->service->id} has no execution steps");
                return false;
            }

            // Create execution progress records for each step
            $executionSteps = $appointment->service->activeExecutionSteps()->orderBy('step_order')->get();
            
            foreach ($executionSteps as $step) {
                ExecutionProgress::create([
                    'appointment_id' => $appointment->id,
                    'execution_step_id' => $step->id,
                    'status' => ExecutionProgress::STATUS_PENDING,
                    'is_active' => true,
                ]);
            }

            // Generate tasks for each execution step
            $this->generateTasksFromExecutionSteps($appointment, $executionSteps);

            // Update appointment execution status
            $appointment->update([
                'execution_status' => Appointment::EXECUTION_STATUS_SCHEDULED
            ]);

            DB::commit();
            Log::info("Execution workflow initialized for appointment {$appointment->id}");
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to initialize execution for appointment {$appointment->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate tasks from execution steps
     */
    protected function generateTasksFromExecutionSteps(Appointment $appointment, $executionSteps): void
    {
        foreach ($executionSteps as $step) {
            $task = Task::create([
                'title' => $step->step_name,
                'description' => $step->step_description,
                'prerequisites' => $step->dependencies,
                'appointment_id' => $appointment->id,
                'execution_step_id' => $step->id,
                'execution_phase' => $step->step_type,
                'priority' => $this->determineTaskPriority($step),
                'status' => Task::STATUS_PENDING,
                'due_date' => $this->calculateTaskDueDate($appointment, $step),
                'estimated_cost' => $this->estimateTaskCost($step),
                'cost_currency' => 'EGP',
                'is_active' => true,
            ]);

            // Link task to execution progress
            ExecutionProgress::where('appointment_id', $appointment->id)
                ->where('execution_step_id', $step->id)
                ->update(['task_id' => $task->id]);

            // Auto-assign resources if possible
            $this->autoAssignResources($task, $step);
        }
    }

    /**
     * Determine task priority based on execution step
     */
    protected function determineTaskPriority(ServiceExecutionStep $step): string
    {
        return match($step->step_type) {
            ServiceExecutionStep::TYPE_PREPARATION => Task::PRIORITY_MEDIUM,
            ServiceExecutionStep::TYPE_EXECUTION => Task::PRIORITY_HIGH,
            ServiceExecutionStep::TYPE_VERIFICATION => Task::PRIORITY_MEDIUM,
            ServiceExecutionStep::TYPE_DELIVERY => Task::PRIORITY_HIGH,
            default => Task::PRIORITY_MEDIUM
        };
    }

    /**
     * Calculate task due date based on appointment and step
     */
    protected function calculateTaskDueDate(Appointment $appointment, ServiceExecutionStep $step): ?string
    {
        if (!$step->estimated_duration_minutes) {
            return null;
        }

        // Calculate based on step order and estimated duration
        $baseDate = $appointment->appointment_date;
        $stepOffset = ($step->step_order - 1) * 30; // 30 minutes buffer between steps
        
        return $baseDate->addMinutes($stepOffset + $step->estimated_duration_minutes);
    }

    /**
     * Estimate task cost based on execution step
     */
    protected function estimateTaskCost(ServiceExecutionStep $step): ?float
    {
        // This could be enhanced with more sophisticated cost calculation
        // For now, return a basic estimate based on step type
        return match($step->step_type) {
            ServiceExecutionStep::TYPE_PREPARATION => 50.00,
            ServiceExecutionStep::TYPE_EXECUTION => 100.00,
            ServiceExecutionStep::TYPE_VERIFICATION => 75.00,
            ServiceExecutionStep::TYPE_DELIVERY => 25.00,
            default => 50.00
        };
    }

    /**
     * Auto-assign resources to task
     */
    protected function autoAssignResources(Task $task, ServiceExecutionStep $step): void
    {
        if (!$step->required_resources) {
            return;
        }

        $requiredResources = $step->required_resources_array;
        
        foreach ($requiredResources as $resource) {
            $this->assignResourceToTask($task, $resource);
        }
    }

    /**
     * Assign a specific resource to a task
     */
    protected function assignResourceToTask(Task $task, string $resourceType): void
    {
        $resource = $this->findAvailableResource($resourceType);
        
        if ($resource) {
            ResourceAllocation::create([
                'task_id' => $task->id,
                'resource_type' => $resourceType,
                'resource_id' => $resource->id,
                'resource_name' => $resource->name ?? $resource->title ?? 'Unknown',
                'allocated_from' => now(),
                'status' => ResourceAllocation::STATUS_ALLOCATED,
                'is_active' => true,
            ]);
        }
    }

    /**
     * Find available resource of specified type
     */
    protected function findAvailableResource(string $resourceType)
    {
        return match($resourceType) {
            'partner' => Partner::active()->available()->first(),
            'supplier' => Supplier::active()->available()->first(),
            'equipment' => null, // Equipment model would be implemented here
            'location' => null, // Location model would be implemented here
            'product' => null, // Product availability would be checked here
            default => null
        };
    }

    /**
     * Start execution of an appointment
     */
    public function startExecution(Appointment $appointment): bool
    {
        try {
            DB::beginTransaction();

            // Update appointment status
            $appointment->startExecution();

            // Start first pending execution step
            $firstStep = $appointment->executionProgress()
                ->where('status', ExecutionProgress::STATUS_PENDING)
                ->orderBy('execution_step_id')
                ->first();

            if ($firstStep) {
                $this->startExecutionStep($firstStep);
            }

            DB::commit();
            Log::info("Execution started for appointment {$appointment->id}");
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to start execution for appointment {$appointment->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Start execution of a specific step
     */
    public function startExecutionStep(ExecutionProgress $executionStep): bool
    {
        try {
            $executionStep->update([
                'status' => ExecutionProgress::STATUS_IN_PROGRESS,
                'started_at' => now()
            ]);

            // Update related task if exists
            if ($executionStep->task) {
                $executionStep->task->start();
            }

            Log::info("Execution step {$executionStep->id} started");
            return true;

        } catch (\Exception $e) {
            Log::error("Failed to start execution step {$executionStep->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Complete execution of a specific step
     */
    public function completeExecutionStep(ExecutionProgress $executionStep): bool
    {
        try {
            DB::beginTransaction();

            $executionStep->update([
                'status' => ExecutionProgress::STATUS_COMPLETED,
                'completed_at' => now()
            ]);

            // Update related task if exists
            if ($executionStep->task) {
                $executionStep->task->complete();
            }

            // Release allocated resources
            $this->releaseTaskResources($executionStep->task);

            // Check if all steps are completed
            $appointment = $executionStep->appointment;
            if ($this->isAppointmentExecutionComplete($appointment)) {
                $this->completeAppointmentExecution($appointment);
            } else {
                // Start next pending step
                $this->startNextPendingStep($appointment);
            }

            DB::commit();
            Log::info("Execution step {$executionStep->id} completed");
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to complete execution step {$executionStep->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Release resources allocated to a task
     */
    protected function releaseTaskResources(?Task $task): void
    {
        if (!$task) {
            return;
        }

        $task->resourceAllocations()
            ->current()
            ->update(['status' => ResourceAllocation::STATUS_RELEASED]);
    }

    /**
     * Check if appointment execution is complete
     */
    protected function isAppointmentExecutionComplete(Appointment $appointment): bool
    {
        $totalSteps = $appointment->executionProgress()->count();
        $completedSteps = $appointment->executionProgress()
            ->where('status', ExecutionProgress::STATUS_COMPLETED)
            ->count();

        return $totalSteps > 0 && $totalSteps === $completedSteps;
    }

    /**
     * Complete appointment execution
     */
    protected function completeAppointmentExecution(Appointment $appointment): void
    {
        $appointment->completeExecution();
        Log::info("Appointment {$appointment->id} execution completed");
    }

    /**
     * Start next pending execution step
     */
    protected function startNextPendingStep(Appointment $appointment): void
    {
        $nextStep = $appointment->executionProgress()
            ->where('status', ExecutionProgress::STATUS_PENDING)
            ->orderBy('execution_step_id')
            ->first();

        if ($nextStep) {
            $this->startExecutionStep($nextStep);
        }
    }

    /**
     * Block execution step with reason
     */
    public function blockExecutionStep(ExecutionProgress $executionStep, string $reason): bool
    {
        try {
            $executionStep->update([
                'status' => ExecutionProgress::STATUS_BLOCKED,
                'blocking_reason' => $reason
            ]);

            Log::info("Execution step {$executionStep->id} blocked: {$reason}");
            return true;

        } catch (\Exception $e) {
            Log::error("Failed to block execution step {$executionStep->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get execution progress summary for an appointment
     */
    public function getExecutionProgressSummary(Appointment $appointment): array
    {
        $progress = $appointment->executionProgress()
            ->with('executionStep')
            ->orderBy('execution_step_id')
            ->get();

        $summary = [
            'total_steps' => $progress->count(),
            'completed_steps' => $progress->where('status', ExecutionProgress::STATUS_COMPLETED)->count(),
            'in_progress_steps' => $progress->where('status', ExecutionProgress::STATUS_IN_PROGRESS)->count(),
            'pending_steps' => $progress->where('status', ExecutionProgress::STATUS_PENDING)->count(),
            'blocked_steps' => $progress->where('status', ExecutionProgress::STATUS_BLOCKED)->count(),
            'progress_percentage' => 0,
            'estimated_completion_time' => null,
            'steps' => $progress->map(function ($step) {
                return [
                    'id' => $step->id,
                    'step_name' => $step->executionStep->step_name,
                    'step_type' => $step->executionStep->step_type,
                    'status' => $step->status,
                    'status_label' => $step->status_label,
                    'started_at' => $step->started_at,
                    'completed_at' => $step->completed_at,
                    'duration' => $step->formatted_duration,
                    'blocking_reason' => $step->blocking_reason,
                ];
            })
        ];

        if ($summary['total_steps'] > 0) {
            $summary['progress_percentage'] = round(($summary['completed_steps'] / $summary['total_steps']) * 100);
        }

        return $summary;
    }
}
