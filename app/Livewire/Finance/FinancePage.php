<?php

namespace App\Livewire\Finance;

use App\Models\FinanceTransaction;
use Livewire\Component;

class FinancePage extends Component
{
    public function render()
    {
        $now   = now();
        $year  = $now->year;
        $month = $now->month;

        // Current month totals
        $monthIncome  = FinanceTransaction::income()->forMonth($year, $month)->sum('amount');
        $monthExpense = FinanceTransaction::expense()->forMonth($year, $month)->sum('amount');
        $monthBalance = $monthIncome - $monthExpense;

        // Current year totals
        $yearIncome  = FinanceTransaction::income()->forYear($year)->sum('amount');
        $yearExpense = FinanceTransaction::expense()->forYear($year)->sum('amount');

        // Monthly trend for current year (12 months)
        $monthlyData = FinanceTransaction::selectRaw("
            MONTH(transaction_date) as month,
            SUM(CASE WHEN type = 'income'  THEN amount ELSE 0 END) as income,
            SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense
        ")->forYear($year)
          ->groupByRaw('MONTH(transaction_date)')
          ->orderByRaw('MONTH(transaction_date)')
          ->get()
          ->keyBy('month');

        $chartLabels  = [];
        $chartIncome  = [];
        $chartExpense = [];

        $monthNames = ['ມ.ກ', 'ກ.ພ', 'ມ.ນ', 'ເມ.ສ', 'ພ.ພ', 'ມິ.ຖ', 'ກ.ລ', 'ສ.ຫ', 'ກ.ຍ', 'ຕ.ລ', 'ພ.ຈ', 'ທ.ວ'];

        for ($m = 1; $m <= 12; $m++) {
            $chartLabels[]  = $monthNames[$m - 1];
            $chartIncome[]  = (float) ($monthlyData[$m]->income  ?? 0);
            $chartExpense[] = (float) ($monthlyData[$m]->expense ?? 0);
        }

        // Category breakdown (current month)
        $categoryBreakdown = FinanceTransaction::with('category')
            ->selectRaw('category_id, type, SUM(amount) as total')
            ->forMonth($year, $month)
            ->groupBy('category_id', 'type')
            ->get()
            ->groupBy('type');

        // Recent transactions
        $recentTransactions = FinanceTransaction::with('category')
            ->orderByDesc('transaction_date')
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        return view('livewire.finance.finance-page', compact(
            'monthIncome', 'monthExpense', 'monthBalance',
            'yearIncome', 'yearExpense',
            'chartLabels', 'chartIncome', 'chartExpense',
            'categoryBreakdown', 'recentTransactions',
            'month', 'year'
        ))->layout('components.layouts.app', ['title' => __('messages.finance')]);
    }
}
