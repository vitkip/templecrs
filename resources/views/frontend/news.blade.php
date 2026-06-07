@extends('frontend.layout')

@section('content')

@php
    $locale   = app()->getLocale();
    $newsJson = $news->map(function ($n) {
        return [
            'id'          => $n->id,
            'title'       => $n->title,
            'excerpt'     => $n->excerpt ?? '',
            'cover'       => $n->cover_image_url,
            'date'        => $n->published_date_formatted,
            'is_featured' => (bool) $n->is_featured,
            'url'         => route('frontend.news.show', $n->id),
            'search_text' => strtolower(implode(' ', array_filter([
                $n->title_lo, $n->title_en,
                $n->excerpt_lo, $n->excerpt_en,
            ]))),
        ];
    })->values()->toArray();
@endphp

{{-- ══════════════════════════════════════════════════════════════
     PAGE HEADER
══════════════════════════════════════════════════════════════ --}}
<div class="relative overflow-hidden" style="background: linear-gradient(135deg, #5c2d00 0%, #8d4b00 50%, #545f73 100%);">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-96 h-96 bg-white/10 rounded-full -translate-x-1/3 -translate-y-1/3 blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-80 h-80 bg-white/10 rounded-full translate-x-1/4 translate-y-1/4 blur-3xl"></div>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 relative z-10">
        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-xs text-white/60 mb-6">
            <a href="{{ route('frontend.index') }}" class="hover:text-white transition-colors flex items-center gap-1">
                <span class="material-symbols-outlined text-xs">home</span>
                {{ __('messages.homepage') }}
            </a>
            <span class="material-symbols-outlined text-[10px] text-white/30">chevron_right</span>
            <span class="text-white/90 font-medium">{{ __('messages.news') }}</span>
        </nav>

        <div class="flex items-center gap-2 mb-2 justify-center">
            <span class="w-8 h-1 bg-amber-400 rounded-full"></span>
            <span class="text-[10px] font-bold text-amber-400 uppercase tracking-widest">{{ __('messages.news_activities') }}</span>
            <span class="w-8 h-1 bg-amber-400 rounded-full"></span>
        </div>
        <h1 class="text-headline-lg lg:text-[40px] font-bold text-white text-center mb-2">
            {{ __('messages.news_listing_title') }}
        </h1>
        <p class="text-body-md text-white/70 text-center max-w-2xl mx-auto">
            {{ __('messages.news_listing_subtitle') }}
        </p>

        {{-- Stats chips --}}
        <div class="flex flex-wrap items-center justify-center gap-3 mt-6">
            <div class="flex items-center gap-1.5 px-4 py-2 bg-white/10 rounded-full border border-white/15 backdrop-blur-sm">
                <span class="material-symbols-outlined text-sm text-amber-300">newspaper</span>
                <span class="text-sm font-bold text-white">{{ $news->count() }}</span>
                <span class="text-xs text-white/60">{{ __('messages.news') }}</span>
            </div>
            <div class="flex items-center gap-1.5 px-4 py-2 bg-white/10 rounded-full border border-white/15 backdrop-blur-sm">
                <span class="material-symbols-outlined text-sm text-amber-300">star</span>
                <span class="text-sm font-bold text-white">{{ $news->where('is_featured', true)->count() }}</span>
                <span class="text-xs text-white/60">{{ __('messages.featured') }}</span>
            </div>
        </div>
    </div>

    {{-- Bottom Wave --}}
    <div class="absolute bottom-0 left-0 w-full z-20">
        <svg viewBox="0 0 1440 56" xmlns="http://www.w3.org/2000/svg" class="w-full">
            <path fill="#FFFBEB" d="M0,20L60,23C120,26,240,32,360,33C480,34,600,28,720,28C840,28,960,34,1080,34C1200,34,1320,28,1380,25L1440,22L1440,56L1380,56C1320,56,1200,56,1080,56C960,56,840,56,720,56C600,56,480,56,360,56C240,56,120,56,60,56L0,56Z"/>
        </svg>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     SEARCH + FILTER + GRID (Alpine Component)
══════════════════════════════════════════════════════════════ --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10"
     x-data="{
        search: '',
        showFeatured: false,
        news: @js($newsJson),
        perPage: 9,
        currentPage: 1,
        perPageOptions: [9, 18, 27],
        get filtered() {
            const q = this.search.toLowerCase().trim();
            return this.news.filter(n => {
                if (this.showFeatured && !n.is_featured) return false;
                if (q && !n.search_text.includes(q)) return false;
                return true;
            });
        },
        get totalPages() {
            return Math.max(1, Math.ceil(this.filtered.length / this.perPage));
        },
        get paginated() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.filtered.slice(start, start + this.perPage);
        },
        get pageNumbers() {
            const total = this.totalPages;
            const curr = this.currentPage;
            if (total <= 7) return Array.from({length: total}, (_, i) => i + 1);
            if (curr <= 4) return [1, 2, 3, 4, 5, '...', total];
            if (curr >= total - 3) return [1, '...', total-4, total-3, total-2, total-1, total];
            return [1, '...', curr-1, curr, curr+1, '...', total];
        },
        init() {
            this.$watch('search',      () => { this.currentPage = 1; });
            this.$watch('showFeatured',() => { this.currentPage = 1; });
            this.$watch('perPage',     () => { this.currentPage = 1; });
        }
     }">

    {{-- ─── Search + Filter Bar ─── --}}
    <div class="mb-8 space-y-4">
        <div class="relative max-w-xl mx-auto">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-xl text-on-surface-variant/50 pointer-events-none">search</span>
            <input
                x-model="search"
                type="text"
                placeholder="{{ __('messages.search_news') }}"
                class="w-full pl-12 pr-12 py-3.5 rounded-xl border border-outline-variant bg-white text-body-md text-on-surface placeholder:text-on-surface-variant/50 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 shadow-sm transition-all"
            />
            <button x-show="search"
                    @click="search = ''"
                    class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 rounded-lg text-on-surface-variant hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined text-base">close</span>
            </button>
        </div>

        {{-- Featured Filter --}}
        <div class="flex flex-wrap gap-2 justify-center">
            <button @click="showFeatured = false"
                    :class="!showFeatured
                        ? 'bg-primary text-white border-primary shadow-md shadow-primary/20'
                        : 'bg-white text-on-surface-variant border-outline-variant hover:border-primary/40 hover:text-primary'"
                    class="px-4 py-2 rounded-lg text-label-sm font-semibold border transition-all duration-200 flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm">newspaper</span>
                {{ __('messages.all_news') }}
                <span x-text="news.length"
                      :class="!showFeatured ? 'bg-white/25 text-white' : 'bg-surface-container text-on-surface-variant'"
                      class="text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[20px] text-center"></span>
            </button>
            <button @click="showFeatured = true"
                    :class="showFeatured
                        ? 'bg-primary text-white border-primary shadow-md shadow-primary/20'
                        : 'bg-white text-on-surface-variant border-outline-variant hover:border-primary/40 hover:text-primary'"
                    class="px-4 py-2 rounded-lg text-label-sm font-semibold border transition-all duration-200 flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm">star</span>
                {{ __('messages.featured_only') }}
                <span x-text="news.filter(n => n.is_featured).length"
                      :class="showFeatured ? 'bg-white/25 text-white' : 'bg-surface-container text-on-surface-variant'"
                      class="text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[20px] text-center"></span>
            </button>
        </div>

        {{-- Results count --}}
        <div class="text-center">
            <span class="text-xs text-on-surface-variant">
                <span x-text="Math.min((currentPage-1)*perPage+1, filtered.length)" class="font-bold text-primary"></span>–<span x-text="Math.min(currentPage*perPage, filtered.length)" class="font-bold text-primary"></span>
                /
                <span x-text="filtered.length" class="font-bold text-primary"></span>
                {{ __('messages.news_results_count') }}
            </span>
        </div>
    </div>

    {{-- ─── News Grid ─── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <template x-for="item in paginated" :key="item.id">
            <a :href="item.url" class="block group"
               x-transition:enter="transition ease-out duration-200"
               x-transition:enter-start="opacity-0 translate-y-2"
               x-transition:enter-end="opacity-100 translate-y-0">
                <div class="bg-white rounded-2xl border border-outline-variant overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 h-full flex flex-col">

                    {{-- Cover Image --}}
                    <div class="relative h-48 overflow-hidden shrink-0">
                        <template x-if="item.cover">
                            <img :src="item.cover" :alt="item.title" loading="lazy"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                        </template>
                        <template x-if="!item.cover">
                            <div class="w-full h-full bg-gradient-to-br from-primary/15 to-secondary/15 flex items-center justify-center">
                                <span class="material-symbols-outlined text-6xl text-on-surface-variant/20">newspaper</span>
                            </div>
                        </template>

                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>

                        {{-- Featured Badge --}}
                        <div x-show="item.is_featured" class="absolute top-3 left-3">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-tertiary text-white text-[10px] font-bold rounded-full shadow-lg">
                                <span class="material-symbols-outlined text-xs">star</span>
                                {{ __('messages.featured') }}
                            </span>
                        </div>

                        {{-- Date overlay --}}
                        <div class="absolute bottom-3 left-3">
                            <span class="text-[10px] text-white/80 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[10px]">calendar_today</span>
                                <span x-text="item.date"></span>
                            </span>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 x-text="item.title"
                                class="text-body-lg font-bold text-on-surface line-clamp-2 group-hover:text-primary transition-colors duration-200 leading-snug mb-2"></h3>
                            <p x-show="item.excerpt"
                               x-text="item.excerpt"
                               class="text-sm text-on-surface-variant line-clamp-2 leading-relaxed"></p>
                        </div>
                        <div class="mt-4 flex items-center gap-1 text-[11px] text-primary font-bold group-hover:gap-2 transition-all">
                            <span>{{ __('messages.read_more') }}</span>
                            <span class="material-symbols-outlined text-[12px]">arrow_forward</span>
                        </div>
                    </div>
                </div>
            </a>
        </template>

    </div>

    {{-- ─── Pagination ─── --}}
    <div x-show="totalPages > 1 && filtered.length > 0" class="mt-10 flex justify-center">
        <div class="inline-flex items-center gap-1 px-3 py-2.5 bg-white rounded-2xl shadow-sm border border-outline-variant/60 flex-wrap justify-center">
            <button @click="if(currentPage > 1) currentPage--"
                    :disabled="currentPage === 1"
                    :class="currentPage === 1 ? 'opacity-30 cursor-not-allowed' : 'hover:bg-surface-container cursor-pointer'"
                    class="w-9 h-9 flex items-center justify-center rounded-full text-on-surface-variant transition-all">
                <span class="material-symbols-outlined text-base leading-none">chevron_left</span>
            </button>
            <template x-for="(page, idx) in pageNumbers" :key="idx">
                <div class="contents">
                    <button x-show="page !== '...'"
                            @click="currentPage = page"
                            :class="currentPage === page
                                ? 'bg-primary text-white shadow-sm shadow-primary/30'
                                : 'text-on-surface hover:bg-surface-container hover:text-primary'"
                            class="w-9 h-9 flex items-center justify-center rounded-full text-sm font-semibold transition-all">
                        <span x-text="page"></span>
                    </button>
                    <span x-show="page === '...'"
                          class="w-9 h-9 flex items-center justify-center text-on-surface-variant/50 text-sm select-none">···</span>
                </div>
            </template>
            <button @click="if(currentPage < totalPages) currentPage++"
                    :disabled="currentPage === totalPages"
                    :class="currentPage === totalPages ? 'opacity-30 cursor-not-allowed' : 'hover:bg-surface-container cursor-pointer'"
                    class="w-9 h-9 flex items-center justify-center rounded-full text-on-surface-variant transition-all">
                <span class="material-symbols-outlined text-base leading-none">chevron_right</span>
            </button>
            <div class="ml-1 relative">
                <select x-model.number="perPage"
                        class="pl-3 pr-8 py-2 rounded-full border border-outline-variant/70 bg-white text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 appearance-none cursor-pointer">
                    <template x-for="opt in perPageOptions" :key="opt">
                        <option :value="opt" x-text="opt + ' / page'"></option>
                    </template>
                </select>
                <span class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 material-symbols-outlined text-xs text-on-surface-variant">expand_more</span>
            </div>
        </div>
    </div>

    {{-- ─── Empty State ─── --}}
    <div x-show="filtered.length === 0"
         x-transition.opacity
         class="text-center py-20">
        <span class="material-symbols-outlined text-7xl text-on-surface-variant/15 mb-4 block">manage_search</span>
        <p class="text-body-lg font-semibold text-on-surface-variant mb-2">{{ __('messages.no_news_results_found') }}</p>
        <p class="text-sm text-on-surface-variant/70 mb-6">{{ __('messages.no_news_results_hint') }}</p>
        <button @click="search = ''; showFeatured = false"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-xl text-label-md font-bold hover:bg-primary-container transition-all btn-press">
            <span class="material-symbols-outlined text-base">restart_alt</span>
            {{ __('messages.clear_filters') }}
        </button>
    </div>

</div>

@endsection
