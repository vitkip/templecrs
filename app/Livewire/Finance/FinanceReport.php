<?php

namespace App\Livewire\Finance;

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

        if (!$this->reportYear)  $this->reportYear  = now()->year;
        if (!$this->reportMonth) $this->reportMonth = now()->month;
    }

    public function updatedPeriod(): void
    {
        if ($this->period !== 'custom') {
            $this->dateFrom = $this->dateTo = '';
        }
        // Normalize reportMonth to the first month of its quarter (1, 4, 7, 10)
        // so the dropdown selection always matches the computed date range.
        if ($this->period === 'quarter') {
            $quarter = (int) ceil($this->reportMonth / 3);
            $this->reportMonth = ($quarter - 1) * 3 + 1;
        }
    }

    private function getDateRange(): array
    {
        return match ($this->period) {
            'month'   => [
                \Carbon\Carbon::createFromDate($this->reportYear, $this->reportMonth, 1)->startOfMonth()->format('Y-m-d'),
                \Carbon\Carbon::createFromDate($this->reportYear, $this->reportMonth, 1)->endOfMonth()->format('Y-m-d'),
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
        $quarter = (int) ceil($this->reportMonth / 3);
        $start   = ($quarter - 1) * 3 + 1;
        $end     = $start + 2;
        return [
            \Carbon\Carbon::createFromDate($this->reportYear, $start, 1)->startOfMonth()->format('Y-m-d'),
            \Carbon\Carbon::createFromDate($this->reportYear, $end, 1)->endOfMonth()->format('Y-m-d'),
        ];
    }

    public function render()
    {
        [$from, $to] = $this->getDateRange();

        // ── Totals by currency (no cross-currency conversion) ─────────────────
        $totalsRaw = FinanceTransaction::selectRaw('currency, type, SUM(amount) as total')
            ->dateBetween($from, $to)
            ->groupBy('currency', 'type')
            ->get();

        $currencies    = FinanceTransaction::CURRENCIES;
        $byCurrencyMap = [];

        foreach (array_keys($currencies) as $code) {
            $inc = $totalsRaw->where('type', 'income') ->where('currency', $code)->first();
            $exp = $totalsRaw->where('type', 'expense')->where('currency', $code)->first();
            if ($inc || $exp) {
                $byCurrencyMap[$code] = [
                    'income'  => (float) ($inc->total ?? 0),
                    'expense' => (float) ($exp->total ?? 0),
                    'balance' => (float) ($inc->total ?? 0) - (float) ($exp->total ?? 0),
                ];
            }
        }

        // ── Category breakdown, grouped by type → currency → category ─────────
        $byCategoryRaw = FinanceTransaction::with('category')
            ->selectRaw('category_id, type, currency, SUM(amount) as total, COUNT(*) as count')
            ->dateBetween($from, $to)
            ->groupBy('category_id', 'type', 'currency')
            ->get();

        // byCategory[type][currency] = Collection of rows
        $byCategory = [];
        foreach (['income', 'expense'] as $t) {
            foreach (array_keys($currencies) as $code) {
                $rows = $byCategoryRaw->where('type', $t)->where('currency', $code);
                if ($rows->isNotEmpty()) {
                    $byCategory[$t][$code] = $rows->sortByDesc('total')->values();
                }
            }
        }

        // ── Chart data per currency ────────────────────────────────────────────
        $monthNames   = ['ມ.ກ', 'ກ.ພ', 'ມ.ນ', 'ເມ.ສ', 'ພ.ພ', 'ມິ.ຖ', 'ກ.ລ', 'ສ.ຫ', 'ກ.ຍ', 'ຕ.ລ', 'ພ.ຈ', 'ທ.ວ'];
        $allChartData = [];

        foreach (array_keys($currencies) as $code) {
            $breakdown = FinanceTransaction::selectRaw("
                YEAR(transaction_date) as y, MONTH(transaction_date) as m,
                SUM(CASE WHEN type = 'income'  THEN amount ELSE 0 END) as income,
                SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense
            ")->dateBetween($from, $to)
              ->where('currency', $code)
              ->groupByRaw('YEAR(transaction_date), MONTH(transaction_date)')
              ->orderByRaw('YEAR(transaction_date), MONTH(transaction_date)')
              ->get();

            if ($breakdown->isNotEmpty()) {
                $allChartData[$code] = [
                    'labels'  => $breakdown->map(fn($r) => $monthNames[$r->m - 1] . ' ' . $r->y)->toArray(),
                    'income'  => $breakdown->pluck('income')->map(fn($v) => (float) $v)->toArray(),
                    'expense' => $breakdown->pluck('expense')->map(fn($v) => (float) $v)->toArray(),
                ];
            }
        }

        // ── Transaction list ──────────────────────────────────────────────────
        $transactions = FinanceTransaction::with('category', 'creator')
            ->dateBetween($from, $to)
            ->orderBy('transaction_date')
            ->orderByDesc('created_at')
            ->get();

        $years = range(now()->year, max(now()->year - 5, 2020));

        return view('livewire.finance.finance-report', compact(
            'from', 'to',
            'byCurrencyMap', 'byCategory',
            'allChartData',
            'transactions', 'currencies', 'years'
        ))->layout('components.layouts.app', ['title' => __('messages.finance_report')]);
    }
}
