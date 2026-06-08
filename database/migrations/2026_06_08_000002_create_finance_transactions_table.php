<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finance_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('finance_categories')->restrictOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->enum('type', ['income', 'expense']);
            $table->decimal('amount', 15, 2);
            $table->string('description', 1000);
            $table->string('reference_number', 100)->nullable();
            $table->date('transaction_date');
            $table->string('receipt_path', 500)->nullable();
            $table->text('note')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['type', 'transaction_date']);
            $table->index('transaction_date');
            $table->index('category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_transactions');
    }
};
