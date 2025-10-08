<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Governorate extends Model
{
    /** @use HasFactory<\Database\Factories\GovernorateFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'base_fixed_fee',
        'added_fees',
        'mahr_percentage',
        'is_active',
    ];

    protected $casts = [
        'base_fixed_fee' => 'decimal:2',
        'added_fees' => 'decimal:2',
        'mahr_percentage' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}


