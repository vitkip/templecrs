@extends('frontend.layout')

@section('content')

@php
    $locale = app()->getLocale();

    $personnelJson = $personnel->map(function ($p) use ($locale) {
        return [
            'id'               => $p->id,
            'name'             => $p->display_name,
            'title'            => $p->display_title,
            'position'         => $p->display_position,
            'gender'           => $p->gender,
            'gender_badge'     => $p->gender_badge,
            'photo_url'        => $p->photo_url ? \Illuminate\Support\Facades\Storage::url($p->photo_url) : null,
            'dept_id'          => (string) ($p->department_id ?? ''),
            'dept_name'        => $p->department?->name ?? '',
            'bio'              => $p->display_bio,
            'education'        => $locale === 'lo' ? ($p->education_lo ?? $p->education_en) : ($p->education_en ?? $p->education_lo),
            'current_temple'   => $locale === 'lo' ? ($p->current_temple_lo ?? $p->current_temple_en) : ($p->current_temple_en ?? $p->current_temple_lo),
            'date_of_ordination' => $p->date_of_ordination?->format('d/m/Y'),
            'pansa'            => $p->pansa,
            'email'            => $p->email,
            'phone'            => $p->phone,
            'facebook'         => $p->facebook,
            'search_text'      => strtolower(implode(' ', array_filter([
                $p->name_lo, $p->name_en,
                $p->position_lo, $p->position_en,
                $p->title_lo, $p->title_en,
                $p->department?->name_lo, $p->department?->name_en,
            ]))),
        ];
    })->values()->toArray();
@endphp

{{-- ══════════════════════════════════════════════════════════════
     PAGE HEADER
══════════════════════════════════════════════════════════════ --}}
<div class="relative overflow-hidden" style="background: linear-gradient(135deg, #6b4c00 0%, #545f73 60%, #765700 100%);">
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
            <span class="text-white/90 font-medium">{{ __('messages.personnel') }}</span>
        </nav>

        <div class="flex items-center gap-2 mb-2 justify-center">
            <span class="w-8 h-1 bg-amber-400 rounded-full"></span>
            <span class="text-[10px] font-bold text-amber-400 uppercase tracking-widest">{{ __('messages.our_people') }}</span>
            <span class="w-8 h-1 bg-amber-400 rounded-full"></span>
        </div>
        <h1 class="text-headline-lg lg:text-[40px] font-bold text-white text-center mb-2">
            {{ __('messages.personnel_directory') }}
        </h1>
        <p class="text-body-md text-white/70 text-center max-w-2xl mx-auto">
            {{ __('messages.personnel_dir_subtitle') }}
        </p>

        {{-- Stats chips --}}
        <div class="flex flex-wrap items-center justify-center gap-3 mt-6">
            <div class="flex items-center gap-1.5 px-4 py-2 bg-white/10 rounded-full border border-white/15 backdrop-blur-sm">
                <span class="material-symbols-outlined text-sm text-amber-300">group</span>
                <span class="text-sm font-bold text-white">{{ $personnel->count() }}</span>
                <span class="text-xs text-white/60">{{ __('messages.personnel') }}</span>
            </div>
            <div class="flex items-center gap-1.5 px-4 py-2 bg-white/10 rounded-full border border-white/15 backdrop-blur-sm">
                <span class="material-symbols-outlined text-sm text-amber-300">verified</span>
                <span class="text-sm font-bold text-white">{{ $personnel->where('gender', 'monk')->count() }}</span>
                <span class="text-xs text-white/60">{{ __('messages.stat_monks') }}</span>
            </div>
            <div class="flex items-center gap-1.5 px-4 py-2 bg-white/10 rounded-full border border-white/15 backdrop-blur-sm">
                <span class="material-symbols-outlined text-sm text-amber-300">corporate_fare</span>
                <span class="text-sm font-bold text-white">{{ $departments->count() }}</span>
                <span class="text-xs text-white/60">{{ __('messages.departments') }}</span>
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
        activeDept: 'all',
        personnel: @js($personnelJson),
        perPage: 9,
        currentPage: 1,
        perPageOptions: [9, 18, 27],
        get filtered() {
            const q = this.search.toLowerCase().trim();
            return this.personnel.filter(p => {
                const deptOk = this.activeDept === 'all' || p.dept_id === this.activeDept;
                const searchOk = !q || p.search_text.includes(q);
                return deptOk && searchOk;
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
            this.$watch('perPage',    () => { this.currentPage = 1; });
        }
     }">

    {{-- ─── Search + Department Filter Bar ─── --}}
    <div class="mb-8 space-y-4">
        {{-- Search Input --}}
        <div class="relative max-w-xl mx-auto">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-xl text-on-surface-variant/50 pointer-events-none">search</span>
            <input
                x-model="search"
                type="text"
                placeholder="{{ __('messages.search_placeholder') }}"
                class="w-full pl-12 pr-12 py-3.5 rounded-xl border border-outline-variant bg-white text-body-md text-on-surface placeholder:text-on-surface-variant/50 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 shadow-sm transition-all"
            />
            <button x-show="search"
                    @click="search = ''"
                    class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 rounded-lg text-on-surface-variant hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined text-base">close</span>
            </button>
        </div>

        {{-- Department Filter Tabs --}}
        @if($departments->count() > 0)
        <div class="flex flex-wrap gap-2 justify-center">
            <button @click="activeDept = 'all'"
                    :class="activeDept === 'all'
                        ? 'bg-primary text-white border-primary shadow-md shadow-primary/20'
                        : 'bg-white text-on-surface-variant border-outline-variant hover:border-primary/40 hover:text-primary'"
                    class="px-4 py-2 rounded-lg text-label-sm font-semibold border transition-all duration-200 flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm">apps</span>
                {{ __('messages.all_departments') }}
                <span x-text="personnel.length"
                      :class="activeDept === 'all' ? 'bg-white/25 text-white' : 'bg-surface-container text-on-surface-variant'"
                      class="text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[20px] text-center"></span>
            </button>

            @foreach($departments as $dept)
            @php
                $deptPersonnelCount = $personnel->where('department_id', $dept->id)->count();
            @endphp
            <button @click="activeDept = '{{ $dept->id }}'"
                    :class="activeDept === '{{ $dept->id }}'
                        ? 'bg-primary text-white border-primary shadow-md shadow-primary/20'
                        : 'bg-white text-on-surface-variant border-outline-variant hover:border-primary/40 hover:text-primary'"
                    class="px-4 py-2 rounded-lg text-label-sm font-semibold border transition-all duration-200 flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm">corporate_fare</span>
                {{ $dept->name }}
                <span :class="activeDept === '{{ $dept->id }}' ? 'bg-white/25 text-white' : 'bg-surface-container text-on-surface-variant'"
                      class="text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[20px] text-center">{{ $deptPersonnelCount }}</span>
            </button>
            @endforeach
        </div>
        @endif

        {{-- Results Count --}}
        <div class="text-center">
            <span class="text-xs text-on-surface-variant">
                <span x-text="Math.min((currentPage-1)*perPage+1, filtered.length)" class="font-bold text-primary"></span>–<span x-text="Math.min(currentPage*perPage, filtered.length)" class="font-bold text-primary"></span>
                /
                <span x-text="filtered.length" class="font-bold text-primary"></span>
                {{ __('messages.results_count') }}
            </span>
        </div>
    </div>

    {{-- ─── Personnel Grid ─── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <template x-for="person in paginated" :key="person.id">
            <div class="group relative bg-white rounded-2xl border border-outline-variant/60 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-400 hover:-translate-y-1.5 flex flex-col"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">

                {{-- Card Header (gradient banner) --}}
                <div class="relative h-24 shrink-0 overflow-hidden"
                     :class="person.gender === 'monk'
                        ? 'bg-gradient-to-br from-amber-200 via-amber-100 to-yellow-50'
                        : 'bg-gradient-to-br from-slate-100 via-surface-container to-surface-container-low'">
                    {{-- Decorative circles --}}
                    <div class="absolute -top-6 -right-6 w-24 h-24 rounded-full opacity-20"
                         :class="person.gender === 'monk' ? 'bg-amber-400' : 'bg-slate-300'"></div>
                    <div class="absolute -bottom-4 -left-4 w-16 h-16 rounded-full opacity-15"
                         :class="person.gender === 'monk' ? 'bg-amber-500' : 'bg-slate-400'"></div>

                    {{-- Gender Badge --}}
                    <div class="absolute top-3 right-3 z-10">
                        <span class="px-2.5 py-1 rounded-full text-[9px] font-bold tracking-wide shadow-md"
                              :class="person.gender_badge.class"
                              x-text="person.gender_badge.label"></span>
                    </div>
                </div>

                {{-- Circular Avatar (overlapping header) --}}
                <div class="relative flex justify-center -mt-12 px-4 shrink-0 z-10">
                    <div class="relative">
                        <template x-if="person.photo_url">
                            <img :src="person.photo_url" :alt="person.name"
                                 loading="lazy"
                                 class="w-24 h-24 rounded-full object-cover ring-4 ring-white shadow-lg group-hover:scale-105 transition-transform duration-500 ease-out" />
                        </template>
                        <template x-if="!person.photo_url">
                            <div class="w-24 h-24 rounded-full ring-4 ring-white shadow-lg flex items-center justify-center"
                                 :class="person.gender === 'monk'
                                    ? 'bg-gradient-to-br from-amber-100 to-amber-200'
                                    : 'bg-gradient-to-br from-surface-container to-surface-container-high'">
                                <span class="material-symbols-outlined text-5xl"
                                      :class="person.gender === 'monk' ? 'text-amber-400/60' : 'text-on-surface-variant/25'">person</span>
                            </div>
                        </template>
                        {{-- Monk golden ring --}}
                        <template x-if="person.gender === 'monk'">
                            <div class="absolute inset-0 rounded-full ring-2 ring-amber-400 ring-offset-2 ring-offset-white pointer-events-none"></div>
                        </template>
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="px-5 pb-5 pt-3 flex flex-col flex-1 gap-3">

                    {{-- Name + Position (centered) --}}
                    <div class="text-center">
                        <p x-show="person.title"
                           x-text="person.title"
                           class="text-[10px] text-primary font-bold uppercase tracking-widest mb-1"></p>
                        <h3 x-text="person.name"
                            class="text-body-lg font-bold text-on-surface leading-snug group-hover:text-primary transition-colors duration-200"></h3>
                        <p x-show="person.position"
                           x-text="person.position"
                           class="text-xs text-on-surface-variant mt-0.5 font-medium"></p>
                    </div>

                    {{-- Department (centered) --}}
                    <div x-show="person.dept_name" class="flex justify-center">
                        <span class="inline-flex items-center gap-1 text-[10px] text-primary/80 bg-primary/5 px-2.5 py-1 rounded-md border border-primary/10 font-semibold">
                            <span class="material-symbols-outlined text-xs">corporate_fare</span>
                            <span x-text="person.dept_name"></span>
                        </span>
                    </div>

                    {{-- Divider --}}
                    <div class="border-t border-outline-variant/40"></div>

                    {{-- Bio --}}
                    <p x-show="person.bio"
                       x-text="person.bio"
                       class="text-xs text-on-surface-variant leading-relaxed line-clamp-3"></p>

                    {{-- Education --}}
                    <div x-show="person.education" class="flex items-start gap-1.5 text-xs text-on-surface-variant">
                        <span class="material-symbols-outlined text-sm text-secondary shrink-0 mt-px">school</span>
                        <span x-text="person.education" class="line-clamp-2"></span>
                    </div>

                    {{-- Monk-specific info --}}
                    <template x-if="person.gender === 'monk' && (person.date_of_ordination || person.pansa || person.current_temple)">
                        <div class="rounded-lg bg-amber-50 border border-amber-100 px-3 py-2.5 space-y-1.5">
                            <div x-show="person.date_of_ordination" class="flex items-center gap-2 text-xs text-amber-800">
                                <span class="material-symbols-outlined text-sm text-amber-600">event</span>
                                <span class="font-medium">{{ __('messages.date_of_ordination') }}:</span>
                                <span x-text="person.date_of_ordination"></span>
                            </div>
                            <div x-show="person.pansa" class="flex items-center gap-2 text-xs text-amber-800">
                                <span class="material-symbols-outlined text-sm text-amber-600">nights_stay</span>
                                <span class="font-medium">{{ __('messages.pansa_label') }}:</span>
                                <span x-text="person.pansa + ' {{ __('messages.pansa_label') }}'"></span>
                            </div>
                            <div x-show="person.current_temple" class="flex items-center gap-2 text-xs text-amber-800">
                                <span class="material-symbols-outlined text-sm text-amber-600">temple_buddhist</span>
                                <span class="font-medium">{{ __('messages.current_temple') }}:</span>
                                <span x-text="person.current_temple" class="line-clamp-1"></span>
                            </div>
                        </div>
                    </template>

                    {{-- Contact Row (pushed to bottom, centered) --}}
                    <div class="mt-auto">
                        <div x-show="person.email || person.phone || person.facebook"
                             class="pt-3 border-t border-outline-variant/40 flex items-center justify-center gap-2">
                            <a x-show="person.email"
                               :href="'mailto:' + (person.email || '')"
                               @click.stop
                               class="w-9 h-9 rounded-full bg-primary/5 text-primary flex items-center justify-center hover:bg-primary hover:text-white transition-all shadow-sm text-sm"
                               title="Email">
                                <span class="material-symbols-outlined text-base">mail</span>
                            </a>
                            <a x-show="person.phone"
                               :href="'tel:' + (person.phone || '')"
                               @click.stop
                               class="w-9 h-9 rounded-full bg-primary/5 text-primary flex items-center justify-center hover:bg-primary hover:text-white transition-all shadow-sm"
                               title="Phone">
                                <span class="material-symbols-outlined text-base">phone</span>
                            </a>
                            <a x-show="person.facebook"
                               :href="person.facebook || '#'"
                               target="_blank"
                               rel="noopener noreferrer"
                               @click.stop
                               class="w-9 h-9 rounded-full bg-primary/5 text-primary flex items-center justify-center hover:bg-primary hover:text-white transition-all shadow-sm"
                               title="Facebook">
                                <span class="material-symbols-outlined text-base">share</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
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
        <p class="text-body-lg font-semibold text-on-surface-variant mb-2">{{ __('messages.no_results_found') }}</p>
        <p class="text-sm text-on-surface-variant/70 mb-6">{{ __('messages.search_placeholder') }}</p>
        <button @click="search = ''; activeDept = 'all'"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-xl text-label-md font-bold hover:bg-primary-container transition-all btn-press">
            <span class="material-symbols-outlined text-base">restart_alt</span>
            {{ __('messages.clear_filters') }}
        </button>
    </div>

</div>

@endsection
