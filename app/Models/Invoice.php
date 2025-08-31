<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Invoice extends Model implements HasMedia
{
    use LogsActivity, InteractsWithMedia;

    protected $fillable = [
        'invoice_number',
        'client_id',
        'service_id',
        'appointment_id',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'currency',
        'status',
        'issue_date',
        'due_date',
        'paid_date',
        'notes',
        'payment_terms',
        'payment_method',
        'billing_address',
        'is_active',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'issue_date' => 'date',
        'due_date' => 'date',
        'paid_date' => 'date',
        'is_active' => 'boolean',
    ];

    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_SENT = 'sent';
    const STATUS_PAID = 'paid';
    const STATUS_OVERDUE = 'overdue';
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

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())->where('status', '!=', self::STATUS_PAID);
    }

    public function scopeDueThisWeek($query)
    {
        return $query->whereBetween('due_date', [now(), now()->addWeek()])->where('status', '!=', self::STATUS_PAID);
    }

    // Methods
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_DRAFT => 'مسودة',
            self::STATUS_SENT => 'مرسلة',
            self::STATUS_PAID => 'مدفوعة',
            self::STATUS_OVERDUE => 'متأخرة',
            self::STATUS_CANCELLED => 'ملغية',
            default => 'غير محدد'
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_DRAFT => 'gray',
            self::STATUS_SENT => 'blue',
            self::STATUS_PAID => 'green',
            self::STATUS_OVERDUE => 'red',
            self::STATUS_CANCELLED => 'red',
            default => 'gray'
        };
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isSent(): bool
    {
        return $this->status === self::STATUS_SENT;
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    public function isOverdue(): bool
    {
        return $this->status === self::STATUS_OVERDUE;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function canBeSent(): bool
    {
        return $this->isDraft();
    }

    public function canBePaid(): bool
    {
        return $this->isSent() || $this->isOverdue();
    }

    public function canBeCancelled(): bool
    {
        return !$this->isPaid();
    }

    public function markAsSent(): void
    {
        if ($this->canBeSent()) {
            $this->update(['status' => self::STATUS_SENT]);
        }
    }

    public function markAsPaid(string $paymentMethod = null): void
    {
        if ($this->canBePaid()) {
            $this->update([
                'status' => self::STATUS_PAID,
                'paid_date' => now(),
                'payment_method' => $paymentMethod
            ]);
        }
    }

    public function markAsCancelled(): void
    {
        if ($this->canBeCancelled()) {
            $this->update(['status' => self::STATUS_CANCELLED]);
        }
    }

    public function calculateTotal(): void
    {
        $this->total_amount = $this->subtotal + $this->tax_amount - $this->discount_amount;
    }

    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->total_amount, 2) . ' ' . $this->currency;
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return number_format($this->subtotal, 2) . ' ' . $this->currency;
    }

    public function getFormattedTaxAmountAttribute(): string
    {
        return number_format($this->tax_amount, 2) . ' ' . $this->currency;
    }

    public function getFormattedDiscountAmountAttribute(): string
    {
        return number_format($this->discount_amount, 2) . ' ' . $this->currency;
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['invoice_number', 'status', 'total_amount', 'due_date'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Media Library
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments')
            ->acceptsMimeTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/jpg']);
    }

    // Generate unique invoice number
    public static function generateInvoiceNumber(): string
    {
        $prefix = 'INV';
        $year = now()->format('Y');
        $month = now()->format('m');
        
        $lastInvoice = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();
        
        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->invoice_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return sprintf('%s-%s%s-%04d', $prefix, $year, $month, $newNumber);
    }
}
