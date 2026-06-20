<?php

namespace App\Http\Controllers;

use App\Models\FinanceTransaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

        [$from, $to] = $this->getDateRange($period, $reportYear, $reportMonth, $dateFrom, $dateTo);

        $totalIncome  = FinanceTransaction::income()->dateBetween($from, $to)->sum('amount');
        $totalExpense = FinanceTransaction::expense()->dateBetween($from, $to)->sum('amount');
        $netBalance   = $totalIncome - $totalExpense;

        $byCategory = FinanceTransaction::with('category')
            ->selectRaw('category_id, type, SUM(amount) as total, COUNT(*) as count')
            ->dateBetween($from, $to)
            ->groupBy('category_id', 'type')
            ->get()
            ->groupBy('type');

        $transactions = FinanceTransaction::with('category', 'creator')
            ->dateBetween($from, $to)
            ->orderBy('transaction_date')
            ->get();

        $orgName    = \App\Models\Setting::get('org_name_lo',      'ກຳມາທິການ ສາທາຣະນູປະການ ສູນກາງ ອພສ');
        $orgAddress = \App\Models\Setting::get('org_address_lo',   'ວັດທາດຫຼວງເໜືອ ນະຄອນຫຼວງວຽງຈັນ');
        $orgPhone   = \App\Models\Setting::get('org_phone',        '');
        $orgEmail   = \App\Models\Setting::get('org_email',        '');
        $orgWebsite = \App\Models\Setting::get('org_website',      '');
        $logoKey    = \App\Models\Setting::get('org_logo_url');
        $orgLogoPath = $logoKey && file_exists(storage_path('app/public/' . $logoKey))
            ? storage_path('app/public/' . $logoKey)
            : null;

        $pdf = Pdf::loadView('finance.report-pdf', compact(
            'from', 'to', 'totalIncome', 'totalExpense', 'netBalance',
            'byCategory', 'transactions', 'orgName', 'orgAddress',
            'orgPhone', 'orgEmail', 'orgWebsite', 'orgLogoPath', 'period',
            'reportYear', 'reportMonth'
        ))
        ->setBasePath(base_path())
        ->setPaper('a4', 'portrait');

        $filename = 'finance-report-' . $from . '-to-' . $to . '.pdf';

        if (ob_get_length()) {
            ob_end_clean();
        }

        return $pdf->stream($filename);
    }

    private function getDateRange(string $period, int $year, int $month, ?string $from, ?string $to): array
    {
        return match ($period) {
            'month'   => [
                now()->setYear($year)->setMonth($month)->startOfMonth()->format('Y-m-d'),
                now()->setYear($year)->setMonth($month)->endOfMonth()->format('Y-m-d'),
            ],
            'quarter' => $this->quarterRange($year, $month),
            'year'    => ["{$year}-01-01", "{$year}-12-31"],
            'custom'  => [$from ?: now()->startOfMonth()->format('Y-m-d'), $to ?: now()->format('Y-m-d')],
            default   => [now()->startOfMonth()->format('Y-m-d'), now()->format('Y-m-d')],
        };
    }

    private function quarterRange(int $year, int $month): array
    {
        $quarter = (int) ceil($month / 3);
        $start   = ($quarter - 1) * 3 + 1;
        $end     = $start + 2;
        return [
            now()->setYear($year)->setMonth($start)->startOfMonth()->format('Y-m-d'),
            now()->setYear($year)->setMonth($end)->endOfMonth()->format('Y-m-d'),
        ];
    }
}
