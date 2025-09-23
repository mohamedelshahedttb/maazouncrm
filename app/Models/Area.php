<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    /** @use HasFactory<\Database\Factories\AreaFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'transportation_fee',
        'mahr_percentage',
        'is_active',
    ];

    protected $casts = [
        'transportation_fee' => 'decimal:2',
        'mahr_percentage' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
