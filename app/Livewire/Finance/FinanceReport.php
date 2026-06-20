<?php

namespace App\Livewire\Finance;

use App\Models\FinanceCategory;
use App\Models\FinanceTransaction;
use Livewire\Attributes\Url;
use Livewire\Component;

class FinanceReport extends Component
{
    #[Url]
    public string $period = 'month';  // month | quarter | year | custom

    #[Url]
    public int $reportYear = 0;

    #[Url]
    public int $reportMonth = 0;

    #[Url]
    public string $dateFrom = '';

    #[Url]
    public string $dateTo = '';

    public function mount(): void
    {
        abort_unless(auth()->check() && auth()->user()->canManageFinance(), 403);

        if (!$this->reportYear) $this->reportYear   = now()->year;
        if (!$this->reportMonth) $this->reportMonth = now()->month;
    }

    public function updatedPeriod(): void
    {
        // reset custom dates when switching period type
        if ($this->period !== 'custom') {
            $this->dateFrom = $this->dateTo = '';
        }
    }

    private function getDateRange(): array
    {
        return match ($this->period) {
            'month'   => [
                now()->setYear($this->reportYear)->setMonth($this->reportMonth)->startOfMonth()->format('Y-m-d'),
                now()->setYear($this->reportYear)->setMonth($this->reportMonth)->endOfMonth()->format('Y-m-d'),
            ],
            'quarter' => $this->quarterRange(),
            'year'    => ["{$this->reportYear}-01-01", "{$this->reportYear}-12-31"],
            'custom'  => [$this->dateFrom ?: now()->startOfMonth()->format('Y-m-d'),
                          $this->dateTo   ?: now()->format('Y-m-d')],
            default   => [now()->startOfMonth()->format('Y-m-d'), now()->format('Y-m-d')],
        };
    }

    private function quarterRange(): array
    {
        $month   = $this->reportMonth;
        $quarter = (int) ceil($month / 3);
        $start   = ($quarter - 1) * 3 + 1;
        $end     = $start + 2;
        return [
            now()->setYear($this->reportYear)->setMonth($start)->startOfMonth()->format('Y-m-d'),
            now()->setYear($this->reportYear)->setMonth($end)->endOfMonth()->format('Y-m-d'),
        ];
    }

    public function render()
    {
        [$from, $to] = $this->getDateRange();

        $base = FinanceTransaction::with('category')->dateBetween($from, $to);

        $totalIncome  = (clone $base)->income()->sum('amount');
        $totalExpense = (clone $base)->expense()->sum('amount');
        $netBalance   = $totalIncome - $totalExpense;

        // By category
        $byCategory = FinanceTransaction::with('category')
            ->selectRaw('category_id, type, SUM(amount) as total, COUNT(*) as count')
            ->dateBetween($from, $to)
            ->groupBy('category_id', 'type')
            ->get()
            ->groupBy('type');

        // Monthly breakdown (for year/custom)
        $monthlyBreakdown = FinanceTransaction::selectRaw("
            YEAR(transaction_date) as y, MONTH(transaction_date) as m,
            SUM(CASE WHEN type = 'income'  THEN amount ELSE 0 END) as income,
            SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense
        ")->dateBetween($from, $to)
          ->groupByRaw('YEAR(transaction_date), MONTH(transaction_date)')
          ->orderByRaw('YEAR(transaction_date), MONTH(transaction_date)')
          ->get();

        // Chart data
        $monthNames   = ['ມ.ກ', 'ກ.ພ', 'ມ.ນ', 'ເມ.ສ', 'ພ.ພ', 'ມິ.ຖ', 'ກ.ລ', 'ສ.ຫ', 'ກ.ຍ', 'ຕ.ລ', 'ພ.ຈ', 'ທ.ວ'];
        $chartLabels  = $monthlyBreakdown->map(fn($r) => $monthNames[$r->m - 1] . ' ' . $r->y)->toArray();
        $chartIncome  = $monthlyBreakdown->pluck('income')->map(fn($v) => (float) $v)->toArray();
        $chartExpense = $monthlyBreakdown->pluck('expense')->map(fn($v) => (float) $v)->toArray();

        // Pie chart: income categories
        $incomeCategories  = $byCategory->get('income',  collect());
        $expenseCategories = $byCategory->get('expense', collect());

        // Transaction list for report
        $transactions = FinanceTransaction::with('category', 'creator')
            ->dateBetween($from, $to)
            ->orderBy('transaction_date')
            ->orderByDesc('created_at')
            ->get();

        $years = range(now()->year, max(now()->year - 5, 2020));

        return view('livewire.finance.finance-report', compact(
            'from', 'to', 'totalIncome', 'totalExpense', 'netBalance',
            'byCategory', 'incomeCategories', 'expenseCategories',
            'monthlyBreakdown', 'chartLabels', 'chartIncome', 'chartExpense',
            'transactions', 'years'
        ))->layout('components.layouts.app', ['title' => __('messages.finance_report')]);
    }
}
