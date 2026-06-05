<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'admin', 'manager', 'staff'])
                  ->default('staff')
                  ->after('email');
            $table->boolean('is_active')->default(true)->after('role');
            $table->string('phone', 50)->nullable()->after('is_active');
            $table->string('avatar_url', 500)->nullable()->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_active', 'phone', 'avatar_url']);
        });
    }
};
