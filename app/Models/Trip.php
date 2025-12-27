<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'governorate_id',
        'departure_governorate_id',
        'category_ids',
        'trip_type',
        'trip_types',
        'passing_governorates',
        'duration_hours',
        'start_date',
        'end_date',
        'start_time',
        'max_persons',
        'available_seats',
        'price',
        'included_places',
        'images',
        'features',
        'requirements',
        'meeting_point',
        'vip_commission',
        'source_type',
        'created_by',
        'created_by_admin',
        'created_by_admin_id',
        'status',
        'rejection_reason',
        'is_featured',
        'views_count',
        'bookings_count',
    ];

    protected $casts = [
        'included_places' => 'array',
        'images' => 'array',
        'features' => 'array',
        'trip_types' => 'array',
        'passing_governorates' => 'array',
        'category_ids' => 'array',
        'price' => 'decimal:2',
        'vip_commission' => 'decimal:2',
        'is_featured' => 'boolean',
        'created_by_admin' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'datetime:H:i',
        'views_count' => 'integer',
        'bookings_count' => 'integer',
    ];

    // العلاقة مع المحافظة
    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }

    // العلاقة مع محافظة الانطلاق
    public function departureGovernorate()
    {
        return $this->belongsTo(Governorate::class, 'departure_governorate_id');
    }

    // العلاقة مع منشئ الرحلة (لـ VIP users)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // العلاقة مع المسؤول الذي أنشأ الرحلة
    public function adminCreator()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'created_by_admin_id');
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

    // العلاقة مع العروض
    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    // Helper method للحصول على الفئات
    public function getCategoriesAttribute()
    {
        if (! $this->category_ids || empty($this->category_ids)) {
            return collect([]);
        }

        return Category::whereIn('id', $this->category_ids)->get();
    }

    // Accessor للحصول على أول فئة (للتوافق مع الكود القديم)
    public function getCategoryAttribute()
    {
        $categories = $this->categories;

        return $categories->first();
    }
}
