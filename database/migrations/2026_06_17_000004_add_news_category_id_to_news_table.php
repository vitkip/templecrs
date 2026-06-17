<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add column (idempotent — skip if already exists from a failed run)
        if (!Schema::hasColumn('news', 'news_category_id')) {
            Schema::table('news', function (Blueprint $table) {
                $table->unsignedBigInteger('news_category_id')
                      ->nullable()
                      ->after('author_id');
            });
        }

        // 2. Add index (separate call — try/catch in case it already exists)
        try {
            Schema::table('news', function (Blueprint $table) {
                $table->index('news_category_id', 'news_category_id_index');
            });
        } catch (\Throwable) {
            // Index already exists (e.g. created by a previous FK attempt)
        }

        // 3. Add FK constraint (optional — some MySQL 5.7/shared-hosting servers
        //    refuse the constraint due to strict FK validation. The Eloquent
        //    relationship handles nullification in application code, so the DB
        //    constraint is a nice-to-have, not a requirement.)
        try {
            Schema::table('news', function (Blueprint $table) {
                $table->foreign('news_category_id', 'news_news_category_id_foreign')
                      ->references('id')
                      ->on('news_categories')
                      ->nullOnDelete();
            });
        } catch (\Throwable) {
            // FK not supported on this server — column + index still present.
        }
    }

    public function down(): void
    {
        // Each operation is a separate Schema call so a failure in one
        // does not prevent the subsequent ones from running.

        try {
            Schema::table('news', function (Blueprint $table) {
                $table->dropForeign('news_news_category_id_foreign');
            });
        } catch (\Throwable) {}

        try {
            Schema::table('news', function (Blueprint $table) {
                $table->dropIndex('news_category_id_index');
            });
        } catch (\Throwable) {}

        if (Schema::hasColumn('news', 'news_category_id')) {
            Schema::table('news', function (Blueprint $table) {
                $table->dropColumn('news_category_id');
            });
        }
    }
};
