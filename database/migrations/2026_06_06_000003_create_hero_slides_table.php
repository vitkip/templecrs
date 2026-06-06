<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();

            // Saffron Accent Texts - Bilingual
            $table->string('title_lo', 500)->nullable();
            $table->string('title_en', 500)->nullable();
            $table->string('subtitle_lo', 1000)->nullable();
            $table->string('subtitle_en', 1000)->nullable();

            // Background Image
            $table->string('image_path', 500);

            // Call to Action button details
            $table->string('button_link', 500)->nullable();
            $table->string('button_text_lo', 100)->nullable();
            $table->string('button_text_en', 100)->nullable();

            // Display Control
            $table->smallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_slides');
    }
};
