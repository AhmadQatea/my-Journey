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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('trip_id')->constrained()->onDelete('cascade');
            $table->integer('guest_count');
            $table->date('booking_date');
            $table->decimal('total_price', 10, 2);
            $table->enum('status', ['معلقة', 'مؤكدة', 'مرفوضة', 'ملغاة'])->default('معلقة');
            $table->text('rejection_reason')->nullable();
            $table->boolean('created_by_admin')->default(false);
            $table->unsignedBigInteger('created_by_admin_id')->nullable();
            $table->unsignedBigInteger('confirmed_by_admin_id')->nullable();
            $table->text('special_requests')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
