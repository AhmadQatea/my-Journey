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
        'permissions',
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
        'permissions' => 'array',
        'is_super_admin' => 'boolean',
        'is_active' => 'boolean',
    ];

    // العلاقة مع الدور
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // العلاقة مع الإشعارات
    public function notifications()
    {
        return $this->hasMany(AdminNotification::class);
    }

    /**
     * الحصول على عدد الإشعارات غير المقروءة
     */
    public function getUnreadNotificationsCount(): int
    {
        return $this->notifications()->unread()->count();
    }

    // التحقق من الصلاحيات
    public function hasRole($role)
    {
        return $this->role && $this->role->name === $role;
    }

    public function hasPermission($permission): bool
    {
        // Super admin has all permissions
        if ($this->is_super_admin) {
            return true;
        }

        // Check individual permissions first
        if ($this->permissions && in_array($permission, $this->permissions)) {
            return true;
        }

        // Check role permissions
        if ($this->role && $this->role->hasPermission($permission)) {
            return true;
        }

        return false;
    }

    /**
     * Get all permissions for this admin (individual + role permissions).
     */
    public function getAllPermissions(): array
    {
        $permissions = [];

        // Add individual permissions
        if ($this->permissions) {
            $permissions = array_merge($permissions, $this->permissions);
        }

        // Add role permissions
        if ($this->role && $this->role->permissions) {
            $permissions = array_merge($permissions, $this->role->permissions);
        }

        // Remove duplicates
        return array_unique($permissions);
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

    /**
     * Get the role slug for URL.
     */
    public function getRoleSlug(): ?string
    {
        // تحميل العلاقة إذا لم تكن محملة
        if (! $this->relationLoaded('role')) {
            $this->load('role');
        }

        // إذا لم يكن هناك role_id أو role غير موجود
        if (! $this->role_id || ! $this->role) {
            return null;
        }

        $roleName = $this->role->name;

        if (empty($roleName)) {
            return null;
        }

        // تحويل role name إلى slug للـ URL بناءً على نوع الدور
        return match ($roleName) {
            'big_boss' => 'bigboss',
            'site_admin' => 'site',
            'booking_admin' => 'booking',
            'users_admin' => 'users',
            default => str_replace('_', '', $roleName), // fallback: إزالة underscore
        };
    }
}
