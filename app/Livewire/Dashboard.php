<?php

namespace App\Livewire;

use App\Models\Department;
use App\Models\Document;
use App\Models\FinanceTransaction;
use App\Models\News;
use App\Models\Personnel;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();
        $now  = now();
        $data = [];

        if ($user->isAdmin()) {
            $data['personnel_total']    = Cache::remember('dash_personnel_total', 300, fn() => Personnel::count());
            $data['personnel_active']   = Cache::remember('dash_personnel_active', 300, fn() => Personnel::where('is_active', true)->count());
            $data['personnel_monks']    = Cache::remember('dash_personnel_monks', 300, fn() => Personnel::where('gender', 'monk')->where('is_active', true)->count());
            $data['personnel_lay']      = Cache::remember('dash_personnel_lay', 300, fn() => Personnel::whereIn('gender', ['male', 'female'])->where('is_active', true)->count());
            $data['recent_personnel']   = Cache::remember('dash_recent_personnel', 300, fn() => Personnel::where('is_active', true)->latest()->limit(5)->get());
        }

        if ($user->canManageNews()) {
            $data['news_total']         = Cache::remember('dash_news_total', 300, fn() => News::count());
            $data['news_published']     = Cache::remember('dash_news_published', 300, fn() => News::where('is_active', true)->whereNotNull('published_at')->where('published_at', '<=', now())->count());
            $data['news_draft']         = ($data['news_total'] ?? 0) - ($data['news_published'] ?? 0);
            $data['recent_news']        = Cache::remember('dash_recent_news', 300, fn() => News::where('is_active', true)->whereNotNull('published_at')->latest('published_at')->limit(5)->get());
        }

        if ($user->canManageDocuments()) {
            $data['docs_total']         = Cache::remember('dash_docs_total', 300, fn() => Document::count());
            $data['docs_active']        = Cache::remember('dash_docs_active', 300, fn() => Document::where('is_active', true)->count());
            $data['docs_downloads']     = Cache::remember('dash_docs_downloads', 300, fn() => Document::sum('download_count'));
            $data['recent_docs']        = Cache::remember('dash_recent_docs', 300, fn() => Document::where('is_active', true)->latest()->limit(5)->get());
        }

        if ($user->isSuperAdmin()) {
            $data['users_total']        = Cache::remember('dash_users_total', 300, fn() => User::count());
            $data['departments_total']  = Cache::remember('dash_depts_total', 300, fn() => Department::count());
        }

        if ($user->canManageFinance()) {
            $year  = $now->year;
            $month = $now->month;

            $data['month_income']   = Cache::remember("dash_month_income_{$year}_{$month}", 300, fn() => FinanceTransaction::income()->forMonth($year, $month)->sum('amount'));
            $data['month_expense']  = Cache::remember("dash_month_expense_{$year}_{$month}", 300, fn() => FinanceTransaction::expense()->forMonth($year, $month)->sum('amount'));
            $data['month_balance']  = $data['month_income'] - $data['month_expense'];
            $data['year_income']    = Cache::remember("dash_year_income_{$year}", 300, fn() => FinanceTransaction::income()->forYear($year)->sum('amount'));
            $data['year_expense']   = Cache::remember("dash_year_expense_{$year}", 300, fn() => FinanceTransaction::expense()->forYear($year)->sum('amount'));
            $data['recent_finance'] = Cache::remember("dash_recent_finance", 300, fn() => FinanceTransaction::with('category')->latest('transaction_date')->limit(5)->get());

            $monthNames = ['ມ.ກ', 'ກ.ພ', 'ມ.ນ', 'ເມ.ສ', 'ພ.ພ', 'ມິ.ຖ', 'ກ.ລ', 'ສ.ຫ', 'ກ.ຍ', 'ຕ.ລ', 'ພ.ຈ', 'ທ.ວ'];

            $monthlyData = Cache::remember("dash_monthly_chart_{$year}", 300, fn() =>
                FinanceTransaction::selectRaw("
                    MONTH(transaction_date) as month,
                    SUM(CASE WHEN type = 'income'  THEN amount ELSE 0 END) as income,
                    SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense
                ")->forYear($year)->groupByRaw('MONTH(transaction_date)')->orderByRaw('MONTH(transaction_date)')->get()->keyBy('month')
            );

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
