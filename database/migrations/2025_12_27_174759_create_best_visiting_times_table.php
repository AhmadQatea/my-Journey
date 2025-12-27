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
        Schema::create('best_visiting_times', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // spring, summer, autumn, winter
            $table->string('name_ar'); // الربيع، الصيف، الخريف، الشتاء
            $table->string('icon')->nullable(); // FontAwesome icon class
            $table->string('color')->nullable(); // Color code
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('best_visiting_times');
    }
};
