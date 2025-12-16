<?php

// app/Models/Category.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    // Helper method للحصول على عدد الرحلات المرتبطة بهذه الفئة
    public function tripsCount()
    {
        return Trip::whereJsonContains('category_ids', $this->id)->count();
    }

    // Helper method للحصول على عدد الأماكن السياحية المرتبطة بهذه الفئة
    public function touristSpotsCount()
    {
        return TouristSpot::whereJsonContains('category_ids', $this->id)->count();
    }
}
