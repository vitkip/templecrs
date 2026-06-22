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
                <p class="text-body-sm text-on-surface-variant mt-0.5">{{ $from }} → {{ $to }}</p>
            </div>
        </div>
        <a href="{{ route('finance.report.pdf', request()->query()) }}" target="_blank"
            class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-error text-white hover:bg-error/90 transition-all font-bold text-label-md shadow-md btn-press">
            <span class="material-symbols-outlined text-base">picture_as_pdf</span>
            {{ __('ສົງອອກຂໍ້ມູນ PDF') }}
        </a>
    </div>

    {{-- Period Selector --}}
    <div class="bg-surface-container rounded-2xl border border-outline-variant p-4 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <select wire:model.live="period"
                class="w-full px-3 py-2 bg-surface border border-outline-variant rounded-xl text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20">
                <option value="month">{{ __('messages.monthly') }}</option>
                <option value="quarter">{{ __('messages.quarterly') }}</option>
                <option value="year">{{ __('messages.yearly') }}</option>
                <option value="custom">{{ __('messages.custom_range') }}</option>
            </select>
            <select wire:model.live="reportYear"
                class="w-full px-3 py-2 bg-surface border border-outline-variant rounded-xl text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20">
                @foreach ($years as $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </select>
            @if ($period === 'month')
                <select wire:model.live="reportMonth"
                    class="w-full px-3 py-2 bg-surface border border-outline-variant rounded-xl text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20">
                    @foreach (range(1, 12) as $m)
                        <option value="{{ $m }}">{{ now()->setMonth($m)->locale('lo')->translatedFormat('F') }}</option>
                    @endforeach
                </select>
            @elseif ($period === 'quarter')
                <select wire:model.live="reportMonth"
                    class="w-full px-3 py-2 bg-surface border border-outline-variant rounded-xl text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20">
                    <option value="1">{{ __('messages.q1') }}</option>
                    <option value="4">{{ __('messages.q2') }}</option>
                    <option value="7">{{ __('messages.q3') }}</option>
                    <option value="10">{{ __('messages.q4') }}</option>
                </select>
            @endif
            @if ($period === 'custom')
                <div class="flex gap-2 items-center sm:col-span-2">
                    <input wire:model.live="dateFrom" type="date"
                        class="flex-1 px-3 py-2 bg-surface border border-outline-variant rounded-xl text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/20" />
                    <span class="text-on-surface-variant text-xs">→</span>
                    <input wire:model.live="dateTo" type="date"
                        class="flex-1 px-3 py-2 bg-surface border border-outline-variant rounded-xl text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/20" />
                </div>
            @endif
        </div>
    </div>

    {{-- ── Summary by Currency ────────────────────────────────────────────────── --}}
    <div class="bg-surface-container rounded-2xl border border-outline-variant p-5 mb-6">
        <h2 class="text-title-sm font-bold text-on-surface mb-4">ສະຫຼຸບລວມ — ແຍກຕາມສະກຸນເງີນ</h2>

        @if (empty($byCurrencyMap))
            <p class="text-body-sm text-on-surface-variant text-center py-4">ຍັງບໍ່ມີຂໍ້ມູນໃນຊ່ວງນີ້</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-body-sm">
                    <thead>
                        <tr
                            class="border-b border-outline-variant text-[10px] font-bold text-on-surface-variant uppercase tracking-wide">
                            <th class="pb-2 text-left">ສະກຸນເງີນ</th>
                            <th class="pb-2 text-right text-green-700">ລາຍຮັບ</th>
                            <th class="pb-2 text-right text-red-600">ລາຍຈ່າຍ</th>
                            <th class="pb-2 text-right">ຍອດຄົງເຫຼືອ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @foreach ($currencies as $code => $cfg)
                            @if (!empty($byCurrencyMap[$code]))
                                @php $row = $byCurrencyMap[$code]; @endphp
                                <tr>
                                    <td class="py-2 font-bold">
                                        <span class="text-base">{{ $cfg['symbol'] }}</span>
                                        {{ $cfg['name_lo'] }}
                                        <span class="text-[10px] text-on-surface-variant ml-1">{{ $code }}</span>
                                    </td>
                                    <td class="py-2 text-right font-bold text-green-700">
                                        {{ number_format($row['income'], $cfg['decimals'], '.', ',') }}</td>
                                    <td class="py-2 text-right font-bold text-red-600">
                                        {{ number_format($row['expense'], $cfg['decimals'], '.', ',') }}</td>
                                    <td
                                        class="py-2 text-right font-bold {{ $row['balance'] >= 0 ? 'text-primary' : 'text-error' }}">
                                        {{ ($row['balance'] >= 0 ? '+' : '') . number_format($row['balance'], $cfg['decimals'], '.', ',') }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- ── Bar Chart (per currency) ───────────────────────────────────────── --}}
    @if (!empty($allChartData))
        <div class="bg-surface-container rounded-2xl border border-outline-variant p-5 mb-6"
            x-data="financeChart(@js($allChartData), @js($currencies))">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <h2 class="text-title-sm font-bold text-on-surface">ກຣາບ ລາຍຮັບ / ລາຍຈ່າຍ</h2>
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
                <canvas id="reportBarChart"></canvas>
            </div>
        </div>
    @endif

    {{-- ── Category Breakdown ─────────────────────────────────────────────────── --}}
    @if (!empty($byCategory))
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
            @foreach (['income' => ['label' => 'ລາຍຮັບ', 'color' => 'green'], 'expense' => ['label' => 'ລາຍຈ່າຍ', 'color' => 'red']] as $type => $meta)
                @if (!empty($byCategory[$type]))
                    <div class="bg-surface-container rounded-2xl border border-outline-variant p-5">
                        <h3 class="text-title-sm font-bold text-{{ $meta['color'] }}-700 mb-4 flex items-center gap-2">
                            <span
                                class="material-symbols-outlined text-base">{{ $type === 'income' ? 'trending_up' : 'trending_down' }}</span>
                            ໝວດ{{ $meta['label'] }}
                        </h3>
                        @foreach ($byCategory[$type] as $code => $rows)
                            @php $cfg = $currencies[$code]; @endphp
                            <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wide mb-1.5 mt-3 first:mt-0">
                                {{ $cfg['symbol'] }} {{ $cfg['name_lo'] }} ({{ $code }})
                            </p>
                            <div class="space-y-1.5">
                                @foreach ($rows as $row)
                                    <div class="flex items-center justify-between gap-2 text-body-sm">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <span
                                                class="material-symbols-outlined text-sm text-on-surface-variant shrink-0">{{ $row->category->icon ?? 'category' }}</span>
                                            <span class="text-on-surface truncate">{{ $row->category->name ?? '—' }}</span>
                                            <span class="text-[10px] text-on-surface-variant shrink-0">({{ $row->count }})</span>
                                        </div>
                                        <span class="font-bold text-{{ $meta['color'] }}-700 shrink-0">
                                            {{ number_format((float) $row->total, $cfg['decimals'], '.', ',') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @endif
            @endforeach
        </div>
    @endif

    {{-- ── Transaction List ───────────────────────────────────────────────────── --}}
    <div class="bg-surface-container rounded-2xl border border-outline-variant overflow-hidden">
        <div class="p-4 border-b border-outline-variant">
            <h2 class="text-title-sm font-bold text-on-surface">ລາຍການທໍາລະດ ({{ $transactions->count() }} ລາຍການ)</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-body-sm">
                <thead class="bg-surface-container-high">
                    <tr>
                        <th class="px-4 py-2 text-left text-[10px] font-bold text-on-surface-variant uppercase">ວັນທີ
                        </th>
                        <th class="px-4 py-2 text-left text-[10px] font-bold text-on-surface-variant uppercase">ປະເພດ
                        </th>
                        <th class="px-4 py-2 text-left text-[10px] font-bold text-on-surface-variant uppercase">ໝວດ</th>
                        <th class="px-4 py-2 text-left text-[10px] font-bold text-on-surface-variant uppercase">ລາຍລະອຽດ
                        </th>
                        <th class="px-4 py-2 text-right text-[10px] font-bold text-on-surface-variant uppercase">
                            ຈຳນວນເງີນ</th>
                        <th class="px-4 py-2 text-left text-[10px] font-bold text-on-surface-variant uppercase">ອ້າງອີງ
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($transactions as $tx)
                        <tr class="hover:bg-surface-container-high/40 transition-colors">
                            <td class="px-4 py-2 whitespace-nowrap text-on-surface-variant">
                                {{ $tx->transaction_date_formatted }}</td>
                            <td class="px-4 py-2">
                                @if ($tx->is_income)
                                    <span class="text-green-700 font-bold text-[11px]">↑ {{ __('messages.income') }}</span>
                                @else
                                    <span class="text-red-600 font-bold text-[11px]">↓ {{ __('messages.expense') }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-on-surface-variant">{{ $tx->category->name ?? '—' }}</td>
                            <td class="px-4 py-2 text-on-surface max-w-[200px] truncate">{{ $tx->description }}</td>
                            <td
                                class="px-4 py-2 text-right font-bold {{ $tx->is_income ? 'text-green-700' : 'text-red-600' }} whitespace-nowrap">
                                {{ $tx->is_income ? '+' : '-' }}{{ $tx->amount_formatted }}
                            </td>
                            <td class="px-4 py-2 text-on-surface-variant">{{ $tx->reference_number ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-on-surface-variant">ບໍ່ມີລາຍການ</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@script
<script>
    Alpine.data('financeChart', (allChartData, currencies) => {
        // Kept outside the reactive object so Alpine's Proxy never wraps it.
        // Storing a Chart.js instance as a reactive property causes infinite
        // recursion because Chart.js internally accesses many nested properties.
        let chartInstance = null;

        return {
            active: null,
            availableCodes: [],
            currencies: currencies,

            init() {
                this.availableCodes = Object.keys(allChartData);
                this.active = this.availableCodes.includes('LAK') ? 'LAK' : (this.availableCodes[0] ?? null);
                if (this.active) {
                    this.$nextTick(() => this.buildChart());
                }
            },

            selectCurrency(code) {
                this.active = code;
                this.updateChart();
            },

            buildChart() {
                const ctx = document.getElementById('reportBarChart');
                if (!ctx || !this.active) return;
                if (chartInstance) { chartInstance.destroy(); chartInstance = null; }
                const d = allChartData[this.active];
                const sym = currencies[this.active].symbol;
                chartInstance = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: d.labels,
                        datasets: [
                            { label: 'ລາຍຮັບ (' + sym + ')', data: d.income, backgroundColor: 'rgba(34,197,94,0.7)', borderRadius: 6 },
                            { label: 'ລາຍຈ່າຍ (' + sym + ')', data: d.expense, backgroundColor: 'rgba(239,68,68,0.7)', borderRadius: 6 },
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'top' } },
                        scales: { y: { beginAtZero: true, ticks: { callback: v => v.toLocaleString() } } }
                    }
                });
            },

            updateChart() {
                if (!chartInstance || !this.active) return;
                const d = allChartData[this.active];
                const sym = currencies[this.active].symbol;
                chartInstance.data.labels = d.labels;
                chartInstance.data.datasets[0].data = d.income;
                chartInstance.data.datasets[0].label = 'ລາຍຮັບ (' + sym + ')';
                chartInstance.data.datasets[1].data = d.expense;
                chartInstance.data.datasets[1].label = 'ລາຍຈ່າຍ (' + sym + ')';
                chartInstance.update();
            }
        };
    });
</script>
@endscript