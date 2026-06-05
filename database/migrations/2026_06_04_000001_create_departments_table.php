<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();

            // Bilingual Name & Description
            $table->string('name_lo', 200);                    // ຊື່ພະແນກ (ລາວ) — required
            $table->string('name_en', 200)->nullable();        // Department Name (English)
            $table->text('description_lo')->nullable();        // ລາຍລະອຽດ (ລາວ)
            $table->text('description_en')->nullable();        // Description (English)

            // Department Head — will be set after personnel table exists
            $table->unsignedBigInteger('head_id')->nullable();

            // Display Control
            $table->smallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
