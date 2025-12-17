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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->foreignId('trip_id')->constrained()->onDelete('cascade');
            $table->decimal('discount_percentage', 5, 2);
            $table->decimal('custom_price', 10, 2)->nullable();
            $table->json('custom_included_places')->nullable();
            $table->json('custom_features')->nullable();
            $table->time('custom_start_time')->nullable();
            $table->foreignId('custom_departure_governorate_id')->nullable()->constrained('governorates')->onDelete('set null');
            $table->string('custom_meeting_point')->nullable();
            $table->integer('custom_duration_hours')->nullable();
            $table->integer('custom_max_persons')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['مفعل', 'منتهي'])->default('مفعل');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
