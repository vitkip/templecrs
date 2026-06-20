<?php

namespace App\Livewire;

use App\Models\Department;
use App\Models\Document;
use App\Models\FinanceTransaction;
use App\Models\News;
use App\Models\Personnel;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();
        $now  = now();
        $data = [];

        if ($user->isAdmin()) {
            $data['personnel_total']    = Personnel::count();
            $data['personnel_active']   = Personnel::where('is_active', true)->count();
            $data['personnel_monks']    = Personnel::where('gender', 'monk')->where('is_active', true)->count();
            $data['personnel_lay']      = Personnel::whereIn('gender', ['male', 'female'])->where('is_active', true)->count();
            $data['recent_personnel']   = Personnel::where('is_active', true)->latest()->limit(5)->get();
        }

        if ($user->canManageNews()) {
            $data['news_total']         = News::count();
            $data['news_published']     = News::where('is_active', true)->whereNotNull('published_at')->where('published_at', '<=', now())->count();
            $data['news_draft']         = News::count() - $data['news_published'];
            $data['recent_news']        = News::where('is_active', true)->whereNotNull('published_at')->latest('published_at')->limit(5)->get();
        }

        if ($user->canManageDocuments()) {
            $data['docs_total']         = Document::count();
            $data['docs_active']        = Document::where('is_active', true)->count();
            $data['docs_downloads']     = Document::sum('download_count');
            $data['recent_docs']        = Document::where('is_active', true)->latest()->limit(5)->get();
        }

        if ($user->isSuperAdmin()) {
            $data['users_total']        = User::count();
            $data['departments_total']  = Department::count();
        }

        if ($user->canManageFinance()) {
            $year  = $now->year;
            $month = $now->month;

            $data['month_income']   = FinanceTransaction::income()->forMonth($year, $month)->sum('amount');
            $data['month_expense']  = FinanceTransaction::expense()->forMonth($year, $month)->sum('amount');
            $data['month_balance']  = $data['month_income'] - $data['month_expense'];
            $data['year_income']    = FinanceTransaction::income()->forYear($year)->sum('amount');
            $data['year_expense']   = FinanceTransaction::expense()->forYear($year)->sum('amount');
            $data['recent_finance'] = FinanceTransaction::with('category')->latest('transaction_date')->limit(5)->get();

            $monthNames = ['ມ.ກ', 'ກ.ພ', 'ມ.ນ', 'ເມ.ສ', 'ພ.ພ', 'ມິ.ຖ', 'ກ.ລ', 'ສ.ຫ', 'ກ.ຍ', 'ຕ.ລ', 'ພ.ຈ', 'ທ.ວ'];

            $monthlyData = FinanceTransaction::selectRaw("
                MONTH(transaction_date) as month,
                SUM(CASE WHEN type = 'income'  THEN amount ELSE 0 END) as income,
                SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense
            ")->forYear($year)->groupByRaw('MONTH(transaction_date)')->orderByRaw('MONTH(transaction_date)')->get()->keyBy('month');

            $chartLabels = $chartIncome = $chartExpense = [];
            for ($m = 1; $m <= 12; $m++) {
                $chartLabels[]  = $monthNames[$m - 1];
                $chartIncome[]  = (float) ($monthlyData[$m]->income  ?? 0);
                $chartExpense[] = (float) ($monthlyData[$m]->expense ?? 0);
            }

            $data['chart_labels']  = $chartLabels;
            $data['chart_income']  = $chartIncome;
            $data['chart_expense'] = $chartExpense;
            $data['current_year']  = $year;
            $data['current_month'] = $month;
        }

        return view('livewire.dashboard', $data)
            ->layout('components.layouts.app', ['title' => __('messages.dashboard')]);
    }
}
