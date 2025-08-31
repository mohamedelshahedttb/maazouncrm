<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Supplier extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name',
        'contact_person',
        'phone',
        'email',
        'address',
        'services_products',
        'status',
        'notes',
        'rating',
        'is_active',
    ];

    protected $casts = [
        'rating' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Status constants
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_SUSPENDED = 'suspended';

    // Relationships
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(SupplierOrder::class);
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

    public function scopeByRating($query, $minRating = 0)
    {
        return $query->where('rating', '>=', $minRating);
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

    public function getFormattedRatingAttribute(): string
    {
        return $this->rating ? number_format($this->rating, 1) . '/5' : 'غير محدد';
    }

    public function getRatingStarsAttribute(): string
    {
        if (!$this->rating) {
            return '☆☆☆☆☆';
        }

        $stars = '';
        $fullStars = floor($this->rating);
        $halfStar = $this->rating - $fullStars >= 0.5;

        for ($i = 0; $i < $fullStars; $i++) {
            $stars .= '★';
        }

        if ($halfStar) {
            $stars .= '☆';
        }

        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
        for ($i = 0; $i < $emptyStars; $i++) {
            $stars .= '☆';
        }

        return $stars;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE && $this->is_active;
    }

    public function isAvailable(): bool
    {
        return $this->isActive();
    }

    public function getServicesProductsArrayAttribute(): array
    {
        return explode(',', $this->services_products);
    }

    public function hasServiceOrProduct(string $item): bool
    {
        return in_array($item, $this->services_products_array);
    }

    public function getTotalOrdersAttribute(): int
    {
        return $this->orders()->count();
    }

    public function getCompletedOrdersAttribute(): int
    {
        return $this->orders()->where('status', 'delivered')->count();
    }

    public function getCompletionRateAttribute(): float
    {
        $total = $this->total_orders;
        return $total > 0 ? round(($this->completed_orders / $total) * 100, 2) : 0;
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'status', 'rating', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
