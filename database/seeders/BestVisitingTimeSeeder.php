<?php

namespace Database\Seeders;

use App\Models\BestVisitingTime;
use Illuminate\Database\Seeder;

class BestVisitingTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seasons = [
            [
                'name' => 'spring',
                'name_ar' => 'الربيع',
                'icon' => 'fas fa-seedling',
                'color' => '#4ade80',
            ],
            [
                'name' => 'summer',
                'name_ar' => 'الصيف',
                'icon' => 'fas fa-sun',
                'color' => '#fbbf24',
            ],
            [
                'name' => 'autumn',
                'name_ar' => 'الخريف',
                'icon' => 'fas fa-leaf',
                'color' => '#f97316',
            ],
            [
                'name' => 'winter',
                'name_ar' => 'الشتاء',
                'icon' => 'fas fa-snowflake',
                'color' => '#60a5fa',
            ],
        ];

        foreach ($seasons as $season) {
            BestVisitingTime::firstOrCreate(
                ['name' => $season['name']],
                $season
            );
        }
    }
}
