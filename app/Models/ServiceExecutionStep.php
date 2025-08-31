<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ServiceExecutionStep extends Model
{
    use LogsActivity;

    protected $fillable = [
        'service_id',
        'step_order',
        'step_name',
        'step_description',
        'estimated_duration_minutes',
        'required_resources',
        'dependencies',
        'step_type',
        'is_required',
        'is_active',
    ];

    protected $casts = [
        'step_order' => 'integer',
        'estimated_duration_minutes' => 'integer',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Step type constants
    const TYPE_PREPARATION = 'preparation';
    const TYPE_EXECUTION = 'execution';
    const TYPE_VERIFICATION = 'verification';
    const TYPE_DELIVERY = 'delivery';

    // Relationships
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function executionProgress(): HasMany
    {
        return $this->hasMany(ExecutionProgress::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('step_type', $type);
    }

    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    public function scopeByOrder($query)
    {
        return $query->orderBy('step_order');
    }

    // Methods
    public function getStepTypeLabelAttribute(): string
    {
        return match($this->step_type) {
            self::TYPE_PREPARATION => 'إعداد',
            self::TYPE_EXECUTION => 'تنفيذ',
            self::TYPE_VERIFICATION => 'تحقق',
            self::TYPE_DELIVERY => 'تسليم',
            default => 'غير محدد'
        };
    }

    public function getEstimatedDurationHoursAttribute(): float
    {
        return $this->estimated_duration_minutes ? round($this->estimated_duration_minutes / 60, 2) : 0;
    }

    public function getFormattedDurationAttribute(): string
    {
        if (!$this->estimated_duration_minutes) {
            return 'غير محدد';
        }

        $hours = floor($this->estimated_duration_minutes / 60);
        $minutes = $this->estimated_duration_minutes % 60;

        if ($hours > 0 && $minutes > 0) {
            return "{$hours} ساعة و {$minutes} دقيقة";
        } elseif ($hours > 0) {
            return "{$hours} ساعة";
        } else {
            return "{$minutes} دقيقة";
        }
    }

    public function getDependenciesArrayAttribute(): array
    {
        if (!$this->dependencies) {
            return [];
        }

        return array_filter(array_map('trim', explode(',', $this->dependencies)));
    }

    public function getRequiredResourcesArrayAttribute(): array
    {
        if (!$this->required_resources) {
            return [];
        }

        return array_filter(array_map('trim', explode(',', $this->required_resources)));
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'step_name',
                'step_description',
                'estimated_duration_minutes',
                'step_type',
                'is_required',
                'is_active'
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
