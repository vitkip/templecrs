<?php

namespace App\Livewire\Finance;

use App\Models\FinanceTransaction;
use Livewire\Component;

class FinancePage extends Component
{
    public function mount(): void
    {
        abort_unless(auth()->check() && auth()->user()->canManageFinance(), 403);
    }

    public function render()
    {
        $now   = now();
        $year  = $now->year;
        $month = $now->month;

        // ── Totals for the current month, grouped by currency ──────────────────
        $monthData = FinanceTransaction::selectRaw('currency, type, SUM(amount) as total')
            ->forMonth($year, $month)
            ->groupBy('currency', 'type')
            ->get();

        $monthIncomeByCurrency  = $monthData->where('type', 'income') ->pluck('total', 'currency')->toArray();
        $monthExpenseByCurrency = $monthData->where('type', 'expense')->pluck('total', 'currency')->toArray();

        // ── Year totals by currency ────────────────────────────────────────────
        $yearData = FinanceTransaction::selectRaw('currency, type, SUM(amount) as total')
            ->forYear($year)
            ->groupBy('currency', 'type')
            ->get();

        $yearIncomeByCurrency  = $yearData->where('type', 'income') ->pluck('total', 'currency')->toArray();
        $yearExpenseByCurrency = $yearData->where('type', 'expense')->pluck('total', 'currency')->toArray();

        $currencies = FinanceTransaction::CURRENCIES;

        // ── Monthly trend per currency for the bar chart (no cross-currency conversion) ──
        $monthNames   = ['ມ.ກ', 'ກ.ພ', 'ມ.ນ', 'ເມ.ສ', 'ພ.ພ', 'ມິ.ຖ', 'ກ.ລ', 'ສ.ຫ', 'ກ.ຍ', 'ຕ.ລ', 'ພ.ຈ', 'ທ.ວ'];
        $allChartData = [];

        foreach (array_keys($currencies) as $code) {
            $monthly = FinanceTransaction::selectRaw("
                MONTH(transaction_date) as m,
                SUM(CASE WHEN type = 'income'  THEN amount ELSE 0 END) as income,
                SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense
            ")->forYear($year)
              ->where('currency', $code)
              ->groupByRaw('MONTH(transaction_date)')
              ->orderByRaw('MONTH(transaction_date)')
              ->get()
              ->keyBy('m');

            $incArr = [];
            $expArr = [];
            $hasData = false;
            for ($m = 1; $m <= 12; $m++) {
                $inc = (float) ($monthly[$m]->income  ?? 0);
                $exp = (float) ($monthly[$m]->expense ?? 0);
                $incArr[] = $inc;
                $expArr[] = $exp;
                if ($inc > 0 || $exp > 0) $hasData = true;
            }

            if ($hasData) {
                $allChartData[$code] = [
                    'labels'  => $monthNames,
                    'income'  => $incArr,
                    'expense' => $expArr,
                ];
            }
        }

        // ── Category breakdown for this month, per currency ───────────────────
        $categoryBreakdown = FinanceTransaction::with('category')
            ->selectRaw('category_id, type, currency, SUM(amount) as total')
            ->forMonth($year, $month)
            ->groupBy('category_id', 'type', 'currency')
            ->get()
            ->groupBy('type');

        // ── Recent transactions ───────────────────────────────────────────────
        $recentTransactions = FinanceTransaction::with('category')
            ->orderByDesc('transaction_date')
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        return view('livewire.finance.finance-page', compact(
            'monthIncomeByCurrency', 'monthExpenseByCurrency',
            'yearIncomeByCurrency',  'yearExpenseByCurrency',
            'allChartData',
            'categoryBreakdown', 'recentTransactions',
            'currencies', 'month', 'year'
        ))->layout('components.layouts.app', ['title' => __('messages.finance')]);
    }
}
