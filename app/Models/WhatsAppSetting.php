<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class WhatsAppSetting extends Model
{
    use LogsActivity;

    protected $table = 'whatsapp_settings';

    protected $fillable = [
        'business_name',
        'phone_number',
        'access_token',
        'webhook_url',
        'business_account_id',
        'verify_token',
        'app_secret',
        'status',
        'message_templates',
        'auto_replies',
        'appointment_reminders',
        'follow_up_messages',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'message_templates' => 'array',
        'auto_replies' => 'array',
        'appointment_reminders' => 'boolean',
        'follow_up_messages' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Status constants
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_PENDING_VERIFICATION = 'pending_verification';

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeVerified($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    // Methods
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_ACTIVE => 'نشط',
            self::STATUS_INACTIVE => 'غير نشط',
            self::STATUS_PENDING_VERIFICATION => 'في انتظار التحقق',
            default => 'غير محدد'
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_ACTIVE => 'green',
            self::STATUS_INACTIVE => 'gray',
            self::STATUS_PENDING_VERIFICATION => 'yellow',
            default => 'gray'
        };
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE && $this->is_active;
    }

    public function isVerified(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isPendingVerification(): bool
    {
        return $this->status === self::STATUS_PENDING_VERIFICATION;
    }

    public function canSendMessages(): bool
    {
        return $this->isActive() && $this->access_token;
    }

    public function getMessageTemplate(string $key, array $replacements = []): string
    {
        $templates = $this->message_templates ?? [];
        $template = $templates[$key] ?? '';

        foreach ($replacements as $key => $value) {
            $template = str_replace("{{$key}}", $value, $template);
        }

        return $template;
    }

    public function getAutoReply(string $trigger): string
    {
        $replies = $this->auto_replies ?? [];
        return $replies[$trigger] ?? '';
    }

    public function setMessageTemplate(string $key, string $template): void
    {
        $templates = $this->message_templates ?? [];
        $templates[$key] = $template;
        $this->update(['message_templates' => $templates]);
    }

    public function setAutoReply(string $trigger, string $reply): void
    {
        $replies = $this->auto_replies ?? [];
        $replies[$trigger] = $reply;
        $this->update(['auto_replies' => $replies]);
    }

    public function removeMessageTemplate(string $key): void
    {
        $templates = $this->message_templates ?? [];
        unset($templates[$key]);
        $this->update(['message_templates' => $templates]);
    }

    public function removeAutoReply(string $trigger): void
    {
        $replies = $this->auto_replies ?? [];
        unset($replies[$trigger]);
        $this->update(['auto_replies' => $replies]);
    }

    public function getDefaultMessageTemplates(): array
    {
        return [
            'appointment_reminder' => 'مرحباً {{client_name}}، تذكير بموعدكم غداً في {{appointment_time}} مع {{service_name}}. نرجو التأكيد.',
            'follow_up' => 'مرحباً {{client_name}}، نأمل أن تكون الخدمة قد نالت رضاكم. هل لديكم أي استفسارات؟',
            'welcome' => 'مرحباً {{client_name}}، شكراً لاختياركم خدماتنا. كيف يمكننا مساعدتكم؟',
            'appointment_confirmation' => 'تم تأكيد موعدكم {{appointment_date}} في {{appointment_time}} مع {{service_name}}. ننتظركم.',
            'appointment_cancellation' => 'تم إلغاء موعدكم {{appointment_date}}. يمكنكم إعادة الحجز في أي وقت.',
        ];
    }

    public function getDefaultAutoReplies(): array
    {
        return [
            'hello' => 'مرحباً! كيف يمكننا مساعدتكم اليوم؟',
            'thanks' => 'شكراً لكم! نحن سعداء بخدمتكم.',
            'goodbye' => 'مع السلامة! نأمل رؤيتكم قريباً.',
            'help' => 'يمكننا مساعدتكم في: الزواج، الطلاق، التصديق، الترجمة، والاستشارات القانونية.',
        ];
    }

    public function initializeDefaults(): void
    {
        if (empty($this->message_templates)) {
            $this->update(['message_templates' => $this->getDefaultMessageTemplates()]);
        }

        if (empty($this->auto_replies)) {
            $this->update(['auto_replies' => $this->getDefaultAutoReplies()]);
        }
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'phone_number', 'business_name', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
