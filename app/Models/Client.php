<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Client extends Model implements HasMedia
{
    use HasFactory, LogsActivity, InteractsWithMedia;

    protected $fillable = [
        'name',
        'groom_name',
        'bride_name',
        'guardian_name',
        'phone',
        'email',
        'address',
        'geographical_area',
        'client_status',
        'call_result',
        'next_follow_up_date',
        'relationship_status',
        'bride_age',
        'accessories',
        'event_date',
        'contract_location',
        'contract_cost',
        'discount_type',
        'discount_value',
        'final_price',
        'contract_address',
        'mahr',
        'bride_id_address',
        'contract_delivery_method',
        'contract_delivery_date',
        'temporary_document',
        'sheikh_name',
        'book_number',
        'document_number',
        'coupon_arrival_date',
        'document_receipt_date',
        'document_receiver',
        'delivery_man_name',
        'client_relative_name',
        'google_maps_link',
        'governorate',
        'area',
        'document_status',
        'document_rejection_reason',
        'assigned_partner_id',
        'job_date',
        'job_time',
        'job_number',
        'coupon_number',
        'final_document_delivery_date',
        'final_document_notification_sent',
        'notes',
        'whatsapp_number',
        'facebook_id',
        'facebook_page_id',
        'is_active',
        'service_id',
        'source_id',
        'governorate_id',
        'area_id',
        'client_status',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'next_follow_up_date' => 'date',
        'event_date' => 'date',
        'contract_delivery_date' => 'date',
        'coupon_arrival_date' => 'date',
        'document_receipt_date' => 'date',
        'job_date' => 'date',
        'final_document_delivery_date' => 'date',
        'final_document_notification_sent' => 'boolean',
        'contract_cost' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'final_price' => 'decimal:2',
        'accessories' => 'array',
    ];

    // Status constants
    const STATUS_NEW = 'new';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    
    // Call result constants
    const CALL_RESULT_INTERESTED = 'interested';
    const CALL_RESULT_NOT_INTERESTED = 'not_interested';
    const CALL_RESULT_FOLLOW_UP_LATER = 'follow_up_later';
    const CALL_RESULT_POTENTIAL_CLIENT = 'potential_client';
    const CALL_RESULT_CONFIRMED_BOOKING = 'confirmed_booking';
    const CALL_RESULT_COMPLETED_BOOKING = 'completed_booking';
    const CALL_RESULT_CANCELLED = 'cancelled';
    const CALL_RESULT_INQUIRY = 'inquiry';
    const CALL_RESULT_CLIENT_BOOKING = 'client_booking';
    const CALL_RESULT_NO_ANSWER = 'no_answer';
    const CALL_RESULT_BUSY_NUMBER = 'busy_number';
    
    // Document status constants
    const DOCUMENT_STATUS_PENDING = 'pending';
    const DOCUMENT_STATUS_UNDER_REVIEW = 'under_review';
    const DOCUMENT_STATUS_APPROVED = 'approved';
    const DOCUMENT_STATUS_REJECTED = 'rejected';
    const DOCUMENT_STATUS_INCOMPLETE = 'incomplete';

    // Relationships
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(ClientSource::class, 'source_id');
    }

    public function governorate(): BelongsTo
    {
        return $this->belongsTo(Governorate::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

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

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
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
        return $query->where('client_status', $status);
    }

    // Methods
    public function getClientStatusLabelAttribute(): string
    {
        return match($this->client_status) {
            self::STATUS_NEW => 'جديد',
            self::STATUS_IN_PROGRESS => 'جاري العمل عليه',
            self::STATUS_COMPLETED => 'مكتمل',
            self::STATUS_CANCELLED => 'ملغي',
            default => 'غير محدد'
        };
    }
    
    public function getDocumentReceiverLabelAttribute(): string
    {
        return match($this->document_receiver) {
            'delivery' => 'دليفري',
            'client' => 'العميل',
            'client_relative' => 'أحد أقارب العميل',
            default => '-'
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_NEW => 'جديد',
            self::STATUS_IN_PROGRESS => 'جاري العمل عليه',
            self::STATUS_COMPLETED => 'مكتمل',
            self::STATUS_CANCELLED => 'ملغي',
            default => 'غير محدد'
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_NEW => 'blue',
            self::STATUS_IN_PROGRESS => 'yellow',
            self::STATUS_COMPLETED => 'green',
            self::STATUS_CANCELLED => 'red',
            default => 'gray'
        };
    }

    public function getCallResultLabelAttribute(): string
    {
        return match($this->call_result) {
            self::CALL_RESULT_INTERESTED => 'مهتم',
            self::CALL_RESULT_NOT_INTERESTED => 'غير مهتم',
            self::CALL_RESULT_FOLLOW_UP_LATER => 'متابعة لاحقا',
            self::CALL_RESULT_POTENTIAL_CLIENT => 'عميل محتمل',
            self::CALL_RESULT_CONFIRMED_BOOKING => 'حجز مؤكد',
            self::CALL_RESULT_COMPLETED_BOOKING => 'حجز مكتمل',
            self::CALL_RESULT_CANCELLED => 'ملغي',
            self::CALL_RESULT_INQUIRY => 'استفسار',
            self::CALL_RESULT_CLIENT_BOOKING => 'حجز العميل',
            self::CALL_RESULT_NO_ANSWER => 'لم يتم الرد',
            self::CALL_RESULT_BUSY_NUMBER => 'الرقم مشغول',
            default => 'غير محدد'
        };
    }

    public function getCallResultColorAttribute(): string
    {
        return match($this->call_result) {
            self::CALL_RESULT_INTERESTED => 'green',
            self::CALL_RESULT_NOT_INTERESTED => 'red',
            self::CALL_RESULT_FOLLOW_UP_LATER => 'yellow',
            self::CALL_RESULT_POTENTIAL_CLIENT => 'blue',
            self::CALL_RESULT_CONFIRMED_BOOKING => 'purple',
            self::CALL_RESULT_COMPLETED_BOOKING => 'green',
            self::CALL_RESULT_CANCELLED => 'red',
            self::CALL_RESULT_INQUIRY => 'blue',
            self::CALL_RESULT_CLIENT_BOOKING => 'purple',
            self::CALL_RESULT_NO_ANSWER => 'gray',
            self::CALL_RESULT_BUSY_NUMBER => 'orange',
            default => 'gray'
        };
    }

    public function getDocumentStatusLabelAttribute(): string
    {
        return match($this->document_status) {
            self::DOCUMENT_STATUS_PENDING => 'في الانتظار',
            self::DOCUMENT_STATUS_UNDER_REVIEW => 'قيد المراجعة',
            self::DOCUMENT_STATUS_APPROVED => 'موافق عليه',
            self::DOCUMENT_STATUS_REJECTED => 'مرفوض',
            self::DOCUMENT_STATUS_INCOMPLETE => 'الاوراق غير مكتملة',
            default => 'غير محدد'
        };
    }

    public function getDocumentStatusColorAttribute(): string
    {
        return match($this->document_status) {
            self::DOCUMENT_STATUS_PENDING => 'yellow',
            self::DOCUMENT_STATUS_UNDER_REVIEW => 'blue',
            self::DOCUMENT_STATUS_APPROVED => 'green',
            self::DOCUMENT_STATUS_REJECTED => 'red',
            self::DOCUMENT_STATUS_INCOMPLETE => 'red',
            default => 'gray'
        };
    }

    public function isNew(): bool
    {
        return $this->status === self::STATUS_NEW;
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

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'client_status', 'phone', 'email'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Media Library
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('documents')
            ->acceptsMimeTypes([
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'image/jpeg',
                'image/png',
                'image/jpg'
            ]);
    }

    // Custom accessor for media URLs
    public function getMediaUrlsAttribute()
    {
        return $this->media->map(function ($media) {
            return [
                'id' => $media->id,
                'name' => $media->name,
                'file_name' => $media->file_name,
                'mime_type' => $media->mime_type,
                'size' => $media->size,
                'url' => $media->getUrl(),
                'download_url' => $media->getUrl(),
                'created_at' => $media->created_at
            ];
        });
    }

    // Custom method to get correct media URLs
    public function getCorrectMediaUrl($media)
    {
        // Pass id and filename as separate parameters to match the route
        return route('files.serve', ['id' => $media->id, 'filename' => $media->file_name]);
    }

    // Custom method to get download URLs
    public function getCorrectMediaDownloadUrl($media)
    {
        // Pass id and filename as separate parameters to match the route
        return route('files.download', ['id' => $media->id, 'filename' => $media->file_name]);
    }
}
