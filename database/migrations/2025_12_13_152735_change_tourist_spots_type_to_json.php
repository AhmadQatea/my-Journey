<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // الخطوة 1: تحويل enum إلى TEXT أولاً (لأن MySQL لا يسمح بتحويل enum إلى JSON مباشرة)
        DB::statement('ALTER TABLE tourist_spots MODIFY type TEXT NULL');

        // الخطوة 2: تحويل البيانات الموجودة من enum إلى JSON
        $touristSpots = DB::table('tourist_spots')->get();

        foreach ($touristSpots as $spot) {
            if ($spot->type) {
                $decoded = json_decode($spot->type, true);
                if (! is_array($decoded)) {
                    // تحويل القيمة الواحدة إلى مصفوفة JSON
                    DB::table('tourist_spots')
                        ->where('id', $spot->id)
                        ->update(['type' => json_encode([$spot->type])]);
                }
            }
        }

        // الخطوة 3: تحويل TEXT إلى JSON
        DB::statement('ALTER TABLE tourist_spots MODIFY type JSON NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // الخطوة 1: تحويل JSON إلى TEXT أولاً
        DB::statement('ALTER TABLE tourist_spots MODIFY type TEXT NULL');

        // الخطوة 2: تحويل البيانات من JSON إلى enum (أخذ أول قيمة فقط)
        $touristSpots = DB::table('tourist_spots')->get();

        foreach ($touristSpots as $spot) {
            if ($spot->type) {
                $types = json_decode($spot->type, true);
                if (is_array($types) && count($types) > 0) {
                    // أخذ أول نوع فقط
                    DB::table('tourist_spots')
                        ->where('id', $spot->id)
                        ->update(['type' => $types[0]]);
                }
            }
        }

        // الخطوة 3: تحويل TEXT إلى enum
        DB::statement("ALTER TABLE tourist_spots MODIFY type ENUM('تاريخي', 'طبيعي', 'منتزه', 'جبال ومرتفعات', 'مغامرات ومخاطر') NOT NULL");
    }
};
