<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Source types
    const TYPE_WHATSAPP = 'whatsapp';
    const TYPE_FACEBOOK = 'facebook';
    const TYPE_WEBSITE = 'website';
    const TYPE_REFERRAL = 'referral';
    const TYPE_OTHER = 'other';

    public static function getTypes()
    {
        return [
            self::TYPE_WHATSAPP => 'واتساب',
            self::TYPE_FACEBOOK => 'فيسبوك',
            self::TYPE_WEBSITE => 'الموقع الإلكتروني',
            self::TYPE_REFERRAL => 'إحالة',
            self::TYPE_OTHER => 'أخرى'
        ];
    }

    public function getTypeLabelAttribute()
    {
        return self::getTypes()[$this->type] ?? $this->type;
    }

    public function clients()
    {
        return $this->hasMany(Client::class, 'source_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
