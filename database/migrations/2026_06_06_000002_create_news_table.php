<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();

            // Title — Bilingual
            $table->string('title_lo', 500);
            $table->string('title_en', 500)->nullable();

            // Excerpt — Bilingual (short summary for cards)
            $table->string('excerpt_lo', 1000)->nullable();
            $table->string('excerpt_en', 1000)->nullable();

            // Content — Bilingual (full article body)
            $table->longText('content_lo')->nullable();
            $table->longText('content_en')->nullable();

            // Cover Image
            $table->string('cover_image', 500)->nullable();

            // Publishing
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_featured')->default(false);

            // Display Control
            $table->smallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'published_at']);
            $table->index('is_featured');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
