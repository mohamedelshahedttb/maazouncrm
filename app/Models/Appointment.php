<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Appointment extends Model
{
    use LogsActivity;

    protected $fillable = [
        'client_id',
        'service_id',
        'assigned_to',
        'primary_partner_id',
        'appointment_date',
        'end_time',
        'execution_status',
        'execution_started_at',
        'execution_completed_at',
        'execution_notes',
        'location',
        'status',
        'notes',
        'requirements',
        'whatsapp_reminder_sent',
        'reminder_sent_at',
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
        'end_time' => 'datetime',
        'execution_started_at' => 'datetime',
        'execution_completed_at' => 'datetime',
        'whatsapp_reminder_sent' => 'boolean',
        'reminder_sent_at' => 'datetime',
    ];

    // Status constants
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_RESCHEDULED = 'rescheduled';

    // Execution status constants
    const EXECUTION_STATUS_SCHEDULED = 'scheduled';
    const EXECUTION_STATUS_IN_EXECUTION = 'in_execution';
    const EXECUTION_STATUS_COMPLETED = 'completed';
    const EXECUTION_STATUS_CANCELLED = 'cancelled';

    // Relationships
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function primaryPartner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'primary_partner_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function executionProgress(): HasMany
    {
        return $this->hasMany(ExecutionProgress::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(ClientOrder::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereNotIn('status', [self::STATUS_CANCELLED, self::STATUS_COMPLETED]);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByExecutionStatus($query, $executionStatus)
    {
        return $query->where('execution_status', $executionStatus);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date', '>', now());
    }

    public function scopeToday($query)
    {
        return $query->whereDate('appointment_date', today());
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', self::STATUS_CONFIRMED);
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('appointment_date', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeByExecutionPhase($query, $phase)
    {
        return $query->whereHas('executionProgress.executionStep', function ($q) use ($phase) {
            $q->where('step_type', $phase);
        });
    }

    // Methods
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_SCHEDULED => 'مجدول',
            self::STATUS_CONFIRMED => 'مؤكد',
            self::STATUS_IN_PROGRESS => 'قيد التنفيذ',
            self::STATUS_COMPLETED => 'مكتمل',
            self::STATUS_CANCELLED => 'ملغي',
            self::STATUS_RESCHEDULED => 'إعادة جدولة',
            default => 'غير محدد'
        };
    }

    public function getExecutionStatusLabelAttribute(): string
    {
        return match($this->execution_status) {
            self::EXECUTION_STATUS_SCHEDULED => 'مجدول',
            self::EXECUTION_STATUS_IN_EXECUTION => 'قيد التنفيذ',
            self::EXECUTION_STATUS_COMPLETED => 'مكتمل',
            self::EXECUTION_STATUS_CANCELLED => 'ملغي',
            default => 'غير محدد'
        };
    }

    public function getExecutionStatusColorAttribute(): string
    {
        return match($this->execution_status) {
            self::EXECUTION_STATUS_SCHEDULED => 'blue',
            self::EXECUTION_STATUS_IN_EXECUTION => 'yellow',
            self::EXECUTION_STATUS_COMPLETED => 'green',
            self::EXECUTION_STATUS_CANCELLED => 'red',
            default => 'gray'
        };
    }

    public function getDurationAttribute(): ?int
    {
        if (!$this->end_time) {
            return null;
        }

        return $this->appointment_date->diffInMinutes($this->end_time);
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

    public function getExecutionDurationAttribute(): ?int
    {
        if (!$this->execution_started_at) {
            return null;
        }

        $endTime = $this->execution_completed_at ?? now();
        return $this->execution_started_at->diffInMinutes($endTime);
    }

    public function getFormattedExecutionDurationAttribute(): string
    {
        $duration = $this->execution_duration;
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

    public function isConfirmed(): bool
    {
        return $this->status === self::STATUS_CONFIRMED;
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

    public function isExecutionStarted(): bool
    {
        return $this->execution_status === self::EXECUTION_STATUS_IN_EXECUTION;
    }

    public function isExecutionCompleted(): bool
    {
        return $this->execution_status === self::EXECUTION_STATUS_COMPLETED;
    }

    public function canStartExecution(): bool
    {
        return $this->isConfirmed() && $this->execution_status === self::EXECUTION_STATUS_SCHEDULED;
    }

    public function canCompleteExecution(): bool
    {
        return $this->execution_status === self::EXECUTION_STATUS_IN_EXECUTION;
    }

    public function startExecution(): void
    {
        $this->update([
            'execution_status' => self::EXECUTION_STATUS_IN_EXECUTION,
            'execution_started_at' => now(),
            'status' => self::STATUS_IN_PROGRESS
        ]);
    }

    public function completeExecution(): void
    {
        $this->update([
            'execution_status' => self::EXECUTION_STATUS_COMPLETED,
            'execution_completed_at' => now(),
            'status' => self::STATUS_COMPLETED
        ]);
    }

    public function getProgressPercentageAttribute(): int
    {
        $totalSteps = $this->executionProgress()->count();
        if ($totalSteps === 0) {
            return 0;
        }

        $completedSteps = $this->executionProgress()->where('status', ExecutionProgress::STATUS_COMPLETED)->count();
        return round(($completedSteps / $totalSteps) * 100);
    }

    public function getNextExecutionStepAttribute()
    {
        return $this->executionProgress()
            ->where('status', ExecutionProgress::STATUS_PENDING)
            ->orderBy('execution_step_id')
            ->first();
    }

    public function getBlockedExecutionStepsAttribute()
    {
        return $this->executionProgress()
            ->where('status', ExecutionProgress::STATUS_BLOCKED)
            ->with('executionStep')
            ->get();
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'client_id',
                'service_id',
                'assigned_to',
                'primary_partner_id',
                'appointment_date',
                'end_time',
                'execution_status',
                'execution_started_at',
                'execution_completed_at',
                'execution_notes',
                'location',
                'status',
                'notes',
                'requirements'
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
