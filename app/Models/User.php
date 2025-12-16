<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'password',
        'account_type',
        'identity_verified',
        'identity_front_image',
        'identity_back_image',
        'total_bookings',
        'role_id',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'google_id',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'identity_verified' => 'boolean',
    ];

    // العلاقة مع الدور
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // العلاقة مع المقالات
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    // العلاقة مع الحجوزات
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // التحقق من الصلاحيات
    public function hasRole($role)
    {
        return $this->role && $this->role->name === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        if (! $this->role) {
            return false;
        }

        return in_array($this->role->name, $roles);
    }

    public function hasPermission($permission)
    {
        return $this->role && $this->role->hasPermission($permission);
    }

    public function isAdmin()
    {
        return $this->hasRole('big_boss') ||
               $this->hasRole('site_admin') ||
               $this->hasRole('booking_admin') ||
               $this->hasRole('users_admin');
    }

    public function isVip()
    {
        return $this->account_type === 'vip';
    }

    public function isActive()
    {
        return $this->account_type === 'active';
    }

    public function upgradeToVip(): void
    {
        $this->update(['account_type' => 'vip']);
    }

    public function incrementBookings(): void
    {
        $this->increment('total_bookings');
    }

    public function twoFactorQrCodeSvg(): string
    {
        return app('pragmarx.google2fa')->getQRCodeInline(
            config('app.name'),
            $this->email,
            $this->two_factor_secret
        );
    }
}
