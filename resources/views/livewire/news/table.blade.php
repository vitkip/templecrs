<div>
    {{-- Page Header --}}
    <div class="flex justify-between items-end mb-8 animate-fade-in">
        <div>
            <h2 class="text-headline-lg text-on-surface mb-1">ຂ່າວ ແລະ ກິດຈະກຳ</h2>
            <p class="text-body-md text-on-surface-variant">News & Activities Management</p>
        </div>
        <a href="{{ route('news.create') }}"
           class="bg-primary text-white px-6 py-3 rounded-lg font-bold flex items-center gap-2 hover:bg-primary-container transition-all shadow-md btn-press">
            <span class="material-symbols-outlined">add_circle</span>
            ເພີ່ມຂ່າວໃໝ່
        </a>
    </div>

    {{-- Stats Overview --}}
    <div class="grid grid-cols-12 gap-6 mb-8 animate-fade-in">
        {{-- Total --}}
        <div class="col-span-12 lg:col-span-3 glass-card p-6 rounded-xl border border-outline-variant flex items-center gap-6">
            <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                <span class="material-symbols-outlined text-4xl filled">newspaper</span>
            </div>
            <div>
                <p class="text-label-md text-on-surface-variant uppercase tracking-widest">TOTAL NEWS</p>
                <h3 class="text-headline-md text-on-surface">{{ number_format($stats['total']) }}</h3>
            </div>
        </div>

        {{-- Published --}}
        <div class="col-span-12 lg:col-span-3 glass-card p-6 rounded-xl border border-outline-variant flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-700">
                <span class="material-symbols-outlined text-2xl">check_circle</span>
            </div>
            <div>
                <p class="text-label-md text-on-surface-variant">ເຜີຍແຜ່ / Published</p>
                <h3 class="text-headline-sm text-on-surface">{{ number_format($stats['published']) }}</h3>
            </div>
        </div>

        {{-- Featured --}}
        <div class="col-span-12 lg:col-span-3 glass-card p-6 rounded-xl border border-outline-variant flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center text-amber-700">
                <span class="material-symbols-outlined text-2xl">star</span>
            </div>
            <div>
                <p class="text-label-md text-on-surface-variant">ແນະນຳ / Featured</p>
                <h3 class="text-headline-sm text-on-surface">{{ number_format($stats['featured']) }}</h3>
            </div>
        </div>

        {{-- This Month --}}
        <div class="col-span-12 lg:col-span-3 glass-card p-6 rounded-xl border border-outline-variant flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-secondary/10 flex items-center justify-center text-secondary">
                <span class="material-symbols-outlined text-2xl">calendar_month</span>
            </div>
            <div>
                <p class="text-label-md text-on-surface-variant">ເດືອນນີ້ / This Month</p>
                <h3 class="text-headline-sm text-on-surface">{{ number_format($stats['this_month']) }}</h3>
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
                    <option value="active">ໃຊ້ງານ</option>
                    <option value="inactive">ບໍ່ໃຊ້ງານ</option>
                </select>
            </div>

            {{-- Featured Filter --}}
            <div class="w-40">
                <label class="block text-[10px] font-bold text-on-surface-variant mb-1 uppercase">
                    FEATURED / ແນະນຳ
                </label>
                <select wire:model.live="featuredFilter"
                        class="w-full bg-white border border-outline-variant rounded-lg p-2 text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20">
                    <option value="">ທັງໝົດ</option>
                    <option value="featured">ແນະນຳ</option>
                    <option value="not_featured">ບໍ່ແນະນຳ</option>
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
                   placeholder="ຄົ້ນຫາຫົວຂໍ້ຂ່າວ..." />
        </div>
    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-outline-variant overflow-hidden animate-fade-in">
        <table class="w-full border-collapse text-left">
            <thead>
                <tr class="bg-secondary text-white text-label-md">
                    <th class="p-3 w-16">ຮູບ</th>
                    <th class="p-3 cursor-pointer hover:bg-white/10 transition-colors" wire:click="sortBy('title_lo')">
                        ຫົວຂໍ້ / Title
                        @if ($sortBy === 'title_lo')
                            <span class="material-symbols-outlined text-xs align-middle">{{ $sortDir === 'asc' ? 'arrow_upward' : 'arrow_downward' }}</span>
                        @endif
                    </th>
                    <th class="p-3 cursor-pointer hover:bg-white/10 transition-colors" wire:click="sortBy('published_at')">
                        ວັນທີ່ເຜີຍແຜ່
                        @if ($sortBy === 'published_at')
                            <span class="material-symbols-outlined text-xs align-middle">{{ $sortDir === 'asc' ? 'arrow_upward' : 'arrow_downward' }}</span>
                        @endif
                    </th>
                    <th class="p-3">ຜູ້ຂຽນ</th>
                    <th class="p-3 text-center">ແນະນຳ</th>
                    <th class="p-3 text-center">ສະຖານະ</th>
                    <th class="p-3 text-right">ດຳເນີນການ</th>
                </tr>
            </thead>
            <tbody class="text-table-data">
                @forelse ($newsList as $item)
                    <tr class="border-b border-outline-variant table-row-hover h-[60px]"
                        wire:key="news-{{ $item->id }}">

                        {{-- Cover Image --}}
                        <td class="p-3">
                            @if ($item->cover_image_url)
                                <img src="{{ $item->cover_image_url }}" alt="" class="w-12 h-10 rounded-lg object-cover" />
                            @else
                                <div class="w-12 h-10 rounded-lg bg-surface-container flex items-center justify-center">
                                    <span class="material-symbols-outlined text-on-surface-variant/40 text-lg">image</span>
                                </div>
                            @endif
                        </td>

                        {{-- Title --}}
                        <td class="p-3">
                            <div class="flex flex-col">
                                <a href="{{ route('news.show', $item->id) }}"
                                   class="font-bold text-on-surface hover:text-primary transition-colors">
                                    {{ $item->title_lo }}
                                </a>
                                @if ($item->title_en)
                                    <span class="text-on-surface-variant opacity-70 text-xs">{{ $item->title_en }}</span>
                                @endif
                            </div>
                        </td>

                        {{-- Published Date --}}
                        <td class="p-3 text-on-surface-variant">
                            {{ $item->published_date_formatted }}
                        </td>

                        {{-- Author --}}
                        <td class="p-3 text-on-surface-variant">
                            {{ $item->author?->name ?? '—' }}
                        </td>

                        {{-- Featured Toggle --}}
                        <td class="p-3">
                            <div class="flex justify-center">
                                <button wire:click="toggleFeatured({{ $item->id }})"
                                        class="p-1 transition-colors {{ $item->is_featured ? 'text-amber-500' : 'text-on-surface-variant/30 hover:text-amber-400' }}">
                                    <span class="material-symbols-outlined text-xl {{ $item->is_featured ? 'filled' : '' }}">star</span>
                                </button>
                            </div>
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
                            <a href="{{ route('news.show', $item->id) }}"
                               class="p-1 hover:text-secondary transition-colors inline-block"
                               title="ເບິ່ງລາຍລະອຽດ">
                                <span class="material-symbols-outlined text-lg">visibility</span>
                            </a>
                            <a href="{{ route('news.edit', $item->id) }}"
                               class="p-1 hover:text-primary transition-colors inline-block"
                               title="ແກ້ໄຂ">
                                <span class="material-symbols-outlined text-lg">edit</span>
                            </a>
                            <button wire:click="deleteNews({{ $item->id }})"
                                    wire:confirm="ທ່ານແນ່ໃຈບໍ່ທີ່ຕ້ອງການລຶບຂ່າວນີ້?"
                                    class="p-1 hover:text-error transition-colors"
                                    title="ລຶບ">
                                <span class="material-symbols-outlined text-lg">delete</span>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-12 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-5xl mb-4 block opacity-30">article</span>
                            <p class="text-lg">ບໍ່ພົບຂ່າວ / No news found</p>
                            <a href="{{ route('news.create') }}" class="text-primary hover:underline mt-2 inline-block">
                                ເພີ່ມຂ່າວທຳອິດ
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if ($newsList->hasPages())
            <div class="p-4 flex justify-between items-center bg-surface-container-low border-t border-outline-variant">
                <p class="text-label-md text-on-surface-variant">
                    ສະແດງ {{ $newsList->firstItem() }}–{{ $newsList->lastItem() }} ຈາກ {{ number_format($newsList->total()) }} ລາຍການ
                </p>
                {{ $newsList->links() }}
            </div>
        @endif
    </div>
</div>
