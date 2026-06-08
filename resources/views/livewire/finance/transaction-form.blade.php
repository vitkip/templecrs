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
                        <div class="w-8 h-8 rounded-full bg-green-100 peer-checked:bg-green-200 flex items-center justify-center">
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

        {{-- Main Fields --}}
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

            {{-- Amount + Date (2 cols) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-label-md font-bold text-on-surface mb-1.5">{{ __('messages.amount') }} (ກີບ) <span class="text-error">*</span></label>
                    <div class="relative">
                        <input wire:model="amount" type="number" min="1" step="1"
                               placeholder="0"
                               class="w-full pl-3 pr-16 py-2.5 bg-surface border border-outline-variant rounded-xl text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20
                                      {{ $errors->has('amount') ? 'border-error ring-2 ring-error/20' : '' }}" />
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-label-sm text-on-surface-variant font-bold">ກີບ</span>
                    </div>
                    @error('amount') <p class="mt-1 text-body-sm text-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-label-md font-bold text-on-surface mb-1.5">{{ __('messages.transaction_date') }} <span class="text-error">*</span></label>
                    <input wire:model="transaction_date" type="date"
                           max="{{ now()->format('Y-m-d') }}"
                           class="w-full px-3 py-2.5 bg-surface border border-outline-variant rounded-xl text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20
                                  {{ $errors->has('transaction_date') ? 'border-error ring-2 ring-error/20' : '' }}" />
                    @error('transaction_date') <p class="mt-1 text-body-sm text-error">{{ $message }}</p> @enderror
                </div>
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
        <div class="bg-surface-container rounded-2xl border border-outline-variant p-5">
            <label class="block text-label-md font-bold text-on-surface mb-3">{{ __('messages.receipt') }}</label>

            @if ($existingReceipt)
                <div class="flex items-center gap-3 p-3 bg-surface rounded-xl border border-outline-variant mb-3">
                    <span class="material-symbols-outlined text-primary">receipt</span>
                    <span class="text-body-sm text-on-surface flex-1">{{ basename($existingReceipt) }}</span>
                    <button type="button" wire:click="removeReceipt"
                            class="text-error hover:text-error/80 transition-colors">
                        <span class="material-symbols-outlined text-base">delete</span>
                    </button>
                </div>
            @endif

            <div class="border-2 border-dashed border-outline-variant rounded-xl p-6 text-center hover:border-primary/40 transition-colors">
                <span class="material-symbols-outlined text-2xl text-on-surface-variant mb-2">upload_file</span>
                <p class="text-body-sm text-on-surface-variant mb-2">{{ __('messages.upload_receipt_hint') }}</p>
                <input type="file" wire:model="receipt" accept=".jpg,.jpeg,.png,.pdf"
                       class="block mx-auto text-body-sm text-on-surface-variant file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-label-sm file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-all" />
                <p class="text-[10px] text-on-surface-variant mt-2">JPG, PNG, PDF — {{ __('messages.max_file_size', ['size' => '5MB']) }}</p>
            </div>
            @error('receipt') <p class="mt-1 text-body-sm text-error">{{ $message }}</p> @enderror
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
