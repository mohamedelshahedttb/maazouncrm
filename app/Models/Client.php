<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Client extends Model implements HasMedia
{
    use LogsActivity, InteractsWithMedia;

    protected $fillable = [
        'name',
        'bride_name',
        'guardian_name',
        'phone',
        'email',
        'address',
        'status',
        'notes',
        'whatsapp_number',
        'facebook_id',
        'facebook_page_id',
        'is_active',
        'service_id',
        'source_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Status constants
    const STATUS_NEW = 'new';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    // Relationships
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(ClientSource::class, 'source_id');
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

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Methods
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
            ->logOnly(['name', 'status', 'phone', 'email'])
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
