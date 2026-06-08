<div>

    {{-- Flash Messages --}}
    @if (session('message'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 flex items-center gap-3 animate-fade-in">
            <span class="material-symbols-outlined text-green-600">check_circle</span>
            <span class="text-body-sm">{{ session('message') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 flex items-center gap-3 animate-fade-in">
            <span class="material-symbols-outlined text-red-600">error</span>
            <span class="text-body-sm">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('finance.index') }}"
               class="p-2 rounded-xl hover:bg-surface-container text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined text-xl">arrow_back</span>
            </a>
            <div>
                <h1 class="text-headline-sm font-bold text-on-surface">{{ __('messages.finance_categories') }}</h1>
                <p class="text-body-sm text-on-surface-variant mt-0.5">{{ __('messages.finance_categories_subtitle') }}</p>
            </div>
        </div>
        <button wire:click="create"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white hover:bg-primary/90 transition-all font-bold text-label-md shadow-md btn-press">
            <span class="material-symbols-outlined text-base">add</span>
            {{ __('messages.add_category') }}
        </button>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 gap-3 mb-6">
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-center gap-3">
            <span class="material-symbols-outlined text-green-600">trending_up</span>
            <div>
                <p class="text-[10px] font-bold text-green-700 uppercase tracking-wide">{{ __('messages.income') }}</p>
                <p class="text-title-md font-bold text-green-700">{{ $totalIncome }} {{ __('messages.categories') }}</p>
            </div>
        </div>
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-center gap-3">
            <span class="material-symbols-outlined text-red-600">trending_down</span>
            <div>
                <p class="text-[10px] font-bold text-red-700 uppercase tracking-wide">{{ __('messages.expense') }}</p>
                <p class="text-title-md font-bold text-red-700">{{ $totalExpense }} {{ __('messages.categories') }}</p>
            </div>
        </div>
    </div>

    {{-- Type Filter Tabs --}}
    <div class="flex gap-2 mb-5">
        @foreach ([''=>__('messages.all_types'), 'income'=>__('messages.income'), 'expense'=>__('messages.expense')] as $val => $label)
            <button wire:click="$set('typeFilter', '{{ $val }}')"
                    class="px-4 py-2 rounded-xl text-label-md font-medium transition-all
                           {{ $typeFilter === $val ? 'bg-primary text-white shadow-sm' : 'bg-surface-container border border-outline-variant text-on-surface-variant hover:bg-surface-container-high' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Category Groups --}}
    @if ($categories->isEmpty())
        <div class="bg-surface-container rounded-2xl border border-outline-variant py-16 flex flex-col items-center gap-3 text-center">
            <span class="material-symbols-outlined text-4xl text-on-surface-variant/40">category</span>
            <p class="text-body-md text-on-surface-variant">{{ __('messages.no_categories') }}</p>
            <button wire:click="create"
                    class="mt-2 flex items-center gap-2 px-4 py-2 rounded-xl bg-primary text-white text-label-sm font-bold hover:bg-primary/90 transition-all">
                <span class="material-symbols-outlined text-base">add</span>
                {{ __('messages.add_category') }}
            </button>
        </div>
    @else
        @foreach (['income' => [__('messages.income'),'green','trending_up'], 'expense' => [__('messages.expense'),'red','trending_down']] as $type => [$label, $color, $icon])
            @if (isset($categories[$type]) && $categories[$type]->isNotEmpty())
                <div class="mb-5">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="material-symbols-outlined text-{{ $color }}-600">{{ $icon }}</span>
                        <h2 class="text-title-md font-bold text-on-surface">{{ $label }}</h2>
                        <span class="ml-1 text-label-sm text-on-surface-variant bg-surface-container-high px-2 py-0.5 rounded-full">{{ $categories[$type]->count() }}</span>
                    </div>

                    <div class="bg-surface-container rounded-2xl border border-outline-variant overflow-hidden">
                        <div class="divide-y divide-outline-variant">
                            @foreach ($categories[$type] as $cat)
                                <div class="flex items-center gap-4 px-4 py-3 hover:bg-surface-container-high/40 transition-colors group">

                                    {{-- Icon Badge --}}
                                    <div class="w-10 h-10 rounded-xl bg-{{ $cat->color }}-100 flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-{{ $cat->color }}-600 text-base">{{ $cat->icon }}</span>
                                    </div>

                                    {{-- Name --}}
                                    <div class="flex-1 min-w-0">
                                        <p class="text-body-md font-medium text-on-surface">{{ $cat->name_lo }}</p>
                                        @if ($cat->name_en)
                                            <p class="text-body-sm text-on-surface-variant">{{ $cat->name_en }}</p>
                                        @endif
                                    </div>

                                    {{-- Transaction count --}}
                                    <div class="text-center hidden sm:block">
                                        <p class="text-title-sm font-bold text-on-surface">{{ $cat->transactions_count }}</p>
                                        <p class="text-[10px] text-on-surface-variant">{{ __('messages.transactions') }}</p>
                                    </div>

                                    {{-- Sort order --}}
                                    <div class="text-center hidden md:block">
                                        <p class="text-label-sm text-on-surface-variant">{{ __('messages.order') }}</p>
                                        <p class="text-body-sm font-bold text-on-surface"># {{ $cat->sort_order }}</p>
                                    </div>

                                    {{-- Active toggle --}}
                                    <button wire:click="toggleActive({{ $cat->id }})"
                                            title="{{ $cat->is_active ? __('messages.active') : __('messages.inactive') }}"
                                            class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold transition-all
                                                   {{ $cat->is_active
                                                        ? 'bg-green-100 text-green-700 hover:bg-green-200'
                                                        : 'bg-surface-container-highest text-on-surface-variant hover:bg-surface-container-high' }}">
                                        <span class="material-symbols-outlined text-sm">{{ $cat->is_active ? 'toggle_on' : 'toggle_off' }}</span>
                                        {{ $cat->is_active ? __('messages.active') : __('messages.inactive') }}
                                    </button>

                                    {{-- Actions --}}
                                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button wire:click="edit({{ $cat->id }})"
                                                class="p-1.5 text-on-surface-variant hover:text-primary hover:bg-primary/10 rounded-lg transition-colors"
                                                title="{{ __('messages.edit') }}">
                                            <span class="material-symbols-outlined text-base">edit</span>
                                        </button>
                                        <button wire:click="confirmDelete({{ $cat->id }})"
                                                class="p-1.5 text-on-surface-variant hover:text-error hover:bg-error/10 rounded-lg transition-colors"
                                                title="{{ __('messages.delete') }}">
                                            <span class="material-symbols-outlined text-base">delete</span>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    @endif

    {{-- ══════════════════════════════════
         CREATE / EDIT MODAL
    ══════════════════════════════════ --}}
    @if ($showModal)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
             x-data x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="bg-surface rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 @click.away="$wire.closeModal()">

                {{-- Modal Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-outline-variant sticky top-0 bg-surface z-10">
                    <h2 class="text-title-md font-bold text-on-surface">
                        {{ $editingId ? __('messages.edit_category') : __('messages.add_category') }}
                    </h2>
                    <button wire:click="closeModal"
                            class="p-2 rounded-xl text-on-surface-variant hover:bg-surface-container hover:text-on-surface transition-colors">
                        <span class="material-symbols-outlined text-xl">close</span>
                    </button>
                </div>

                {{-- Modal Body --}}
                <form wire:submit="save" class="px-6 py-5 space-y-4">

                    {{-- Type --}}
                    <div>
                        <label class="block text-label-md font-bold text-on-surface mb-2">{{ __('messages.transaction_type') }} <span class="text-error">*</span></label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" wire:model.live="type" value="income" class="sr-only peer" />
                                <div class="flex items-center gap-2 px-3 py-2.5 rounded-xl border-2 border-outline-variant peer-checked:border-green-500 peer-checked:bg-green-50 transition-all">
                                    <span class="material-symbols-outlined text-green-600 text-base">trending_up</span>
                                    <span class="text-label-md font-bold text-on-surface">{{ __('messages.income') }}</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" wire:model.live="type" value="expense" class="sr-only peer" />
                                <div class="flex items-center gap-2 px-3 py-2.5 rounded-xl border-2 border-outline-variant peer-checked:border-red-500 peer-checked:bg-red-50 transition-all">
                                    <span class="material-symbols-outlined text-red-600 text-base">trending_down</span>
                                    <span class="text-label-md font-bold text-on-surface">{{ __('messages.expense') }}</span>
                                </div>
                            </label>
                        </div>
                        @error('type') <p class="mt-1 text-body-sm text-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Name Lao --}}
                    <div>
                        <label class="block text-label-md font-bold text-on-surface mb-1.5">{{ __('messages.category_name_lo') }} <span class="text-error">*</span></label>
                        <input wire:model="name_lo" type="text"
                               placeholder="{{ __('messages.category_name_lo_placeholder') }}"
                               class="w-full px-3 py-2.5 bg-surface-container border border-outline-variant rounded-xl text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20
                                      {{ $errors->has('name_lo') ? 'border-error ring-2 ring-error/20' : '' }}" />
                        @error('name_lo') <p class="mt-1 text-body-sm text-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Name English --}}
                    <div>
                        <label class="block text-label-md font-bold text-on-surface mb-1.5">{{ __('messages.category_name_en') }}</label>
                        <input wire:model="name_en" type="text"
                               placeholder="{{ __('messages.category_name_en_placeholder') }}"
                               class="w-full px-3 py-2.5 bg-surface-container border border-outline-variant rounded-xl text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20" />
                    </div>

                    {{-- Icon picker --}}
                    <div>
                        <label class="block text-label-md font-bold text-on-surface mb-2">{{ __('messages.icon') }}</label>
                        <div class="grid grid-cols-8 gap-2 p-3 bg-surface-container rounded-xl border border-outline-variant max-h-40 overflow-y-auto">
                            @foreach ($iconOptions as $ic)
                                <button type="button" wire:click="$set('icon', '{{ $ic }}')"
                                        title="{{ $ic }}"
                                        class="w-9 h-9 flex items-center justify-center rounded-lg transition-all
                                               {{ $icon === $ic ? 'bg-primary text-white shadow-sm' : 'hover:bg-surface-container-high text-on-surface-variant' }}">
                                    <span class="material-symbols-outlined text-lg {{ $icon === $ic ? '' : '' }}">{{ $ic }}</span>
                                </button>
                            @endforeach
                        </div>
                        <p class="mt-1 text-[11px] text-on-surface-variant">{{ __('messages.selected') }}: <strong>{{ $icon }}</strong></p>
                    </div>

                    {{-- Colour picker --}}
                    <div>
                        <label class="block text-label-md font-bold text-on-surface mb-2">{{ __('messages.color') }}</label>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $colorMap = [
                                    'green'=>'bg-green-500','emerald'=>'bg-emerald-500','teal'=>'bg-teal-500',
                                    'cyan'=>'bg-cyan-500','blue'=>'bg-blue-500','indigo'=>'bg-indigo-500',
                                    'violet'=>'bg-violet-500','purple'=>'bg-purple-500','pink'=>'bg-pink-500',
                                    'rose'=>'bg-rose-500','red'=>'bg-red-500','orange'=>'bg-orange-500',
                                    'yellow'=>'bg-yellow-400','amber'=>'bg-amber-400','slate'=>'bg-slate-400',
                                ];
                            @endphp
                            @foreach ($colorOptions as $c)
                                <button type="button" wire:click="$set('color', '{{ $c }}')"
                                        title="{{ $c }}"
                                        class="w-8 h-8 rounded-full {{ $colorMap[$c] ?? 'bg-gray-400' }} transition-all
                                               {{ $color === $c ? 'ring-2 ring-offset-2 ring-primary scale-110' : 'hover:scale-110' }}">
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Sort Order + Active (2 col) --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-label-md font-bold text-on-surface mb-1.5">{{ __('messages.sort_order') }}</label>
                            <input wire:model="sort_order" type="number" min="0" max="999"
                                   class="w-full px-3 py-2.5 bg-surface-container border border-outline-variant rounded-xl text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20" />
                            <p class="mt-1 text-[11px] text-on-surface-variant">{{ __('messages.sort_order_hint') }}</p>
                        </div>
                        <div>
                            <label class="block text-label-md font-bold text-on-surface mb-1.5">{{ __('messages.status') }}</label>
                            <label class="flex items-center gap-3 cursor-pointer mt-2">
                                <div class="relative">
                                    <input type="checkbox" wire:model="is_active" class="sr-only peer" />
                                    <div class="w-11 h-6 bg-surface-container-highest rounded-full peer-checked:bg-primary transition-colors"></div>
                                    <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
                                </div>
                                <span class="text-body-md text-on-surface">{{ $is_active ? __('messages.active') : __('messages.inactive') }}</span>
                            </label>
                        </div>
                    </div>

                    {{-- Preview --}}
                    <div class="bg-surface-container rounded-xl border border-outline-variant p-3 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-{{ $color }}-100 flex items-center justify-center">
                            <span class="material-symbols-outlined text-{{ $color }}-600 text-base">{{ $icon }}</span>
                        </div>
                        <div>
                            <p class="text-body-sm font-medium text-on-surface">{{ $name_lo ?: __('messages.category_name_preview') }}</p>
                            @if ($name_en)
                                <p class="text-[11px] text-on-surface-variant">{{ $name_en }}</p>
                            @endif
                        </div>
                        <span class="ml-auto text-[10px] font-bold px-2 py-0.5 rounded-full
                                     {{ $type === 'income' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $type === 'income' ? __('messages.income') : __('messages.expense') }}
                        </span>
                    </div>

                    {{-- Actions --}}
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" wire:click="closeModal"
                                class="px-5 py-2.5 rounded-xl border border-outline-variant text-on-surface-variant hover:bg-surface-container transition-all text-label-md">
                            {{ __('messages.cancel') }}
                        </button>
                        <button type="submit"
                                class="flex items-center gap-2 px-6 py-2.5 rounded-xl bg-primary text-white hover:bg-primary/90 transition-all font-bold text-label-md shadow-md">
                            <span class="material-symbols-outlined text-base" wire:loading.remove wire:target="save">save</span>
                            <span class="material-symbols-outlined text-base animate-spin" wire:loading wire:target="save" style="display:none">progress_activity</span>
                            <span wire:loading.remove wire:target="save">{{ $editingId ? __('messages.update') : __('messages.save') }}</span>
                            <span wire:loading wire:target="save">{{ __('messages.saving') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- ══════════════════════════════════
         DELETE CONFIRM MODAL
    ══════════════════════════════════ --}}
    @if ($confirmDeleteId)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="bg-surface rounded-2xl p-6 max-w-sm w-full shadow-xl">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-error/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-error">warning</span>
                    </div>
                    <h3 class="text-title-md font-bold text-on-surface">{{ __('messages.confirm_delete') }}</h3>
                </div>
                <p class="text-body-md text-on-surface-variant mb-6">{{ __('messages.confirm_delete_category') }}</p>
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
