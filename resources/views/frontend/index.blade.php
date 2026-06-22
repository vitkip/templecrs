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
                    <img src="{{ $slide->image_url }}" alt="{{ $slide->title ?? '' }}"
                         loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                         fetchpriority="{{ $loop->first ? 'high' : 'auto' }}"
                         class="absolute inset-0 w-full h-full object-cover object-center hero-ken-burns" />
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
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20 mb-20">
    <div class="rounded-2xl overflow-hidden shadow-[0_16px_56px_rgba(141,75,0,0.12),0_2px_8px_rgba(0,0,0,0.06)] border border-amber-100/60"
         style="background: rgba(255,255,255,0.97); backdrop-filter: blur(20px);">
        {{-- Top gold accent line --}}
        <div class="h-px bg-gradient-to-r from-primary/20 via-amber-400/60 to-tertiary/20"></div>
        <div class="grid grid-cols-2 lg:grid-cols-4 divide-x divide-y lg:divide-y-0 divide-amber-100/50">
            <div class="px-6 py-7 text-center cursor-default hover:bg-primary/[0.03] transition-colors duration-300">
                <p class="font-bold text-primary leading-none tracking-tight mb-2.5"
                   style="font-size: clamp(32px, 3.5vw, 44px);">{{ $statsNewsCount }}</p>
                <div class="flex items-center justify-center gap-1.5">
                    <span class="material-symbols-outlined text-[12px] text-primary/35">newspaper</span>
                    <span class="text-[9px] text-on-surface-variant/65 uppercase tracking-[0.22em] font-bold">{{ __('messages.news') }}</span>
                </div>
            </div>
            <div class="px-6 py-7 text-center cursor-default hover:bg-tertiary/[0.03] transition-colors duration-300">
                <p class="font-bold text-tertiary leading-none tracking-tight mb-2.5"
                   style="font-size: clamp(32px, 3.5vw, 44px);">{{ $statsPersonnelCount }}</p>
                <div class="flex items-center justify-center gap-1.5">
                    <span class="material-symbols-outlined text-[12px] text-tertiary/35">group</span>
                    <span class="text-[9px] text-on-surface-variant/65 uppercase tracking-[0.22em] font-bold">{{ __('messages.personnel') }}</span>
                </div>
            </div>
            <div class="px-6 py-7 text-center cursor-default hover:bg-secondary/[0.03] transition-colors duration-300">
                <p class="font-bold text-secondary leading-none tracking-tight mb-2.5"
                   style="font-size: clamp(32px, 3.5vw, 44px);">{{ $statsDocumentsCount }}</p>
                <div class="flex items-center justify-center gap-1.5">
                    <span class="material-symbols-outlined text-[12px] text-secondary/35">description</span>
                    <span class="text-[9px] text-on-surface-variant/65 uppercase tracking-[0.22em] font-bold">{{ __('messages.documents_nav') }}</span>
                </div>
            </div>
            <div class="px-6 py-7 text-center cursor-default hover:bg-amber-500/[0.03] transition-colors duration-300">
                <p class="font-bold text-amber-600 leading-none tracking-tight mb-2.5"
                   style="font-size: clamp(32px, 3.5vw, 44px);">{{ $statsMonksCount }}</p>
                <div class="flex items-center justify-center gap-1.5">
                    <span class="material-symbols-outlined text-[12px] text-amber-500/35">self_improvement</span>
                    <span class="text-[9px] text-on-surface-variant/65 uppercase tracking-[0.22em] font-bold">{{ __('messages.stat_monks') }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════
     NEWS SECTION
══════════════════════════════════════════════════════════════ --}}
<section id="news" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-24 scroll-mt-24">
    {{-- Section Header --}}
    <div class="flex items-end justify-between mb-10">
        <div>
            <div class="flex items-center gap-2.5 mb-2.5">
                <span class="block w-5 h-px bg-primary/55"></span>
                <span class="text-[9px] font-bold text-primary/75 uppercase tracking-[0.28em]">{{ __('messages.news_activities') }}</span>
            </div>
            <h2 class="text-headline-lg lg:text-[30px] lg:leading-[42px] text-on-surface font-bold">{{ __('messages.news_activities') }}</h2>
        </div>
        <a href="{{ route('frontend.news') }}"
           class="hidden sm:inline-flex items-center gap-1.5 text-[10px] font-bold text-primary/60 hover:text-primary transition-colors duration-200 group">
            {{ __('messages.latest_news') }}
            <span class="material-symbols-outlined text-[13px] group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
        </a>
    </div>

    @if ($news->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-7 lg:items-stretch">

            {{-- Featured Card — full-bleed cinematic --}}
            @php $featured = $featuredNews->first() ?? $news->first(); @endphp
            <div class="lg:col-span-2 group">
                <a href="{{ route('frontend.news.show', $featured->id) }}" class="block h-full">
                    <article class="relative rounded-2xl overflow-hidden h-full" style="min-height: 380px;">
                        {{-- Full-bleed image --}}
                        @if ($featured->cover_image_url)
                            <img src="{{ $featured->cover_image_url }}" alt="{{ $featured->title_lo }}"
                                 loading="lazy"
                                 class="absolute inset-0 w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-700 ease-out" />
                        @else
                            <div class="absolute inset-0 bg-gradient-to-br from-primary/35 via-primary/12 to-secondary/20">
                                <span class="absolute inset-0 flex items-center justify-center material-symbols-outlined text-[100px] text-white/5">newspaper</span>
                            </div>
                        @endif
                        {{-- Cinematic overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/88 via-black/22 to-black/0"></div>
                        <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(0,0,0,0.12) 0%, transparent 55%);"></div>

                        {{-- Featured badge --}}
                        @if ($featured->is_featured)
                            <div class="absolute top-5 left-5 z-10">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-500/90 backdrop-blur-sm text-white text-[9px] font-bold uppercase tracking-[0.2em] rounded-full shadow-lg">
                                    <span class="material-symbols-outlined text-[10px] filled">star</span>
                                    {{ __('messages.featured') }}
                                </span>
                            </div>
                        @endif

                        {{-- Content overlay — bottom --}}
                        <div class="absolute bottom-0 left-0 right-0 p-7 z-10">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="material-symbols-outlined text-[11px] text-amber-400/75">calendar_today</span>
                                <span class="text-[10px] text-white/55 tracking-wide">{{ $featured->published_date_formatted }}</span>
                            </div>
                            <h3 class="font-bold text-white leading-snug mb-2"
                                style="font-size: clamp(18px, 2.2vw, 24px); text-shadow: 0 1px 16px rgba(0,0,0,0.45);">
                                {{ $featured->title }}
                            </h3>
                            @if (app()->getLocale() === 'lo' && $featured->title_en)
                                <p class="text-sm text-white/45 mb-2">{{ $featured->title_en }}</p>
                            @endif
                            @if ($featured->excerpt)
                                <p class="text-[13px] text-white/65 leading-relaxed mb-5 line-clamp-2">{{ $featured->excerpt }}</p>
                            @endif
                            <div class="inline-flex items-center gap-2 text-amber-400/80 text-[10px] font-bold uppercase tracking-[0.22em] group-hover:gap-3 transition-all duration-300">
                                <span>{{ __('messages.read_more') }}</span>
                                <span class="material-symbols-outlined text-sm">arrow_forward</span>
                            </div>
                        </div>
                    </article>
                </a>
            </div>

            {{-- Sidebar: editorial news list --}}
            <div class="flex flex-col">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-outline-variant/50">
                    <span class="text-[9px] font-bold text-on-surface-variant/55 uppercase tracking-[0.25em]">{{ __('messages.news') }}</span>
                    <a href="{{ route('frontend.news') }}"
                       class="text-[9px] font-bold text-primary/55 hover:text-primary inline-flex items-center gap-1 transition-colors duration-200">
                        {{ __('messages.read_more') }}
                        <span class="material-symbols-outlined text-[11px]">arrow_forward</span>
                    </a>
                </div>
                <div class="flex-1">
                    @foreach ($news->skip(1)->take(3) as $item)
                        <a href="{{ route('frontend.news.show', $item->id) }}" class="block group/item">
                            <div class="flex items-center gap-4 py-4 border-b border-outline-variant/35 group-hover/item:border-primary/20 transition-colors duration-300 relative pl-2.5">
                                {{-- Left hover accent bar --}}
                                <div class="absolute left-0 top-3 bottom-3 w-0.5 bg-primary rounded-full scale-y-0 group-hover/item:scale-y-100 transition-transform duration-300 origin-center"></div>
                                {{-- Thumbnail --}}
                                <div class="w-[68px] h-[68px] rounded-xl overflow-hidden shrink-0 bg-surface-container">
                                    @if ($item->cover_image_url)
                                        <img src="{{ $item->cover_image_url }}" alt="" loading="lazy"
                                             class="w-full h-full object-cover group-hover/item:scale-105 transition-transform duration-300" />
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <span class="material-symbols-outlined text-lg text-on-surface-variant/20">image</span>
                                        </div>
                                    @endif
                                </div>
                                {{-- Text --}}
                                <div class="flex-1 min-w-0">
                                    <p class="text-[9px] text-on-surface-variant/55 mb-1.5 tracking-wide">{{ $item->published_date_formatted }}</p>
                                    <h4 class="text-[13px] font-bold text-on-surface group-hover/item:text-primary transition-colors duration-200 leading-snug line-clamp-2">
                                        {{ $item->title }}
                                    </h4>
                                </div>
                                {{-- Arrow --}}
                                <span class="material-symbols-outlined text-[13px] text-primary/0 group-hover/item:text-primary/45 -translate-x-2 group-hover/item:translate-x-0 transition-all duration-300 shrink-0">arrow_forward</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-20 rounded-2xl border border-outline-variant/40 bg-white/60">
            <span class="material-symbols-outlined text-5xl text-on-surface-variant/15 mb-4 block">newspaper</span>
            <p class="text-body-md text-on-surface-variant/50">{{ __('messages.no_news_yet') }}</p>
        </div>
    @endif
</section>

{{-- ══════════════════════════════════════════════════════════════
     PERSONNEL SECTION
══════════════════════════════════════════════════════════════ --}}
<section id="personnel" class="py-20 scroll-mt-24 relative overflow-hidden"
         style="background: linear-gradient(180deg, #fff9f0 0%, #fffdf7 60%, #fff8ee 100%); border-top: 1px solid rgba(219,194,176,0.45); border-bottom: 1px solid rgba(219,194,176,0.45);">

    {{-- Subtle warm texture orb --}}
    <div class="absolute top-0 right-0 w-[600px] h-[600px] rounded-full pointer-events-none"
         style="background: radial-gradient(circle, rgba(249,189,34,0.04) 0%, transparent 65%); transform: translate(20%, -30%);"></div>
    <div class="absolute bottom-0 left-0 w-[400px] h-[400px] rounded-full pointer-events-none"
         style="background: radial-gradient(circle, rgba(141,75,0,0.04) 0%, transparent 65%); transform: translate(-20%, 30%);"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        {{-- Section Header --}}
        <div class="text-center mb-14">
            <div class="flex items-center justify-center gap-3 mb-3">
                <span class="block h-px w-10 bg-tertiary/40"></span>
                <span class="text-[9px] font-bold text-tertiary/70 uppercase tracking-[0.28em]">{{ __('messages.our_people') }}</span>
                <span class="block h-px w-10 bg-tertiary/40"></span>
            </div>
            <h2 class="text-headline-lg lg:text-[30px] lg:leading-[42px] text-on-surface font-bold mb-3">{{ __('messages.personnel') }}</h2>
            <p class="text-body-md text-on-surface-variant/75 max-w-xl mx-auto leading-relaxed">{{ __('messages.personnel_subtitle') }}</p>
        </div>

        @if ($personnel->count() > 0)
            {{-- View All (top right) --}}
            <div class="flex justify-end mb-7">
                <a href="{{ route('frontend.personnel') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg border border-tertiary/35 text-tertiary text-[11px] font-bold hover:bg-tertiary hover:text-white hover:border-tertiary transition-all duration-200 group">
                    <span class="material-symbols-outlined text-sm">group</span>
                    {{ __('messages.view_all_personnel') }}
                    <span class="material-symbols-outlined text-sm group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                @foreach ($personnel as $person)
                    <div class="group relative bg-white rounded-2xl border border-outline-variant/50 overflow-hidden
                                shadow-[0_2px_12px_rgba(0,0,0,0.05)]
                                hover:shadow-[0_16px_48px_rgba(141,75,0,0.1)]
                                transition-all duration-400 hover:-translate-y-1.5 cursor-pointer flex flex-col">

                        {{-- Card top banner --}}
                        <div class="relative h-14 shrink-0 overflow-hidden
                             {{ $person->isMonk()
                                ? 'bg-gradient-to-br from-amber-200/90 via-yellow-100 to-amber-50'
                                : 'bg-gradient-to-br from-slate-100 via-slate-50 to-white' }}">
                            {{-- Decorative circle 1 --}}
                            <div class="absolute -top-5 -right-5 w-16 h-16 rounded-full
                                        {{ $person->isMonk() ? 'bg-amber-400/20' : 'bg-slate-300/25' }}"></div>
                            {{-- Decorative circle 2 --}}
                            <div class="absolute -bottom-4 -left-3 w-11 h-11 rounded-full
                                        {{ $person->isMonk() ? 'bg-amber-300/15' : 'bg-slate-200/20' }}"></div>
                            {{-- Gender badge --}}
                            <div class="absolute top-2 right-2 z-10">
                                @php $badge = $person->gender_badge; @endphp
                                <span class="px-2 py-0.5 rounded-full text-[8px] font-bold tracking-wide {{ $badge['class'] }} shadow-sm">
                                    {{ $badge['label'] }}
                                </span>
                            </div>
                        </div>

                        {{-- Avatar --}}
                        <div class="relative flex justify-center -mt-9 px-4 shrink-0 z-10">
                            <div class="relative">
                                @if ($person->photo_url)
                                    <img src="{{ Storage::url($person->photo_url) }}" alt="{{ $person->display_name }}"
                                         loading="lazy"
                                         class="w-[72px] h-[72px] rounded-full object-cover ring-4 ring-white shadow-md group-hover:scale-105 transition-transform duration-500 ease-out" />
                                @else
                                    <div class="w-[72px] h-[72px] rounded-full ring-4 ring-white shadow-md flex items-center justify-center
                                                {{ $person->isMonk() ? 'bg-gradient-to-br from-amber-100 to-amber-200' : 'bg-gradient-to-br from-slate-100 to-slate-200' }}">
                                        <span class="material-symbols-outlined text-3xl {{ $person->isMonk() ? 'text-amber-400/55' : 'text-on-surface-variant/20' }}">person</span>
                                    </div>
                                @endif
                                {{-- Monk ring accent --}}
                                @if ($person->isMonk())
                                    <div class="absolute inset-0 rounded-full ring-2 ring-amber-400/70 ring-offset-2 ring-offset-white pointer-events-none"></div>
                                @endif
                            </div>
                        </div>

                        {{-- Info --}}
                        <div class="px-4 pb-5 pt-3 text-center flex flex-col flex-1">
                            @if ($person->display_title)
                                <p class="text-[9px] text-primary/80 font-bold uppercase tracking-[0.18em] mb-1">{{ $person->display_title }}</p>
                            @endif
                            <h3 class="text-[13px] font-bold text-on-surface leading-snug mb-1 group-hover:text-primary transition-colors duration-200">
                                {{ $person->display_name }}
                            </h3>
                            @if ($person->display_position)
                                <p class="text-[11px] text-on-surface-variant/70 font-medium leading-snug">{{ $person->display_position }}</p>
                            @endif
                            @if ($person->department)
                                <div class="mt-auto pt-3">
                                    <span class="text-[9px] text-primary/70 inline-flex items-center gap-1 bg-primary/[0.06] px-2.5 py-1 rounded-md border border-primary/10 font-semibold">
                                        <span class="material-symbols-outlined text-[10px]">corporate_fare</span>
                                        {{ $person->department->name }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- View All CTA bottom --}}
            <div class="text-center mt-12">
                <a href="{{ route('frontend.personnel') }}"
                   class="inline-flex items-center gap-2.5 px-8 py-4 bg-tertiary text-white rounded-xl font-bold text-sm hover:bg-tertiary-container transition-all duration-200 shadow-md shadow-tertiary/20 btn-press group">
                    <span class="material-symbols-outlined text-base">group</span>
                    {{ __('messages.view_all_personnel') }}
                    <span class="material-symbols-outlined text-base group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
                </a>
            </div>
        @else
            <div class="text-center py-16">
                <span class="material-symbols-outlined text-5xl text-on-surface-variant/15 mb-4 block">group</span>
                <p class="text-body-md text-on-surface-variant/50">{{ __('messages.no_personnel_yet') }}</p>
            </div>
        @endif
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════
     DOCUMENTS SECTION
══════════════════════════════════════════════════════════════ --}}
<section id="documents" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 scroll-mt-24">
    {{-- Section Header --}}
    <div class="flex items-end justify-between mb-12">
        <div class="text-center w-full">
            <div class="flex items-center justify-center gap-3 mb-3">
                <span class="block h-px w-10 bg-secondary/40"></span>
                <span class="text-[9px] font-bold text-secondary/70 uppercase tracking-[0.28em]">{{ __('messages.document_library') }}</span>
                <span class="block h-px w-10 bg-secondary/40"></span>
            </div>
            <h2 class="text-headline-lg lg:text-[30px] lg:leading-[42px] text-on-surface font-bold mb-2">{{ __('messages.documents_nav') }}</h2>
            <p class="text-body-md text-on-surface-variant/70">{{ __('messages.documents_subtitle') }}</p>
        </div>
    </div>

    @if ($documents->count() > 0)
        {{-- View All (top right) --}}
        <div class="flex justify-end mb-5">
            <a href="{{ route('frontend.documents') }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg border border-outline-variant/60 text-[10px] font-bold text-on-surface-variant/60 hover:border-primary/40 hover:text-primary hover:bg-primary/[0.04] transition-all duration-200 group">
                <span class="material-symbols-outlined text-[13px]">open_in_new</span>
                {{ __('messages.view_all_documents') }}
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($documents as $doc)
                <div class="group bg-white rounded-xl border border-outline-variant/45
                            shadow-[0_2px_10px_rgba(0,0,0,0.04)]
                            hover:shadow-[0_10px_36px_rgba(141,75,0,0.08)]
                            hover:-translate-y-0.5 hover:border-primary/20
                            transition-all duration-300 flex items-center gap-5 p-5 cursor-pointer relative overflow-hidden">

                    {{-- Left accent bar (slides in on hover) --}}
                    <div class="absolute left-0 top-0 h-full w-[3px] bg-gradient-to-b from-primary/70 to-primary/30
                                scale-y-0 group-hover:scale-y-100 transition-transform duration-300 origin-center rounded-r-full"></div>

                    {{-- File icon --}}
                    <div class="w-[52px] h-[52px] rounded-xl flex items-center justify-center shrink-0
                                bg-primary/[0.07] text-primary
                                group-hover:bg-primary/12 group-hover:scale-105
                                transition-all duration-300">
                        <span class="material-symbols-outlined text-[26px]">{{ $doc->file_icon }}</span>
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-3 mb-1.5">
                            <div class="min-w-0 flex-1">
                                <h4 class="text-[13px] font-bold text-on-surface group-hover:text-primary transition-colors duration-200 leading-snug truncate">
                                    {{ $doc->title }}
                                </h4>
                                @if (app()->getLocale() === 'lo' && $doc->title_en)
                                    <p class="text-[11px] text-on-surface-variant/55 truncate mt-0.5">{{ $doc->title_en }}</p>
                                @endif
                            </div>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[8px] font-bold border shrink-0 tracking-wide {{ $doc->category_color }}">
                                <span class="material-symbols-outlined text-[10px]">{{ $doc->category_icon }}</span>
                                {{ $doc->category_label }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2.5 text-[10px] text-on-surface-variant/50 font-medium flex-wrap mt-1">
                            @if ($doc->doc_number)
                                <span class="font-mono bg-surface-container/50 px-1.5 py-0.5 rounded text-[9px] border border-outline-variant/30">{{ $doc->doc_number }}</span>
                                <span class="w-px h-3 bg-outline-variant/40"></span>
                            @endif
                            @if ($doc->issued_date)
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[10px]">calendar_today</span>
                                    {{ $doc->issued_date->format('d/m/Y') }}
                                </span>
                            @endif
                            @if ($doc->file_path)
                                <span class="w-px h-3 bg-outline-variant/40"></span>
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[10px]">attachment</span>
                                    {{ $doc->file_size_formatted }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Download button --}}
                    @if ($doc->file_path)
                        <a href="{{ route('frontend.document.download', $doc->id) }}"
                           class="shrink-0 w-10 h-10 rounded-lg bg-primary/[0.07] text-primary
                                  flex items-center justify-center
                                  hover:bg-primary hover:text-white
                                  transition-all duration-200 shadow-sm hover:shadow-md btn-press cursor-pointer"
                           title="{{ __('messages.download') }}"
                           @click.stop>
                            <span class="material-symbols-outlined text-[18px]">download</span>
                        </a>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- View All CTA bottom --}}
        <div class="text-center mt-10">
            <a href="{{ route('frontend.documents') }}"
               class="inline-flex items-center gap-2.5 px-8 py-3.5 bg-primary text-white rounded-xl font-bold text-sm hover:bg-primary-container transition-all duration-200 btn-press shadow-md shadow-primary/20 group">
                <span class="material-symbols-outlined text-base">folder_open</span>
                {{ __('messages.view_all_documents') }}
                <span class="material-symbols-outlined text-base group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
            </a>
        </div>
    @else
        <div class="text-center py-20 bg-white/60 rounded-2xl border border-outline-variant/40">
            <span class="material-symbols-outlined text-5xl text-on-surface-variant/15 mb-4 block">folder_off</span>
            <p class="text-body-md text-on-surface-variant/50">{{ __('messages.no_documents_yet') }}</p>
        </div>
    @endif
</section>

@endsection
