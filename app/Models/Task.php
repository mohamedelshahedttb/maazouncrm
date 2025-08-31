<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Task extends Model implements HasMedia
{
    use LogsActivity, InteractsWithMedia;

    protected $fillable = [
        'title',
        'description',
        'prerequisites',
        'appointment_id',
        'assigned_to',
        'partner_id',
        'execution_step_id',
        'resource_allocation_id',
        'execution_phase',
        'priority',
        'status',
        'due_date',
        'started_at',
        'completed_at',
        'execution_notes',
        'deliverables',
        'estimated_cost',
        'cost_currency',
        'location',
        'changes_notes',
        'is_active',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'estimated_cost' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Priority constants
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_DELAYED = 'delayed';

    // Execution phase constants
    const PHASE_PREPARATION = 'preparation';
    const PHASE_EXECUTION = 'execution';
    const PHASE_VERIFICATION = 'verification';
    const PHASE_DELIVERY = 'delivery';

    // Relationships
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function executionStep(): BelongsTo
    {
        return $this->belongsTo(ServiceExecutionStep::class);
    }

    public function resourceAllocation(): BelongsTo
    {
        return $this->belongsTo(ResourceAllocation::class);
    }

    public function resourceAllocations(): HasMany
    {
        return $this->hasMany(ResourceAllocation::class);
    }

    public function getClientAttribute()
    {
        return $this->appointment?->client;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByExecutionPhase($query, $phase)
    {
        return $query->where('execution_phase', $phase);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', self::STATUS_IN_PROGRESS);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
            ->whereNotIn('status', [self::STATUS_COMPLETED, self::STATUS_CANCELLED]);
    }

    public function scopeByAppointment($query, $appointmentId)
    {
        return $query->where('appointment_id', $appointmentId);
    }

    public function scopeByPartner($query, $partnerId)
    {
        return $query->where('partner_id', $partnerId);
    }

    public function scopeByAssignedUser($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    // Methods
    public function getPriorityLabelAttribute(): string
    {
        return match($this->priority) {
            self::PRIORITY_LOW => 'منخفضة',
            self::PRIORITY_MEDIUM => 'متوسطة',
            self::PRIORITY_HIGH => 'عالية',
            self::PRIORITY_URGENT => 'عاجلة',
            default => 'غير محدد'
        };
    }

    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            self::PRIORITY_LOW => 'green',
            self::PRIORITY_MEDIUM => 'yellow',
            self::PRIORITY_HIGH => 'orange',
            self::PRIORITY_URGENT => 'red',
            default => 'gray'
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'معلق',
            self::STATUS_IN_PROGRESS => 'قيد التنفيذ',
            self::STATUS_COMPLETED => 'مكتمل',
            self::STATUS_CANCELLED => 'ملغي',
            self::STATUS_DELAYED => 'متأخر',
            default => 'غير محدد'
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'yellow',
            self::STATUS_IN_PROGRESS => 'blue',
            self::STATUS_COMPLETED => 'green',
            self::STATUS_CANCELLED => 'red',
            self::STATUS_DELAYED => 'orange',
            default => 'gray'
        };
    }

    public function getExecutionPhaseLabelAttribute(): string
    {
        return match($this->execution_phase) {
            self::PHASE_PREPARATION => 'إعداد',
            self::PHASE_EXECUTION => 'تنفيذ',
            self::PHASE_VERIFICATION => 'تحقق',
            self::PHASE_DELIVERY => 'تسليم',
            default => 'غير محدد'
        };
    }

    public function getExecutionPhaseColorAttribute(): string
    {
        return match($this->execution_phase) {
            self::PHASE_PREPARATION => 'blue',
            self::PHASE_EXECUTION => 'green',
            self::PHASE_VERIFICATION => 'yellow',
            self::PHASE_DELIVERY => 'purple',
            default => 'gray'
        };
    }

    public function getFormattedEstimatedCostAttribute(): string
    {
        if (!$this->estimated_cost) {
            return 'غير محدد';
        }

        return number_format($this->estimated_cost, 2) . ' ' . $this->cost_currency;
    }

    public function getDurationAttribute(): ?int
    {
        if (!$this->started_at) {
            return null;
        }

        $endTime = $this->completed_at ?? now();
        return $this->started_at->diffInMinutes($endTime);
    }

    public function getFormattedDurationAttribute(): string
    {
        $duration = $this->duration;
        if (!$duration) {
            return 'غير محدد';
        }

        $hours = floor($duration / 60);
        $minutes = $duration % 60;

        if ($hours > 0 && $minutes > 0) {
            return "{$hours} ساعة و {$minutes} دقيقة";
        } elseif ($hours > 0) {
            return "{$hours} ساعة";
        } else {
            return "{$minutes} دقيقة";
        }
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isInProgress(): bool
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isOverdue(): bool
    {
        if (!$this->due_date || $this->isCompleted() || $this->isCancelled()) {
            return false;
        }

        return now()->isAfter($this->due_date);
    }

    public function canStart(): bool
    {
        return $this->isPending() && !$this->isOverdue();
    }

    public function canComplete(): bool
    {
        return $this->isInProgress();
    }

    public function canCancel(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_IN_PROGRESS]);
    }

    public function start(): void
    {
        $this->update([
            'status' => self::STATUS_IN_PROGRESS,
            'started_at' => now()
        ]);
    }

    public function complete(): void
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'completed_at' => now()
        ]);
    }

    public function cancel(): void
    {
        $this->update([
            'status' => self::STATUS_CANCELLED,
            'completed_at' => now()
        ]);
    }

    public function getPrerequisitesArrayAttribute(): array
    {
        if (!$this->prerequisites) {
            return [];
        }

        return array_filter(array_map('trim', explode(',', $this->prerequisites)));
    }

    public function getDeliverablesArrayAttribute(): array
    {
        if (!$this->deliverables) {
            return [];
        }

        return array_filter(array_map('trim', explode(',', $this->deliverables)));
    }

    public function hasResourceAllocation(): bool
    {
        return $this->resourceAllocations()->exists();
    }

    public function getActiveResourceAllocationsAttribute()
    {
        return $this->resourceAllocations()->current()->get();
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'title',
                'description',
                'prerequisites',
                'appointment_id',
                'assigned_to',
                'partner_id',
                'execution_step_id',
                'execution_phase',
                'priority',
                'status',
                'due_date',
                'started_at',
                'completed_at',
                'execution_notes',
                'deliverables',
                'estimated_cost',
                'cost_currency',
                'location',
                'changes_notes',
                'is_active'
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
