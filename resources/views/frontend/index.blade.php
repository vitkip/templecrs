@extends('frontend.layout')

@section('content')

{{-- ══════════════════════════════════════════════════════════════
     HERO SECTION
══════════════════════════════════════════════════════════════ --}}
@if ($slides->count() > 0)
    {{-- Cinematic Image Slider (AlpineJS) --}}
    <section x-data="{
        activeSlide: 0,
        slidesCount: {{ $slides->count() }},
        autoplayInterval: null,
        progress: 0,
        progressRaf: null,
        progressStart: null,
        progressDuration: 6000,
        startAutoplay() {
            this.resetProgress();
            this.autoplayInterval = setInterval(() => { this.next(); }, this.progressDuration);
        },
        stopAutoplay() {
            clearInterval(this.autoplayInterval);
            cancelAnimationFrame(this.progressRaf);
        },
        resetProgress() {
            this.progress = 0; this.progressStart = null;
            cancelAnimationFrame(this.progressRaf);
            if (this.slidesCount <= 1) { this.progress = 100; return; }
            const tick = (ts) => {
                if (!this.progressStart) this.progressStart = ts;
                this.progress = Math.min(100, ((ts - this.progressStart) / this.progressDuration) * 100);
                if (this.progress < 100) this.progressRaf = requestAnimationFrame(tick);
            };
            this.progressRaf = requestAnimationFrame(tick);
        },
        next() { this.activeSlide = (this.activeSlide + 1) % this.slidesCount; this.resetProgress(); },
        prev() { this.activeSlide = (this.activeSlide - 1 + this.slidesCount) % this.slidesCount; this.resetProgress(); }
    }"
    x-init="if (slidesCount > 1) { startAutoplay() }"
    @mouseenter="stopAutoplay()"
    @mouseleave="if (slidesCount > 1) { startAutoplay() }"
    class="relative overflow-hidden h-[500px] lg:h-[640px]">

        {{-- Background images (Ken Burns per slide) --}}
        <div class="absolute inset-0">
            @foreach ($slides as $index => $slide)
                <div x-show="activeSlide === {{ $index }}"
                     x-cloak
                     x-transition:enter="transition-opacity ease-out duration-1000"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition-opacity ease-in duration-700"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="absolute inset-0 overflow-hidden">
                    <div class="absolute inset-0 bg-cover bg-center hero-ken-burns"
                         style="background-image: url('{{ $slide->image_url }}');"></div>
                </div>
            @endforeach
        </div>

        {{-- Cinematic multi-layer overlay --}}
        <div class="absolute inset-0 z-10 bg-gradient-to-t from-black/85 via-black/30 to-black/5"></div>
        <div class="absolute inset-0 z-10" style="background: radial-gradient(ellipse 90% 55% at 50% 115%, rgba(141,75,0,0.3) 0%, transparent 70%);"></div>

        {{-- Content — bottom-anchored, centered --}}
        <div class="relative z-20 h-full max-w-7xl mx-auto px-6 sm:px-8 lg:px-10 flex flex-col justify-end pb-[90px]">
            <div class="text-center">

                {{-- Eyebrow (static, animates once on load) --}}
                <div class="flex justify-center items-center gap-3 mb-5 hero-reveal">
                    <span class="block w-8 h-px bg-amber-400/70"></span>
                    <span class="text-amber-400/85 text-[9px] font-bold uppercase tracking-[0.28em]">
                        ອົງການພຸດທະສາສະໜາ &nbsp;·&nbsp; BUDDHIST ORGANIZATION
                    </span>
                    <span class="block w-8 h-px bg-amber-400/70"></span>
                </div>

                {{-- Per-slide: title / subtitle / button --}}
                @foreach ($slides as $index => $slide)
                    <div x-show="activeSlide === {{ $index }}"
                         x-cloak
                         x-transition:enter="transition ease-out duration-700"
                         x-transition:enter-start="opacity-0 translate-y-3"
                         x-transition:enter-end="opacity-100 translate-y-0">
                        @if ($slide->title)
                            <h1 class="font-bold text-white mb-3 leading-tight"
                                style="font-size: clamp(26px, 4.5vw, 52px); text-shadow: 0 2px 28px rgba(0,0,0,0.55);">
                                {{ $slide->title }}
                            </h1>
                        @endif
                        @if ($slide->subtitle)
                            <p class="text-white/75 mb-7 leading-relaxed mx-auto max-w-2xl"
                               style="font-size: clamp(14px, 1.7vw, 18px);">
                                {{ $slide->subtitle }}
                            </p>
                        @endif
                        @if ($slide->button_link)
                            <div class="mb-1">
                                <a href="{{ $slide->button_link }}"
                                   class="inline-flex items-center gap-2 px-7 py-3.5 bg-primary text-white rounded-lg font-bold text-sm hover:bg-primary-container transition-all duration-200 shadow-lg shadow-black/30 btn-press group">
                                    {{ $slide->button_text ?: __('messages.read_more') }}
                                    <span class="material-symbols-outlined text-base group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Progress bar (RAF-animated, smooth) --}}
            @if ($slides->count() > 1)
                <div class="mt-6 mx-auto w-64 sm:w-80 h-px bg-white/15 overflow-hidden rounded-full">
                    <div class="h-full bg-amber-400/65 rounded-full"
                         :style="'width:' + progress + '%'"></div>
                </div>
            @endif
        </div>

        {{-- Slide counter + nav (desktop, bottom-right) --}}
        @if ($slides->count() > 1)
            <div class="absolute bottom-[95px] right-8 lg:right-10 z-30 hidden lg:flex items-center gap-3">
                <div class="text-right leading-none">
                    <span x-text="String(activeSlide + 1).padStart(2, '0')"
                          class="text-white text-xl font-bold font-mono block leading-none"></span>
                    <span class="text-white/35 text-[9px] font-mono tracking-widest">/ {{ str_pad($slides->count(), 2, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="flex gap-1.5">
                    <button @click="prev()"
                            class="w-9 h-9 border border-white/20 text-white/55 hover:border-white/55 hover:text-white flex items-center justify-center rounded-lg backdrop-blur-sm transition-all cursor-pointer">
                        <span class="material-symbols-outlined" style="font-size:15px;">arrow_back</span>
                    </button>
                    <button @click="next()"
                            class="w-9 h-9 border border-white/20 text-white/55 hover:border-white/55 hover:text-white flex items-center justify-center rounded-lg backdrop-blur-sm transition-all cursor-pointer">
                        <span class="material-symbols-outlined" style="font-size:15px;">arrow_forward</span>
                    </button>
                </div>
            </div>

            {{-- Mobile arrows (vertical center) --}}
            <button @click="prev()"
                    class="lg:hidden absolute left-3 top-1/2 -translate-y-1/2 z-30 w-10 h-10 border border-white/25 text-white/65 hover:border-white/60 hover:text-white rounded-lg flex items-center justify-center backdrop-blur-sm transition-all cursor-pointer">
                <span class="material-symbols-outlined">chevron_left</span>
            </button>
            <button @click="next()"
                    class="lg:hidden absolute right-3 top-1/2 -translate-y-1/2 z-30 w-10 h-10 border border-white/25 text-white/65 hover:border-white/60 hover:text-white rounded-lg flex items-center justify-center backdrop-blur-sm transition-all cursor-pointer">
                <span class="material-symbols-outlined">chevron_right</span>
            </button>
        @endif

        {{-- Bottom Wave --}}
        <div class="absolute bottom-0 left-0 w-full z-20">
            <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" class="w-full">
                <path fill="#FFFBEB" d="M0,32L60,37.3C120,43,240,53,360,53.3C480,53,600,43,720,42.7C840,43,960,53,1080,53.3C1200,53,1320,43,1380,37.3L1440,32L1440,80L1380,80C1320,80,1200,80,1080,80C960,80,840,80,720,80C600,80,480,80,360,80C240,80,120,80,60,80L0,80Z"/>
            </svg>
        </div>
    </section>

@else
    {{-- Fallback: Sacred Gradient Hero --}}
    <section class="relative overflow-hidden h-[500px] lg:h-[640px]"
             style="background: linear-gradient(155deg, #1c0a00 0%, #3d1c00 28%, #7a3800 62%, #8d4b00 100%);">

        {{-- Ambient golden orbs --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute w-[700px] h-[700px] rounded-full hero-float-slow"
                 style="background: radial-gradient(circle, rgba(249,189,34,0.13) 0%, transparent 60%); top: -280px; right: -80px;"></div>
            <div class="absolute w-[500px] h-[500px] rounded-full hero-float-slower"
                 style="background: radial-gradient(circle, rgba(255,183,125,0.1) 0%, transparent 60%); bottom: -100px; left: -80px;"></div>
            <div class="absolute w-[300px] h-[300px] rounded-full hero-float-medium"
                 style="background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 60%); top: 25%; left: 30%;"></div>
        </div>

        {{-- Sacred geometry grid --}}
        <div class="absolute inset-0" style="opacity: 0.038;">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="dhamma-grid" x="0" y="0" width="64" height="64" patternUnits="userSpaceOnUse">
                        <path d="M 64 0 L 0 0 0 64" fill="none" stroke="#f9bd22" stroke-width="0.5"/>
                        <circle cx="0" cy="0" r="1.5" fill="#f9bd22"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#dhamma-grid)"/>
            </svg>
        </div>

        {{-- Cinematic overlay layers --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
        <div class="absolute inset-0" style="background: radial-gradient(ellipse 90% 55% at 50% 115%, rgba(141,75,0,0.32) 0%, transparent 70%);"></div>

        <div class="relative z-10 h-full max-w-7xl mx-auto px-6 sm:px-8 lg:px-10 flex flex-col justify-end pb-24 text-center">

            {{-- Eyebrow --}}
            <div class="flex justify-center items-center gap-3 mb-5 hero-reveal">
                <span class="block w-8 h-px bg-amber-400/70"></span>
                <span class="text-amber-400/85 text-[9px] font-bold uppercase tracking-[0.28em]">
                    ອົງການພຸດທະສາສະໜາ &nbsp;·&nbsp; BUDDHIST ORGANIZATION
                </span>
                <span class="block w-8 h-px bg-amber-400/70"></span>
            </div>

            <h1 class="font-bold text-white mb-3 leading-tight hero-reveal"
                style="font-size: clamp(26px, 4.5vw, 52px); text-shadow: 0 2px 28px rgba(0,0,0,0.55); animation-delay: 0.12s;">
                {{ $orgName }}
            </h1>
            <p class="text-white/65 mb-8 hero-reveal"
               style="font-size: clamp(13px, 1.6vw, 17px); animation-delay: 0.22s;">
                {{ $orgNameEn }}
            </p>

            <div class="flex flex-col sm:flex-row gap-3 justify-center hero-reveal" style="animation-delay: 0.33s;">
                <a href="{{ route('frontend.news') }}"
                   class="inline-flex items-center justify-center gap-2 px-7 py-3.5 bg-primary text-white rounded-lg font-bold text-sm hover:bg-primary-container transition-all duration-200 shadow-lg shadow-black/40 btn-press group">
                    <span class="material-symbols-outlined text-base">newspaper</span>
                    {{ __('messages.latest_news') }}
                    <span class="material-symbols-outlined text-base group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
                </a>
                <a href="#documents"
                   class="inline-flex items-center justify-center gap-2 px-7 py-3.5 border border-white/25 text-white/90 rounded-lg font-bold text-sm hover:bg-white/10 transition-all duration-200 backdrop-blur-sm btn-press">
                    <span class="material-symbols-outlined text-base">description</span>
                    {{ __('messages.documents_nav') }}
                </a>
            </div>
        </div>

        {{-- Bottom Wave --}}
        <div class="absolute bottom-0 left-0 w-full z-10">
            <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" class="w-full">
                <path fill="#FFFBEB" d="M0,32L60,37.3C120,43,240,53,360,53.3C480,53,600,43,720,42.7C840,43,960,53,1080,53.3C1200,53,1320,43,1380,37.3L1440,32L1440,80L1380,80C1320,80,1200,80,1080,80C960,80,840,80,720,80C600,80,480,80,360,80C240,80,120,80,60,80L0,80Z"/>
            </svg>
        </div>
    </section>
@endif

{{-- ══════════════════════════════════════════════════════════════
     STATS BAR
══════════════════════════════════════════════════════════════ --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 relative z-20 mb-16">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="glass-card p-5 rounded-xl border border-outline-variant text-center shadow-md hover:shadow-lg hover:-translate-y-1 hover:border-primary/30 transition-all duration-300 cursor-pointer">
            <span class="material-symbols-outlined text-3xl text-primary mb-2 block">newspaper</span>
            <p class="text-headline-md text-on-surface font-bold">{{ $news->count() }}</p>
            <p class="text-[10px] text-on-surface-variant uppercase tracking-widest font-semibold">{{ __('messages.news') }}</p>
        </div>
        <div class="glass-card p-5 rounded-xl border border-outline-variant text-center shadow-md hover:shadow-lg hover:-translate-y-1 hover:border-tertiary/30 transition-all duration-300 cursor-pointer">
            <span class="material-symbols-outlined text-3xl text-tertiary mb-2 block">group</span>
            <p class="text-headline-md text-on-surface font-bold">{{ $personnel->count() }}</p>
            <p class="text-[10px] text-on-surface-variant uppercase tracking-widest font-semibold">{{ __('messages.personnel') }}</p>
        </div>
        <div class="glass-card p-5 rounded-xl border border-outline-variant text-center shadow-md hover:shadow-lg hover:-translate-y-1 hover:border-secondary/30 transition-all duration-300 cursor-pointer">
            <span class="material-symbols-outlined text-3xl text-secondary mb-2 block">description</span>
            <p class="text-headline-md text-on-surface font-bold">{{ $documents->count() }}</p>
            <p class="text-[10px] text-on-surface-variant uppercase tracking-widest font-semibold">{{ __('messages.documents_nav') }}</p>
        </div>
        <div class="glass-card p-5 rounded-xl border border-outline-variant text-center shadow-md hover:shadow-lg hover:-translate-y-1 hover:border-amber-500/30 transition-all duration-300 cursor-pointer">
            <span class="material-symbols-outlined text-3xl text-amber-600 mb-2 block">verified</span>
            <p class="text-headline-md text-on-surface font-bold">{{ $personnel->where('gender', 'monk')->count() }}</p>
            <p class="text-[10px] text-on-surface-variant uppercase tracking-widest font-semibold">{{ __('messages.stat_monks') }}</p>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════
     NEWS SECTION
══════════════════════════════════════════════════════════════ --}}
<section id="news" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-20 scroll-mt-24">
    {{-- Section Header --}}
    <div class="flex items-end justify-between mb-8">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="w-8 h-1 bg-primary rounded-full"></span>
                <span class="text-[10px] font-bold text-primary uppercase tracking-widest">{{ __('messages.news_activities') }}</span>
            </div>
            <h2 class="text-headline-lg text-on-surface">{{ __('messages.news_activities') }}</h2>
        </div>
    </div>

    @if ($news->count() > 0)
        {{-- Featured + Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Featured Card (Large) --}}
            @php $featured = $featuredNews->first() ?? $news->first(); @endphp
            <div class="lg:col-span-2 group">
                <a href="{{ route('frontend.news.show', $featured->id) }}" class="block">
                    <div class="relative bg-white rounded-2xl border border-outline-variant overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">
                        {{-- Image --}}
                        <div class="relative h-64 lg:h-80 overflow-hidden">
                            @if ($featured->cover_image_url)
                                <img src="{{ $featured->cover_image_url }}" alt="{{ $featured->title_lo }}"
                                     loading="lazy"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-8xl text-on-surface-variant/20">newspaper</span>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>

                            {{-- Featured Badge --}}
                            @if ($featured->is_featured)
                                <div class="absolute top-4 left-4">
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-tertiary text-white text-[10px] font-bold rounded-full shadow-lg">
                                        <span class="material-symbols-outlined text-xs filled">star</span>
                                        {{ __('messages.featured') }}
                                    </span>
                                </div>
                            @endif

                            {{-- Title Overlay --}}
                            <div class="absolute bottom-0 left-0 right-0 p-6">
                                <p class="text-xs text-white/80 mb-1">
                                    <span class="material-symbols-outlined text-xs align-middle">calendar_today</span>
                                    {{ $featured->published_date_formatted }}
                                </p>
                                <h3 class="text-headline-sm text-white leading-tight">{{ $featured->title }}</h3>
                                @if (app()->getLocale() === 'lo' && $featured->title_en)
                                    <p class="text-sm text-white/70 mt-1">{{ $featured->title_en }}</p>
                                @endif
                            </div>
                        </div>

                        {{-- Excerpt --}}
                        @if ($featured->excerpt)
                            <div class="p-5">
                                <p class="text-body-md text-on-surface-variant line-clamp-2">
                                    {{ $featured->excerpt }}
                                </p>
                            </div>
                        @endif
                    </div>
                </a>
            </div>

            {{-- Small Cards --}}
            <div class="space-y-4">
                @foreach ($news->skip(1)->take(3) as $item)
                    <a href="{{ route('frontend.news.show', $item->id) }}" class="block group">
                        <div class="bg-white rounded-xl border border-outline-variant overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300">
                            <div class="flex gap-4 p-4">
                                {{-- Thumbnail --}}
                                <div class="w-20 h-16 rounded-lg overflow-hidden shrink-0">
                                    @if ($item->cover_image_url)
                                        <img src="{{ $item->cover_image_url }}" alt="" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                                    @else
                                        <div class="w-full h-full bg-surface-container flex items-center justify-center">
                                            <span class="material-symbols-outlined text-on-surface-variant/30">image</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[10px] text-on-surface-variant mb-0.5">
                                        <span class="material-symbols-outlined text-[10px] align-middle">calendar_today</span>
                                        {{ $item->published_date_formatted }}
                                    </p>
                                    <h4 class="text-body-md font-bold text-on-surface line-clamp-2 group-hover:text-primary transition-colors">
                                        {{ $item->title }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @else
        <div class="text-center py-16 bg-white rounded-2xl border border-outline-variant">
            <span class="material-symbols-outlined text-6xl text-on-surface-variant/20 mb-4 block">newspaper</span>
            <p class="text-body-lg text-on-surface-variant">{{ __('messages.no_news_yet') }}</p>
        </div>
    @endif
</section>

{{-- ══════════════════════════════════════════════════════════════
     PERSONNEL SECTION
     ══════════════════════════════════════════════════════════════ --}}
<section id="personnel" class="py-20 scroll-mt-24" style="background: linear-gradient(180deg, #ffffff 0%, #fffbf2 100%); border-top: 1px solid var(--color-outline-variant); border-bottom: 1px solid var(--color-outline-variant);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section Header --}}
        <div class="text-center mb-16">
            <div class="flex items-center gap-2 justify-center mb-2">
                <span class="w-8 h-1 bg-tertiary rounded-full"></span>
                <span class="text-[10px] font-bold text-tertiary uppercase tracking-widest">{{ __('messages.our_people') }}</span>
                <span class="w-8 h-1 bg-tertiary rounded-full"></span>
            </div>
            <h2 class="text-headline-lg text-on-surface mb-3">{{ __('messages.personnel') }}</h2>
            <p class="text-body-lg text-on-surface-variant max-w-2xl mx-auto">{{ __('messages.personnel_subtitle') }}</p>
        </div>

        @if ($personnel->count() > 0)
        {{-- View All Button (top) --}}
        <div class="flex justify-end mb-6">
            <a href="{{ route('frontend.personnel') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-tertiary text-white rounded-xl text-label-md font-bold hover:bg-tertiary/90 transition-all shadow-sm btn-press">
                <span class="material-symbols-outlined text-base">group</span>
                {{ __('messages.view_all_personnel') }}
                <span class="material-symbols-outlined text-base">arrow_forward</span>
            </a>
        </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($personnel as $person)
                    <div class="group relative bg-white rounded-2xl border border-outline-variant/60 overflow-hidden shadow-sm hover:shadow-[0_12px_40px_rgba(141,75,0,0.08)] transition-all duration-500 hover:-translate-y-1.5 cursor-pointer flex flex-col">

                        {{-- Card Header (gradient banner) --}}
                        <div class="relative h-16 shrink-0 overflow-hidden {{ $person->isMonk() ? 'bg-gradient-to-br from-amber-200 via-amber-100 to-yellow-50' : 'bg-gradient-to-br from-slate-100 via-surface-container to-surface-container-low' }}">
                            <div class="absolute -top-4 -right-4 w-16 h-16 rounded-full opacity-20 {{ $person->isMonk() ? 'bg-amber-400' : 'bg-slate-300' }}"></div>
                            {{-- Gender Badge --}}
                            <div class="absolute top-2 right-2 z-10">
                                @php $badge = $person->gender_badge; @endphp
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold tracking-wide {{ $badge['class'] }} shadow-md">
                                    {{ $badge['label'] }}
                                </span>
                            </div>
                        </div>

                        {{-- Circular Avatar --}}
                        <div class="relative flex justify-center -mt-10 px-4 shrink-0 z-10">
                            <div class="relative">
                                @if ($person->photo_url)
                                    <img src="{{ Storage::url($person->photo_url) }}" alt="{{ $person->display_name }}"
                                         loading="lazy"
                                         class="w-20 h-20 rounded-full object-cover ring-4 ring-white shadow-lg group-hover:scale-105 transition-transform duration-500 ease-out" />
                                @else
                                    <div class="w-20 h-20 rounded-full ring-4 ring-white shadow-lg flex items-center justify-center {{ $person->isMonk() ? 'bg-gradient-to-br from-amber-100 to-amber-200' : 'bg-gradient-to-br from-surface-container to-surface-container-high' }}">
                                        <span class="material-symbols-outlined text-4xl {{ $person->isMonk() ? 'text-amber-400/60' : 'text-on-surface-variant/25' }}">person</span>
                                    </div>
                                @endif
                                {{-- Monk golden ring --}}
                                @if ($person->isMonk())
                                    <div class="absolute inset-0 rounded-full ring-2 ring-amber-400 ring-offset-2 ring-offset-white pointer-events-none"></div>
                                @endif
                            </div>
                        </div>

                        {{-- Info --}}
                        <div class="px-4 pb-5 pt-3 text-center flex flex-col flex-1">
                            @if ($person->display_title)
                                <p class="text-[10px] text-primary font-bold uppercase tracking-widest mb-1">{{ $person->display_title }}</p>
                            @endif
                            <h3 class="text-body-md font-bold text-on-surface leading-snug mb-1 group-hover:text-primary transition-colors duration-200">
                                {{ $person->display_name }}
                            </h3>
                            @if ($person->display_position)
                                <p class="text-[11px] text-on-surface-variant font-medium">{{ $person->display_position }}</p>
                            @endif
                            @if ($person->department)
                                <div class="mt-auto pt-3">
                                    <span class="text-[10px] text-primary/80 inline-flex items-center gap-1 bg-primary/5 px-2.5 py-1 rounded-md border border-primary/10 font-semibold">
                                        <span class="material-symbols-outlined text-xs">corporate_fare</span>
                                        {{ $person->department->name }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            {{-- View All CTA at bottom --}}
            <div class="text-center mt-10">
                <a href="{{ route('frontend.personnel') }}"
                   class="inline-flex items-center gap-2 px-7 py-3.5 bg-white border-2 border-tertiary text-tertiary rounded-xl text-label-md font-bold hover:bg-tertiary hover:text-white transition-all shadow-sm btn-press group">
                    <span class="material-symbols-outlined text-base">group</span>
                    {{ __('messages.view_all_personnel') }}
                    <span class="material-symbols-outlined text-base group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
                </a>
            </div>
        @else
            <div class="text-center py-16">
                <span class="material-symbols-outlined text-6xl text-on-surface-variant/20 mb-4 block">group</span>
                <p class="text-body-lg text-on-surface-variant">{{ __('messages.no_personnel_yet') }}</p>
            </div>
        @endif
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════
     DOCUMENTS SECTION
══════════════════════════════════════════════════════════════ --}}
<section id="documents" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 scroll-mt-24">
    {{-- Section Header --}}
    <div class="text-center mb-12">
        <div class="flex items-center gap-2 justify-center mb-2">
            <span class="w-8 h-1 bg-secondary rounded-full"></span>
            <span class="text-[10px] font-bold text-secondary uppercase tracking-widest">{{ __('messages.document_library') }}</span>
            <span class="w-8 h-1 bg-secondary rounded-full"></span>
        </div>
        <h2 class="text-headline-lg text-on-surface mb-2">{{ __('messages.documents_nav') }}</h2>
        <p class="text-body-md text-on-surface-variant">{{ __('messages.documents_subtitle') }}</p>
    </div>

    @if ($documents->count() > 0)
        {{-- View All Button (top) --}}
        <div class="flex justify-end mb-4">
            <a href="{{ route('frontend.documents') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-outline-variant text-label-sm text-on-surface-variant hover:border-primary/50 hover:text-primary hover:bg-primary/5 transition-all font-semibold">
                <span class="material-symbols-outlined text-sm">open_in_new</span>
                {{ __('messages.view_all_documents') }}
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ($documents as $doc)
                <div class="group bg-white rounded-xl border border-outline-variant/60 p-6 shadow-sm hover:shadow-[0_8px_30px_rgba(0,0,0,0.03)] hover:-translate-y-0.5 hover:border-primary/30 transition-all duration-300 flex items-start gap-5 cursor-pointer">
                    {{-- File Icon --}}
                    <div class="w-14 h-14 rounded-xl bg-primary/5 text-primary flex items-center justify-center shrink-0 group-hover:bg-primary/10 transition-colors duration-300">
                        <span class="material-symbols-outlined text-3xl">{{ $doc->file_icon }}</span>
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-4 mb-1.5">
                            <div class="min-w-0">
                                <h4 class="text-body-lg font-bold text-on-surface group-hover:text-primary transition-colors duration-200 truncate">
                                    {{ $doc->title }}
                                </h4>
                                @if (app()->getLocale() === 'lo' && $doc->title_en)
                                    <p class="text-xs text-on-surface-variant truncate mt-0.5 font-medium">{{ $doc->title_en }}</p>
                                @endif
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-bold border shrink-0 tracking-wide {{ $doc->category_color }} shadow-sm">
                                <span class="material-symbols-outlined text-[12px]">{{ $doc->category_icon }}</span>
                                {{ $doc->category_label }}
                            </span>
                        </div>

                        <div class="flex items-center gap-3 text-xs text-on-surface-variant/80 mt-2 font-medium">
                            @if ($doc->doc_number)
                                <span class="font-mono bg-surface-container/50 px-2 py-0.5 rounded border border-outline-variant/30">{{ $doc->doc_number }}</span>
                                <span class="w-px h-3 bg-outline-variant/60"></span>
                            @endif
                            @if ($doc->issued_date)
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-xs">calendar_today</span>
                                    {{ $doc->issued_date->format('d/m/Y') }}
                                </span>
                            @endif
                            @if ($doc->file_path)
                                <span class="w-px h-3 bg-outline-variant/60"></span>
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-xs">attachment</span>
                                    {{ $doc->file_size_formatted }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Download Button (44x44px for a11y touch targets) --}}
                    @if ($doc->file_path)
                        <a href="{{ route('frontend.document.download', $doc->id) }}"
                           class="shrink-0 w-11 h-11 rounded-xl bg-primary/5 text-primary flex items-center justify-center hover:bg-primary hover:text-white transition-all shadow-sm hover:shadow-md btn-press cursor-pointer"
                           title="{{ __('messages.download') }}">
                            <span class="material-symbols-outlined text-xl">download</span>
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
        {{-- View All CTA at bottom --}}
        <div class="text-center mt-10">
            <a href="{{ route('frontend.documents') }}"
               class="inline-flex items-center gap-2 px-7 py-3 bg-primary text-white rounded-xl text-label-md font-bold hover:bg-primary-container transition-all btn-press shadow-md shadow-primary/20">
                <span class="material-symbols-outlined text-base">folder_open</span>
                {{ __('messages.view_all_documents') }}
            </a>
        </div>
    @else
        <div class="text-center py-16 bg-white rounded-2xl border border-outline-variant">
            <span class="material-symbols-outlined text-6xl text-on-surface-variant/20 mb-4 block">folder_off</span>
            <p class="text-body-lg text-on-surface-variant">{{ __('messages.no_documents_yet') }}</p>
        </div>
    @endif
</section>

@endsection
