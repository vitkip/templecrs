<?php

namespace App\Http\Controllers;

use App\Models\FinanceCategory;
use App\Models\FinanceTransaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class FinanceReportController extends Controller
{
    public function pdf(Request $request): \Illuminate\Http\Response
    {
        abort_unless(auth()->check() && auth()->user()->canManageFinance(), 403);

        $period      = $request->input('period', 'month');
        $reportYear  = (int) $request->input('reportYear', now()->year);
        $reportMonth = (int) $request->input('reportMonth', now()->month);
        $dateFrom    = $request->input('dateFrom') ?: null;
        $dateTo      = $request->input('dateTo')   ?: null;
        $categoryId  = $request->input('categoryId') ? (int) $request->input('categoryId') : null;

        [$from, $to] = $this->getDateRange($period, $reportYear, $reportMonth, $dateFrom, $dateTo);

        $selectedCategory = $categoryId ? FinanceCategory::find($categoryId) : null;
        $currencies = FinanceTransaction::CURRENCIES;

        // Totals grouped by currency — filtered by category if specified
        $totalsRaw = FinanceTransaction::selectRaw('currency, type, SUM(amount) as total')
            ->dateBetween($from, $to)
            ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
            ->groupBy('currency', 'type')
            ->get();

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

        // Category breakdown with currency
        $byCategoryRaw = FinanceTransaction::with('category')
            ->selectRaw('category_id, type, currency, SUM(amount) as total, COUNT(*) as count')
            ->dateBetween($from, $to)
            ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
            ->groupBy('category_id', 'type', 'currency')
            ->get();

        $byCategory = [];
        foreach (['income', 'expense'] as $t) {
            foreach (array_keys($currencies) as $code) {
                $rows = $byCategoryRaw->where('type', $t)->where('currency', $code);
                if ($rows->isNotEmpty()) {
                    $byCategory[$t][$code] = $rows->sortByDesc(fn($r) => (float) $r->total)->values();
                }
            }
        }

        $transactions = FinanceTransaction::with('category')
            ->dateBetween($from, $to)
            ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
            ->orderBy('transaction_date')
            ->get();

        $orgName    = \App\Models\Setting::get('org_name_lo',    'ກຳມາທິການ ສາທາຣະນູປະການ ສູນກາງ ອພສ');
        $orgAddress = \App\Models\Setting::get('org_address_lo', 'ວັດທາດຫຼວງເໜືອ ນະຄອນຫຼວງວຽງຈັນ');
        $orgPhone   = \App\Models\Setting::get('org_phone',      '');
        $orgEmail   = \App\Models\Setting::get('org_email',      '');
        $orgWebsite = \App\Models\Setting::get('org_website',    '');
        $logoKey    = \App\Models\Setting::get('org_logo_url');
        $orgLogoPath = $logoKey && file_exists(storage_path('app/public/' . $logoKey))
            ? storage_path('app/public/' . $logoKey)
            : null;

        $pdf = Pdf::loadView('finance.report-pdf', compact(
            'from', 'to',
            'byCurrencyMap', 'byCategory',
            'transactions', 'currencies',
            'orgName', 'orgAddress', 'orgPhone', 'orgEmail', 'orgWebsite', 'orgLogoPath',
            'period', 'reportYear', 'reportMonth', 'selectedCategory', 'categoryId'
        ))
        ->setBasePath(base_path())
        ->setPaper('a4', 'portrait');

        if (ob_get_length()) ob_end_clean();

        $filename = $selectedCategory
            ? 'finance-report-category-' . $selectedCategory->id . '-' . $from . '-to-' . $to . '.pdf'
            : 'finance-report-' . $from . '-to-' . $to . '.pdf';

        return $pdf->stream($filename);
    }

    private function getDateRange(string $period, int $year, int $month, ?string $from, ?string $to): array
    {
        return match ($period) {
            'month'   => [
                \Carbon\Carbon::createFromDate($year, $month, 1)->startOfMonth()->format('Y-m-d'),
                \Carbon\Carbon::createFromDate($year, $month, 1)->endOfMonth()->format('Y-m-d'),
            ],
            'quarter' => $this->quarterRange($year, $month),
            'year'    => ["{$year}-01-01", "{$year}-12-31"],
            'custom'  => [$from ?: now()->startOfMonth()->format('Y-m-d'), $to ?: now()->format('Y-m-d')],
            'all'     => [
                FinanceTransaction::min('transaction_date') ?? '2020-01-01',
                FinanceTransaction::max('transaction_date') ?? now()->format('Y-m-d'),
            ],
            default   => [now()->startOfMonth()->format('Y-m-d'), now()->format('Y-m-d')],
        };
    }

    private function quarterRange(int $year, int $month): array
    {
        $quarter = (int) ceil($month / 3);
        $start   = ($quarter - 1) * 3 + 1;
        $end     = $start + 2;
        return [
            \Carbon\Carbon::createFromDate($year, $start, 1)->startOfMonth()->format('Y-m-d'),
            \Carbon\Carbon::createFromDate($year, $end, 1)->endOfMonth()->format('Y-m-d'),
        ];
    }
}
