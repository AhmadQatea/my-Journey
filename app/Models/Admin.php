<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_super_admin',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_super_admin' => 'boolean',
        'is_active' => 'boolean',
    ];

    // العلاقة مع الدور
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // التحقق من الصلاحيات
    public function hasRole($role)
    {
        return $this->role && $this->role->name === $role;
    }

    public function hasPermission($permission)
    {
        return $this->role && $this->role->hasPermission($permission);
    }

    public function isSuperAdmin(): bool
    {
        return $this->is_super_admin;
    }

    /**
     * Get the guard name for this model.
     */
    public function getGuardName(): string
    {
        return 'admin';
    }

    /**
     * Get the currently authenticated admin.
     */
    public static function current(): ?self
    {
        return auth('admin')->user();
    }

    /**
     * Check if there is an authenticated admin.
     */
    public static function isAuthenticated(): bool
    {
        return auth('admin')->check();
    }
}
