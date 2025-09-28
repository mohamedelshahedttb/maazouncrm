<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory, LogsActivity, InteractsWithMedia;

    protected $fillable = [
        'name',
        'category',
        'description',
        'supplier_id',
        'purchase_price',
        'selling_price',
        'currency',
        'stock_quantity',
        'min_stock_level',
        'sku',
        'status',
        'notes',
        'is_active',
    ];

    protected $attributes = [
        'currency' => 'EGP',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'min_stock_level' => 'integer',
        'is_active' => 'boolean',
    ];

    // Status constants
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_DISCONTINUED = 'discontinued';

    // Category constants
    const CATEGORY_DOCUMENTS = 'documents';
    const CATEGORY_OFFICE_SUPPLIES = 'office_supplies';
    const CATEGORY_BOOKS = 'books';
    const CATEGORY_SERVICES = 'services';
    const CATEGORY_OTHER = 'other';

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

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('stock_quantity <= min_stock_level');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('stock_quantity', '<=', 0);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    // Methods
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_ACTIVE => 'نشط',
            self::STATUS_INACTIVE => 'غير نشط',
            self::STATUS_DISCONTINUED => 'متوقف',
            default => 'غير محدد'
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_ACTIVE => 'green',
            self::STATUS_INACTIVE => 'gray',
            self::STATUS_DISCONTINUED => 'red',
            default => 'gray'
        };
    }

    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            self::CATEGORY_DOCUMENTS => 'مستندات',
            self::CATEGORY_OFFICE_SUPPLIES => 'لوازم مكتبية',
            self::CATEGORY_BOOKS => 'كتب',
            self::CATEGORY_SERVICES => 'خدمات',
            self::CATEGORY_OTHER => 'أخرى',
            default => 'غير محدد'
        };
    }

    public function getFormattedPurchasePriceAttribute(): string
    {
        return number_format($this->purchase_price, 2) . ' ' . $this->currency;
    }

    public function getFormattedSellingPriceAttribute(): string
    {
        return number_format($this->selling_price, 2) . ' ' . $this->currency;
    }

    public function getProfitAttribute(): float
    {
        return $this->selling_price - $this->purchase_price;
    }

    public function getProfitMarginAttribute(): float
    {
        if ($this->purchase_price > 0) {
            return round((($this->selling_price - $this->purchase_price) / $this->purchase_price) * 100, 2);
        }
        return 0;
    }

    public function getFormattedProfitAttribute(): string
    {
        return number_format($this->profit, 2) . ' ' . $this->currency;
    }

    public function getFormattedProfitMarginAttribute(): string
    {
        return $this->profit_margin . '%';
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->stock_quantity <= 0) {
            return 'نفذ المخزون';
        } elseif ($this->stock_quantity <= $this->min_stock_level) {
            return 'مخزون منخفض';
        } else {
            return 'متوفر';
        }
    }

    public function getStockStatusColorAttribute(): string
    {
        if ($this->stock_quantity <= 0) {
            return 'red';
        } elseif ($this->stock_quantity <= $this->min_stock_level) {
            return 'orange';
        } else {
            return 'green';
        }
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->min_stock_level;
    }

    public function isOutOfStock(): bool
    {
        return $this->stock_quantity <= 0;
    }

    public function isInStock(): bool
    {
        return $this->stock_quantity > 0;
    }

    public function needsReorder(): bool
    {
        return $this->isLowStock() && $this->isActive();
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'category', 'stock_quantity', 'status', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Media Library
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('product_images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/gif']);
    }
}
