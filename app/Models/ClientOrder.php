<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ClientOrder extends Model
{
    use LogsActivity;

    protected $fillable = [
        'client_id',
        'service_id',
        'appointment_id',
        'status',
        'total_amount',
        'paid_amount',
        'currency',
        'expected_completion_date',
        'actual_completion_date',
        'requirements',
        'notes',
        'special_instructions',
        'is_active',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'expected_completion_date' => 'date',
        'actual_completion_date' => 'date',
        'is_active' => 'boolean',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    // Relationships
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
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

    public function scopeOverdue($query)
    {
        return $query->where('expected_completion_date', '<', today())->whereNotIn('status', [self::STATUS_COMPLETED, self::STATUS_CANCELLED]);
    }

    // Methods
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'في الانتظار',
            self::STATUS_CONFIRMED => 'مؤكد',
            self::STATUS_IN_PROGRESS => 'قيد التنفيذ',
            self::STATUS_COMPLETED => 'مكتمل',
            self::STATUS_CANCELLED => 'ملغي',
            default => 'غير محدد'
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'blue',
            self::STATUS_CONFIRMED => 'green',
            self::STATUS_IN_PROGRESS => 'yellow',
            self::STATUS_COMPLETED => 'green',
            self::STATUS_CANCELLED => 'red',
            default => 'gray'
        };
    }

    public function getFormattedTotalAmountAttribute(): string
    {
        return number_format($this->total_amount, 2) . ' ' . $this->currency;
    }

    public function getFormattedPaidAmountAttribute(): string
    {
        return number_format($this->paid_amount, 2) . ' ' . $this->currency;
    }

    public function getRemainingAmountAttribute(): float
    {
        return $this->total_amount - $this->paid_amount;
    }

    public function getFormattedRemainingAmountAttribute(): string
    {
        return number_format($this->remaining_amount, 2) . ' ' . $this->currency;
    }

    public function getPaymentPercentageAttribute(): float
    {
        if ($this->total_amount > 0) {
            return round(($this->paid_amount / $this->total_amount) * 100, 2);
        }
        return 0;
    }

    public function getFormattedPaymentPercentageAttribute(): string
    {
        return $this->payment_percentage . '%';
    }

    public function getFormattedExpectedCompletionDateAttribute(): string
    {
        return $this->expected_completion_date ? $this->expected_completion_date->format('Y-m-d') : 'غير محدد';
    }

    public function getFormattedActualCompletionDateAttribute(): string
    {
        return $this->actual_completion_date ? $this->actual_completion_date->format('Y-m-d') : 'غير محدد';
    }

    public function isOverdue(): bool
    {
        return $this->expected_completion_date && $this->expected_completion_date->isPast() && !in_array($this->status, [self::STATUS_COMPLETED, self::STATUS_CANCELLED]);
    }

    public function isFullyPaid(): bool
    {
        return $this->paid_amount >= $this->total_amount;
    }

    public function isPartiallyPaid(): bool
    {
        return $this->paid_amount > 0 && $this->paid_amount < $this->total_amount;
    }

    public function isUnpaid(): bool
    {
        return $this->paid_amount == 0;
    }

    public function canBeConfirmed(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function canBeStarted(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_CONFIRMED]);
    }

    public function canBeCompleted(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_CONFIRMED, self::STATUS_IN_PROGRESS]);
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_CONFIRMED]);
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'total_amount', 'paid_amount', 'expected_completion_date'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
