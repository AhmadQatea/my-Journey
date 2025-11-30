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
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('full_name');
        $table->string('email')->unique();
        $table->string('phone')->nullable();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        $table->enum('account_type', ['visitor', 'active', 'vip'])->default('visitor');
        $table->boolean('identity_verified')->default(false);
        $table->string('identity_front_image')->nullable();
        $table->string('identity_back_image')->nullable();
        $table->integer('total_bookings')->default(0);
        $table->rememberToken();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
