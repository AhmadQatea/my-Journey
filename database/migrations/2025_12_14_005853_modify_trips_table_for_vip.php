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
        // database/migrations/xxxx_modify_trips_table_for_vip.php
        Schema::table('trips', function (Blueprint $table) {
            $table->enum('source_type', ['admin', 'vip_user'])->default('admin');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('vip_commission', 5, 2)->nullable()->comment('نسبة العمولة للمستخدم VIP');
            $table->integer('available_seats')->default(0);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->text('meeting_point')->nullable();
            $table->text('requirements')->nullable();
            $table->json('features')->nullable();
            $table->integer('views_count')->default(0);
            $table->integer('bookings_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
