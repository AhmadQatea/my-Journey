<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'permissions'];

    protected $casts = [
        'permissions' => 'array',
    ];

    // العلاقة مع المستخدمين
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // التحقق من الصلاحية
    public function hasPermission($permission)
    {
        return in_array($permission, $this->permissions ?? []);
    }

    // الحصول على جميع الأدوار
    public static function getRoles()
    {
        return [
            'big_boss' => 'المدير العام',
            'site_admin' => 'مسؤول الموقع',
            'booking_admin' => 'مسؤول الحجوزات',
            'users_admin' => 'مسؤول المستخدمين',
            'user' => 'مستخدم عادي',
        ];
    }

    // الحصول على جميع الصلاحيات
    public static function getPermissions()
    {
        return [
            'users.create',
            'users.read',
            'users.update',
            'users.delete',
            'bookings.create',
            'bookings.read',
            'bookings.update',
            'bookings.delete',
            'trips.create',
            'trips.read',
            'trips.update',
            'trips.delete',
            'articles.create',
            'articles.read',
            'articles.update',
            'articles.delete',
            'offers.create',
            'offers.read',
            'offers.update',
            'offers.delete',
            'settings.manage',
            'manage_governorates',
            'manage_tourist_spots',
            'manage_trips',
            'manage_deals',
            'manage_bookings',
            'manage_articles',
            'view_users',
        ];
    }
}
