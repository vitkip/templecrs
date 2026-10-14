<div class="max-w-2xl mx-auto">
    {{-- Header --}}
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('finance.transactions.index') }}"
           class="p-2 rounded-xl hover:bg-surface-container text-on-surface-variant hover:text-on-surface transition-colors">
            <span class="material-symbols-outlined text-xl">arrow_back</span>
        </a>
        <div>
            <h1 class="text-headline-sm font-bold text-on-surface">
                {{ $transactionId ? __('messages.edit_transaction') : __('messages.add_transaction') }}
            </h1>
            <p class="text-body-sm text-on-surface-variant mt-0.5">{{ __('messages.transaction_form_subtitle') }}</p>
        </div>
    </div>

    <form wire:submit="save" class="space-y-5">

        {{-- Type Toggle --}}
        <div class="bg-surface-container rounded-2xl border border-outline-variant p-5">
            <label class="block text-label-md font-bold text-on-surface mb-3">{{ __('messages.transaction_type') }} <span class="text-error">*</span></label>
            <div class="grid grid-cols-2 gap-3">
                <label class="cursor-pointer">
                    <input type="radio" wire:model.live="type" value="income" class="sr-only peer" />
                    <div class="flex items-center gap-3 px-4 py-3 rounded-xl border-2 border-outline-variant
                                peer-checked:border-green-500 peer-checked:bg-green-50 transition-all">
                        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                            <span class="material-symbols-outlined text-green-600 text-base">trending_up</span>
                        </div>
                        <div>
                            <p class="text-label-md font-bold text-on-surface">{{ __('messages.income') }}</p>
                            <p class="text-[10px] text-on-surface-variant">{{ __('messages.income_hint') }}</p>
                        </div>
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" wire:model.live="type" value="expense" class="sr-only peer" />
                    <div class="flex items-center gap-3 px-4 py-3 rounded-xl border-2 border-outline-variant
                                peer-checked:border-red-500 peer-checked:bg-red-50 transition-all">
                        <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                            <span class="material-symbols-outlined text-red-600 text-base">trending_down</span>
                        </div>
                        <div>
                            <p class="text-label-md font-bold text-on-surface">{{ __('messages.expense') }}</p>
                            <p class="text-[10px] text-on-surface-variant">{{ __('messages.expense_hint') }}</p>
                        </div>
                    </div>
                </label>
            </div>
            @error('type') <p class="mt-1 text-body-sm text-error">{{ $message }}</p> @enderror
        </div>

        {{-- Currency Selector --}}
        <div class="bg-surface-container rounded-2xl border border-outline-variant p-5">
            <label class="block text-label-md font-bold text-on-surface mb-3">ສະກຸນເງີນ <span class="text-error">*</span></label>
            <div class="grid grid-cols-4 gap-2">
                @foreach ($currencies as $code => $cfg)
                    <label class="cursor-pointer">
                        <input type="radio" wire:model.live="currency" value="{{ $code }}" class="sr-only peer" />
                        <div class="flex flex-col items-center gap-1 py-3 px-2 rounded-xl border-2 border-outline-variant
                                    peer-checked:border-primary peer-checked:bg-primary/5 transition-all text-center">
                            <span class="text-xl font-bold {{ $currency === $code ? 'text-primary' : 'text-on-surface-variant' }}">{{ $cfg['symbol'] }}</span>
                            <span class="text-[11px] font-bold {{ $currency === $code ? 'text-primary' : 'text-on-surface' }}">{{ $code }}</span>
                            <span class="text-[10px] {{ $currency === $code ? 'text-primary/70' : 'text-on-surface-variant' }}">{{ $cfg['name_lo'] }}</span>
                        </div>
                    </label>
                @endforeach
            </div>
            @error('currency') <p class="mt-1 text-body-sm text-error">{{ $message }}</p> @enderror
        </div>

        {{-- Main Fields --}}
        @php
            $currencyCfg    = $currencies[$currency];
            $isLak          = $currency === 'LAK';
            $amtDecimals    = $isLak ? 0 : 2;
            $currentPresets = $presets[$currency] ?? $presets['LAK'];

            // Build initial display value (formatted, with comma separators)
            $amtDisplay = '';
            if ($amount !== '' && $amount !== null) {
                $rawNum     = (float) str_replace(',', '', $amount);
                $amtDisplay = number_format($rawNum, $amtDecimals, '.', ',');
            }
        @endphp

        <div class="bg-surface-container rounded-2xl border border-outline-variant p-5 space-y-4">

            {{-- Category --}}
            <div>
                <label class="block text-label-md font-bold text-on-surface mb-1.5">{{ __('messages.category') }} <span class="text-error">*</span></label>
                <select wire:model="category_id"
                        class="w-full px-3 py-2.5 bg-surface border border-outline-variant rounded-xl text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20
                               {{ $errors->has('category_id') ? 'border-error ring-2 ring-error/20' : '' }}">
                    <option value="">— {{ __('messages.select_category') }} —</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <p class="mt-1 text-body-sm text-error">{{ $message }}</p> @enderror
            </div>

            {{-- Amount --}}
            <div x-data="{
                    display: @js($amtDisplay),
                    isLak:   @js($isLak),
                    dec:     @js($amtDecimals),

                    fmt(raw) {
                        if (raw === '' || raw === null) return '';
                        let n = parseFloat(raw);
                        if (isNaN(n)) return '';
                        if (this.isLak) {
                            return String(Math.round(n)).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                        }
                        let parts = n.toFixed(2).split('.');
                        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                        return parts.join('.');
                    },
                    isPreset(val) {
                        let raw = parseFloat(String(this.display).replace(/,/g, '')) || 0;
                        return Math.abs(raw - val) < 0.001;
                    },
                    setPreset(val) {
                        this.display = this.fmt(String(val));
                        $refs.amtInput.value = this.display;
                        $wire.set('amount', String(val));
                    },
                    onInput(e) {
                        let raw = e.target.value.replace(/[^0-9.]/g, '');
                        if (!this.isLak) {
                            let parts = raw.split('.');
                            if (parts.length > 2) raw = parts[0] + '.' + parts.slice(1).join('');
                            if (parts.length === 2) raw = parts[0] + '.' + parts[1].slice(0, 2);
                        } else {
                            raw = raw.replace(/\./g, '');
                        }
                        if (raw === '' || raw === '.') {
                            this.display = '';
                            e.target.value = '';
                            $wire.set('amount', '');
                            return;
                        }
                        this.display = this.fmt(raw);
                        e.target.value = this.display;
                        $wire.set('amount', raw);
                    }
                }"
                x-init="$watch('$wire.currency', () => { display = ''; $wire.set('amount', ''); $nextTick(() => { if ($refs.amtInput) $refs.amtInput.value = ''; }); })">

                <label class="block text-label-md font-bold text-on-surface mb-1.5">
                    {{ __('messages.amount') }}
                    <span class="font-normal text-on-surface-variant">({{ $currencyCfg['symbol'] }} {{ $currencyCfg['name_lo'] }})</span>
                    <span class="text-error">*</span>
                </label>

                {{-- Quick presets --}}
                <div class="flex flex-wrap gap-1.5 mb-2">
                    @foreach ($currentPresets as $preset)
                        <button type="button"
                                @click="setPreset({{ $preset }})"
                                :class="isPreset({{ $preset }})
                                    ? 'border-primary bg-primary/10 text-primary ring-1 ring-primary/30'
                                    : 'border-outline-variant bg-surface text-on-surface-variant hover:border-primary/40 hover:bg-primary/5 hover:text-primary'"
                                class="px-2.5 py-1 rounded-lg text-[11px] font-bold border transition-all leading-none">
                            {{ number_format($preset, $amtDecimals, '.', ',') }}
                        </button>
                    @endforeach
                </div>

                {{-- Amount input --}}
                <div class="relative">
                    <input type="text"
                           x-ref="amtInput"
                           :inputmode="isLak ? 'numeric' : 'decimal'"
                           value="{{ $amtDisplay }}"
                           @input="onInput($event)"
                           @focus="$el.select()"
                           placeholder="{{ $isLak ? '0' : '0.00' }}"
                           class="w-full pl-3 pr-14 py-2.5 bg-surface border border-outline-variant rounded-xl text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20
                                  {{ $errors->has('amount') ? 'border-error ring-2 ring-error/20' : '' }}" />
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-label-sm text-on-surface-variant font-bold select-none">
                        {{ $currencyCfg['symbol'] }}
                    </span>
                </div>
                @error('amount') <p class="mt-1 text-body-sm text-error">{{ $message }}</p> @enderror
            </div>

            {{-- Date --}}
            <div>
                <label class="block text-label-md font-bold text-on-surface mb-1.5">{{ __('messages.transaction_date') }} <span class="text-error">*</span></label>
                <input wire:model="transaction_date" type="date"
                       max="{{ now()->format('Y-m-d') }}"
                       class="w-full px-3 py-2.5 bg-surface border border-outline-variant rounded-xl text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20
                              {{ $errors->has('transaction_date') ? 'border-error ring-2 ring-error/20' : '' }}" />
                @error('transaction_date') <p class="mt-1 text-body-sm text-error">{{ $message }}</p> @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-label-md font-bold text-on-surface mb-1.5">{{ __('messages.description') }} <span class="text-error">*</span></label>
                <input wire:model="description" type="text"
                       placeholder="{{ __('messages.description_placeholder') }}"
                       class="w-full px-3 py-2.5 bg-surface border border-outline-variant rounded-xl text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20
                              {{ $errors->has('description') ? 'border-error ring-2 ring-error/20' : '' }}" />
                @error('description') <p class="mt-1 text-body-sm text-error">{{ $message }}</p> @enderror
            </div>

            {{-- Reference Number --}}
            <div>
                <label class="block text-label-md font-bold text-on-surface mb-1.5">{{ __('messages.ref_number') }}</label>
                <input wire:model="reference_number" type="text"
                       placeholder="{{ __('messages.ref_number_placeholder') }}"
                       class="w-full px-3 py-2.5 bg-surface border border-outline-variant rounded-xl text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20" />
            </div>

            {{-- Note --}}
            <div>
                <label class="block text-label-md font-bold text-on-surface mb-1.5">{{ __('messages.note') }}</label>
                <textarea wire:model="note" rows="3"
                          placeholder="{{ __('messages.note_placeholder') }}"
                          class="w-full px-3 py-2.5 bg-surface border border-outline-variant rounded-xl text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20 resize-none"></textarea>
            </div>
        </div>

        {{-- Receipt Upload --}}
        <div class="bg-surface-container rounded-2xl border border-outline-variant p-5"
             x-data="{ openLightbox: false, previewLightbox: false }">
            <div class="flex items-center justify-between mb-3">
                <label class="block text-label-md font-bold text-on-surface">{{ __('messages.receipt') }}</label>
                @if ($existingReceipt)
                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-primary bg-primary/10 px-2.5 py-0.5 rounded-full">
                        <span class="material-symbols-outlined text-xs">check_circle</span>
                        {{ __('messages.current_receipt') }}
                    </span>
                @endif
            </div>

            {{-- Existing Saved Receipt --}}
            @if ($existingReceipt)
                <div class="p-3.5 sm:p-4 bg-surface rounded-2xl border border-outline-variant mb-4 shadow-xs">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3.5">
                        {{-- Image / File Thumbnail --}}
                        @if ($this->receiptIsImage)
                            <div class="relative group shrink-0 w-24 h-24 sm:w-28 sm:h-28 rounded-xl overflow-hidden bg-surface-container border border-outline-variant cursor-pointer shadow-xs"
                                 @click="openLightbox = true"
                                 title="{{ __('messages.view_image') }}">
                                <img src="{{ $this->existingReceiptUrl }}"
                                     alt="Receipt"
                                     class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                                <div class="absolute inset-0 bg-black/45 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white gap-1 backdrop-blur-[1px]">
                                    <span class="material-symbols-outlined text-2xl">zoom_in</span>
                                    <span class="text-[11px] font-medium">{{ __('messages.view_image') }}</span>
                                </div>
                            </div>
                        @else
                            <div class="shrink-0 w-20 h-20 sm:w-24 sm:h-24 rounded-xl bg-red-50 border border-red-200 flex flex-col items-center justify-center text-red-600">
                                <span class="material-symbols-outlined text-3xl">picture_as_pdf</span>
                                <span class="text-[10px] font-bold mt-1 uppercase">{{ pathinfo($existingReceipt, PATHINFO_EXTENSION) }}</span>
                            </div>
                        @endif

                        {{-- Receipt Info & Action Buttons --}}
                        <div class="flex-1 min-w-0 space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-bold {{ $this->receiptIsImage ? 'bg-primary/10 text-primary' : 'bg-red-100 text-red-700' }}">
                                    <span class="material-symbols-outlined text-xs">{{ $this->receiptIsImage ? 'image' : 'description' }}</span>
                                    {{ strtoupper(pathinfo($existingReceipt, PATHINFO_EXTENSION)) }}
                                </span>
                                <span class="text-xs text-on-surface-variant font-medium truncate">{{ basename($existingReceipt) }}</span>
                            </div>

                            <p class="text-[11px] text-on-surface-variant/80">
                                {{ $this->receiptIsImage ? 'ຮູບພາບໃບຮັບເງິນ / ໃບບິນທີ່ແນບໄວ້' : 'ເອກະສານໃບຮັບເງິນ / PDF' }}
                            </p>

                            <div class="flex flex-wrap items-center gap-2 pt-1">
                                @if ($this->receiptIsImage)
                                    <button type="button"
                                            @click="openLightbox = true"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-surface-container hover:bg-surface-container-high text-on-surface text-label-sm font-semibold transition-colors border border-outline-variant cursor-pointer">
                                        <span class="material-symbols-outlined text-base text-primary">visibility</span>
                                        <span>{{ __('messages.view_image') }}</span>
                                    </button>
                                @endif

                                @if ($transactionId)
                                    <a href="{{ route('finance.transactions.receipt.download', $transactionId) }}"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-primary text-white hover:bg-primary/90 text-label-sm font-bold transition-all shadow-xs cursor-pointer">
                                        <span class="material-symbols-outlined text-base">download</span>
                                        <span>{{ __('messages.download') }}</span>
                                    </a>
                                @else
                                    <button type="button"
                                            wire:click="downloadReceipt"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-primary text-white hover:bg-primary/90 text-label-sm font-bold transition-all shadow-xs cursor-pointer">
                                        <span class="material-symbols-outlined text-base">download</span>
                                        <span>{{ __('messages.download') }}</span>
                                    </button>
                                @endif

                                <a href="{{ $this->existingReceiptUrl }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-on-surface-variant hover:text-on-surface hover:bg-surface-container text-label-sm font-medium transition-colors"
                                   title="{{ __('messages.open_in_new_tab') }}">
                                    <span class="material-symbols-outlined text-base">open_in_new</span>
                                    <span class="hidden sm:inline">{{ __('messages.open_in_new_tab') }}</span>
                                </a>

                                <button type="button"
                                        wire:click="removeReceipt"
                                        wire:confirm="ທ່ານແນ່ໃຈບໍ່ວ່າຕ້ອງການລຶບໃບຮັບເງິນນີ້?"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-error hover:bg-error/10 text-label-sm font-medium transition-colors ml-auto">
                                    <span class="material-symbols-outlined text-base">delete</span>
                                    <span>{{ __('messages.delete') }}</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Lightbox Modal for Existing Image --}}
                    @if ($this->receiptIsImage)
                        <template x-teleport="body">
                            <div x-show="openLightbox"
                                 x-cloak
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 @keydown.escape.window="openLightbox = false"
                                 class="fixed inset-0 z-[99999] flex flex-col bg-black/90 backdrop-blur-md select-none"
                                 role="dialog"
                                 aria-modal="true">

                                {{-- Lightbox Top Bar --}}
                                <div class="flex items-center justify-between px-4 sm:px-6 py-3 bg-black/60 shrink-0 border-b border-white/10">
                                    <div class="flex items-center gap-3 text-white min-w-0">
                                        <span class="material-symbols-outlined text-amber-400">receipt_long</span>
                                        <div class="min-w-0">
                                            <p class="text-sm font-bold truncate">{{ __('messages.receipt') }}</p>
                                            <p class="text-xs text-white/60 truncate">{{ basename($existingReceipt) }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 shrink-0">
                                        @if ($transactionId)
                                            <a href="{{ route('finance.transactions.receipt.download', $transactionId) }}"
                                               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-primary hover:bg-primary/90 text-white text-xs font-bold transition-all shadow-sm">
                                                <span class="material-symbols-outlined text-base">download</span>
                                                <span>{{ __('messages.download') }}</span>
                                            </a>
                                        @else
                                            <button type="button"
                                                    wire:click="downloadReceipt"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-primary hover:bg-primary/90 text-white text-xs font-bold transition-all shadow-sm">
                                                <span class="material-symbols-outlined text-base">download</span>
                                                <span>{{ __('messages.download') }}</span>
                                            </button>
                                        @endif

                                        <a href="{{ $this->existingReceiptUrl }}"
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           class="w-9 h-9 rounded-lg bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors"
                                           title="{{ __('messages.open_in_new_tab') }}">
                                            <span class="material-symbols-outlined text-lg">open_in_new</span>
                                        </a>

                                        <button type="button"
                                                @click="openLightbox = false"
                                                class="w-9 h-9 rounded-lg bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors"
                                                title="Esc">
                                            <span class="material-symbols-outlined text-xl">close</span>
                                        </button>
                                    </div>
                                </div>

                                {{-- Main Lightbox Preview Area --}}
                                <div class="flex-1 flex items-center justify-center p-4 sm:p-6 overflow-auto"
                                     @click.self="openLightbox = false">
                                    <img src="{{ $this->existingReceiptUrl }}"
                                         alt="Receipt Image"
                                         class="max-w-full max-h-[85vh] object-contain rounded-xl shadow-2xl border border-white/10 transition-all" />
                                </div>
                            </div>
                        </template>
                    @endif
                </div>
            @endif

            {{-- Newly Uploaded File (Pending Save) --}}
            @if ($receipt)
                @php
                    $isNewImage = false;
                    $tempUrl = null;
                    try {
                        if (is_object($receipt) && method_exists($receipt, 'temporaryUrl') && str_starts_with($receipt->getMimeType(), 'image/')) {
                            $tempUrl = $receipt->temporaryUrl();
                            $isNewImage = true;
                        }
                    } catch (\Throwable $e) {
                        $isNewImage = false;
                    }
                @endphp
                <div class="mb-4 p-4 bg-primary/5 rounded-2xl border-2 border-primary/30">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-label-sm font-bold text-primary flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-base">add_photo_alternate</span>
                            {{ __('messages.new_receipt_selected') }}
                        </span>
                        <button type="button"
                                wire:click="cancelNewReceipt"
                                class="text-error hover:text-error/80 text-xs font-semibold flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">cancel</span>
                            {{ __('messages.cancel') }}
                        </button>
                    </div>

                    <div class="flex items-center gap-3">
                        @if ($isNewImage && $tempUrl)
                            <div class="relative group shrink-0 w-20 h-20 rounded-xl overflow-hidden bg-surface border border-outline-variant cursor-pointer shadow-xs"
                                 @click="previewLightbox = true"
                                 title="{{ __('messages.view_image') }}">
                                <img src="{{ $tempUrl }}" alt="New Receipt Preview" class="w-full h-full object-cover" />
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                    <span class="material-symbols-outlined text-xl">zoom_in</span>
                                </div>
                            </div>
                        @else
                            <div class="shrink-0 w-16 h-16 rounded-xl bg-surface-container flex items-center justify-center text-on-surface-variant">
                                <span class="material-symbols-outlined text-2xl">description</span>
                            </div>
                        @endif

                        <div class="flex-1 min-w-0">
                            <p class="text-body-sm font-bold text-on-surface truncate">{{ $receipt->getClientOriginalName() }}</p>
                            <p class="text-[11px] text-on-surface-variant">
                                {{ round($receipt->getSize() / 1024, 1) }} KB · <span class="text-primary font-semibold">ກຽມບັນທຶກ</span>
                            </p>
                        </div>
                    </div>

                    {{-- Lightbox for newly selected image --}}
                    @if ($isNewImage && $tempUrl)
                        <template x-teleport="body">
                            <div x-show="previewLightbox"
                                 x-cloak
                                 @keydown.escape.window="previewLightbox = false"
                                 class="fixed inset-0 z-[99999] flex flex-col bg-black/90 backdrop-blur-md select-none"
                                 role="dialog"
                                 aria-modal="true">
                                <div class="flex items-center justify-between px-6 py-3 bg-black/60 shrink-0 border-b border-white/10">
                                    <p class="text-sm font-bold text-white truncate">{{ $receipt->getClientOriginalName() }}</p>
                                    <button type="button" @click="previewLightbox = false" class="text-white hover:text-white/80 p-1">
                                        <span class="material-symbols-outlined text-xl">close</span>
                                    </button>
                                </div>
                                <div class="flex-1 flex items-center justify-center p-4" @click.self="previewLightbox = false">
                                    <img src="{{ $tempUrl }}" alt="New Receipt" class="max-w-full max-h-[85vh] object-contain rounded-xl shadow-2xl" />
                                </div>
                            </div>
                        </template>
                    @endif
                </div>
            @endif

            {{-- Upload Area / Dropzone --}}
            <div class="relative border-2 border-dashed border-outline-variant rounded-xl p-6 text-center hover:border-primary/50 transition-colors bg-surface/50">
                <div wire:loading.remove wire:target="receipt">
                    <span class="material-symbols-outlined text-3xl text-on-surface-variant mb-2">upload_file</span>
                    <p class="text-body-sm text-on-surface-variant mb-2">
                        {{ $existingReceipt ? __('messages.replace_receipt_hint') : __('messages.upload_receipt_hint') }}
                    </p>
                    <input type="file" wire:model="receipt" accept=".jpg,.jpeg,.png,.pdf"
                           class="block mx-auto text-body-sm text-on-surface-variant file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-label-sm file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-all cursor-pointer" />
                    <p class="text-[10px] text-on-surface-variant mt-2">JPG, PNG, PDF — {{ __('messages.max_file_size', ['size' => '5MB']) }}</p>
                </div>

                {{-- Uploading State --}}
                <div wire:loading wire:target="receipt" class="py-4">
                    <span class="material-symbols-outlined text-3xl text-primary animate-spin">progress_activity</span>
                    <p class="text-body-sm text-primary font-bold mt-2">ກຳລັງໂຫລດໄຟລ໌...</p>
                </div>
            </div>
            @error('receipt') <p class="mt-1.5 text-body-sm text-error">{{ $message }}</p> @enderror
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between gap-3 pt-2">
            <a href="{{ route('finance.transactions.index') }}"
               class="px-5 py-2.5 rounded-xl border border-outline-variant text-on-surface-variant hover:bg-surface-container transition-all text-label-md">
                {{ __('messages.cancel') }}
            </a>
            <button type="submit"
                    class="flex items-center gap-2 px-6 py-2.5 rounded-xl bg-primary text-white hover:bg-primary/90 transition-all font-bold text-label-md shadow-md btn-press">
                <span class="material-symbols-outlined text-base" wire:loading.remove>save</span>
                <span class="material-symbols-outlined text-base animate-spin" wire:loading style="display:none">progress_activity</span>
                <span wire:loading.remove>{{ $transactionId ? __('messages.update') : __('messages.save') }}</span>
                <span wire:loading>{{ __('messages.saving') }}</span>
            </button>
        </div>
    </form>
</div>
