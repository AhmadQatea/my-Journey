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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->foreignId('governorate_id')->constrained()->onDelete('cascade');
            $table->foreignId('departure_governorate_id')->nullable()->constrained('governorates')->onDelete('set null');
            $table->enum('trip_type', ['داخل المحافظة', 'عدة محافظات']);
            $table->json('trip_types')->nullable()->comment('أنواع الرحلة: بحرية، تراثية، مناظر خلابة، إلخ');
            $table->json('passing_governorates')->nullable()->comment('المحافظات التي سنمر بها');
            $table->enum('source_type', ['admin', 'vip_user'])->default('admin');
            $table->json('category_ids')->nullable();
            $table->decimal('vip_commission', 5, 2)->nullable()->comment('نسبة العمولة للمستخدم VIP');
            $table->integer('duration_hours');
            $table->integer('available_seats')->default(0);
            $table->time('start_time');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->text('meeting_point')->nullable();
            $table->text('requirements')->nullable();
            $table->integer('max_persons');
            $table->decimal('price', 10, 2);
            $table->json('included_places');
            $table->json('features')->nullable();
            $table->json('images')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['معلقة', 'مقبولة', 'مرفوضة', 'قيد التفعيل'])->default('معلقة');
            $table->text('rejection_reason')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('views_count')->default(0);
            $table->integer('bookings_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
