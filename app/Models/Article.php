<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'trip_id',
        'title',
        'content',
        'excerpt',
        'rating',
        'images',
        'status',
        'rejection_reason',
        'views_count',
        'created_by_admin',
        'created_by_admin_id',
        'confirmed_by_admin_id',
    ];

    protected $casts = [
        'images' => 'array',
        'rating' => 'integer',
        'views_count' => 'integer',
        'trip_id' => 'integer',
        'created_by_admin' => 'boolean',
    ];

    // العلاقة مع المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // العلاقة مع الرحلة (اختيارية)
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    // العلاقة مع المسؤول الذي أنشأ المقال
    public function adminCreator()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'created_by_admin_id');
    }

    // العلاقة مع المسؤول الذي أكد المقال
    public function adminConfirmer()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'confirmed_by_admin_id');
    }

    // Scope للحصول على المقالات المنشورة
    public function scopePublished($query)
    {
        return $query->where('status', 'منشورة');
    }

    // Scope للحصول على المقالات المعلقة
    public function scopePending($query)
    {
        return $query->where('status', 'معلقة');
    }

    // Scope للحصول على المقالات المرفوضة
    public function scopeRejected($query)
    {
        return $query->where('status', 'مرفوضة');
    }
}
