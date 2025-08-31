<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'is_active',
        'specialization',
        'notes',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @return array<string, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    // Role constants
    const ROLE_ADMIN = 'admin';
    const ROLE_STAFF = 'staff';
    const ROLE_PARTNER = 'partner';

    // Relationships
    public function appointments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Appointment::class, 'assigned_to');
    }

    public function tasks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_active', true);
    }

    // Methods
    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            self::ROLE_ADMIN => 'مدير',
            self::ROLE_STAFF => 'موظف',
            self::ROLE_PARTNER => 'شريك',
            default => 'غير محدد'
        };
    }

    public function getRoleColorAttribute(): string
    {
        return match($this->role) {
            self::ROLE_ADMIN => 'red',
            self::ROLE_STAFF => 'blue',
            self::ROLE_PARTNER => 'green',
            default => 'gray'
        };
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isStaff(): bool
    {
        return $this->role === self::ROLE_STAFF;
    }

    public function isPartner(): bool
    {
        return $this->role === self::ROLE_PARTNER;
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }
}
