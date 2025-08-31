<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ExecutionProgress extends Model
{
    use LogsActivity;

    protected $fillable = [
        'appointment_id',
        'execution_step_id',
        'task_id',
        'status',
        'started_at',
        'completed_at',
        'execution_notes',
        'blocking_reason',
        'assigned_to',
        'assigned_partner_id',
        'is_active',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_BLOCKED = 'blocked';
    const STATUS_SKIPPED = 'skipped';

    // Relationships
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function executionStep(): BelongsTo
    {
        return $this->belongsTo(ServiceExecutionStep::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignedPartner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'assigned_partner_id');
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

    public function scopeBlocked($query)
    {
        return $query->where('status', self::STATUS_BLOCKED);
    }

    public function scopeByAppointment($query, $appointmentId)
    {
        return $query->where('appointment_id', $appointmentId);
    }

    // Methods
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'معلق',
            self::STATUS_IN_PROGRESS => 'قيد التنفيذ',
            self::STATUS_COMPLETED => 'مكتمل',
            self::STATUS_BLOCKED => 'معطل',
            self::STATUS_SKIPPED => 'تم تخطيه',
            default => 'غير محدد'
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'yellow',
            self::STATUS_IN_PROGRESS => 'blue',
            self::STATUS_COMPLETED => 'green',
            self::STATUS_BLOCKED => 'red',
            self::STATUS_SKIPPED => 'gray',
            default => 'gray'
        };
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

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isBlocked(): bool
    {
        return $this->status === self::STATUS_BLOCKED;
    }

    public function isInProgress(): bool
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    public function canStart(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function canComplete(): bool
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'status',
                'started_at',
                'completed_at',
                'execution_notes',
                'blocking_reason',
                'assigned_to',
                'assigned_partner_id'
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
