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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();

            // قصة الموقع وروايته
            $table->text('about_story')->nullable();
            $table->text('about_mission')->nullable();
            $table->text('about_vision')->nullable();

            // معلومات التواصل
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_address')->nullable();
            $table->text('working_hours')->nullable(); // JSON أو نص

            // الشروط والأحكام
            $table->longText('terms_and_conditions')->nullable();

            // سياسة الخصوصية
            $table->longText('privacy_policy')->nullable();

            // سياسة ملفات التعريف
            $table->longText('cookie_policy')->nullable();

            // روابط مواقع التواصل الاجتماعي
            $table->string('social_facebook')->nullable();
            $table->string('social_twitter')->nullable();
            $table->string('social_instagram')->nullable();
            $table->string('social_youtube')->nullable();
            $table->string('social_linkedin')->nullable();
            $table->string('social_whatsapp')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
