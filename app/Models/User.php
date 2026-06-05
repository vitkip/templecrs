<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'phone',
        'avatar_url',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    /* ── Role Labels ────────────────────────────────── */

    public const ROLES = [
        'super_admin' => 'ຜູ້ເບິ່ງແຍງສູງສຸດ / Super Admin',
        'admin'       => 'ຜູ້ເບິ່ງແຍງ / Admin',
        'manager'     => 'ຜູ້ຈັດການ / Manager',
        'staff'       => 'ພະນັກງານ / Staff',
    ];

    public const ROLE_BADGE = [
        'super_admin' => ['label' => 'Super Admin', 'class' => 'bg-purple-700 text-white'],
        'admin'       => ['label' => 'Admin',       'class' => 'bg-primary text-white'],
        'manager'     => ['label' => 'Manager',     'class' => 'bg-secondary text-white'],
        'staff'       => ['label' => 'Staff',       'class' => 'bg-outline-variant text-on-surface'],
    ];

    public function getRoleLabelAttribute(): string
    {
        return self::ROLES[$this->role] ?? $this->role;
    }

    public function getRoleBadgeAttribute(): array
    {
        return self::ROLE_BADGE[$this->role] ?? ['label' => $this->role, 'class' => 'bg-gray-400 text-white'];
    }

    /* ── Helpers ────────────────────────────────────── */

    public function isSuperAdmin(): bool { return $this->role === 'super_admin'; }
    public function isAdmin(): bool      { return in_array($this->role, ['super_admin', 'admin']); }
    public function isManager(): bool    { return $this->role === 'manager'; }
    public function isStaff(): bool      { return $this->role === 'staff'; }

    /* ── Scopes ─────────────────────────────────────── */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, ?string $term)
    {
        if (!$term) return $query;
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'LIKE', "%{$term}%")
              ->orWhere('email', 'LIKE', "%{$term}%")
              ->orWhere('phone', 'LIKE', "%{$term}%");
        });
    }
}
