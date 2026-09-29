<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('news', 'gallery_images')) {
            Schema::table('news', function (Blueprint $table) {
                $table->json('gallery_images')->nullable()->after('cover_image');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('news', 'gallery_images')) {
            Schema::table('news', function (Blueprint $table) {
                $table->dropColumn('gallery_images');
            });
        }
    }
};
