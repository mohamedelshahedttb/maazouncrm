<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SupplierOrder extends Model
{
    use LogsActivity;

    protected $fillable = [
        'supplier_id',
        'order_number',
        'description',
        'quantity',
        'unit_price',
        'total_amount',
        'currency',
        'expected_delivery_date',
        'actual_delivery_date',
        'status',
        'notes',
        'quality_notes',
        'delivery_notes',
        'is_active',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'expected_delivery_date' => 'date',
        'actual_delivery_date' => 'date',
        'is_active' => 'boolean',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    // Relationships
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
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

    public function scopeConfirmed($query)
    {
        return $query->where('status', self::STATUS_CONFIRMED);
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', self::STATUS_IN_PROGRESS);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', self::STATUS_DELIVERED);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    public function scopeOverdue($query)
    {
        return $query->where('expected_delivery_date', '<', today())->whereNotIn('status', [self::STATUS_DELIVERED, self::STATUS_CANCELLED]);
    }

    public function scopeBySupplier($query, $supplierId)
    {
        return $query->where('supplier_id', $supplierId);
    }

    public function scopeDueToday($query)
    {
        return $query->whereDate('expected_delivery_date', today());
    }

    public function scopeDueThisWeek($query)
    {
        return $query->whereBetween('expected_delivery_date', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    // Methods
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'في الانتظار',
            self::STATUS_CONFIRMED => 'مؤكد',
            self::STATUS_IN_PROGRESS => 'قيد التنفيذ',
            self::STATUS_DELIVERED => 'تم التسليم',
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
            self::STATUS_DELIVERED => 'green',
            self::STATUS_CANCELLED => 'red',
            default => 'gray'
        };
    }

    public function getFormattedUnitPriceAttribute(): string
    {
        return number_format($this->unit_price, 2) . ' ' . $this->currency;
    }

    public function getFormattedTotalAmountAttribute(): string
    {
        return number_format($this->total_amount, 2) . ' ' . $this->currency;
    }

    public function getFormattedExpectedDeliveryDateAttribute(): string
    {
        return $this->expected_delivery_date ? $this->expected_delivery_date->format('Y-m-d') : 'غير محدد';
    }

    public function getFormattedActualDeliveryDateAttribute(): string
    {
        return $this->actual_delivery_date ? $this->actual_delivery_date->format('Y-m-d') : 'غير محدد';
    }

    public function isOverdue(): bool
    {
        return $this->expected_delivery_date && $this->expected_delivery_date->isPast() && !in_array($this->status, [self::STATUS_DELIVERED, self::STATUS_CANCELLED]);
    }

    public function isDueToday(): bool
    {
        return $this->expected_delivery_date && $this->expected_delivery_date->isToday();
    }

    public function isDueThisWeek(): bool
    {
        return $this->expected_delivery_date && $this->expected_delivery_date->between(now()->startOfWeek(), now()->endOfWeek());
    }

    public function canBeConfirmed(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function canBeStarted(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_CONFIRMED]);
    }

    public function canBeDelivered(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_CONFIRMED, self::STATUS_IN_PROGRESS]);
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_CONFIRMED]);
    }

    public function confirm(): void
    {
        if ($this->canBeConfirmed()) {
            $this->update(['status' => self::STATUS_CONFIRMED]);
        }
    }

    public function start(): void
    {
        if ($this->canBeStarted()) {
            $this->update(['status' => self::STATUS_IN_PROGRESS]);
        }
    }

    public function deliver(): void
    {
        if ($this->canBeDelivered()) {
            $this->update([
                'status' => self::STATUS_DELIVERED,
                'actual_delivery_date' => now(),
            ]);
        }
    }

    public function cancel(): void
    {
        if ($this->canBeCancelled()) {
            $this->update(['status' => self::STATUS_CANCELLED]);
        }
    }

    public function getDeliveryDelayAttribute(): int
    {
        if ($this->actual_delivery_date && $this->expected_delivery_date) {
            return $this->expected_delivery_date->diffInDays($this->actual_delivery_date);
        }
        return 0;
    }

    public function getFormattedDeliveryDelayAttribute(): string
    {
        $delay = $this->delivery_delay;
        if ($delay === 0) {
            return 'في الموعد';
        } elseif ($delay > 0) {
            return "متأخر {$delay} يوم";
        } else {
            return "مبكر " . abs($delay) . " يوم";
        }
    }

    public function getDeliveryDelayColorAttribute(): string
    {
        $delay = $this->delivery_delay;
        if ($delay === 0) {
            return 'green';
        } elseif ($delay > 0) {
            return 'red';
        } else {
            return 'blue';
        }
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'expected_delivery_date', 'actual_delivery_date', 'total_amount'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
