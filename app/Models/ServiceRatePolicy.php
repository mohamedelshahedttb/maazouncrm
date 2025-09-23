<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRatePolicy extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceRatePolicyFactory> */
    use HasFactory;

    protected $fillable = [
        'service_id',
        'mahr_min',
        'mahr_max',
        'fixed_fee',
        'is_active',
    ];

    protected $casts = [
        'mahr_min' => 'decimal:2',
        'mahr_max' => 'decimal:2',
        'fixed_fee' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
