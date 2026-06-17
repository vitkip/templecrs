@extends('frontend.layout')

@section('content')

@php
    $locale = app()->getLocale();

    $docCategoryMap = \App\Models\DocumentCategory::all()->keyBy('slug');
    $fallbackCat    = (object)['name_lo'=>'ອື່ນໆ','name_en'=>'Other','icon'=>'description','color_class'=>'text-gray-700 bg-gray-50 border-gray-200'];

    $documentsJson = $documents->map(function ($d) use ($locale, $docCategoryMap, $fallbackCat) {
        $cat = $docCategoryMap->get($d->category) ?? $fallbackCat;
        return [
            'id'           => $d->id,
            'title'        => $locale === 'lo'
                ? ($d->title_lo ?? $d->title_en ?? '')
                : ($d->title_en ?? $d->title_lo ?? ''),
            'doc_number'   => $d->doc_number ?? '',
            'category'     => $d->category ?? 'other',
            'cat_label'    => $locale === 'lo' ? $cat->name_lo : ($cat->name_en ?? $cat->name_lo),
            'cat_icon'     => $cat->icon,
            'cat_color'    => $cat->color_class,
            'description'  => $locale === 'lo'
                ? ($d->description_lo ?? $d->description_en ?? '')
                : ($d->description_en ?? $d->description_lo ?? ''),
            'dept_id'      => (string) ($d->department_id ?? ''),
            'dept_name'    => $d->department?->name ?? '',
            'issued_date'  => $d->issued_date?->format('d/m/Y') ?? '',
            'file_icon'    => $d->file_icon,
            'file_size'    => $d->file_size_formatted,
            'file_name'    => $d->file_name ?? '',
            'has_file'       => (bool) $d->file_path,
            'download_url'   => $d->file_path ? route('frontend.document.download', $d->id) : null,
            'download_count' => (int) $d->download_count,
            'search_text'  => strtolower(implode(' ', array_filter([
                $d->title_lo, $d->title_en,
                $d->doc_number,
                $d->description_lo, $d->description_en,
                $d->department?->name_lo, $d->department?->name_en,
                $locale === 'lo' ? $cat->name_lo : ($cat->name_en ?? $cat->name_lo),
            ]))),
        ];
    })->values()->toArray();

    $categoriesUsed = $documents->pluck('category')->unique()->values()->toArray();
@endphp

{{-- ══════════════════════════════════════════════════════════════
     PAGE HEADER
══════════════════════════════════════════════════════════════ --}}
<div class="relative overflow-hidden" style="background: linear-gradient(135deg, #1a3a5c 0%, #545f73 60%, #2d5016 100%);">
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
            <span class="text-white/90 font-medium">{{ __('messages.documents_nav') }}</span>
        </nav>

        <div class="flex items-center gap-2 mb-2 justify-center">
            <span class="w-8 h-1 bg-amber-400 rounded-full"></span>
            <span class="text-[10px] font-bold text-amber-400 uppercase tracking-widest">DMS</span>
            <span class="w-8 h-1 bg-amber-400 rounded-full"></span>
        </div>
        <h1 class="text-headline-lg lg:text-[40px] font-bold text-white text-center mb-2">
            {{ __('messages.document_library_title') }}
        </h1>
        <p class="text-body-md text-white/70 text-center max-w-2xl mx-auto">
            {{ __('messages.document_library_subtitle') }}
        </p>

        {{-- Stats chips --}}
        <div class="flex flex-wrap items-center justify-center gap-3 mt-6">
            <div class="flex items-center gap-1.5 px-4 py-2 bg-white/10 rounded-full border border-white/15 backdrop-blur-sm">
                <span class="material-symbols-outlined text-sm text-amber-300">description</span>
                <span class="text-sm font-bold text-white">{{ $documents->count() }}</span>
                <span class="text-xs text-white/60">{{ __('messages.documents_nav') }}</span>
            </div>
            <div class="flex items-center gap-1.5 px-4 py-2 bg-white/10 rounded-full border border-white/15 backdrop-blur-sm">
                <span class="material-symbols-outlined text-sm text-amber-300">corporate_fare</span>
                <span class="text-sm font-bold text-white">{{ $departments->count() }}</span>
                <span class="text-xs text-white/60">{{ __('messages.departments') }}</span>
            </div>
            <div class="flex items-center gap-1.5 px-4 py-2 bg-white/10 rounded-full border border-white/15 backdrop-blur-sm">
                <span class="material-symbols-outlined text-sm text-amber-300">folder_open</span>
                <span class="text-sm font-bold text-white">{{ count($categoriesUsed) }}</span>
                <span class="text-xs text-white/60">{{ __('messages.document_category') }}</span>
            </div>
            @if($totalDownloads > 0)
            <div class="flex items-center gap-1.5 px-4 py-2 bg-white/10 rounded-full border border-white/15 backdrop-blur-sm">
                <span class="material-symbols-outlined text-sm text-amber-300">download</span>
                <span class="text-sm font-bold text-white">{{ number_format($totalDownloads) }}</span>
                <span class="text-xs text-white/60">ດາວໂຫລດ</span>
            </div>
            @endif
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
     SEARCH + FILTER + LIST (Alpine Component)
══════════════════════════════════════════════════════════════ --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10"
     x-data="{
        search: '',
        activeDept: 'all',
        activeCat: 'all',
        documents: @js($documentsJson),
        perPage: 10,
        currentPage: 1,
        perPageOptions: [10, 20, 50],
        get filtered() {
            const q = this.search.toLowerCase().trim();
            return this.documents.filter(d => {
                const deptOk = this.activeDept === 'all' || d.dept_id === this.activeDept;
                const catOk  = this.activeCat  === 'all' || d.category === this.activeCat;
                const searchOk = !q || d.search_text.includes(q);
                return deptOk && catOk && searchOk;
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
            this.$watch('search',     () => { this.currentPage = 1; });
            this.$watch('activeDept', () => { this.currentPage = 1; });
            this.$watch('activeCat',  () => { this.currentPage = 1; });
            this.$watch('perPage',    () => { this.currentPage = 1; });
        }
     }">

    {{-- ─── Search Bar ─── --}}
    <div class="mb-6 space-y-4">
        <div class="relative max-w-xl mx-auto">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-xl text-on-surface-variant/50 pointer-events-none">search</span>
            <input
                x-model="search"
                type="text"
                placeholder="{{ __('messages.search_documents') }}"
                class="w-full pl-12 pr-12 py-3.5 rounded-xl border border-outline-variant bg-white text-body-md text-on-surface placeholder:text-on-surface-variant/50 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 shadow-sm transition-all"
            />
            <button x-show="search"
                    @click="search = ''"
                    class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 rounded-lg text-on-surface-variant hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined text-base">close</span>
            </button>
        </div>

        {{-- Department Filter --}}
        @if($departments->count() > 0)
        <div class="flex flex-wrap gap-2 justify-center">
            <button @click="activeDept = 'all'"
                    :class="activeDept === 'all'
                        ? 'bg-primary text-white border-primary shadow-md shadow-primary/20'
                        : 'bg-white text-on-surface-variant border-outline-variant hover:border-primary/40 hover:text-primary'"
                    class="px-4 py-2 rounded-lg text-label-sm font-semibold border transition-all duration-200 flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm">apps</span>
                {{ __('messages.all_departments') }}
                <span x-text="documents.length"
                      :class="activeDept === 'all' ? 'bg-white/25 text-white' : 'bg-surface-container text-on-surface-variant'"
                      class="text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[20px] text-center"></span>
            </button>

            @foreach($departments as $dept)
            @php $deptCount = $documents->where('department_id', $dept->id)->count(); @endphp
            <button @click="activeDept = '{{ $dept->id }}'"
                    :class="activeDept === '{{ $dept->id }}'
                        ? 'bg-primary text-white border-primary shadow-md shadow-primary/20'
                        : 'bg-white text-on-surface-variant border-outline-variant hover:border-primary/40 hover:text-primary'"
                    class="px-4 py-2 rounded-lg text-label-sm font-semibold border transition-all duration-200 flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm">corporate_fare</span>
                {{ $dept->name }}
                <span :class="activeDept === '{{ $dept->id }}' ? 'bg-white/25 text-white' : 'bg-surface-container text-on-surface-variant'"
                      class="text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[20px] text-center">{{ $deptCount }}</span>
            </button>
            @endforeach
        </div>
        @endif

        {{-- Category Filter --}}
        @if(count($categoriesUsed) > 1)
        <div class="flex flex-wrap gap-2 justify-center">
            <button @click="activeCat = 'all'"
                    :class="activeCat === 'all'
                        ? 'bg-secondary text-white border-secondary shadow-sm'
                        : 'bg-white text-on-surface-variant border-outline-variant hover:border-secondary/40 hover:text-secondary'"
                    class="px-3 py-1.5 rounded-lg text-label-sm font-semibold border transition-all duration-200 flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm">grid_view</span>
                {{ __('messages.all_categories') }}
            </button>

            @foreach($categoriesUsed as $catKey)
            @php
                $catMeta  = $docCategoryMap->get($catKey) ?? $fallbackCat;
                $catLabel = $locale === 'lo' ? $catMeta->name_lo : ($catMeta->name_en ?? $catMeta->name_lo);
                $catCount = $documents->where('category', $catKey)->count();
            @endphp
            <button @click="activeCat = '{{ $catKey }}'"
                    :class="activeCat === '{{ $catKey }}'
                        ? 'bg-secondary text-white border-secondary shadow-sm'
                        : 'bg-white text-on-surface-variant border-outline-variant hover:border-secondary/40 hover:text-secondary'"
                    class="px-3 py-1.5 rounded-lg text-label-sm font-semibold border transition-all duration-200 flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm">{{ $catMeta->icon }}</span>
                {{ $catLabel }}
                <span :class="activeCat === '{{ $catKey }}' ? 'bg-white/25 text-white' : 'bg-surface-container text-on-surface-variant'"
                      class="text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[20px] text-center">{{ $catCount }}</span>
            </button>
            @endforeach
        </div>
        @endif

        {{-- Results count --}}
        <div class="text-center">
            <span class="text-xs text-on-surface-variant">
                <span x-text="Math.min((currentPage-1)*perPage+1, filtered.length)" class="font-bold text-primary"></span>–<span x-text="Math.min(currentPage*perPage, filtered.length)" class="font-bold text-primary"></span>
                /
                <span x-text="filtered.length" class="font-bold text-primary"></span>
                {{ __('messages.doc_results_count') }}
            </span>
        </div>
    </div>

    {{-- ─── Document List ─── --}}
    <div class="space-y-3">

        <template x-for="doc in paginated" :key="doc.id">
            <div class="group bg-white rounded-xl border border-outline-variant/60 overflow-hidden shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0">

                <div class="flex items-start gap-4 p-4 sm:p-5">

                    {{-- File Type Icon --}}
                    <div class="shrink-0 w-11 h-11 rounded-xl flex items-center justify-center bg-surface-container-low border border-outline-variant/40">
                        <span class="material-symbols-outlined text-xl text-on-surface-variant/60"
                              x-text="doc.file_icon"></span>
                    </div>

                    {{-- Main Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-start gap-x-3 gap-y-1 mb-1">
                            {{-- Category Badge --}}
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full border"
                                  :class="doc.cat_color">
                                <span class="material-symbols-outlined text-[11px]" x-text="doc.cat_icon"></span>
                                <span x-text="doc.cat_label"></span>
                            </span>
                            {{-- Doc Number --}}
                            <span x-show="doc.doc_number"
                                  class="text-[10px] text-on-surface-variant font-mono bg-surface-container px-2 py-0.5 rounded-md border border-outline-variant/50"
                                  x-text="doc.doc_number"></span>
                        </div>

                        {{-- Title --}}
                        <h3 x-text="doc.title"
                            class="text-body-md font-semibold text-on-surface leading-snug group-hover:text-primary transition-colors duration-200 line-clamp-2"></h3>

                        {{-- Description --}}
                        <p x-show="doc.description"
                           x-text="doc.description"
                           class="text-xs text-on-surface-variant mt-1 line-clamp-2 leading-relaxed"></p>

                        {{-- Meta Row --}}
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-2">
                            <span x-show="doc.dept_name"
                                  class="flex items-center gap-1 text-[10px] text-primary/80 font-semibold">
                                <span class="material-symbols-outlined text-[12px]">corporate_fare</span>
                                <span x-text="doc.dept_name"></span>
                            </span>
                            <span x-show="doc.issued_date"
                                  class="flex items-center gap-1 text-[10px] text-on-surface-variant">
                                <span class="material-symbols-outlined text-[12px]">calendar_today</span>
                                <span x-text="doc.issued_date"></span>
                            </span>
                            <span x-show="doc.file_size && doc.file_size !== '—'"
                                  class="flex items-center gap-1 text-[10px] text-on-surface-variant">
                                <span class="material-symbols-outlined text-[12px]">data_usage</span>
                                <span x-text="doc.file_size"></span>
                            </span>
                            <span x-show="doc.download_count > 0"
                                  class="flex items-center gap-1 text-[10px] text-indigo-600 font-semibold">
                                <span class="material-symbols-outlined text-[12px]">download</span>
                                <span x-text="doc.download_count.toLocaleString() + ' ຄັ້ງ'"></span>
                            </span>
                        </div>
                    </div>

                    {{-- Download Button --}}
                    <div class="shrink-0 self-center">
                        <template x-if="doc.has_file && doc.download_url">
                            <a :href="doc.download_url"
                               class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-xl text-label-sm font-bold hover:bg-primary-container transition-all btn-press shadow-sm shadow-primary/20">
                                <span class="material-symbols-outlined text-base">download</span>
                                <span class="hidden sm:inline">{{ __('messages.download') }}</span>
                            </a>
                        </template>
                        <template x-if="!doc.has_file || !doc.download_url">
                            <span class="flex items-center gap-1.5 px-3 py-2 bg-surface-container text-on-surface-variant rounded-xl text-label-sm border border-outline-variant/50 cursor-not-allowed opacity-60">
                                <span class="material-symbols-outlined text-base">block</span>
                                <span class="hidden sm:inline text-xs">N/A</span>
                            </span>
                        </template>
                    </div>
                </div>
            </div>
        </template>

    </div>

    {{-- ─── Pagination ─── --}}
    <div x-show="totalPages > 1 && filtered.length > 0" class="mt-8 flex justify-center">
        <div class="inline-flex items-center gap-1 px-3 py-2.5 bg-white rounded-2xl shadow-sm border border-outline-variant/60 flex-wrap justify-center">
            {{-- Prev --}}
            <button @click="if(currentPage > 1) currentPage--"
                    :disabled="currentPage === 1"
                    :class="currentPage === 1 ? 'opacity-30 cursor-not-allowed' : 'hover:bg-surface-container cursor-pointer'"
                    class="w-9 h-9 flex items-center justify-center rounded-full text-on-surface-variant transition-all">
                <span class="material-symbols-outlined text-base leading-none">chevron_left</span>
            </button>

            {{-- Page Numbers --}}
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

            {{-- Next --}}
            <button @click="if(currentPage < totalPages) currentPage++"
                    :disabled="currentPage === totalPages"
                    :class="currentPage === totalPages ? 'opacity-30 cursor-not-allowed' : 'hover:bg-surface-container cursor-pointer'"
                    class="w-9 h-9 flex items-center justify-center rounded-full text-on-surface-variant transition-all">
                <span class="material-symbols-outlined text-base leading-none">chevron_right</span>
            </button>

            {{-- Per page select --}}
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
        <p class="text-body-lg font-semibold text-on-surface-variant mb-2">{{ __('messages.no_doc_results_found') }}</p>
        <p class="text-sm text-on-surface-variant/70 mb-6">{{ __('messages.no_doc_results_hint') }}</p>
        <button @click="search = ''; activeDept = 'all'; activeCat = 'all'"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-xl text-label-md font-bold hover:bg-primary-container transition-all btn-press">
            <span class="material-symbols-outlined text-base">restart_alt</span>
            {{ __('messages.clear_filters') }}
        </button>
    </div>

</div>

@endsection
