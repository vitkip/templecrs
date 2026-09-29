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
                <p class="text-body-sm text-on-surface-variant mt-0.5">
                    {{ $from }} → {{ $to }}
                    @if ($selectedCategory)
                        • <span class="font-bold {{ $selectedCategory->is_income ? 'text-green-700' : 'text-red-600' }}">
                            ສະເພາະໝວດໝູ່: {{ $selectedCategory->name }}
                        </span>
                    @endif
                </p>
            </div>
        </div>
        <a href="{{ route('finance.report.pdf', array_filter(['period' => $period, 'reportYear' => $reportYear, 'reportMonth' => $reportMonth, 'dateFrom' => $dateFrom, 'dateTo' => $dateTo, 'categoryId' => $categoryId])) }}" target="_blank"
            class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-error text-white hover:bg-error/90 transition-all font-bold text-label-md shadow-md btn-press">
            <span class="material-symbols-outlined text-base">picture_as_pdf</span>
            {{ $selectedCategory ? 'ສົ່ງອອກ PDF ໝວດໝູ່ນີ້' : 'ສົ່ງອອກຂໍ້ມູນ PDF' }}
        </a>
    </div>

    {{-- Filter Selector Bar --}}
    <div class="bg-surface-container rounded-2xl border border-outline-variant p-4 mb-6 shadow-xs">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            {{-- Period Type --}}
            <div>
                <label class="block text-[11px] font-bold text-on-surface-variant mb-1">ຊ່ວງເວລາ (Period)</label>
                <select wire:model.live="period"
                    class="w-full px-3 py-2 bg-surface border border-outline-variant rounded-xl text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20">
                    <option value="month">{{ __('messages.monthly') }}</option>
                    <option value="quarter">{{ __('messages.quarterly') }}</option>
                    <option value="year">{{ __('messages.yearly') }}</option>
                    <option value="custom">{{ __('messages.custom_range') }}</option>
                    <option value="all">ທັງໝົດ (All Time)</option>
                </select>
            </div>

            {{-- Year / Month / Quarter controls based on period --}}
            @if ($period !== 'all' && $period !== 'custom')
                <div>
                    <label class="block text-[11px] font-bold text-on-surface-variant mb-1">ປີ (Year)</label>
                    <select wire:model.live="reportYear"
                        class="w-full px-3 py-2 bg-surface border border-outline-variant rounded-xl text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20">
                        @foreach ($years as $y)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if ($period === 'month')
                <div>
                    <label class="block text-[11px] font-bold text-on-surface-variant mb-1">ເດືອນ (Month)</label>
                    <select wire:model.live="reportMonth"
                        class="w-full px-3 py-2 bg-surface border border-outline-variant rounded-xl text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20">
                        @foreach (range(1, 12) as $m)
                            <option value="{{ $m }}">{{ now()->setMonth($m)->locale('lo')->translatedFormat('F') }}</option>
                        @endforeach
                    </select>
                </div>
            @elseif ($period === 'quarter')
                <div>
                    <label class="block text-[11px] font-bold text-on-surface-variant mb-1">ໄຕຣມາດ (Quarter)</label>
                    <select wire:model.live="reportMonth"
                        class="w-full px-3 py-2 bg-surface border border-outline-variant rounded-xl text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20">
                        <option value="1">{{ __('messages.q1') }}</option>
                        <option value="4">{{ __('messages.q2') }}</option>
                        <option value="7">{{ __('messages.q3') }}</option>
                        <option value="10">{{ __('messages.q4') }}</option>
                    </select>
                </div>
            @endif

            @if ($period === 'custom')
                <div class="sm:col-span-2">
                    <label class="block text-[11px] font-bold text-on-surface-variant mb-1">ຊ່ວງວັນທີ (Custom Range)</label>
                    <div class="flex gap-2 items-center">
                        <input wire:model.live="dateFrom" type="date"
                            class="flex-1 px-3 py-2 bg-surface border border-outline-variant rounded-xl text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/20" />
                        <span class="text-on-surface-variant text-xs">→</span>
                        <input wire:model.live="dateTo" type="date"
                            class="flex-1 px-3 py-2 bg-surface border border-outline-variant rounded-xl text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/20" />
                    </div>
                </div>
            @endif

            {{-- Category Filter --}}
            <div class="{{ ($period === 'all') ? 'sm:col-span-2 lg:col-span-3' : '' }}">
                <label class="block text-[11px] font-bold text-on-surface-variant mb-1">ກັ່ນຕອງຕາມໝວດໝູ່ (Category)</label>
                <select wire:model.live="categoryId"
                    class="w-full px-3 py-2 bg-surface border border-outline-variant rounded-xl text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20">
                    <option value="">-- ທຸກໝວດໝູ່ (All Categories) --</option>
                    @if ($incomeCategories->isNotEmpty())
                        <optgroup label="── ໝວດລາຍຮັບ ──">
                            @foreach ($incomeCategories as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </optgroup>
                    @endif
                    @if ($expenseCategories->isNotEmpty())
                        <optgroup label="── ໝວດລາຍຈ່າຍ ──">
                            @foreach ($expenseCategories as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </optgroup>
                    @endif
                </select>
            </div>
        </div>

        {{-- Active Filter Badge --}}
        @if ($selectedCategory)
            <div class="mt-3 pt-3 border-t border-outline-variant/60 flex items-center justify-between flex-wrap gap-2">
                <div class="flex items-center gap-2">
                    <span class="text-body-sm text-on-surface-variant">ກຳລັງສະແດງລາຍງານສະເພາະ:</span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-label-md font-bold {{ $selectedCategory->is_income ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        <span class="material-symbols-outlined text-sm">{{ $selectedCategory->icon ?? ($selectedCategory->is_income ? 'arrow_downward' : 'arrow_upward') }}</span>
                        {{ $selectedCategory->name }}
                        <span class="text-xs opacity-75">({{ $selectedCategory->is_income ? 'ໝວດລາຍຮັບ' : 'ໝວດລາຍຈ່າຍ' }})</span>
                    </span>
                </div>
                <button wire:click="clearCategory"
                    class="text-body-sm text-primary hover:underline flex items-center gap-1 font-bold">
                    <span class="material-symbols-outlined text-sm">close</span>
                    ສະແດງທຸກໝວດໝູ່ (Clear Filter)
                </button>
            </div>
        @endif
    </div>

    {{-- ── Summary by Currency ────────────────────────────────────────────────── --}}
    <div class="bg-surface-container rounded-2xl border border-outline-variant p-5 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-title-sm font-bold text-on-surface">
                ສະຫຼຸບລວມ — ແຍກຕາມສະກຸນເງີນ
                @if ($selectedCategory)
                    <span class="text-xs font-normal text-on-surface-variant ml-2">(ໝວດ: {{ $selectedCategory->name }})</span>
                @endif
            </h2>
        </div>

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
                <h2 class="text-title-sm font-bold text-on-surface">
                    ກຣາບ ລາຍຮັບ / ລາຍຈ່າຍ
                    @if ($selectedCategory)
                        <span class="text-xs font-normal text-on-surface-variant ml-1">({{ $selectedCategory->name }})</span>
                    @endif
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
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-title-sm font-bold text-{{ $meta['color'] }}-700 flex items-center gap-2">
                                <span
                                    class="material-symbols-outlined text-base">{{ $type === 'income' ? 'trending_up' : 'trending_down' }}</span>
                                ໝວດ{{ $meta['label'] }}
                            </h3>
                            @if (!$selectedCategory)
                                <span class="text-[10px] text-on-surface-variant">ຄລິກທີ່ໝວດໝູ່ເພື່ອກັ່ນຕອງ</span>
                            @endif
                        </div>
                        @foreach ($byCategory[$type] as $code => $rows)
                            @php
                                $cfg = $currencies[$code];
                                $typeTotal = $overallTotals[$type][$code] ?? 0;
                            @endphp
                            <div class="flex items-center justify-between mb-2 mt-4 first:mt-0">
                                <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wide">
                                    {{ $cfg['symbol'] }} {{ $cfg['name_lo'] }} ({{ $code }})
                                </p>
                                @if ($typeTotal > 0)
                                    <span class="text-[10px] text-on-surface-variant">ລວມ: {{ number_format($typeTotal, $cfg['decimals'], '.', ',') }}</span>
                                @endif
                            </div>
                            <div class="space-y-2">
                                @foreach ($rows as $row)
                                    @php
                                        $catPct = $typeTotal > 0 ? round(((float)$row->total / $typeTotal) * 100, 1) : 0;
                                        $isThisActive = $categoryId == $row->category_id;
                                    @endphp
                                    <div wire:click="$set('categoryId', {{ $isThisActive ? 'null' : $row->category_id }})"
                                        class="p-2.5 rounded-xl border transition-all cursor-pointer {{ $isThisActive ? 'bg-primary/5 border-primary shadow-xs' : 'bg-surface border-outline-variant/60 hover:border-primary/50 hover:bg-surface-container-high/40' }}"
                                        title="{{ $isThisActive ? 'ຄລິກເພື່ອຍົກເລີກການເລືອກ' : 'ຄລິກເພື່ອເບິ່ງລາຍງານສະເພາະໝວດນີ້' }}">
                                        <div class="flex items-center justify-between gap-2 text-body-sm">
                                            <div class="flex items-center gap-2 min-w-0">
                                                <span
                                                    class="material-symbols-outlined text-base text-on-surface-variant shrink-0">{{ $row->category->icon ?? 'category' }}</span>
                                                <span class="text-on-surface font-semibold truncate">{{ $row->category->name ?? '—' }}</span>
                                                <span class="text-[11px] text-on-surface-variant shrink-0">({{ $row->count }} ລາຍການ)</span>
                                            </div>
                                            <div class="flex items-center gap-2 shrink-0">
                                                <span class="text-[11px] font-bold px-1.5 py-0.5 rounded bg-surface-container-high text-on-surface-variant">
                                                    {{ $catPct }}%
                                                </span>
                                                <span class="font-bold text-{{ $meta['color'] }}-700">
                                                    {{ number_format((float) $row->total, $cfg['decimals'], '.', ',') }}
                                                </span>
                                            </div>
                                        </div>
                                        {{-- Progress Bar --}}
                                        <div class="w-full bg-outline-variant/30 rounded-full h-1.5 mt-2 overflow-hidden">
                                            <div class="h-1.5 rounded-full {{ $type === 'income' ? 'bg-green-600' : 'bg-red-600' }}"
                                                style="width: {{ min($catPct, 100) }}%"></div>
                                        </div>
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
        <div class="p-4 border-b border-outline-variant flex items-center justify-between">
            <h2 class="text-title-sm font-bold text-on-surface">
                ລາຍການທຸລະກຳ ({{ $transactions->count() }} ລາຍການ)
                @if ($selectedCategory)
                    <span class="text-body-xs font-normal text-on-surface-variant ml-1">
                        — ສະເພາະ: {{ $selectedCategory->name }}
                    </span>
                @endif
            </h2>
            @if ($selectedCategory)
                <button wire:click="clearCategory" class="text-body-xs text-primary font-bold hover:underline">
                    ສະແດງທຸກໝວດໝູ່
                </button>
            @endif
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