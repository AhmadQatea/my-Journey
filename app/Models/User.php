<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'identity_verified' => 'boolean',
    ];

    // العلاقة مع الأدوار
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    // العلاقة مع الرحلات (للمستخدمين VIP)
    public function createdTrips()
    {
        return $this->hasMany(Trip::class, 'created_by');
    }

    // العلاقة مع الحجوزات
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // العلاقة مع المقالات
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    // التحقق إذا كان المستخدم VIP
    public function isVip()
    {
        return $this->account_type === 'vip';
    }

    // التحقق إذا كان المستخدم نشط
    public function isActive()
    {
        return $this->account_type === 'active';
    }
}
