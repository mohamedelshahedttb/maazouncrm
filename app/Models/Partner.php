<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Partner extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name',
        'license_number',
        'service_scope',
        'phone',
        'email',
        'address',
        'commission_rate',
        'status',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Status constants
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_SUSPENDED = 'suspended';

    // Relationships
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function assistanceRequests(): HasMany
    {
        return $this->hasMany(PartnerAssistanceRequest::class, 'assisting_partner_id');
    }

    public function requestedAssistance(): HasMany
    {
        return $this->hasMany(PartnerAssistanceRequest::class, 'requesting_partner_id');
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

    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)->where('is_active', true);
    }

    // Methods
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_ACTIVE => 'نشط',
            self::STATUS_INACTIVE => 'غير نشط',
            self::STATUS_SUSPENDED => 'معلق',
            default => 'غير محدد'
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_ACTIVE => 'green',
            self::STATUS_INACTIVE => 'gray',
            self::STATUS_SUSPENDED => 'red',
            default => 'gray'
        };
    }

    public function getFormattedCommissionAttribute(): string
    {
        return $this->commission_rate . '%';
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE && $this->is_active;
    }

    public function isAvailable(): bool
    {
        return $this->isActive();
    }

    public function getServiceScopeArrayAttribute(): array
    {
        return explode(',', $this->service_scope);
    }

    public function hasServiceScope(string $scope): bool
    {
        return in_array($scope, $this->service_scope_array);
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'status', 'commission_rate', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
