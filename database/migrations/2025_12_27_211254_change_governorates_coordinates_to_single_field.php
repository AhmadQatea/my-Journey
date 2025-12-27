<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('governorates', function (Blueprint $table) {
            // إضافة حقل coordinates جديد
            $table->string('coordinates')->nullable()->after('location');
        });

        // نقل البيانات من latitude و longitude إلى coordinates
        $governorates = DB::table('governorates')->whereNotNull('latitude')->whereNotNull('longitude')->get();
        foreach ($governorates as $governorate) {
            if ($governorate->latitude && $governorate->longitude) {
                DB::table('governorates')
                    ->where('id', $governorate->id)
                    ->update([
                        'coordinates' => $governorate->latitude.', '.$governorate->longitude,
                    ]);
            }
        }

        // حذف الحقول القديمة
        Schema::table('governorates', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('governorates', function (Blueprint $table) {
            // إعادة إضافة الحقول القديمة
            $table->decimal('latitude', 10, 8)->nullable()->after('location');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        });

        // نقل البيانات من coordinates إلى latitude و longitude
        $governorates = DB::table('governorates')->whereNotNull('coordinates')->get();
        foreach ($governorates as $governorate) {
            if ($governorate->coordinates) {
                $coords = explode(',', $governorate->coordinates);
                if (count($coords) === 2) {
                    $lat = trim($coords[0]);
                    $lng = trim($coords[1]);
                    DB::table('governorates')
                        ->where('id', $governorate->id)
                        ->update([
                            'latitude' => $lat,
                            'longitude' => $lng,
                        ]);
                }
            }
        }

        // حذف حقل coordinates
        Schema::table('governorates', function (Blueprint $table) {
            $table->dropColumn('coordinates');
        });
    }
};
