<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();

            // Document Identity — Bilingual
            $table->string('title_lo', 300);
            $table->string('title_en', 300)->nullable();
            $table->string('doc_number', 100)->nullable();

            // Category
            $table->enum('category', [
                'order',        // ຄຳສັ່ງ
                'announcement', // ແຈ້ງການ
                'certificate',  // ໃບຢັ້ງຢືນ
                'report',       // ລາຍງານ
                'project',      // ໂຄງການ
                'other',        // ອື່ນໆ
            ])->default('other');

            // Description — Bilingual
            $table->text('description_lo')->nullable();
            $table->text('description_en')->nullable();

            // File Info
            $table->string('file_path', 500)->nullable();
            $table->string('file_name', 300)->nullable();
            $table->string('file_type', 150)->nullable();
            $table->unsignedBigInteger('file_size')->nullable();

            // Dates
            $table->date('issued_date')->nullable();

            // Display Control
            $table->smallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'sort_order']);
            $table->index('category');
            $table->index('department_id');
            $table->index('doc_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
