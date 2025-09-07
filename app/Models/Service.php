<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Service extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'category',
        'description',
        'price',
        'currency',
        'duration_minutes',
        'requirements',
        'notes',
        'is_active',
    ];

    protected $attributes = [
        'currency' => 'EGP',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration_minutes' => 'integer',
        'is_active' => 'boolean',
    ];

    // Category constants
    const CATEGORY_MARRIAGE = 'marriage';
    const CATEGORY_DIVORCE = 'divorce';
    const CATEGORY_NOTARIZATION = 'notarization';
    const CATEGORY_TRANSLATION = 'translation';
    const CATEGORY_CONSULTATION = 'consultation';
    const CATEGORY_OTHER = 'other';

    // Relationships
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(ClientOrder::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function requiredDocuments(): HasMany
    {
        return $this->hasMany(RequiredDocument::class)->ordered();
    }

    public function activeRequiredDocuments(): HasMany
    {
        return $this->requiredDocuments()->active();
    }

    public function executionSteps(): HasMany
    {
        return $this->hasMany(ServiceExecutionStep::class)->orderBy('step_order');
    }

    public function activeExecutionSteps(): HasMany
    {
        return $this->executionSteps()->active();
    }

    public function requiredExecutionSteps(): HasMany
    {
        return $this->executionSteps()->required();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Methods
    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            self::CATEGORY_MARRIAGE => 'زواج',
            self::CATEGORY_DIVORCE => 'طلاق',
            self::CATEGORY_NOTARIZATION => 'تصديق',
            self::CATEGORY_TRANSLATION => 'ترجمة',
            self::CATEGORY_CONSULTATION => 'استشارة',
            self::CATEGORY_OTHER => 'أخرى',
            default => 'غير محدد'
        };
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 2) . ' ' . $this->currency;
    }

    public function getFormattedDurationAttribute(): string
    {
        if (!$this->duration_minutes) {
            return 'غير محدد';
        }

        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        if ($hours > 0 && $minutes > 0) {
            return "{$hours} ساعة و {$minutes} دقيقة";
        } elseif ($hours > 0) {
            return "{$hours} ساعة";
        } else {
            return "{$minutes} دقيقة";
        }
    }

    public function getEstimatedTotalDurationAttribute(): int
    {
        return $this->activeExecutionSteps()->sum('estimated_duration_minutes');
    }

    public function getFormattedTotalDurationAttribute(): string
    {
        $totalMinutes = $this->estimated_total_duration;
        if (!$totalMinutes) {
            return 'غير محدد';
        }

        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;

        if ($hours > 0 && $minutes > 0) {
            return "{$hours} ساعة و {$minutes} دقيقة";
        } elseif ($hours > 0) {
            return "{$hours} ساعة";
        } else {
            return "{$minutes} دقيقة";
        }
    }

    public function hasExecutionSteps(): bool
    {
        return $this->activeExecutionSteps()->exists();
    }

    public function getExecutionStepsCountAttribute(): int
    {
        return $this->activeExecutionSteps()->count();
    }

    public function getPreparationStepsAttribute()
    {
        return $this->executionSteps()->byType(ServiceExecutionStep::TYPE_PREPARATION)->get();
    }

    public function getExecutionStepsAttribute()
    {
        return $this->executionSteps()->byType(ServiceExecutionStep::TYPE_EXECUTION)->get();
    }

    public function getVerificationStepsAttribute()
    {
        return $this->executionSteps()->byType(ServiceExecutionStep::TYPE_VERIFICATION)->get();
    }

    public function getDeliveryStepsAttribute()
    {
        return $this->executionSteps()->byType(ServiceExecutionStep::TYPE_DELIVERY)->get();
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
                'category',
                'description',
                'price',
                'currency',
                'duration_minutes',
                'requirements',
                'notes',
                'is_active'
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
