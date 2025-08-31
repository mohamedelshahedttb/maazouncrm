<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RequiredDocument extends Model
{
    use LogsActivity;

    protected $fillable = [
        'service_id',
        'document_name',
        'description',
        'document_type',
        'file_format',
        'max_file_size_mb',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'max_file_size_mb' => 'integer',
        'sort_order' => 'integer',
    ];

    // Document type constants
    const TYPE_REQUIRED = 'required';
    const TYPE_OPTIONAL = 'optional';
    const TYPE_CONDITIONAL = 'conditional';

    // Relationships
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('document_type', $type);
    }

    public function scopeRequired($query)
    {
        return $query->where('document_type', self::TYPE_REQUIRED);
    }

    public function scopeOptional($query)
    {
        return $query->where('document_type', self::TYPE_OPTIONAL);
    }

    public function scopeConditional($query)
    {
        return $query->where('document_type', self::TYPE_CONDITIONAL);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('document_name');
    }

    // Methods
    public function getDocumentTypeLabelAttribute(): string
    {
        return match($this->document_type) {
            self::TYPE_REQUIRED => 'مطلوب',
            self::TYPE_OPTIONAL => 'اختياري',
            self::TYPE_CONDITIONAL => 'مشروط',
            default => 'غير محدد'
        };
    }

    public function getDocumentTypeColorAttribute(): string
    {
        return match($this->document_type) {
            self::TYPE_REQUIRED => 'red',
            self::TYPE_OPTIONAL => 'blue',
            self::TYPE_CONDITIONAL => 'yellow',
            default => 'gray'
        };
    }

    public function isRequired(): bool
    {
        return $this->document_type === self::TYPE_REQUIRED;
    }

    public function isOptional(): bool
    {
        return $this->document_type === self::TYPE_OPTIONAL;
    }

    public function isConditional(): bool
    {
        return $this->document_type === self::TYPE_CONDITIONAL;
    }

    public function getFormattedFileFormatAttribute(): string
    {
        if (!$this->file_format) {
            return 'جميع الصيغ';
        }

        $formats = explode(',', $this->file_format);
        $formattedFormats = array_map(function($format) {
            return strtoupper(trim($format));
        }, $formats);

        return implode(', ', $formattedFormats);
    }

    public function getFormattedMaxFileSizeAttribute(): string
    {
        if ($this->max_file_size_mb >= 1024) {
            return round($this->max_file_size_mb / 1024, 1) . ' GB';
        }
        
        return $this->max_file_size_mb . ' MB';
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['document_name', 'document_type', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
