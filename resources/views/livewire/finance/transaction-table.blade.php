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
                <span class="material-symbols-outlined text-base">bar_chart</span>
                {{ __('messages.finance_report') }}
            </a>
            <a href="{{ route('finance.transactions.create') }}"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white hover:bg-primary/90 transition-all font-bold text-label-md shadow-md btn-press">
                <span class="material-symbols-outlined text-base">add</span>
                {{ __('messages.add_transaction') }}
            </a>
        </div>
    </div>

    {{-- Summary Strip: per-currency breakdown --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">

        {{-- Income --}}
        <div class="bg-green-50 border border-green-200 rounded-xl px-4 py-3">
            <div class="flex items-center gap-2 mb-2">
                <span class="material-symbols-outlined text-green-600 text-base">trending_up</span>
                <p class="text-[10px] font-bold text-green-700 uppercase tracking-wide">{{ __('messages.total_income') }}</p>
            </div>
            @php $hasIncome = false; @endphp
            @foreach ($currencies as $code => $cfg)
                @if (!empty($byCurrency[$code]['income']) && $byCurrency[$code]['income'] > 0)
                    @php $hasIncome = true; @endphp
                    <div class="flex items-baseline justify-between gap-2 py-0.5">
                        <span class="text-[11px] text-green-700 shrink-0">{{ $cfg['symbol'] }} {{ $cfg['name_lo'] }}</span>
                        <span class="text-label-sm font-bold text-green-700">{{ number_format($byCurrency[$code]['income'], $cfg['decimals'], '.', ',') }}</span>
                    </div>
                @endif
            @endforeach
            @if (!$hasIncome)
                <p class="text-body-sm text-on-surface-variant/60">—</p>
            @endif
        </div>

        {{-- Expense --}}
        <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3">
            <div class="flex items-center gap-2 mb-2">
                <span class="material-symbols-outlined text-red-600 text-base">trending_down</span>
                <p class="text-[10px] font-bold text-red-700 uppercase tracking-wide">{{ __('messages.total_expense') }}</p>
            </div>
            @php $hasExpense = false; @endphp
            @foreach ($currencies as $code => $cfg)
                @if (!empty($byCurrency[$code]['expense']) && $byCurrency[$code]['expense'] > 0)
                    @php $hasExpense = true; @endphp
                    <div class="flex items-baseline justify-between gap-2 py-0.5">
                        <span class="text-[11px] text-red-700 shrink-0">{{ $cfg['symbol'] }} {{ $cfg['name_lo'] }}</span>
                        <span class="text-label-sm font-bold text-red-700">{{ number_format($byCurrency[$code]['expense'], $cfg['decimals'], '.', ',') }}</span>
                    </div>
                @endif
            @endforeach
            @if (!$hasExpense)
                <p class="text-body-sm text-on-surface-variant/60">—</p>
            @endif
        </div>

        {{-- Balance per currency --}}
        <div class="bg-primary/5 border border-primary/20 rounded-xl px-4 py-3">
            <div class="flex items-center gap-2 mb-2">
                <span class="material-symbols-outlined text-primary text-base">account_balance_wallet</span>
                <p class="text-[10px] font-bold text-primary uppercase tracking-wide">{{ __('messages.balance') }}</p>
            </div>
            @php $hasBalance = false; @endphp
            @foreach ($currencies as $code => $cfg)
                @if (!empty($byCurrency[$code]))
                    @php
                        $hasBalance = true;
                        $bal = ($byCurrency[$code]['income'] ?? 0) - ($byCurrency[$code]['expense'] ?? 0);
                    @endphp
                    <div class="flex items-baseline justify-between gap-2 py-0.5">
                        <span class="text-[11px] text-on-surface-variant shrink-0">{{ $cfg['symbol'] }} {{ $cfg['name_lo'] }}</span>
                        <span class="text-label-sm font-bold {{ $bal >= 0 ? 'text-primary' : 'text-error' }}">
                            {{ ($bal >= 0 ? '+' : '') . number_format($bal, $cfg['decimals'], '.', ',') }}
                        </span>
                    </div>
                @endif
            @endforeach
            @if (!$hasBalance)
                <p class="text-body-sm text-on-surface-variant/60">—</p>
            @endif
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-surface-container rounded-2xl border border-outline-variant p-4 mb-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
            <div class="relative lg:col-span-2">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-base">search</span>
                <input wire:model.live.debounce.400ms="search" type="text"
                       placeholder="{{ __('messages.search_transactions') }}"
                       class="w-full pl-9 pr-3 py-2 bg-surface border border-outline-variant rounded-xl text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20" />
            </div>
            <select wire:model.live="typeFilter"
                    class="w-full px-3 py-2 bg-surface border border-outline-variant rounded-xl text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20">
                <option value="">{{ __('messages.all_types') }}</option>
                <option value="income">{{ __('messages.income') }}</option>
                <option value="expense">{{ __('messages.expense') }}</option>
            </select>
            <select wire:model.live="categoryFilter"
                    class="w-full px-3 py-2 bg-surface border border-outline-variant rounded-xl text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20">
                <option value="">{{ __('messages.all_categories') }}</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
            <select wire:model.live="currencyFilter"
                    class="w-full px-3 py-2 bg-surface border border-outline-variant rounded-xl text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20">
                <option value="">ທຸກສະກຸນ</option>
                @foreach ($currencies as $code => $cfg)
                    <option value="{{ $code }}">{{ $cfg['symbol'] }} {{ $cfg['name_lo'] }}</option>
                @endforeach
            </select>
            <div class="flex gap-2 items-center">
                <input wire:model.live="dateFrom" type="date"
                       class="flex-1 px-2 py-2 bg-surface border border-outline-variant rounded-xl text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/20" />
                <span class="text-on-surface-variant text-xs">→</span>
                <input wire:model.live="dateTo" type="date"
                       class="flex-1 px-2 py-2 bg-surface border border-outline-variant rounded-xl text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/20" />
            </div>
        </div>
        @if ($search || $typeFilter || $categoryFilter || $currencyFilter || $dateFrom || $dateTo)
            <div class="mt-3 flex justify-end">
                <button wire:click="clearFilters"
                        class="flex items-center gap-1 text-label-sm text-on-surface-variant hover:text-error transition-colors">
                    <span class="material-symbols-outlined text-base">clear</span>
                    {{ __('messages.clear_filters') }}
                </button>
            </div>
        @endif
    </div>

    {{-- Table --}}
    <div class="bg-surface-container rounded-2xl border border-outline-variant overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-surface-container-high">
                    <tr>
                        <th class="px-4 py-3 text-left">
                            <button wire:click="sortBy('transaction_date')" class="flex items-center gap-1 text-label-sm font-bold text-on-surface-variant hover:text-on-surface">
                                {{ __('messages.transaction_date') }}
                                @if ($sortBy === 'transaction_date')
                                    <span class="material-symbols-outlined text-xs">{{ $sortDir === 'asc' ? 'arrow_upward' : 'arrow_downward' }}</span>
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-3 text-left text-label-sm font-bold text-on-surface-variant">{{ __('messages.type') }}</th>
                        <th class="px-4 py-3 text-left text-label-sm font-bold text-on-surface-variant">{{ __('messages.category') }}</th>
                        <th class="px-4 py-3 text-left text-label-sm font-bold text-on-surface-variant">{{ __('messages.description') }}</th>
                        <th class="px-4 py-3 text-right">
                            <button wire:click="sortBy('amount')" class="flex items-center gap-1 text-label-sm font-bold text-on-surface-variant hover:text-on-surface ml-auto">
                                {{ __('messages.amount') }}
                                @if ($sortBy === 'amount')
                                    <span class="material-symbols-outlined text-xs">{{ $sortDir === 'asc' ? 'arrow_upward' : 'arrow_downward' }}</span>
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-3 text-left text-label-sm font-bold text-on-surface-variant">{{ __('messages.ref_number') }}</th>
                        <th class="px-4 py-3 text-center text-label-sm font-bold text-on-surface-variant">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($transactions as $tx)
                        <tr class="hover:bg-surface-container-high/50 transition-colors">
                            <td class="px-4 py-3 text-body-sm text-on-surface-variant whitespace-nowrap">
                                {{ $tx->transaction_date_formatted }}
                            </td>
                            <td class="px-4 py-3">
                                @if ($tx->is_income)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-[11px] font-bold">
                                        <span class="material-symbols-outlined text-xs">trending_up</span>{{ __('messages.income') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-red-100 text-red-700 text-[11px] font-bold">
                                        <span class="material-symbols-outlined text-xs">trending_down</span>{{ __('messages.expense') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm text-on-surface-variant">{{ $tx->category->icon ?? 'category' }}</span>
                                    <span class="text-body-sm text-on-surface">{{ $tx->category->name ?? '—' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-body-sm text-on-surface max-w-[180px] truncate" title="{{ $tx->description }}">
                                {{ $tx->description }}
                            </td>
                            <td class="px-4 py-3 text-right font-bold whitespace-nowrap {{ $tx->is_income ? 'text-green-700' : 'text-red-600' }}">
                                {{ $tx->is_income ? '+' : '-' }}{{ $tx->amount_formatted }}
                            </td>
                            <td class="px-4 py-3 text-body-sm text-on-surface-variant">{{ $tx->reference_number ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('finance.transactions.edit', $tx->id) }}"
                                       class="p-1.5 text-on-surface-variant hover:text-primary hover:bg-primary/10 rounded-lg transition-colors">
                                        <span class="material-symbols-outlined text-base">edit</span>
                                    </a>
                                    <button wire:click="confirmDelete({{ $tx->id }})"
                                            class="p-1.5 text-on-surface-variant hover:text-error hover:bg-error/10 rounded-lg transition-colors">
                                        <span class="material-symbols-outlined text-base">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <span class="material-symbols-outlined text-4xl text-on-surface-variant/40">receipt_long</span>
                                    <p class="text-body-md text-on-surface-variant">{{ __('messages.no_transactions') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($transactions->hasPages())
            <div class="px-4 py-3 border-t border-outline-variant">{{ $transactions->links() }}</div>
        @endif
    </div>

    {{-- Delete Confirm Modal --}}
    @if ($confirmDeleteId)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="bg-surface rounded-2xl p-6 max-w-sm w-full shadow-xl">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-error/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-error">warning</span>
                    </div>
                    <h3 class="text-title-md font-bold text-on-surface">{{ __('messages.confirm_delete') }}</h3>
                </div>
                <p class="text-body-md text-on-surface-variant mb-6">{{ __('messages.confirm_delete_transaction') }}</p>
                <div class="flex gap-3 justify-end">
                    <button wire:click="cancelDelete"
                            class="px-4 py-2 rounded-xl border border-outline-variant text-on-surface-variant hover:bg-surface-container transition-all text-label-md">
                        {{ __('messages.cancel') }}
                    </button>
                    <button wire:click="delete"
                            class="px-4 py-2 rounded-xl bg-error text-white hover:bg-error/90 transition-all font-bold text-label-md">
                        {{ __('messages.delete') }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
