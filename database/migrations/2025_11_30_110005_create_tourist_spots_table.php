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
    Schema::create('tourist_spots', function (Blueprint $table) {
        $table->id();
        $table->foreignId('governorate_id')->constrained()->onDelete('cascade');
        $table->string('name');
        $table->text('description');
        $table->string('location');
        $table->enum('type', ['بحري', 'تراثي', 'مناظر خلابة', 'تاریخي']);
        $table->json('images')->nullable();
        $table->decimal('entrance_fee', 10, 2)->default(0);
        $table->string('opening_hours')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tourist_spots');
    }
};
