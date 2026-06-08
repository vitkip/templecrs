<div>

    {{-- Page Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-headline-md font-bold text-on-surface">{{ __('messages.finance') }}</h1>
            <p class="text-body-md text-on-surface-variant mt-1">{{ __('messages.finance_subtitle') }}</p>
        </div>
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('finance.categories.index') }}"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-outline-variant text-on-surface-variant hover:bg-surface-container transition-all text-label-md">
                <span class="material-symbols-outlined text-base">category</span>
                {{ __('messages.finance_categories') }}
            </a>
            <a href="{{ route('finance.report') }}"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-outline-variant text-on-surface-variant hover:bg-surface-container transition-all text-label-md">
                <span class="material-symbols-outlined text-base">picture_as_pdf</span>
                {{ __('messages.finance_report') }}
            </a>
            <a href="{{ route('finance.transactions.index') }}"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-outline-variant text-on-surface-variant hover:bg-surface-container transition-all text-label-md">
                <span class="material-symbols-outlined text-base">receipt_long</span>
                {{ __('messages.all_transactions') }}
            </a>
            <a href="{{ route('finance.transactions.create') }}"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white hover:bg-primary/90 transition-all font-bold text-label-md shadow-md btn-press">
                <span class="material-symbols-outlined text-base">add</span>
                {{ __('messages.add_transaction') }}
            </a>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Month Income --}}
        <div class="bg-surface-container rounded-2xl border border-outline-variant p-5">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-9 h-9 rounded-xl bg-green-100 flex items-center justify-center">
                    <span class="material-symbols-outlined text-green-600 text-lg">trending_up</span>
                </div>
                <span class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wide">{{ __('messages.income') }}</span>
            </div>
            <p class="text-title-lg font-bold text-green-600">{{ number_format((float)$monthIncome, 0, '.', ',') }}</p>
            <p class="text-[10px] text-on-surface-variant mt-1">ກີບ · {{ __('messages.this_month') }}</p>
        </div>

        {{-- Month Expense --}}
        <div class="bg-surface-container rounded-2xl border border-outline-variant p-5">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-9 h-9 rounded-xl bg-red-100 flex items-center justify-center">
                    <span class="material-symbols-outlined text-red-600 text-lg">trending_down</span>
                </div>
                <span class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wide">{{ __('messages.expense') }}</span>
            </div>
            <p class="text-title-lg font-bold text-red-600">{{ number_format((float)$monthExpense, 0, '.', ',') }}</p>
            <p class="text-[10px] text-on-surface-variant mt-1">ກີບ · {{ __('messages.this_month') }}</p>
        </div>

        {{-- Month Balance --}}
        <div class="bg-primary/5 rounded-2xl border border-primary/20 p-5">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-9 h-9 rounded-xl bg-primary/15 flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary text-lg">account_balance_wallet</span>
                </div>
                <span class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wide">{{ __('messages.balance') }}</span>
            </div>
            <p class="text-title-lg font-bold {{ $monthBalance >= 0 ? 'text-primary' : 'text-error' }}">{{ number_format((float)$monthBalance, 0, '.', ',') }}</p>
            <p class="text-[10px] text-on-surface-variant mt-1">ກີບ · {{ __('messages.net_balance') }}</p>
        </div>

        {{-- Year Income --}}
        <div class="bg-surface-container rounded-2xl border border-outline-variant p-5">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-9 h-9 rounded-xl bg-blue-100 flex items-center justify-center">
                    <span class="material-symbols-outlined text-blue-600 text-lg">calendar_month</span>
                </div>
                <span class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wide">{{ __('messages.this_year') }} {{ $year }}</span>
            </div>
            <p class="text-title-lg font-bold text-blue-600">{{ number_format((float)($yearIncome - $yearExpense), 0, '.', ',') }}</p>
            <p class="text-[10px] text-on-surface-variant mt-1">ກີບ · {{ __('messages.net_balance') }}</p>
        </div>
    </div>

    {{-- Chart + Category --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
        {{-- Bar Chart --}}
        <div class="lg:col-span-2 bg-surface-container rounded-2xl border border-outline-variant p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-title-md font-bold text-on-surface">{{ __('messages.monthly_trend') }} {{ $year }}</h2>
                <div class="flex items-center gap-4 text-[10px] font-bold text-on-surface-variant">
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-sm bg-green-500 inline-block"></span> {{ __('messages.income') }}</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-sm bg-red-400 inline-block"></span> {{ __('messages.expense') }}</span>
                </div>
            </div>
            <div id="fin-dash-chart-data"
                 data-labels='@json($chartLabels)'
                 data-income='@json($chartIncome)'
                 data-expense='@json($chartExpense)'
                 hidden></div>
            <div class="relative h-56">
                <canvas id="fin-dash-bar"></canvas>
            </div>
        </div>

        {{-- Category Breakdown (this month) --}}
        <div class="bg-surface-container rounded-2xl border border-outline-variant p-5">
            <h2 class="text-title-md font-bold text-on-surface mb-4">{{ __('messages.category_breakdown') }}</h2>
            @if ($categoryBreakdown->isEmpty())
                <div class="flex flex-col items-center justify-center h-40 text-center">
                    <span class="material-symbols-outlined text-3xl text-on-surface-variant/40 mb-2">pie_chart</span>
                    <p class="text-body-sm text-on-surface-variant">{{ __('messages.no_data_this_month') }}</p>
                </div>
            @else
                <div class="space-y-2">
                    @foreach (['income' => ['text-green-700','bg-green-100'], 'expense' => ['text-red-700','bg-red-100']] as $t => $colors)
                        @if (isset($categoryBreakdown[$t]))
                            <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wide mt-3 first:mt-0">{{ __('messages.' . $t) }}</p>
                            @foreach ($categoryBreakdown[$t] as $row)
                                <div class="flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span class="material-symbols-outlined text-sm {{ $colors[0] }} shrink-0">{{ $row->category->icon ?? 'category' }}</span>
                                        <span class="text-body-sm text-on-surface truncate">{{ $row->category->name ?? '?' }}</span>
                                    </div>
                                    <span class="text-label-sm font-bold {{ $colors[0] }} whitespace-nowrap">{{ number_format((float)$row->total, 0, '.', ',') }}</span>
                                </div>
                            @endforeach
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Recent Transactions --}}
    <div class="bg-surface-container rounded-2xl border border-outline-variant">
        <div class="px-5 py-4 border-b border-outline-variant flex items-center justify-between">
            <h2 class="text-title-md font-bold text-on-surface">{{ __('messages.recent_transactions') }}</h2>
            <a href="{{ route('finance.transactions.index') }}" class="text-label-sm text-primary hover:underline">{{ __('messages.view_all') }}</a>
        </div>
        @if ($recentTransactions->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center px-6">
                <span class="material-symbols-outlined text-4xl text-on-surface-variant/40 mb-3">receipt_long</span>
                <p class="text-body-md text-on-surface-variant">{{ __('messages.no_transactions') }}</p>
                <a href="{{ route('finance.transactions.create') }}"
                   class="mt-4 flex items-center gap-2 px-4 py-2 rounded-xl bg-primary text-white text-label-sm font-bold hover:bg-primary/90 transition-all">
                    <span class="material-symbols-outlined text-base">add</span>
                    {{ __('messages.add_transaction') }}
                </a>
            </div>
        @else
            <div class="divide-y divide-outline-variant">
                @foreach ($recentTransactions as $tx)
                    <div class="flex items-center gap-4 px-5 py-3 hover:bg-surface-container-high/40 transition-colors">
                        <div class="w-9 h-9 rounded-xl {{ $tx->is_income ? 'bg-green-100' : 'bg-red-100' }} flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-base {{ $tx->is_income ? 'text-green-600' : 'text-red-600' }}">{{ $tx->category->icon ?? 'category' }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-body-sm font-medium text-on-surface truncate">{{ $tx->description }}</p>
                            <p class="text-[11px] text-on-surface-variant">{{ $tx->category->name ?? '—' }} · {{ $tx->transaction_date_formatted }}</p>
                        </div>
                        <p class="text-label-md font-bold whitespace-nowrap {{ $tx->is_income ? 'text-green-700' : 'text-red-600' }}">
                            {{ $tx->is_income ? '+' : '-' }}{{ number_format((float)$tx->amount, 0, '.', ',') }}
                        </p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
(function () {
    var _dashChart = null;

    function initDashChart() {
        var store  = document.getElementById('fin-dash-chart-data');
        var canvas = document.getElementById('fin-dash-bar');
        if (!store || !canvas || typeof Chart === 'undefined') return;

        var labels  = JSON.parse(store.getAttribute('data-labels')  || '[]');
        var income  = JSON.parse(store.getAttribute('data-income')  || '[]');
        var expense = JSON.parse(store.getAttribute('data-expense') || '[]');

        if (_dashChart) { _dashChart.destroy(); _dashChart = null; }
        if (!labels.length) return;

        _dashChart = new Chart(canvas, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    { label: '{{ __('messages.income') }}',  data: income,  backgroundColor: 'rgba(34,197,94,0.7)',   borderRadius: 6, borderSkipped: false },
                    { label: '{{ __('messages.expense') }}', data: expense, backgroundColor: 'rgba(248,113,113,0.7)', borderRadius: 6, borderSkipped: false }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 11 } } },
                    y: {
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: {
                            font: { size: 10 },
                            callback: function (v) {
                                return v >= 1000000 ? (v / 1000000).toFixed(1) + 'M'
                                     : v >= 1000    ? (v / 1000).toFixed(0)    + 'K'
                                     : v;
                            }
                        }
                    }
                }
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initDashChart);
    } else {
        initDashChart();
    }

    document.addEventListener('livewire:updated', function () {
        requestAnimationFrame(initDashChart);
    });
}());
</script>
@endpush
