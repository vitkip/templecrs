<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personnel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();

            // Person Type
            $table->enum('gender', ['monk', 'male', 'female'])->nullable();

            // Name — Bilingual
            $table->string('first_name_lo', 100)->nullable();
            $table->string('first_name_en', 100)->nullable();
            $table->string('last_name_lo', 100)->nullable();
            $table->string('last_name_en', 100)->nullable();
            $table->string('name_lo', 200);                   // ຊື່ເຕັມ (ລາວ) — required
            $table->string('name_en', 200)->nullable();        // Full Name (English)

            // Title / Honorific — Bilingual
            $table->string('title_lo', 100)->nullable();       // ຄຳນຳໜ້າ (ລາວ)
            $table->string('title_en', 100)->nullable();       // Honorific (English)

            // Position — Bilingual
            $table->string('position_lo', 200);                // ຕຳແໜ່ງ (ລາວ) — required
            $table->string('position_en', 200)->nullable();    // Position (English)

            // Location — Bilingual
            $table->string('birth_village_lo', 200)->nullable();
            $table->string('birth_village_en', 200)->nullable();
            $table->string('district_lo', 100)->nullable();
            $table->string('district_en', 100)->nullable();
            $table->string('province_lo', 100)->nullable();
            $table->string('province_en', 100)->nullable();

            // Monk-Specific Fields
            $table->string('current_temple_lo', 300)->nullable();  // ວັດທີ່ຢູ່ (ລາວ)
            $table->string('current_temple_en', 300)->nullable();  // Current Temple (English)
            $table->date('date_of_ordination')->nullable();        // ວັນທີ່ອຸປະສົມ
            $table->unsignedSmallInteger('pansa')->nullable();     // ພັນສາ (Vassa seniority)

            // Contact & Social
            $table->string('facebook', 300)->nullable();
            $table->string('photo_url', 500)->nullable();
            $table->string('email', 120)->nullable();
            $table->string('phone', 50)->nullable();

            // Biography & Education — Bilingual
            $table->text('bio_lo')->nullable();
            $table->text('bio_en')->nullable();
            $table->string('education_lo', 300)->nullable();
            $table->string('education_en', 300)->nullable();

            // Personal Info
            $table->date('date_of_birth')->nullable();

            // Term of Service
            $table->year('term_start')->nullable();
            $table->year('term_end')->nullable();

            // Display Control
            $table->smallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['is_active', 'sort_order']);
            $table->index('department_id');
            $table->index('gender');
        });

        // Add foreign key for department head after personnel table exists
        Schema::table('departments', function (Blueprint $table) {
            $table->foreign('head_id')->references('id')->on('personnel')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropForeign(['head_id']);
        });

        Schema::dropIfExists('personnel');
    }
};
