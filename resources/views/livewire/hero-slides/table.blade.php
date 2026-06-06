<div>
    {{-- Page Header --}}
    <div class="flex justify-between items-end mb-8 animate-fade-in">
        <div>
            <h2 class="text-headline-lg text-on-surface mb-1">ຈັດການສະໄລ້ Hero</h2>
            <p class="text-body-md text-on-surface-variant">Hero Slides Management</p>
        </div>
        <a href="{{ route('hero-slides.create') }}"
           class="bg-primary text-white px-6 py-3 rounded-lg font-bold flex items-center gap-2 hover:bg-primary-container transition-all shadow-md btn-press">
            <span class="material-symbols-outlined">add_circle</span>
            ເພີ່ມສະໄລ້ໃໝ່
        </a>
    </div>

    {{-- Stats Overview --}}
    <div class="grid grid-cols-12 gap-6 mb-8 animate-fade-in">
        {{-- Total --}}
        <div class="col-span-12 md:col-span-6 glass-card p-6 rounded-xl border border-outline-variant flex items-center gap-6">
            <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                <span class="material-symbols-outlined text-4xl filled">photo_library</span>
            </div>
            <div>
                <p class="text-label-md text-on-surface-variant uppercase tracking-widest">TOTAL SLIDES</p>
                <h3 class="text-headline-md text-on-surface">{{ number_format($stats['total']) }}</h3>
            </div>
        </div>

        {{-- Active --}}
        <div class="col-span-12 md:col-span-6 glass-card p-6 rounded-xl border border-outline-variant flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-700">
                <span class="material-symbols-outlined text-2xl">check_circle</span>
            </div>
            <div>
                <p class="text-label-md text-on-surface-variant">ເປີດໃຊ້ງານ / Active</p>
                <h3 class="text-headline-sm text-on-surface">{{ number_format($stats['active']) }}</h3>
            </div>
        </div>
    </div>

    {{-- Filters Bar --}}
    <div class="bg-surface-container-low p-4 rounded-xl border border-outline-variant mb-6 flex flex-wrap items-end gap-4 animate-fade-in">
        <div class="flex-1 flex flex-wrap gap-4 min-w-[300px]">
            {{-- Status Filter --}}
            <div class="w-40">
                <label class="block text-[10px] font-bold text-on-surface-variant mb-1 uppercase">
                    STATUS / ສະຖານະ
                </label>
                <select wire:model.live="statusFilter"
                        class="w-full bg-white border border-outline-variant rounded-lg p-2 text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20">
                    <option value="">ທັງໝົດ</option>
                    <option value="active">ເປີດໃຊ້ງານ</option>
                    <option value="inactive">ປິດໃຊ້ງານ</option>
                </select>
            </div>
        </div>

        <button wire:click="clearFilters"
                class="px-4 py-2 border border-outline-variant rounded-lg text-body-md font-bold text-on-surface-variant hover:bg-surface-container transition-all flex items-center gap-2 btn-press">
            <span class="material-symbols-outlined text-sm">filter_list_off</span>
            ລ້າງ
        </button>
    </div>

    {{-- Search Bar --}}
    <div class="mb-6 animate-fade-in">
        <div class="relative max-w-md">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
            <input type="text"
                   wire:model.live.debounce.300ms="search"
                   class="w-full pl-10 pr-4 py-2.5 bg-white border border-outline-variant rounded-lg text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all"
                   placeholder="ຄົ້ນຫາຫົວຂໍ້..." />
        </div>
    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-outline-variant overflow-hidden animate-fade-in">
        <table class="w-full border-collapse text-left">
            <thead>
                <tr class="bg-secondary text-white text-label-md">
                    <th class="p-3 w-28">ຮູບສະໄລ້</th>
                    <th class="p-3 cursor-pointer hover:bg-white/10 transition-colors" wire:click="sortBy('title_lo')">
                        ຫົວຂໍ້ / Title
                        @if ($sortBy === 'title_lo')
                            <span class="material-symbols-outlined text-xs align-middle">{{ $sortDir === 'asc' ? 'arrow_upward' : 'arrow_downward' }}</span>
                        @endif
                    </th>
                    <th class="p-3 cursor-pointer hover:bg-white/10 transition-colors text-center w-28" wire:click="sortBy('sort_order')">
                        ລຳດັບ
                        @if ($sortBy === 'sort_order')
                            <span class="material-symbols-outlined text-xs align-middle">{{ $sortDir === 'asc' ? 'arrow_upward' : 'arrow_downward' }}</span>
                        @endif
                    </th>
                    <th class="p-3 text-center w-28">ສະຖານະ</th>
                    <th class="p-3 text-right w-32">ດຳເນີນການ</th>
                </tr>
            </thead>
            <tbody class="text-table-data">
                @forelse ($slides as $item)
                    <tr class="border-b border-outline-variant table-row-hover h-[70px]"
                        wire:key="slide-{{ $item->id }}">

                        {{-- Slide Image --}}
                        <td class="p-3">
                            <img src="{{ $item->image_url }}" alt="" class="w-24 h-14 rounded-lg object-cover border border-outline-variant" />
                        </td>

                        {{-- Title & Subtitle --}}
                        <td class="p-3">
                            <div class="flex flex-col">
                                <span class="font-bold text-on-surface">
                                    {{ $item->title_lo ?: '— ບໍ່ມີຫົວຂໍ້ —' }}
                                </span>
                                @if ($item->title_en)
                                    <span class="text-on-surface-variant opacity-70 text-xs">{{ $item->title_en }}</span>
                                @endif
                                @if ($item->subtitle_lo)
                                    <span class="text-xs text-on-surface-variant line-clamp-1 mt-0.5">{{ $item->subtitle_lo }}</span>
                                @endif
                            </div>
                        </td>

                        {{-- Sort Order --}}
                        <td class="p-3 text-center font-mono">
                            {{ $item->sort_order }}
                        </td>

                        {{-- Active Toggle --}}
                        <td class="p-3">
                            <div class="flex justify-center">
                                <label class="toggle-switch">
                                    <input type="checkbox"
                                           {{ $item->is_active ? 'checked' : '' }}
                                           wire:click="toggleActive({{ $item->id }})" />
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                        </td>

                        {{-- Actions --}}
                        <td class="p-3 text-right">
                            <a href="{{ route('hero-slides.edit', $item->id) }}"
                               class="p-1 hover:text-primary transition-colors inline-block"
                               title="ແກ້ໄຂ">
                                <span class="material-symbols-outlined text-lg">edit</span>
                            </a>
                            <button wire:click="deleteSlide({{ $item->id }})"
                                    wire:confirm="ທ່ານແນ່ໃຈບໍ່ທີ່ຕ້ອງການລຶບສະໄລ້ນີ້?"
                                    class="p-1 hover:text-error transition-colors"
                                    title="ລຶບ">
                                <span class="material-symbols-outlined text-lg">delete</span>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-12 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-5xl mb-4 block opacity-30">photo_library</span>
                            <p class="text-lg">ບໍ່ພົບຮູບສະໄລ້ / No slides found</p>
                            <a href="{{ route('hero-slides.create') }}" class="text-primary hover:underline mt-2 inline-block">
                                ເພີ່ມສະໄລ້ທຳອິດ
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if ($slides->hasPages())
            <div class="p-4 flex justify-between items-center bg-surface-container-low border-t border-outline-variant">
                <p class="text-label-md text-on-surface-variant">
                    ສະແດງ {{ $slides->firstItem() }}–{{ $slides->lastItem() }} ຈາກ {{ number_format($slides->total()) }} ລາຍການ
                </p>
                {{ $slides->links() }}
            </div>
        @endif
    </div>
</div>
