<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BestVisitingTime extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'name_ar', 'icon', 'color'];

    public function governorates(): BelongsToMany
    {
        return $this->belongsToMany(Governorate::class, 'governorate_best_visiting_time');
    }
}
