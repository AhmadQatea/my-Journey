<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TouristSpot extends Model
{
    use HasFactory;

    protected $fillable = [
        'governorate_id',
        'name',
        'description',
        'location',
        'coordinates',
        'category_ids',
        'images',
        'entrance_fee',
        'opening_hours',
    ];

    protected $casts = [
        'images' => 'array',
        'category_ids' => 'array',
        'entrance_fee' => 'decimal:2',
    ];

    // العلاقة مع المحافظة
    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
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

    // Accessor للحصول على type (للتوافق مع الكود القديم)
    public function getTypeAttribute()
    {
        $categories = $this->categories;

        return $categories->pluck('name')->toArray();
    }
}
