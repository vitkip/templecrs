<div>

    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('finance.index') }}"
               class="p-2 rounded-xl hover:bg-surface-container text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined text-xl">arrow_back</span>
            </a>
            <div>
                <h1 class="text-headline-sm font-bold text-on-surface">{{ __('messages.finance_report') }}</h1>
                <p class="text-body-sm text-on-surface-variant">{{ $from }} → {{ $to }}</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('finance.report.pdf', ['period' => $period, 'reportYear' => $reportYear, 'reportMonth' => $reportMonth, 'dateFrom' => $dateFrom, 'dateTo' => $dateTo]) }}"
               download="finance-report-{{ $from }}-to-{{ $to }}.pdf"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-error text-white hover:bg-error/90 transition-all font-bold text-label-md shadow-md btn-press">
                <span class="material-symbols-outlined text-base">picture_as_pdf</span>
                {{ __('messages.download_pdf') }}
            </a>
        </div>
    </div>

    {{-- Period Selector --}}
    <div class="bg-surface-container rounded-2xl border border-outline-variant p-4 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
            {{-- Period Type --}}
            <div>
                <label class="block text-label-sm font-bold text-on-surface-variant mb-1.5">{{ __('messages.report_period') }}</label>
                <select wire:model.live="period"
                        class="w-full px-3 py-2 bg-surface border border-outline-variant rounded-xl text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20">
                    <option value="month">{{ __('messages.monthly') }}</option>
                    <option value="quarter">{{ __('messages.quarterly') }}</option>
                    <option value="year">{{ __('messages.yearly') }}</option>
                    <option value="custom">{{ __('messages.custom_range') }}</option>
                </select>
            </div>

            {{-- Year --}}
            @if ($period !== 'custom')
                <div>
                    <label class="block text-label-sm font-bold text-on-surface-variant mb-1.5">{{ __('messages.year') }}</label>
                    <select wire:model.live="reportYear"
                            class="w-full px-3 py-2 bg-surface border border-outline-variant rounded-xl text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20">
                        @foreach ($years as $y)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Month (only for month/quarter) --}}
                @if (in_array($period, ['month', 'quarter']))
                    <div>
                        <label class="block text-label-sm font-bold text-on-surface-variant mb-1.5">
                            {{ $period === 'month' ? __('messages.month') : __('messages.quarter') }}
                        </label>
                        @if ($period === 'month')
                            <select wire:model.live="reportMonth"
                                    class="w-full px-3 py-2 bg-surface border border-outline-variant rounded-xl text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20">
                                @foreach (['ມັງກອນ','ກຸມພາ','ມີນາ','ເມສາ','ພຶດສະພາ','ມິຖຸນາ','ກໍລະກົດ','ສິງຫາ','ກັນຍາ','ຕຸລາ','ພະຈິກ','ທັນວາ'] as $i => $mn)
                                    <option value="{{ $i + 1 }}">{{ $mn }}</option>
                                @endforeach
                            </select>
                        @else
                            <select wire:model.live="reportMonth"
                                    class="w-full px-3 py-2 bg-surface border border-outline-variant rounded-xl text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20">
                                <option value="1">{{ __('messages.quarter') }} 1 (ມ.ກ - ມ.ນ)</option>
                                <option value="4">{{ __('messages.quarter') }} 2 (ເມ.ສ - ມິ.ຖ)</option>
                                <option value="7">{{ __('messages.quarter') }} 3 (ກ.ລ - ກ.ຍ)</option>
                                <option value="10">{{ __('messages.quarter') }} 4 (ຕ.ລ - ທ.ວ)</option>
                            </select>
                        @endif
                    </div>
                @endif
            @else
                {{-- Custom Range --}}
                <div>
                    <label class="block text-label-sm font-bold text-on-surface-variant mb-1.5">{{ __('messages.date_from') }}</label>
                    <input wire:model.live="dateFrom" type="date"
                           class="w-full px-3 py-2 bg-surface border border-outline-variant rounded-xl text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20" />
                </div>
                <div>
                    <label class="block text-label-sm font-bold text-on-surface-variant mb-1.5">{{ __('messages.date_to') }}</label>
                    <input wire:model.live="dateTo" type="date"
                           class="w-full px-3 py-2 bg-surface border border-outline-variant rounded-xl text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20" />
                </div>
            @endif
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-green-50 border border-green-200 rounded-2xl p-5 text-center">
            <p class="text-[10px] font-bold text-green-700 uppercase tracking-wider mb-1">{{ __('messages.total_income') }}</p>
            <p class="text-headline-sm font-bold text-green-700">{{ number_format((float)$totalIncome, 0, '.', ',') }}</p>
            <p class="text-body-sm text-green-600 mt-1">ກີບ</p>
        </div>
        <div class="bg-red-50 border border-red-200 rounded-2xl p-5 text-center">
            <p class="text-[10px] font-bold text-red-700 uppercase tracking-wider mb-1">{{ __('messages.total_expense') }}</p>
            <p class="text-headline-sm font-bold text-red-700">{{ number_format((float)$totalExpense, 0, '.', ',') }}</p>
            <p class="text-body-sm text-red-600 mt-1">ກີບ</p>
        </div>
        <div class="{{ $netBalance >= 0 ? 'bg-primary/5 border-primary/20' : 'bg-error/5 border-error/20' }} border rounded-2xl p-5 text-center">
            <p class="text-[10px] font-bold {{ $netBalance >= 0 ? 'text-primary' : 'text-error' }} uppercase tracking-wider mb-1">{{ __('messages.net_balance') }}</p>
            <p class="text-headline-sm font-bold {{ $netBalance >= 0 ? 'text-primary' : 'text-error' }}">{{ number_format((float)$netBalance, 0, '.', ',') }}</p>
            <p class="text-body-sm {{ $netBalance >= 0 ? 'text-primary/70' : 'text-error/70' }} mt-1">ກີບ</p>
        </div>
    </div>

    {{-- Chart data store (Livewire morphs these attributes on filter change) --}}
    <div id="fin-report-chart-data"
         data-labels='@json($chartLabels)'
         data-income='@json($chartIncome)'
         data-expense='@json($chartExpense)'
         data-income-amounts='@json($incomeCategories->pluck("total")->map(fn($v) => (float)$v)->values())'
         data-income-names='@json($incomeCategories->map(fn($r) => $r->category?->name ?? "?")->values())'
         data-expense-amounts='@json($expenseCategories->pluck("total")->map(fn($v) => (float)$v)->values())'
         data-expense-names='@json($expenseCategories->map(fn($r) => $r->category?->name ?? "?")->values())'
         hidden></div>

    {{-- Charts Row --}}
    @if (count($chartLabels) > 0)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
        {{-- Bar Chart --}}
        <div class="lg:col-span-2 bg-surface-container rounded-2xl border border-outline-variant p-5">
            <h2 class="text-title-md font-bold text-on-surface mb-4">{{ __('messages.monthly_trend') }}</h2>
            <div class="relative h-56">
                <canvas id="fin-report-bar"></canvas>
            </div>
            <div class="flex items-center justify-center gap-6 mt-3 text-[10px] font-bold text-on-surface-variant">
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-green-500 inline-block"></span> {{ __('messages.income') }}</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-red-400 inline-block"></span> {{ __('messages.expense') }}</span>
            </div>
        </div>

        {{-- Pie Charts --}}
        <div class="bg-surface-container rounded-2xl border border-outline-variant p-5 space-y-4">
            <div>
                <h3 class="text-label-md font-bold text-green-700 mb-2">{{ __('messages.income') }}</h3>
                @if ($incomeCategories->isNotEmpty())
                    <div class="relative h-28">
                        <canvas id="fin-report-pie-income"></canvas>
                    </div>
                @else
                    <p class="text-body-sm text-on-surface-variant text-center py-4">—</p>
                @endif
            </div>
            <div class="border-t border-outline-variant pt-4">
                <h3 class="text-label-md font-bold text-red-600 mb-2">{{ __('messages.expense') }}</h3>
                @if ($expenseCategories->isNotEmpty())
                    <div class="relative h-28">
                        <canvas id="fin-report-pie-expense"></canvas>
                    </div>
                @else
                    <p class="text-body-sm text-on-surface-variant text-center py-4">—</p>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- Category Breakdown Table --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
        @foreach (['income' => [__('messages.income'), 'green'], 'expense' => [__('messages.expense'), 'red']] as $t => [$label, $color])
            <div class="bg-surface-container rounded-2xl border border-outline-variant overflow-hidden">
                <div class="px-5 py-3 bg-{{ $color }}-50 border-b border-{{ $color }}-200 flex items-center gap-2">
                    <span class="material-symbols-outlined text-{{ $color }}-600 text-base">{{ $t === 'income' ? 'trending_up' : 'trending_down' }}</span>
                    <h3 class="text-label-md font-bold text-{{ $color }}-700">{{ $label }} — {{ __('messages.by_category') }}</h3>
                </div>
                @if (isset($byCategory[$t]) && $byCategory[$t]->isNotEmpty())
                    @php $typeTotal = $byCategory[$t]->sum('total'); @endphp
                    <div class="divide-y divide-outline-variant">
                        @foreach ($byCategory[$t]->sortByDesc('total') as $row)
                            @php $pct = $typeTotal > 0 ? round(($row->total / $typeTotal) * 100) : 0; @endphp
                            <div class="px-5 py-3">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-sm text-on-surface-variant">{{ $row->category->icon ?? 'category' }}</span>
                                        <span class="text-body-sm text-on-surface">{{ $row->category->name ?? '?' }}</span>
                                        <span class="text-[10px] text-on-surface-variant">({{ $row->count }})</span>
                                    </div>
                                    <span class="text-label-sm font-bold text-{{ $color }}-700">{{ number_format((float)$row->total, 0, '.', ',') }} ກີບ</span>
                                </div>
                                <div class="w-full bg-{{ $color }}-100 rounded-full h-1.5">
                                    <div class="bg-{{ $color }}-500 h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="px-5 py-3 bg-{{ $color }}-50 border-t border-{{ $color }}-200 flex justify-between">
                        <span class="text-label-sm font-bold text-{{ $color }}-700">{{ __('messages.total') }}</span>
                        <span class="text-label-sm font-bold text-{{ $color }}-700">{{ number_format((float)$typeTotal, 0, '.', ',') }} ກີບ</span>
                    </div>
                @else
                    <div class="px-5 py-8 text-center text-body-sm text-on-surface-variant">{{ __('messages.no_data') }}</div>
                @endif
            </div>
        @endforeach
    </div>

    {{-- Transaction Detail Table --}}
    <div class="bg-surface-container rounded-2xl border border-outline-variant overflow-hidden">
        <div class="px-5 py-4 border-b border-outline-variant flex items-center justify-between">
            <h2 class="text-title-md font-bold text-on-surface">{{ __('messages.transaction_history') }}</h2>
            <span class="text-label-sm text-on-surface-variant">{{ $transactions->count() }} {{ __('messages.items') }}</span>
        </div>
        @if ($transactions->isEmpty())
            <div class="py-12 text-center text-body-md text-on-surface-variant">{{ __('messages.no_transactions') }}</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-surface-container-high">
                        <tr>
                            <th class="px-4 py-2.5 text-left text-label-sm font-bold text-on-surface-variant">{{ __('messages.transaction_date') }}</th>
                            <th class="px-4 py-2.5 text-left text-label-sm font-bold text-on-surface-variant">{{ __('messages.type') }}</th>
                            <th class="px-4 py-2.5 text-left text-label-sm font-bold text-on-surface-variant">{{ __('messages.category') }}</th>
                            <th class="px-4 py-2.5 text-left text-label-sm font-bold text-on-surface-variant">{{ __('messages.description') }}</th>
                            <th class="px-4 py-2.5 text-right text-label-sm font-bold text-on-surface-variant">{{ __('messages.amount') }}</th>
                            <th class="px-4 py-2.5 text-left text-label-sm font-bold text-on-surface-variant">{{ __('messages.ref_number') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @foreach ($transactions as $tx)
                            <tr class="hover:bg-surface-container-high/40 transition-colors">
                                <td class="px-4 py-2.5 text-body-sm text-on-surface-variant whitespace-nowrap">{{ $tx->transaction_date_formatted }}</td>
                                <td class="px-4 py-2.5">
                                    @if ($tx->is_income)
                                        <span class="text-[10px] font-bold text-green-700 bg-green-100 px-1.5 py-0.5 rounded-full">{{ __('messages.income') }}</span>
                                    @else
                                        <span class="text-[10px] font-bold text-red-700 bg-red-100 px-1.5 py-0.5 rounded-full">{{ __('messages.expense') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2.5 text-body-sm text-on-surface">{{ $tx->category->name ?? '—' }}</td>
                                <td class="px-4 py-2.5 text-body-sm text-on-surface">{{ $tx->description }}</td>
                                <td class="px-4 py-2.5 text-right font-bold text-body-sm whitespace-nowrap {{ $tx->is_income ? 'text-green-700' : 'text-red-600' }}">
                                    {{ $tx->is_income ? '+' : '-' }}{{ number_format((float)$tx->amount, 0, '.', ',') }}
                                </td>
                                <td class="px-4 py-2.5 text-body-sm text-on-surface-variant">{{ $tx->reference_number ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-surface-container-high">
                        <tr>
                            <td colspan="4" class="px-4 py-2.5 text-label-sm font-bold text-on-surface">{{ __('messages.total') }}</td>
                            <td class="px-4 py-2.5 text-right">
                                <span class="text-label-sm font-bold text-green-700 block">+{{ number_format((float)$totalIncome, 0, '.', ',') }}</span>
                                <span class="text-label-sm font-bold text-red-600 block">-{{ number_format((float)$totalExpense, 0, '.', ',') }}</span>
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
(function () {
    var _reportCharts = [];

    function initReportCharts() {
        var store = document.getElementById('fin-report-chart-data');
        if (!store || typeof Chart === 'undefined') return;

        var labels         = JSON.parse(store.getAttribute('data-labels')          || '[]');
        var income         = JSON.parse(store.getAttribute('data-income')          || '[]');
        var expense        = JSON.parse(store.getAttribute('data-expense')         || '[]');
        var incomeAmounts  = JSON.parse(store.getAttribute('data-income-amounts')  || '[]');
        var incomeNames    = JSON.parse(store.getAttribute('data-income-names')    || '[]');
        var expenseAmounts = JSON.parse(store.getAttribute('data-expense-amounts') || '[]');
        var expenseNames   = JSON.parse(store.getAttribute('data-expense-names')   || '[]');

        _reportCharts.forEach(function (c) { c.destroy(); });
        _reportCharts = [];

        var barCanvas = document.getElementById('fin-report-bar');
        if (barCanvas && labels.length) {
            _reportCharts.push(new Chart(barCanvas, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        { label: '{{ __('messages.income') }}',  data: income,  backgroundColor: 'rgba(34,197,94,0.7)',   borderRadius: 5, borderSkipped: false },
                        { label: '{{ __('messages.expense') }}', data: expense, backgroundColor: 'rgba(248,113,113,0.7)', borderRadius: 5, borderSkipped: false }
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { size: 10 } } },
                        y: {
                            grid: { color: 'rgba(0,0,0,0.04)' },
                            ticks: {
                                font: { size: 9 },
                                callback: function (v) {
                                    return v >= 1000000 ? (v / 1000000).toFixed(1) + 'M'
                                         : v >= 1000    ? (v / 1000).toFixed(0)    + 'K'
                                         : v;
                                }
                            }
                        }
                    }
                }
            }));
        }

        var pieIncomeCanvas = document.getElementById('fin-report-pie-income');
        if (pieIncomeCanvas && incomeAmounts.length) {
            _reportCharts.push(new Chart(pieIncomeCanvas, {
                type: 'doughnut',
                data: {
                    labels: incomeNames,
                    datasets: [{ data: incomeAmounts, backgroundColor: ['#22c55e','#16a34a','#4ade80','#86efac','#bbf7d0','#6ee7b7'], borderWidth: 1 }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { font: { size: 9 }, boxWidth: 10 } } } }
            }));
        }

        var pieExpenseCanvas = document.getElementById('fin-report-pie-expense');
        if (pieExpenseCanvas && expenseAmounts.length) {
            _reportCharts.push(new Chart(pieExpenseCanvas, {
                type: 'doughnut',
                data: {
                    labels: expenseNames,
                    datasets: [{ data: expenseAmounts, backgroundColor: ['#ef4444','#dc2626','#f87171','#fca5a5','#fecaca','#f97316','#fb923c'], borderWidth: 1 }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { font: { size: 9 }, boxWidth: 10 } } } }
            }));
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initReportCharts);
    } else {
        initReportCharts();
    }

    document.addEventListener('livewire:updated', function () {
        requestAnimationFrame(initReportCharts);
    });
}());
</script>
@endpush
