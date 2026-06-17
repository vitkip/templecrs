<div>

    {{-- Flash Messages --}}
    @if (session('message'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 flex items-center gap-3">
            <span class="material-symbols-outlined text-green-600">check_circle</span>
            <span class="text-body-sm">{{ session('message') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 flex items-center gap-3">
            <span class="material-symbols-outlined text-red-600">error</span>
            <span class="text-body-sm">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('news.index') }}"
               class="p-2 rounded-xl hover:bg-surface-container text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined text-xl">arrow_back</span>
            </a>
            <div>
                <h1 class="text-headline-sm font-bold text-on-surface">ໝວດຂ່າວ / News Categories</h1>
                <p class="text-body-sm text-on-surface-variant mt-0.5">ຈັດການໝວດໝູ່ຂ່າວທັງໝົດ / Manage all news categories</p>
            </div>
        </div>
        <button wire:click="create"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white hover:bg-primary/90 transition-all font-bold text-label-md shadow-md btn-press">
            <span class="material-symbols-outlined text-base">add</span>
            ເພີ່ມໝວດໃໝ່ / Add Category
        </button>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-6">
        <div class="bg-surface-container rounded-xl border border-outline-variant p-4 flex items-center gap-3">
            <span class="material-symbols-outlined text-primary">newspaper</span>
            <div>
                <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wide">ໝວດທັງໝົດ</p>
                <p class="text-title-md font-bold text-on-surface">{{ $categories->count() }}</p>
            </div>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-center gap-3">
            <span class="material-symbols-outlined text-green-600">check_circle</span>
            <div>
                <p class="text-[10px] font-bold text-green-700 uppercase tracking-wide">ໃຊ້ງານ / Active</p>
                <p class="text-title-md font-bold text-green-700">{{ $categories->where('is_active', true)->count() }}</p>
            </div>
        </div>
        <div class="bg-surface-container rounded-xl border border-outline-variant p-4 flex items-center gap-3 col-span-2 sm:col-span-1">
            <span class="material-symbols-outlined text-on-surface-variant">article</span>
            <div>
                <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wide">ຂ່າວທັງໝົດ</p>
                <p class="text-title-md font-bold text-on-surface">{{ $categories->sum('news_count') }}</p>
            </div>
        </div>
    </div>

    {{-- Category List --}}
    @if ($categories->isEmpty())
        <div class="bg-surface-container rounded-2xl border border-outline-variant py-16 flex flex-col items-center gap-3 text-center">
            <span class="material-symbols-outlined text-4xl text-on-surface-variant/40">newspaper</span>
            <p class="text-body-md text-on-surface-variant">ຍັງບໍ່ມີໝວດຂ່າວ / No categories yet</p>
            <button wire:click="create"
                    class="mt-2 flex items-center gap-2 px-4 py-2 rounded-xl bg-primary text-white text-label-sm font-bold hover:bg-primary/90 transition-all">
                <span class="material-symbols-outlined text-base">add</span>
                ເພີ່ມໝວດທຳອິດ
            </button>
        </div>
    @else
        <div class="bg-surface-container rounded-2xl border border-outline-variant overflow-hidden">
            <div class="divide-y divide-outline-variant">
                @foreach ($categories as $cat)
                    @php $colorClass = \App\Models\NewsCategory::$colorMap[$cat->color] ?? 'text-blue-700 bg-blue-50 border-blue-200'; @endphp
                    <div class="flex items-center gap-4 px-4 py-3 hover:bg-surface-container-high/40 transition-colors group">

                        {{-- Icon Badge --}}
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 border {{ $colorClass }}">
                            <span class="material-symbols-outlined text-base">{{ $cat->icon }}</span>
                        </div>

                        {{-- Name + Slug --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <p class="text-body-md font-medium text-on-surface">{{ $cat->name_lo }}</p>
                                <span class="text-[10px] font-mono bg-surface-container-highest text-on-surface-variant px-1.5 py-0.5 rounded">{{ $cat->slug }}</span>
                            </div>
                            @if ($cat->name_en)
                                <p class="text-body-sm text-on-surface-variant">{{ $cat->name_en }}</p>
                            @endif
                        </div>

                        {{-- News count --}}
                        <div class="text-center hidden sm:block shrink-0">
                            <p class="text-title-sm font-bold text-on-surface">{{ $cat->news_count }}</p>
                            <p class="text-[10px] text-on-surface-variant">ຂ່າວ</p>
                        </div>

                        {{-- Sort order --}}
                        <div class="text-center hidden md:block shrink-0">
                            <p class="text-label-sm text-on-surface-variant">ລຳດັບ</p>
                            <p class="text-body-sm font-bold text-on-surface"># {{ $cat->sort_order }}</p>
                        </div>

                        {{-- Active toggle --}}
                        <button wire:click="toggleActive({{ $cat->id }})"
                                class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold transition-all shrink-0
                                       {{ $cat->is_active
                                            ? 'bg-green-100 text-green-700 hover:bg-green-200'
                                            : 'bg-surface-container-highest text-on-surface-variant hover:bg-surface-container-high' }}">
                            <span class="material-symbols-outlined text-sm">{{ $cat->is_active ? 'toggle_on' : 'toggle_off' }}</span>
                            {{ $cat->is_active ? 'ໃຊ້ງານ' : 'ປິດ' }}
                        </button>

                        {{-- Actions --}}
                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity shrink-0">
                            <button wire:click="edit({{ $cat->id }})"
                                    class="p-1.5 text-on-surface-variant hover:text-primary hover:bg-primary/10 rounded-lg transition-colors"
                                    title="ແກ້ໄຂ">
                                <span class="material-symbols-outlined text-base">edit</span>
                            </button>
                            <button wire:click="confirmDelete({{ $cat->id }})"
                                    class="p-1.5 text-on-surface-variant hover:text-error hover:bg-error/10 rounded-lg transition-colors"
                                    title="ລຶບ">
                                <span class="material-symbols-outlined text-base">delete</span>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
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
                        {{ $editingId ? 'ແກ້ໄຂໝວດຂ່າວ / Edit Category' : 'ເພີ່ມໝວດໝູ່ໃໝ່ / New Category' }}
                    </h2>
                    <button wire:click="closeModal"
                            class="p-2 rounded-xl text-on-surface-variant hover:bg-surface-container hover:text-on-surface transition-colors">
                        <span class="material-symbols-outlined text-xl">close</span>
                    </button>
                </div>

                {{-- Modal Body --}}
                <form wire:submit="save" class="px-6 py-5 space-y-4">

                    {{-- Name Lao --}}
                    <div>
                        <label class="block text-label-md font-bold text-on-surface mb-1.5">
                            ຊື່ໝວດ (ພາສາລາວ) <span class="text-error">*</span>
                        </label>
                        <input wire:model.live="name_lo" type="text"
                               placeholder="ຕ.ວ. ຂ່າວການເມືອງ, ຂ່າວທ່ອງທ່ຽວ..."
                               class="w-full px-3 py-2.5 bg-surface-container border border-outline-variant rounded-xl text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20
                                      {{ $errors->has('name_lo') ? 'border-error ring-2 ring-error/20' : '' }}" />
                        @error('name_lo') <p class="mt-1 text-body-sm text-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Name English --}}
                    <div>
                        <label class="block text-label-md font-bold text-on-surface mb-1.5">Category Name (English)</label>
                        <input wire:model="name_en" type="text"
                               placeholder="e.g. Politics, Tourism..."
                               class="w-full px-3 py-2.5 bg-surface-container border border-outline-variant rounded-xl text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20" />
                    </div>

                    {{-- Slug --}}
                    <div>
                        <label class="block text-label-md font-bold text-on-surface mb-1.5">
                            Slug <span class="text-error">*</span>
                        </label>
                        <input wire:model="slug" type="text"
                               placeholder="politics, tourism, announcement..."
                               {{ $editingId ? 'readonly' : '' }}
                               class="w-full px-3 py-2.5 bg-surface-container border border-outline-variant rounded-xl text-body-md font-mono focus:outline-none focus:ring-2 focus:ring-primary/20
                                      {{ $editingId ? 'opacity-60 cursor-not-allowed' : '' }}
                                      {{ $errors->has('slug') ? 'border-error ring-2 ring-error/20' : '' }}" />
                        <p class="mt-1 text-[11px] text-on-surface-variant">ຕົວພິມນ້ອຍ, ຕົວເລກ, - ຫຼື _ ເທົ່ານັ້ນ · ສ້າງອັດຕະໂນມັດຈາກຊື່ລາວ</p>
                        @error('slug') <p class="mt-1 text-body-sm text-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Icon picker --}}
                    <div>
                        <label class="block text-label-md font-bold text-on-surface mb-2">Icon</label>
                        <div class="grid grid-cols-10 gap-1.5 p-3 bg-surface-container rounded-xl border border-outline-variant max-h-36 overflow-y-auto">
                            @foreach ($iconOptions as $ic)
                                <button type="button" wire:click="$set('icon', '{{ $ic }}')"
                                        title="{{ $ic }}"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg transition-all
                                               {{ $icon === $ic ? 'bg-primary text-white shadow-sm' : 'hover:bg-surface-container-high text-on-surface-variant' }}">
                                    <span class="material-symbols-outlined text-base">{{ $ic }}</span>
                                </button>
                            @endforeach
                        </div>
                        <p class="mt-1 text-[11px] text-on-surface-variant">ເລືອກ: <strong>{{ $icon }}</strong></p>
                    </div>

                    {{-- Color picker --}}
                    <div>
                        <label class="block text-label-md font-bold text-on-surface mb-2">ສີ / Color</label>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $colorDots = [
                                    'red'=>'bg-red-500','blue'=>'bg-blue-500','amber'=>'bg-amber-400',
                                    'green'=>'bg-green-500','purple'=>'bg-purple-500','gray'=>'bg-gray-400',
                                    'indigo'=>'bg-indigo-500','teal'=>'bg-teal-500','rose'=>'bg-rose-500',
                                    'orange'=>'bg-orange-500','cyan'=>'bg-cyan-500','pink'=>'bg-pink-500',
                                ];
                            @endphp
                            @foreach (\App\Models\NewsCategory::$colorOptions as $c)
                                <button type="button" wire:click="$set('color', '{{ $c }}')"
                                        title="{{ $c }}"
                                        class="w-8 h-8 rounded-full {{ $colorDots[$c] ?? 'bg-blue-400' }} transition-all
                                               {{ $color === $c ? 'ring-2 ring-offset-2 ring-primary scale-110' : 'hover:scale-110' }}">
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Sort Order + Active --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-label-md font-bold text-on-surface mb-1.5">ລຳດັບ / Sort Order</label>
                            <input wire:model="sort_order" type="number" min="0" max="999"
                                   class="w-full px-3 py-2.5 bg-surface-container border border-outline-variant rounded-xl text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20" />
                        </div>
                        <div>
                            <label class="block text-label-md font-bold text-on-surface mb-1.5">ສະຖານະ / Status</label>
                            <label class="flex items-center gap-3 cursor-pointer mt-2">
                                <div class="relative">
                                    <input type="checkbox" wire:model="is_active" class="sr-only peer" />
                                    <div class="w-11 h-6 bg-surface-container-highest rounded-full peer-checked:bg-primary transition-colors"></div>
                                    <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
                                </div>
                                <span class="text-body-md text-on-surface">{{ $is_active ? 'ໃຊ້ງານ' : 'ປິດ' }}</span>
                            </label>
                        </div>
                    </div>

                    {{-- Preview --}}
                    @php $prevColor = \App\Models\NewsCategory::$colorMap[$color] ?? 'text-blue-700 bg-blue-50 border-blue-200'; @endphp
                    <div class="bg-surface-container rounded-xl border border-outline-variant p-3 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 border {{ $prevColor }}">
                            <span class="material-symbols-outlined text-base">{{ $icon }}</span>
                        </div>
                        <div>
                            <p class="text-body-sm font-medium text-on-surface">{{ $name_lo ?: 'ຊື່ໝວດ (ລາວ)' }}</p>
                            @if ($name_en)
                                <p class="text-[11px] text-on-surface-variant">{{ $name_en }}</p>
                            @endif
                        </div>
                        @if ($slug)
                            <span class="ml-auto text-[10px] font-mono bg-surface-container-highest text-on-surface-variant px-1.5 py-0.5 rounded">{{ $slug }}</span>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" wire:click="closeModal"
                                class="px-5 py-2.5 rounded-xl border border-outline-variant text-on-surface-variant hover:bg-surface-container transition-all text-label-md">
                            ຍົກເລີກ / Cancel
                        </button>
                        <button type="submit"
                                class="flex items-center gap-2 px-6 py-2.5 rounded-xl bg-primary text-white hover:bg-primary/90 transition-all font-bold text-label-md shadow-md">
                            <span class="material-symbols-outlined text-base" wire:loading.remove wire:target="save">save</span>
                            <span class="material-symbols-outlined text-base animate-spin hidden" wire:loading.class.remove="hidden" wire:loading wire:target="save">progress_activity</span>
                            <span wire:loading.remove wire:target="save">{{ $editingId ? 'ອັບເດດ' : 'ບັນທຶກ' }}</span>
                            <span wire:loading wire:target="save">ກຳລັງບັນທຶກ...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- DELETE CONFIRM MODAL --}}
    @if ($confirmDeleteId)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="bg-surface rounded-2xl p-6 max-w-sm w-full shadow-xl">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-error/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-error">warning</span>
                    </div>
                    <h3 class="text-title-md font-bold text-on-surface">ຢືນຢັນການລຶບ</h3>
                </div>
                <p class="text-body-md text-on-surface-variant mb-2">
                    ທ່ານແນ່ໃຈບໍ່ທີ່ຈະລຶບໝວດນີ້?
                </p>
                <p class="text-body-sm text-on-surface-variant/70 mb-6">
                    ໝວດທີ່ມີຂ່າວຢູ່ລຸ່ມ <strong>ບໍ່ສາມາດລຶບໄດ້</strong> / Categories with articles cannot be deleted.
                </p>
                <div class="flex gap-3 justify-end">
                    <button wire:click="cancelDelete"
                            class="px-4 py-2 rounded-xl border border-outline-variant text-on-surface-variant hover:bg-surface-container transition-all text-label-md">
                        ຍົກເລີກ
                    </button>
                    <button wire:click="delete"
                            class="px-4 py-2 rounded-xl bg-error text-white hover:bg-error/90 transition-all font-bold text-label-md">
                        ລຶບ / Delete
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
