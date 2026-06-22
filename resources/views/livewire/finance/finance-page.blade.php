<div>
    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-headline-md font-bold text-on-surface">{{ __('messages.finance') }}</h1>
            <p class="text-body-md text-on-surface-variant mt-1">
                {{ __('messages.finance_overview_subtitle') }} — {{ now()->locale('lo')->translatedFormat('F Y') }}
            </p>
        </div>
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('finance.report') }}"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-outline-variant text-on-surface-variant hover:bg-surface-container transition-all text-label-md">
                <span class="material-symbols-outlined text-base">bar_chart</span>
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

    {{-- ── Summary Cards: This Month & This Year (per currency) ─────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">

        {{-- Monthly Summary --}}
        <div class="bg-surface-container rounded-2xl border border-outline-variant p-5">
            <div class="flex items-center gap-2 mb-4">
                <span class="material-symbols-outlined text-primary text-xl">calendar_month</span>
                <h2 class="text-title-sm font-bold text-on-surface">ສະຫຼຸບ​ ເດືອນ {{ now()->locale('lo')->translatedFormat('F') }} {{ $year }}</h2>
            </div>
            @php
                $allMonthlyCodes = array_unique(array_merge(array_keys($monthIncomeByCurrency), array_keys($monthExpenseByCurrency)));
                $sortedMonthly   = array_filter(array_keys($currencies), fn($c) => in_array($c, $allMonthlyCodes));
            @endphp
            @if (empty($sortedMonthly))
                <p class="text-body-sm text-on-surface-variant text-center py-4">ຍັງບໍ່ມີລາຍການ​ ເດືອນນີ້</p>
            @else
                <div class="space-y-3">
                    @foreach ($sortedMonthly as $code)
                        @php
                            $cfg = $currencies[$code];
                            $inc = (float) ($monthIncomeByCurrency[$code]  ?? 0);
                            $exp = (float) ($monthExpenseByCurrency[$code] ?? 0);
                            $bal = $inc - $exp;
                        @endphp
                        <div class="rounded-xl border border-outline-variant p-3">
                            <div class="flex items-center justify-between mb-2">
                                <span class="flex items-center gap-1.5 text-label-sm font-bold text-on-surface">
                                    <span class="text-base leading-none">{{ $cfg['symbol'] }}</span>
                                    {{ $cfg['name_lo'] }} ({{ $code }})
                                </span>
                                <span class="text-label-sm font-bold {{ $bal >= 0 ? 'text-primary' : 'text-error' }}">
                                    {{ ($bal >= 0 ? '+' : '') . number_format($bal, $cfg['decimals'], '.', ',') }}
                                </span>
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-[11px]">
                                <div class="flex items-center gap-1 text-green-700">
                                    <span class="material-symbols-outlined text-xs">trending_up</span>
                                    <span>{{ number_format($inc, $cfg['decimals'], '.', ',') }}</span>
                                </div>
                                <div class="flex items-center gap-1 text-red-600 justify-end">
                                    <span class="material-symbols-outlined text-xs">trending_down</span>
                                    <span>{{ number_format($exp, $cfg['decimals'], '.', ',') }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Yearly Summary --}}
        <div class="bg-surface-container rounded-2xl border border-outline-variant p-5">
            <div class="flex items-center gap-2 mb-4">
                <span class="material-symbols-outlined text-secondary text-xl">event_note</span>
                <h2 class="text-title-sm font-bold text-on-surface">ສະຫຼຸບ​ ປີ {{ $year }}</h2>
            </div>
            @php
                $allYearlyCodes = array_unique(array_merge(array_keys($yearIncomeByCurrency), array_keys($yearExpenseByCurrency)));
                $sortedYearly   = array_filter(array_keys($currencies), fn($c) => in_array($c, $allYearlyCodes));
            @endphp
            @if (empty($sortedYearly))
                <p class="text-body-sm text-on-surface-variant text-center py-4">ຍັງບໍ່ມີລາຍການ​ ປີນີ້</p>
            @else
                <div class="space-y-3">
                    @foreach ($sortedYearly as $code)
                        @php
                            $cfg = $currencies[$code];
                            $inc = (float) ($yearIncomeByCurrency[$code]  ?? 0);
                            $exp = (float) ($yearExpenseByCurrency[$code] ?? 0);
                            $bal = $inc - $exp;
                        @endphp
                        <div class="rounded-xl border border-outline-variant p-3">
                            <div class="flex items-center justify-between mb-2">
                                <span class="flex items-center gap-1.5 text-label-sm font-bold text-on-surface">
                                    <span class="text-base leading-none">{{ $cfg['symbol'] }}</span>
                                    {{ $cfg['name_lo'] }} ({{ $code }})
                                </span>
                                <span class="text-label-sm font-bold {{ $bal >= 0 ? 'text-primary' : 'text-error' }}">
                                    {{ ($bal >= 0 ? '+' : '') . number_format($bal, $cfg['decimals'], '.', ',') }}
                                </span>
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-[11px]">
                                <div class="flex items-center gap-1 text-green-700">
                                    <span class="material-symbols-outlined text-xs">trending_up</span>
                                    <span>{{ number_format($inc, $cfg['decimals'], '.', ',') }}</span>
                                </div>
                                <div class="flex items-center gap-1 text-red-600 justify-end">
                                    <span class="material-symbols-outlined text-xs">trending_down</span>
                                    <span>{{ number_format($exp, $cfg['decimals'], '.', ',') }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- ── Bar Chart (all currencies) ────────────────────────────────────────── --}}
    @if (!empty($allChartData))
        <div class="bg-surface-container rounded-2xl border border-outline-variant p-5 mb-6"
             x-data="financePageChart(@js($allChartData), @js($currencies))">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <h2 class="text-title-sm font-bold text-on-surface">
                    ກຣາບ ລາຍຮັບ / ລາຍຈ່າຍ — ປີ {{ $year }}
                </h2>
                <div class="flex gap-1 flex-wrap">
                    <template x-for="code in availableCodes" :key="code">
                        <button @click="selectCurrency(code)"
                                :class="active === code
                                    ? 'bg-primary text-on-primary'
                                    : 'bg-surface border border-outline-variant text-on-surface-variant hover:bg-surface-container-high'"
                                class="text-[11px] px-2.5 py-0.5 rounded-full font-bold transition-colors"
                                x-text="currencies[code].symbol + ' ' + code">
                        </button>
                    </template>
                </div>
            </div>
            <div class="h-56">
                <canvas id="financeBarChart"></canvas>
            </div>
        </div>
    @endif

    {{-- ── Category Breakdown + Recent Transactions ──────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        {{-- Category Breakdown --}}
        <div class="bg-surface-container rounded-2xl border border-outline-variant p-5">
            <h2 class="text-title-sm font-bold text-on-surface mb-4">ແບ່ງໝວດ​ ເດືອນນີ້</h2>

            {{-- Income categories --}}
            @if ($categoryBreakdown->has('income') && $categoryBreakdown['income']->isNotEmpty())
                <p class="text-[10px] font-bold text-green-700 uppercase tracking-wide mb-2 flex items-center gap-1">
                    <span class="material-symbols-outlined text-xs">trending_up</span>{{ __('messages.income') }}
                </p>
                <div class="space-y-1.5 mb-4">
                    @foreach ($categoryBreakdown['income'] as $row)
                        @php $cfg = $currencies[$row->currency]; @endphp
                        <div class="flex items-center justify-between gap-2 text-body-sm">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="material-symbols-outlined text-sm text-on-surface-variant shrink-0">{{ $row->category->icon ?? 'category' }}</span>
                                <span class="text-on-surface truncate">{{ $row->category->name ?? '—' }}</span>
                                <span class="text-[10px] text-on-surface-variant shrink-0 px-1 py-0.5 bg-surface-container-high rounded">{{ $row->currency }}</span>
                            </div>
                            <span class="font-bold text-green-700 shrink-0">{{ number_format((float) $row->total, $cfg['decimals'], '.', ',') }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Expense categories --}}
            @if ($categoryBreakdown->has('expense') && $categoryBreakdown['expense']->isNotEmpty())
                <p class="text-[10px] font-bold text-red-600 uppercase tracking-wide mb-2 flex items-center gap-1">
                    <span class="material-symbols-outlined text-xs">trending_down</span>{{ __('messages.expense') }}
                </p>
                <div class="space-y-1.5">
                    @foreach ($categoryBreakdown['expense'] as $row)
                        @php $cfg = $currencies[$row->currency]; @endphp
                        <div class="flex items-center justify-between gap-2 text-body-sm">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="material-symbols-outlined text-sm text-on-surface-variant shrink-0">{{ $row->category->icon ?? 'category' }}</span>
                                <span class="text-on-surface truncate">{{ $row->category->name ?? '—' }}</span>
                                <span class="text-[10px] text-on-surface-variant shrink-0 px-1 py-0.5 bg-surface-container-high rounded">{{ $row->currency }}</span>
                            </div>
                            <span class="font-bold text-red-600 shrink-0">{{ number_format((float) $row->total, $cfg['decimals'], '.', ',') }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            @if ($categoryBreakdown->isEmpty())
                <p class="text-body-sm text-on-surface-variant text-center py-4">ຍັງບໍ່ມີຂໍ້ມູນ​ ເດືອນນີ້</p>
            @endif
        </div>

        {{-- Recent Transactions --}}
        <div class="bg-surface-container rounded-2xl border border-outline-variant p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-title-sm font-bold text-on-surface">{{ __('messages.recent_transactions') }}</h2>
                <a href="{{ route('finance.transactions.index') }}"
                   class="text-label-sm text-primary hover:underline">{{ __('messages.view_all') }}</a>
            </div>
            @forelse ($recentTransactions as $tx)
                <div class="flex items-center gap-3 py-2 border-b border-outline-variant last:border-0">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 {{ $tx->is_income ? 'bg-green-100' : 'bg-red-100' }}">
                        <span class="material-symbols-outlined text-sm {{ $tx->is_income ? 'text-green-600' : 'text-red-600' }}">
                            {{ $tx->is_income ? 'trending_up' : 'trending_down' }}
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-body-sm text-on-surface truncate">{{ $tx->description }}</p>
                        <p class="text-[10px] text-on-surface-variant">{{ $tx->transaction_date_formatted }} · {{ $tx->category->name ?? '—' }}</p>
                    </div>
                    <span class="text-label-sm font-bold shrink-0 {{ $tx->is_income ? 'text-green-700' : 'text-red-600' }}">
                        {{ $tx->is_income ? '+' : '-' }}{{ $tx->amount_formatted }}
                    </span>
                </div>
            @empty
                <div class="flex flex-col items-center gap-2 py-8">
                    <span class="material-symbols-outlined text-3xl text-on-surface-variant/40">receipt_long</span>
                    <p class="text-body-sm text-on-surface-variant">{{ __('messages.no_transactions') }}</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

@script
<script>
    Alpine.data('financePageChart', (allChartData, currencies) => {
        // Store chart instance outside reactive object to avoid Alpine Proxy recursion
        let chartInstance = null;

        return {
            active: null,
            availableCodes: [],
            currencies: currencies,

            init() {
                this.availableCodes = Object.keys(allChartData);
                // Default to LAK if available, else first currency with data
                this.active = this.availableCodes.includes('LAK')
                    ? 'LAK'
                    : (this.availableCodes[0] ?? null);
                if (this.active) {
                    this.$nextTick(() => this.buildChart());
                }
            },

            selectCurrency(code) {
                this.active = code;
                this.updateChart();
            },

            buildChart() {
                const ctx = document.getElementById('financeBarChart');
                if (!ctx || !this.active) return;
                if (chartInstance) { chartInstance.destroy(); chartInstance = null; }

                const d   = allChartData[this.active];
                const sym = currencies[this.active].symbol;
                const lbl = currencies[this.active].name_lo;

                chartInstance = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: d.labels,
                        datasets: [
                            {
                                label: 'ລາຍຮັບ (' + sym + ' ' + lbl + ')',
                                data: d.income,
                                backgroundColor: 'rgba(34,197,94,0.7)',
                                borderRadius: 6,
                            },
                            {
                                label: 'ລາຍຈ່າຍ (' + sym + ' ' + lbl + ')',
                                data: d.expense,
                                backgroundColor: 'rgba(239,68,68,0.7)',
                                borderRadius: 6,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'top' } },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { callback: v => v.toLocaleString() }
                            }
                        }
                    }
                });
            },

            updateChart() {
                if (!chartInstance || !this.active) return;
                const d   = allChartData[this.active];
                const sym = currencies[this.active].symbol;
                const lbl = currencies[this.active].name_lo;

                chartInstance.data.labels            = d.labels;
                chartInstance.data.datasets[0].data  = d.income;
                chartInstance.data.datasets[0].label = 'ລາຍຮັບ (' + sym + ' ' + lbl + ')';
                chartInstance.data.datasets[1].data  = d.expense;
                chartInstance.data.datasets[1].label = 'ລາຍຈ່າຍ (' + sym + ' ' + lbl + ')';
                chartInstance.update();
            }
        };
    });
</script>
@endscript
