<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            // إزالة is_active وإضافة status
            $table->dropColumn('is_active');
            $table->enum('status', ['مفعل', 'منتهي'])->default('مفعل')->after('end_date');

            // الحقول المخصصة للعرض (يمكن تعديلها من الرحلة الأصلية)
            $table->decimal('custom_price', 10, 2)->nullable()->after('discount_percentage');
            $table->json('custom_included_places')->nullable()->after('custom_price');
            $table->json('custom_features')->nullable()->after('custom_included_places');
            $table->time('custom_start_time')->nullable()->after('custom_features');
            $table->foreignId('custom_departure_governorate_id')->nullable()->constrained('governorates')->onDelete('set null')->after('custom_start_time');
            $table->string('custom_meeting_point')->nullable()->after('custom_departure_governorate_id');
            $table->integer('custom_duration_hours')->nullable()->after('custom_meeting_point');
            $table->integer('custom_max_persons')->nullable()->after('custom_duration_hours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'custom_price',
                'custom_included_places',
                'custom_features',
                'custom_start_time',
                'custom_departure_governorate_id',
                'custom_meeting_point',
                'custom_duration_hours',
                'custom_max_persons',
            ]);
            $table->boolean('is_active')->default(true);
        });
    }
};
