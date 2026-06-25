<div class="relative w-full max-w-md"
     x-data="{ focused: false }"
     @click.outside="focused = false; $wire.open = false">

    {{-- Search Input --}}
    <div class="relative">
        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-base pointer-events-none">search</span>
        <input
            wire:model.live.debounce.300ms="query"
            type="text"
            autocomplete="off"
            class="w-full pl-10 pr-8 py-2 bg-surface-container-low border border-outline-variant rounded-full text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all"
            placeholder="{{ __('messages.search_placeholder') }}"
            @focus="focused = true"
            @keydown.escape="focused = false; $wire.clear()" />
        @if ($query !== '')
            <button wire:click="clear"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined text-base">close</span>
            </button>
        @endif
    </div>

    {{-- Dropdown Results --}}
    @if ($open && count($results))
        <div class="absolute top-full left-0 right-0 mt-2 bg-surface-bright border border-outline-variant rounded-2xl shadow-xl z-50 overflow-hidden max-h-[420px] overflow-y-auto">

            {{-- Group by type --}}
            @php
                $typeLabels = [
                    'personnel' => ['label' => __('messages.personnel'), 'icon' => 'group',                    'color' => 'text-primary'],
                    'news'      => ['label' => __('messages.news'),      'icon' => 'newspaper',                'color' => 'text-secondary'],
                    'document'  => ['label' => __('messages.documents'), 'icon' => 'description',             'color' => 'text-tertiary'],
                    'finance'   => ['label' => __('messages.finance'),   'icon' => 'account_balance_wallet',  'color' => 'text-success'],
                ];
                $grouped = collect($results)->groupBy('type');
            @endphp

            @foreach ($grouped as $type => $items)
                @php $meta = $typeLabels[$type] ?? ['label' => $type, 'icon' => 'search', 'color' => 'text-primary']; @endphp

                {{-- Section header --}}
                <div class="flex items-center gap-2 px-4 pt-3 pb-1.5">
                    <span class="material-symbols-outlined text-sm {{ $meta['color'] }}">{{ $meta['icon'] }}</span>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">{{ $meta['label'] }}</span>
                </div>

                @foreach ($items as $item)
                    <a href="{{ $item['url'] }}"
                       wire:navigate
                       @click="focused = false; $wire.clear()"
                       class="flex items-center gap-3 px-4 py-2.5 hover:bg-surface-container transition-colors">
                        <div class="w-7 h-7 rounded-lg {{ $item['bg'] }} flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-sm {{ $item['color'] }}">{{ $item['icon'] }}</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-label-sm font-semibold text-on-surface truncate">{{ $item['label'] }}</p>
                            @if ($item['sub'])
                                <p class="text-[11px] text-on-surface-variant truncate">{{ $item['sub'] }}</p>
                            @endif
                        </div>
                        <span class="material-symbols-outlined text-sm text-on-surface-variant shrink-0">chevron_right</span>
                    </a>
                @endforeach

                @if (!$loop->last)
                    <div class="border-t border-outline-variant mx-4"></div>
                @endif
            @endforeach

            <div class="border-t border-outline-variant px-4 py-2.5">
                <p class="text-[11px] text-on-surface-variant text-center">
                    {{ count($results) }} {{ app()->getLocale() === 'lo' ? 'ຜົນການຄົ້ນຫາ' : 'results' }} — <kbd class="bg-surface-container px-1 rounded text-[10px]">Esc</kbd> {{ app()->getLocale() === 'lo' ? 'ປິດ' : 'to close' }}
                </p>
            </div>
        </div>
    @endif

    {{-- No results state --}}
    @if ($open && strlen($query) >= 2 && count($results) === 0)
        <div class="absolute top-full left-0 right-0 mt-2 bg-surface-bright border border-outline-variant rounded-2xl shadow-xl z-50 overflow-hidden">
            <div class="px-4 py-6 text-center">
                <span class="material-symbols-outlined text-on-surface-variant text-3xl">search_off</span>
                <p class="text-label-sm text-on-surface-variant mt-2">{{ __('messages.no_results_found') }}</p>
                <p class="text-[11px] text-on-surface-variant/70 mt-0.5">"{{ $query }}"</p>
            </div>
        </div>
    @endif
</div>
