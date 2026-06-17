<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('personnel', function (Blueprint $table) {
            $table->string('affiliation_level', 20)->nullable()->after('department_id'); // 'central' or 'provincial'
            $table->string('affiliation_province', 100)->nullable()->after('affiliation_level');
        });
    }

    public function down(): void
    {
        Schema::table('personnel', function (Blueprint $table) {
            $table->dropColumn(['affiliation_level', 'affiliation_province']);
        });
    }
};
