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
        Schema::table('trips', function (Blueprint $table) {
            // المحافظة التي ننطلق منها
            $table->foreignId('departure_governorate_id')->nullable()->after('governorate_id')->constrained('governorates')->onDelete('set null');

            // أنواع الرحلة (JSON array - يمكن أن يكون أكثر من نوع)
            $table->json('trip_types')->nullable()->after('trip_type')->comment('أنواع الرحلة: بحرية، تراثية، مناظر خلابة، إلخ');

            // المحافظات التي سنمر بها (عند اختيار "عدة محافظات")
            $table->json('passing_governorates')->nullable()->after('departure_governorate_id')->comment('المحافظات التي سنمر بها');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropForeign(['departure_governorate_id']);
            $table->dropColumn(['departure_governorate_id', 'trip_types', 'passing_governorates']);
        });
    }
};
