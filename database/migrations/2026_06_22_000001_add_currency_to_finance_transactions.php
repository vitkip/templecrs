<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('finance_transactions', function (Blueprint $table) {
            // Currency code stored with every transaction
            $table->enum('currency', ['LAK', 'THB', 'USD', 'CNY'])
                  ->default('LAK')
                  ->after('type');

            // How many LAK equal 1 unit of this currency at transaction time
            // Locked at save — ensures historical accuracy for financial reports
            $table->decimal('exchange_rate', 15, 6)
                  ->default(1.000000)
                  ->after('currency');

            // Canonical LAK amount = amount × exchange_rate
            // ALL aggregations (totals, charts, reports) use this column
            $table->decimal('amount_lak', 15, 2)
                  ->default(0)
                  ->after('exchange_rate');
        });

        // Backfill existing rows: all are LAK with rate 1, so amount_lak = amount
        DB::table('finance_transactions')
            ->whereNull('deleted_at')
            ->update([
                'currency'      => 'LAK',
                'exchange_rate' => 1.000000,
                'amount_lak'    => DB::raw('amount'),
            ]);

        // Also backfill soft-deleted rows so the column is never NULL
        DB::table('finance_transactions')
            ->whereNotNull('deleted_at')
            ->update([
                'currency'      => 'LAK',
                'exchange_rate' => 1.000000,
                'amount_lak'    => DB::raw('amount'),
            ]);
    }

    public function down(): void
    {
        Schema::table('finance_transactions', function (Blueprint $table) {
            $table->dropColumn(['currency', 'exchange_rate', 'amount_lak']);
        });
    }
};
