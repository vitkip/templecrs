<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Remove exchange rate and LAK-converted amount.
        // Amounts are now stored in their native currency only.
        // Reports and totals are separated by currency — no conversion.
        Schema::table('finance_transactions', function (Blueprint $table) {
            $table->dropColumn(['exchange_rate', 'amount_lak']);
        });
    }

    public function down(): void
    {
        Schema::table('finance_transactions', function (Blueprint $table) {
            $table->decimal('exchange_rate', 15, 6)->default(1.000000)->after('currency');
            $table->decimal('amount_lak', 15, 2)->default(0)->after('exchange_rate');
        });
    }
};
