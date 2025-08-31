<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PartnerAssistanceRequest extends Model
{
    use LogsActivity;

    protected $fillable = [
        'requesting_partner_id',
        'assisting_partner_id',
        'service_type',
        'requested_date_time',
        'location',
        'description',
        'status',
        'commission_amount',
        'notes',
        'accepted_at',
        'completed_at',
        'is_active',
    ];

    protected $casts = [
        'requested_date_time' => 'datetime',
        'accepted_at' => 'datetime',
        'completed_at' => 'datetime',
        'commission_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_REJECTED = 'rejected';

    // Relationships
    public function requestingPartner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'requesting_partner_id');
    }

    public function assistingPartner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'assisting_partner_id');
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

    public function scopeAccepted($query)
    {
        return $query->where('status', self::STATUS_ACCEPTED);
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', self::STATUS_IN_PROGRESS);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeByRequestingPartner($query, $partnerId)
    {
        return $query->where('requesting_partner_id', $partnerId);
    }

    public function scopeByAssistingPartner($query, $partnerId)
    {
        return $query->where('assisting_partner_id', $partnerId);
    }

    // Methods
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'في الانتظار',
            self::STATUS_ACCEPTED => 'مقبول',
            self::STATUS_IN_PROGRESS => 'قيد التنفيذ',
            self::STATUS_COMPLETED => 'مكتمل',
            self::STATUS_REJECTED => 'مرفوض',
            default => 'غير محدد'
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'blue',
            self::STATUS_ACCEPTED => 'green',
            self::STATUS_IN_PROGRESS => 'yellow',
            self::STATUS_COMPLETED => 'green',
            self::STATUS_REJECTED => 'red',
            default => 'gray'
        };
    }

    public function getFormattedRequestedDateTimeAttribute(): string
    {
        return $this->requested_date_time->format('Y-m-d H:i');
    }

    public function getFormattedAcceptedDateAttribute(): string
    {
        return $this->accepted_at ? $this->accepted_at->format('Y-m-d H:i') : 'غير محدد';
    }

    public function getFormattedCompletedDateAttribute(): string
    {
        return $this->completed_at ? $this->completed_at->format('Y-m-d H:i') : 'غير محدد';
    }

    public function getFormattedCommissionAmountAttribute(): string
    {
        return $this->commission_amount ? number_format($this->commission_amount, 2) . ' جنيه مصري' : 'غير محدد';
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isAccepted(): bool
    {
        return $this->status === self::STATUS_ACCEPTED;
    }

    public function isInProgress(): bool
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function canBeAccepted(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function canBeRejected(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function canBeStarted(): bool
    {
        return $this->status === self::STATUS_ACCEPTED;
    }

    public function canBeCompleted(): bool
    {
        return in_array($this->status, [self::STATUS_ACCEPTED, self::STATUS_IN_PROGRESS]);
    }

    public function accept(): void
    {
        if ($this->canBeAccepted()) {
            $this->update([
                'status' => self::STATUS_ACCEPTED,
                'accepted_at' => now(),
            ]);
        }
    }

    public function reject(): void
    {
        if ($this->canBeRejected()) {
            $this->update(['status' => self::STATUS_REJECTED]);
        }
    }

    public function start(): void
    {
        if ($this->canBeStarted()) {
            $this->update(['status' => self::STATUS_IN_PROGRESS]);
        }
    }

    public function complete(): void
    {
        if ($this->canBeCompleted()) {
            $this->update([
                'status' => self::STATUS_COMPLETED,
                'completed_at' => now(),
            ]);
        }
    }

    public function getDurationAttribute(): int
    {
        if ($this->accepted_at && $this->completed_at) {
            return $this->accepted_at->diffInMinutes($this->completed_at);
        }
        return 0;
    }

    public function getFormattedDurationAttribute(): string
    {
        $duration = $this->duration;
        if ($duration === 0) {
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

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'assisting_partner_id', 'service_type', 'requested_date_time'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
