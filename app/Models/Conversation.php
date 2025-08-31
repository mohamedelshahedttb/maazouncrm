<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Conversation extends Model
{
    use LogsActivity;

    protected $fillable = [
        'client_id',
        'user_id',
        'type',
        'content',
        'summary',
        'direction',
        'phone_number',
        'whatsapp_number',
        'email_address',
        'status',
        'conversation_date',
        'follow_up_notes',
        'follow_up_date',
        'is_active',
    ];

    protected $casts = [
        'conversation_date' => 'datetime',
        'follow_up_date' => 'date',
        'is_active' => 'boolean',
    ];

    // Type constants
    const TYPE_PHONE = 'phone';
    const TYPE_WHATSAPP = 'whatsapp';
    const TYPE_EMAIL = 'email';
    const TYPE_IN_PERSON = 'in_person';
    const TYPE_OTHER = 'other';

    // Direction constants
    const DIRECTION_INCOMING = 'incoming';
    const DIRECTION_OUTGOING = 'outgoing';

    // Status constants
    const STATUS_ACTIVE = 'active';
    const STATUS_RESOLVED = 'resolved';
    const STATUS_PENDING_FOLLOWUP = 'pending_followup';

    // Relationships
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByDirection($query, $direction)
    {
        return $query->where('direction', $direction);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeIncoming($query)
    {
        return $query->where('direction', self::DIRECTION_INCOMING);
    }

    public function scopeOutgoing($query)
    {
        return $query->where('direction', self::DIRECTION_OUTGOING);
    }

    public function scopeNeedsFollowUp($query)
    {
        return $query->where('status', self::STATUS_PENDING_FOLLOWUP);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('conversation_date', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('conversation_date', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    // Methods
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            self::TYPE_PHONE => 'هاتف',
            self::TYPE_WHATSAPP => 'واتس اب',
            self::TYPE_EMAIL => 'بريد إلكتروني',
            self::TYPE_IN_PERSON => 'شخصي',
            self::TYPE_OTHER => 'أخرى',
            default => 'غير محدد'
        };
    }

    public function getDirectionLabelAttribute(): string
    {
        return match($this->direction) {
            self::DIRECTION_INCOMING => 'وارد',
            self::DIRECTION_OUTGOING => 'صادر',
            default => 'غير محدد'
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_ACTIVE => 'نشط',
            self::STATUS_RESOLVED => 'محلول',
            self::STATUS_PENDING_FOLLOWUP => 'في انتظار المتابعة',
            default => 'غير محدد'
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_ACTIVE => 'blue',
            self::STATUS_RESOLVED => 'green',
            self::STATUS_PENDING_FOLLOWUP => 'orange',
            default => 'gray'
        };
    }

    public function getFormattedConversationDateAttribute(): string
    {
        return $this->conversation_date->format('Y-m-d H:i');
    }

    public function getFormattedFollowUpDateAttribute(): string
    {
        return $this->follow_up_date ? $this->follow_up_date->format('Y-m-d') : 'غير محدد';
    }

    public function getShortContentAttribute(): string
    {
        return Str::limit($this->content, 100);
    }

    public function isIncoming(): bool
    {
        return $this->direction === self::DIRECTION_INCOMING;
    }

    public function isOutgoing(): bool
    {
        return $this->direction === self::DIRECTION_OUTGOING;
    }

    public function isPhone(): bool
    {
        return $this->type === self::TYPE_PHONE;
    }

    public function isWhatsApp(): bool
    {
        return $this->type === self::TYPE_WHATSAPP;
    }

    public function isEmail(): bool
    {
        return $this->type === self::TYPE_EMAIL;
    }

    public function isInPerson(): bool
    {
        return $this->type === self::TYPE_IN_PERSON;
    }

    public function needsFollowUp(): bool
    {
        return $this->status === self::STATUS_PENDING_FOLLOWUP;
    }

    public function isResolved(): bool
    {
        return $this->status === self::STATUS_RESOLVED;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function markAsResolved(): void
    {
        $this->update(['status' => self::STATUS_RESOLVED]);
    }

    public function markForFollowUp(string $notes = null, $followUpDate = null): void
    {
        $this->update([
            'status' => self::STATUS_PENDING_FOLLOWUP,
            'follow_up_notes' => $notes,
            'follow_up_date' => $followUpDate ?? now()->addDays(7),
        ]);
    }

    public function isOverdueForFollowUp(): bool
    {
        return $this->follow_up_date && $this->follow_up_date->isPast() && $this->needsFollowUp();
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['type', 'direction', 'status', 'conversation_date'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
