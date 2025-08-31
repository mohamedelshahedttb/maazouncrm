<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ResourceAllocation extends Model
{
    use LogsActivity;

    protected $fillable = [
        'task_id',
        'execution_progress_id',
        'resource_type',
        'resource_id',
        'resource_name',
        'allocated_from',
        'allocated_until',
        'status',
        'allocation_notes',
        'release_notes',
        'is_active',
    ];

    protected $casts = [
        'allocated_from' => 'datetime',
        'allocated_until' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Resource type constants
    const TYPE_PARTNER = 'partner';
    const TYPE_SUPPLIER = 'supplier';
    const TYPE_EQUIPMENT = 'equipment';
    const TYPE_LOCATION = 'location';
    const TYPE_PRODUCT = 'product';

    // Status constants
    const STATUS_ALLOCATED = 'allocated';
    const STATUS_IN_USE = 'in_use';
    const STATUS_RELEASED = 'released';
    const STATUS_OVERDUE = 'overdue';

    // Relationships
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function executionProgress(): BelongsTo
    {
        return $this->belongsTo(ExecutionProgress::class);
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'resource_id')->where('resource_type', self::TYPE_PARTNER);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'resource_id')->where('resource_type', self::TYPE_SUPPLIER);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'resource_id')->where('resource_type', self::TYPE_PRODUCT);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('resource_type', $type);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeAllocated($query)
    {
        return $query->where('status', self::STATUS_ALLOCATED);
    }

    public function scopeInUse($query)
    {
        return $query->where('status', self::STATUS_IN_USE);
    }

    public function scopeReleased($query)
    {
        return $query->where('status', self::STATUS_RELEASED);
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', self::STATUS_OVERDUE);
    }

    public function scopeByResource($query, $type, $id)
    {
        return $query->where('resource_type', $type)->where('resource_id', $id);
    }

    public function scopeCurrent($query)
    {
        return $query->where('status', '!=', self::STATUS_RELEASED);
    }

    // Methods
    public function getResourceTypeLabelAttribute(): string
    {
        return match($this->resource_type) {
            self::TYPE_PARTNER => 'شريك',
            self::TYPE_SUPPLIER => 'مورد',
            self::TYPE_EQUIPMENT => 'معدات',
            self::TYPE_LOCATION => 'موقع',
            self::TYPE_PRODUCT => 'منتج',
            default => 'غير محدد'
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_ALLOCATED => 'مخصص',
            self::STATUS_IN_USE => 'قيد الاستخدام',
            self::STATUS_RELEASED => 'تم الإفراج',
            self::STATUS_OVERDUE => 'متأخر',
            default => 'غير محدد'
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_ALLOCATED => 'blue',
            self::STATUS_IN_USE => 'green',
            self::STATUS_RELEASED => 'gray',
            self::STATUS_OVERDUE => 'red',
            default => 'gray'
        };
    }

    public function getDurationAttribute(): int
    {
        $endTime = $this->allocated_until ?? now();
        return $this->allocated_from->diffInMinutes($endTime);
    }

    public function getFormattedDurationAttribute(): string
    {
        $duration = $this->duration;
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

    public function isOverdue(): bool
    {
        if (!$this->allocated_until) {
            return false;
        }

        return now()->isAfter($this->allocated_until) && $this->status !== self::STATUS_RELEASED;
    }

    public function isActive(): bool
    {
        return in_array($this->status, [self::STATUS_ALLOCATED, self::STATUS_IN_USE]);
    }

    public function canRelease(): bool
    {
        return in_array($this->status, [self::STATUS_ALLOCATED, self::STATUS_IN_USE]);
    }

    public function markAsInUse(): void
    {
        $this->update(['status' => self::STATUS_IN_USE]);
    }

    public function release(string $notes = null): void
    {
        $this->update([
            'status' => self::STATUS_RELEASED,
            'release_notes' => $notes,
            'allocated_until' => now()
        ]);
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'resource_type',
                'resource_id',
                'resource_name',
                'allocated_from',
                'allocated_until',
                'status',
                'allocation_notes',
                'release_notes'
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
