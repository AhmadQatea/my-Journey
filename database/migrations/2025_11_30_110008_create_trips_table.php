<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('trips', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description');
        $table->foreignId('governorate_id')->constrained()->onDelete('cascade');
        $table->enum('trip_type', ['داخل المحافظة', 'عدة محافظات']);
        $table->integer('duration_hours');
        $table->time('start_time');
        $table->integer('max_persons');
        $table->decimal('price', 10, 2);
        $table->json('included_places');
        $table->json('images')->nullable();
        $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
        $table->enum('status', ['معلقة', 'مقبولة', 'مرفوضة'])->default('معلقة');
        $table->boolean('is_featured')->default(false);
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
