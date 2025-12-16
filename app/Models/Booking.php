<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'trip_id',
        'guest_count',
        'booking_date',
        'total_price',
        'status',
        'special_requests',
        'admin_notes',
        'rejection_reason',
        'created_by_admin',
        'created_by_admin_id',
        'confirmed_by_admin_id',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'total_price' => 'decimal:2',
        'guest_count' => 'integer',
        'created_by_admin' => 'boolean',
    ];

    // العلاقة مع المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // العلاقة مع الرحلة
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    // العلاقة مع المسؤول الذي أنشأ الحجز (إذا كان من المسؤولين)
    public function adminCreator()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'created_by_admin_id');
    }

    // العلاقة مع المسؤول الذي أكد الحجز
    public function adminConfirmer()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'confirmed_by_admin_id');
    }

    // Scope للحصول على الحجوزات المؤكدة
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'مؤكدة');
    }

    // Scope للحصول على الحجوزات المعلقة
    public function scopePending($query)
    {
        return $query->where('status', 'معلقة');
    }

    // Scope للحصول على الحجوزات المرفوضة
    public function scopeRejected($query)
    {
        return $query->where('status', 'مرفوضة');
    }

    // Scope للحصول على الحجوزات الملغاة
    public function scopeCancelled($query)
    {
        return $query->where('status', 'ملغاة');
    }
}
