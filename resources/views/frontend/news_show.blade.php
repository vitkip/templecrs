@extends('frontend.layout')

@section('content')

{{-- ══════════════════════════════════════════════════════════════
     READING PROGRESS BAR (fixed, gold)
     ══════════════════════════════════════════════════════════════ --}}
<div class="fixed top-0 left-0 right-0 z-[200] h-[3px] pointer-events-none"
     x-data="{ pct: 0 }"
     x-init="
        const tick = () => {
            const el = document.documentElement;
            const scrolled = el.scrollTop || document.body.scrollTop;
            const total = el.scrollHeight - el.clientHeight;
            pct = total > 0 ? Math.round((scrolled / total) * 1000) / 10 : 0;
        };
        window.addEventListener('scroll', tick, { passive: true });
     ">
    <div class="h-full"
         :style="`width: ${pct}%; background: linear-gradient(90deg, #B8960C 0%, #D4AF37 50%, #F5D060 100%); transition: width 80ms linear;`">
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     HERO — full-bleed image with title overlay
     ══════════════════════════════════════════════════════════════ --}}
@php
    $wordCount    = str_word_count(strip_tags($newsItem->content ?? ''));
    $readMinutes  = max(1, (int) ceil($wordCount / 200));
    $readLabel    = app()->getLocale() === 'lo' ? "{$readMinutes} ນາທີ" : "{$readMinutes} min read";
    $copiedLabel  = app()->getLocale() === 'lo' ? 'ສຳເນົາແລ້ວ!' : 'Copied!';
    $copyLabel    = app()->getLocale() === 'lo' ? 'ສຳເນົາລິ້ງ' : 'Copy link';

    $allPhotos = [];
    if ($newsItem->cover_image_url) {
        $allPhotos[] = [
            'url'      => $newsItem->cover_image_url,
            'caption'  => 'ຮູບປົກ: ' . $newsItem->title,
            'is_cover' => true,
        ];
    }
    if (!empty($newsItem->gallery_images)) {
        foreach ($newsItem->gallery_image_urls as $idx => $gUrl) {
            $allPhotos[] = [
                'url'      => $gUrl,
                'caption'  => 'ຮູບພາບປະກອບ ' . ($idx + 1) . ' — ' . $newsItem->title,
                'is_cover' => false,
            ];
        }
    }
@endphp

<div x-data="{
    open: false,
    currentIndex: 0,
    photos: @js($allPhotos),
    copied: false,
    touchStartX: 0,
    touchEndX: 0,
    openGallery(idx) {
        if (!this.photos || this.photos.length === 0) return;
        this.currentIndex = (idx >= 0 && idx < this.photos.length) ? idx : 0;
        this.open = true;
        document.body.style.overflow = 'hidden';
    },
    closeGallery() {
        this.open = false;
        document.body.style.overflow = '';
    },
    nextPhoto() {
        if (this.photos && this.photos.length > 1) {
            this.currentIndex = (this.currentIndex + 1) % this.photos.length;
        }
    },
    prevPhoto() {
        if (this.photos && this.photos.length > 1) {
            this.currentIndex = (this.currentIndex - 1 + this.photos.length) % this.photos.length;
        }
    },
    goTo(idx) {
        if (idx >= 0 && idx < this.photos.length) {
            this.currentIndex = idx;
        }
    },
    handleKeyDown(e) {
        if (!this.open) return;
        if (e.key === 'Escape') this.closeGallery();
        if (e.key === 'ArrowRight' || e.key === 'ArrowDown') this.nextPhoto();
        if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') this.prevPhoto();
    },
    handleTouchStart(e) {
        if (e.changedTouches && e.changedTouches.length > 0) {
            this.touchStartX = e.changedTouches[0].screenX;
        }
    },
    handleTouchEnd(e) {
        if (e.changedTouches && e.changedTouches.length > 0) {
            this.touchEndX = e.changedTouches[0].screenX;
            const diff = this.touchEndX - this.touchStartX;
            if (Math.abs(diff) > 40) {
                if (diff < 0) this.nextPhoto();
                else this.prevPhoto();
            }
        }
    },
    copyLink() {
        navigator.clipboard.writeText(window.location.href)
            .then(() => { this.copied = true; setTimeout(() => this.copied = false, 2500); })
            .catch(() => {});
    }
}"
@keydown.window="handleKeyDown($event)">

<div class="relative w-full overflow-hidden animate-fade-in" style="height: clamp(380px, 52vh, 580px);">

    {{-- Background --}}
    @if ($newsItem->cover_image_url)
        <img src="{{ $newsItem->cover_image_url }}"
             alt="{{ $newsItem->title }}"
             @click="openGallery(0)"
             class="absolute inset-0 w-full h-full object-cover cursor-pointer hover:scale-102 transition-transform duration-700"
             title="ຄລິກເພື່ອເບິ່ງຮູບຂະໜາດໃຫຍ່" />
    @else
        <div class="absolute inset-0" style="background: linear-gradient(135deg, #1a2744 0%, #2c3e5c 50%, #1e3050 100%);">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, rgba(212,175,55,0.10) 1px, transparent 0); background-size: 28px 28px;"></div>
        </div>
    @endif

    {{-- Gradient overlays for legibility --}}
    <div class="absolute inset-0 pointer-events-none" style="background: linear-gradient(to top, rgba(8,10,18,0.95) 0%, rgba(8,10,18,0.6) 40%, rgba(8,10,18,0.20) 100%);"></div>
    <div class="absolute inset-0 pointer-events-none" style="background: linear-gradient(90deg, rgba(8,10,18,0.35) 0%, transparent 70%);"></div>

    {{-- Breadcrumb (top) --}}
    <div class="absolute top-20 left-0 right-0">
        <div class="max-w-5xl mx-auto px-4 sm:px-8">
            <nav class="flex items-center gap-1 text-[11px] text-white/55">
                <a href="{{ route('frontend.index') }}" class="hover:text-white/90 transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined" style="font-size:12px;">home</span>
                    {{ __('messages.homepage') }}
                </a>
                <span class="material-symbols-outlined opacity-40" style="font-size:10px;">chevron_right</span>
                <a href="{{ route('frontend.news') }}" class="hover:text-white/90 transition-colors">{{ __('messages.news_activities') }}</a>
                <span class="material-symbols-outlined opacity-40" style="font-size:10px;">chevron_right</span>
                <span class="text-white/75 truncate max-w-[160px] sm:max-w-sm">{{ $newsItem->title }}</span>
            </nav>
        </div>
    </div>

    {{-- Hero content — anchored to bottom --}}
    <div class="absolute bottom-0 left-0 right-0 pb-8 sm:pb-10 pointer-events-auto">
        <div class="max-w-5xl mx-auto px-4 sm:px-8">

            {{-- Badges --}}
            <div class="flex items-center flex-wrap gap-2 mb-4">
                @if ($newsItem->category)
                    <a href="{{ route('frontend.news') }}?category={{ $newsItem->category->id }}"
                       class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wide text-white/90 bg-white/15 border border-white/20 backdrop-blur-sm hover:bg-white/25 transition-colors"
                       title="{{ app()->getLocale() === 'lo' ? 'ເບິ່ງຂ່າວໃນໝວດນີ້' : 'Browse this category' }}">
                        <span class="material-symbols-outlined" style="font-size:10px;">{{ $newsItem->category->icon }}</span>
                        {{ $newsItem->category->name }}
                    </a>
                @endif
                @if ($newsItem->is_featured)
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wide text-white"
                          style="background: linear-gradient(135deg, #D4AF37, #F5D060); box-shadow: 0 2px 14px rgba(212,175,55,0.55);">
                        <span class="material-symbols-outlined filled" style="font-size:10px;">star</span>
                        {{ __('messages.featured') }}
                    </span>
                @endif
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-medium text-white/75 bg-white/10 border border-white/15 backdrop-blur-sm">
                    <span class="material-symbols-outlined" style="font-size:11px;">schedule</span>
                    {{ $readLabel }}
                </span>
                @if (count($allPhotos) > 0)
                    <button type="button"
                            @click="openGallery(0)"
                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold text-white bg-white/20 hover:bg-white/30 border border-white/25 backdrop-blur-sm transition-all shadow-xs cursor-pointer">
                        <span class="material-symbols-outlined" style="font-size:11px;">photo_library</span>
                        <span>{{ count($allPhotos) }} ຮູບ</span>
                    </button>
                @endif
            </div>

            {{-- Title --}}
            <h1 class="text-white font-bold leading-tight mb-3 text-lao"
                style="font-size: clamp(20px, 3.5vw, 36px); max-width: 820px; text-shadow: 0 2px 24px rgba(0,0,0,0.6);">
                {{ $newsItem->title }}
            </h1>

            @if (app()->getLocale() === 'lo' && $newsItem->title_en)
                <p class="text-white/55 italic mb-4" style="font-size: 14px; max-width: 680px;">
                    {{ $newsItem->title_en }}
                </p>
            @endif

            {{-- Meta row --}}
            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-[12px] text-white/60">
                <span class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined" style="font-size:13px; color: #D4AF37;">calendar_today</span>
                    {{ $newsItem->published_date_formatted }}
                </span>
                @if ($newsItem->author)
                    <span class="w-1 h-1 rounded-full bg-white/25"></span>
                    <span class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined" style="font-size:13px; color: #D4AF37;">person</span>
                        {{ $newsItem->author->name }}
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     ARTICLE LAYOUT — content + sidebar
     ══════════════════════════════════════════════════════════════ --}}
<div class="max-w-5xl mx-auto px-4 sm:px-8">

    {{-- Toolbar: back + share --}}
    <div class="flex items-center justify-between py-5 mb-8" style="border-bottom: 1px solid rgba(0,0,0,0.08);">
        <a href="{{ route('frontend.news') }}"
           class="inline-flex items-center gap-2 text-[13px] text-on-surface-variant hover:text-primary font-medium transition-colors group btn-press">
            <span class="material-symbols-outlined text-base group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
            {{ __('messages.back_to_list') }}
        </a>

        <button @click="copyLink()"
                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-[12px] font-semibold border transition-all duration-200 btn-press"
                :class="copied
                    ? 'border-green-400/50 text-green-600 bg-green-50'
                    : 'border-outline-variant text-on-surface-variant hover:border-primary hover:text-primary hover:bg-primary/5'">
            <span class="material-symbols-outlined" style="font-size:14px;"
                  x-text="copied ? 'check_circle' : 'link'">link</span>
            <span x-text="copied ? '{{ $copiedLabel }}' : '{{ $copyLabel }}'">{{ $copyLabel }}</span>
        </button>
    </div>

    {{-- Two-col: article + sticky sidebar --}}
    <div class="lg:flex lg:gap-14">

        {{-- ── MAIN ARTICLE ── --}}
        <article class="flex-1 min-w-0 mb-16">

            {{-- Excerpt / summary box --}}
            @if ($newsItem->excerpt)
                <div class="mb-8 p-5 rounded-2xl"
                     style="background: linear-gradient(135deg, rgba(212,175,55,0.08) 0%, rgba(212,175,55,0.03) 100%);
                            border: 1px solid rgba(212,175,55,0.22);
                            border-left: 3px solid #D4AF37;">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined" style="font-size:16px; color: #D4AF37;">format_quote</span>
                        <span class="text-[10px] font-bold uppercase tracking-widest" style="color: #D4AF37;">
                            {{ __('messages.news_excerpt') }}
                        </span>
                    </div>
                    <p class="text-body-lg text-on-surface-variant italic leading-relaxed text-lao">
                        {{ $newsItem->excerpt }}
                    </p>
                </div>
            @endif

            {{-- Full content --}}
            <div class="prose max-w-none text-on-surface/88 text-lao"
                 style="font-size: 16.5px; line-height: 1.9;">
                {!! nl2br(e($newsItem->content)) !!}
            </div>

            {{-- Photo Gallery --}}
            @if (!empty($newsItem->gallery_images) && count($newsItem->gallery_images) > 0)
                <div class="mt-12 pt-8 border-t border-outline-variant/60">
                    <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
                        <div>
                            <h3 class="text-title-lg font-bold text-on-surface flex items-center gap-2 text-lao">
                                <span class="material-symbols-outlined text-primary">photo_library</span>
                                {{ app()->getLocale() === 'lo' ? 'ຮູບພາບປະກອບ' : 'Photo Gallery' }}
                                <span class="text-label-md font-bold px-2.5 py-0.5 rounded-full bg-primary/10 text-primary">
                                    {{ count($newsItem->gallery_images) }} ຮູບ
                                </span>
                            </h3>
                            <p class="text-body-xs text-on-surface-variant mt-0.5">
                                ຄລິກທີ່ຮູບເພື່ອເບິ່ງຂະໜາດໃຫຍ່ ແລະ ສາມາດເລື່ອນເບິ່ງຮູບທັງໝົດໄດ້
                            </p>
                        </div>

                        <button type="button"
                                @click="openGallery({{ $newsItem->cover_image_url ? 1 : 0 }})"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[12px] font-bold text-primary bg-primary/10 hover:bg-primary/15 border border-primary/20 transition-all active:scale-95 cursor-pointer">
                            <span class="material-symbols-outlined text-base">slideshow</span>
                            <span>ເປີດສະໄລ້ຮູບພາບ</span>
                        </button>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3.5">
                        @foreach ($newsItem->gallery_image_urls as $idx => $gUrl)
                            @php $photoIdx = $newsItem->cover_image_url ? $idx + 1 : $idx; @endphp
                            <div @click="openGallery({{ $photoIdx }})"
                                 class="group relative aspect-[4/3] rounded-2xl overflow-hidden bg-surface-container border border-outline-variant/80 cursor-pointer shadow-xs hover:shadow-md hover:border-primary/50 transition-all duration-300"
                                 title="ຄລິກເພື່ອເບິ່ງຮູບໃຫຍ່">
                                <img src="{{ $gUrl }}"
                                     alt="{{ $newsItem->title }} - {{ $idx + 1 }}"
                                     loading="lazy"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                                
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/15 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-between p-3">
                                    <div class="flex justify-end">
                                        <span class="w-8 h-8 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white shadow-sm">
                                            <span class="material-symbols-outlined text-base">zoom_in</span>
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between text-white text-[11px] font-medium">
                                        <span>ຮູບທີ {{ $idx + 1 }}</span>
                                        <span class="opacity-80">ຄລິກເບິ່ງ</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Article end ornament --}}
            <div class="mt-14 flex items-center gap-4">
                <div class="h-px flex-1" style="background: linear-gradient(to right, rgba(0,0,0,0.08), transparent);"></div>
                <span class="material-symbols-outlined" style="font-size:18px; color: rgba(212,175,55,0.5);">spa</span>
                <div class="h-px flex-1" style="background: linear-gradient(to left, rgba(0,0,0,0.08), transparent);"></div>
            </div>
        </article>

        {{-- ── STICKY SIDEBAR (desktop) ── --}}
        <aside class="hidden lg:block w-52 shrink-0">
            <div class="sticky top-24 space-y-3">

                {{-- Article info card --}}
                <div class="rounded-2xl p-4 space-y-3"
                     style="background: rgba(255,255,255,0.75); border: 1px solid rgba(0,0,0,0.07); backdrop-filter: blur(8px);">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant/70">
                        {{ app()->getLocale() === 'lo' ? 'ຂໍ້ມູນ' : 'Article Info' }}
                    </p>
                    <div class="flex items-center gap-2 text-[12px] text-on-surface-variant">
                        <span class="w-5 h-5 rounded-lg flex items-center justify-center shrink-0"
                              style="background: rgba(212,175,55,0.12);">
                            <span class="material-symbols-outlined" style="font-size:12px; color: #D4AF37;">schedule</span>
                        </span>
                        <span>{{ $readLabel }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-[12px] text-on-surface-variant">
                        <span class="w-5 h-5 rounded-lg flex items-center justify-center shrink-0"
                              style="background: rgba(212,175,55,0.12);">
                            <span class="material-symbols-outlined" style="font-size:12px; color: #D4AF37;">calendar_today</span>
                        </span>
                        <span>{{ $newsItem->published_date_formatted }}</span>
                    </div>
                    @if ($newsItem->author)
                        <div class="flex items-center gap-2 text-[12px] text-on-surface-variant">
                            <span class="w-5 h-5 rounded-lg flex items-center justify-center shrink-0"
                                  style="background: rgba(212,175,55,0.12);">
                                <span class="material-symbols-outlined" style="font-size:12px; color: #D4AF37;">person</span>
                            </span>
                            <span class="truncate">{{ $newsItem->author->name }}</span>
                        </div>
                    @endif
                    @if ($newsItem->category)
                        <div class="pt-2 border-t border-outline-variant/30">
                            <a href="{{ route('frontend.news') }}"
                               class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-[11px] font-semibold transition-colors"
                               style="background: rgba(212,175,55,0.10); color: #B8960C;">
                                <span class="material-symbols-outlined" style="font-size:11px;">{{ $newsItem->category->icon }}</span>
                                {{ $newsItem->category->name }}
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Share card --}}
                <div class="rounded-2xl p-4"
                     style="background: rgba(255,255,255,0.75); border: 1px solid rgba(0,0,0,0.07); backdrop-filter: blur(8px);">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant/70 mb-3">
                        {{ app()->getLocale() === 'lo' ? 'ແບ່ງປັນ' : 'Share' }}
                    </p>
                    <button @click="copyLink()"
                            class="w-full flex items-center gap-2 px-3 py-2 rounded-xl text-[12px] font-semibold transition-all duration-200 btn-press"
                            :class="copied
                                ? 'bg-green-50 text-green-600'
                                : 'text-primary bg-primary/6 hover:bg-primary/12'">
                        <span class="material-symbols-outlined" style="font-size:14px;"
                              x-text="copied ? 'check' : 'content_copy'">content_copy</span>
                        <span x-text="copied ? '{{ $copiedLabel }}' : '{{ $copyLabel }}'">{{ $copyLabel }}</span>
                    </button>
                </div>
            </div>
        </aside>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     RELATED NEWS
     ══════════════════════════════════════════════════════════════ --}}
@if ($relatedNews->count() > 0)
    <section class="mb-20" style="background: linear-gradient(to bottom, transparent, rgba(212,175,55,0.04), transparent);">
        <div class="max-w-5xl mx-auto px-4 sm:px-8 py-14">

            {{-- Section header --}}
            <div class="flex items-center gap-3 mb-8">
                <div class="h-px flex-1" style="background: linear-gradient(to right, transparent, rgba(212,175,55,0.35));"></div>
                <div class="flex flex-col items-center gap-1 shrink-0">
                    <div class="flex items-center gap-2.5">
                        <span class="material-symbols-outlined" style="font-size:16px; color: #D4AF37;">spa</span>
                        <h3 class="text-headline-sm text-on-surface font-bold">
                            {{ app()->getLocale() === 'lo' ? 'ຂ່າວສານໜ້າສົນໃຈ' : 'Related Articles' }}
                        </h3>
                    </div>
                    @if ($newsItem->category)
                        <span class="inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-0.5 rounded-full"
                              style="background: rgba(212,175,55,0.12); color: #B8960C;">
                            <span class="material-symbols-outlined" style="font-size:10px;">{{ $newsItem->category->icon }}</span>
                            {{ $newsItem->category->name }}
                        </span>
                    @endif
                </div>
                <div class="h-px flex-1" style="background: linear-gradient(to left, transparent, rgba(212,175,55,0.35));"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
                @foreach ($relatedNews as $item)
                    <a href="{{ route('frontend.news.show', $item->id) }}" class="block group">
                        <div class="bg-white rounded-2xl overflow-hidden flex flex-col h-full transition-all duration-300 hover:-translate-y-1 hover:shadow-xl"
                             style="border: 1px solid rgba(0,0,0,0.07); box-shadow: 0 2px 8px rgba(0,0,0,0.05);">

                            {{-- Image --}}
                            <div class="relative h-44 shrink-0 overflow-hidden"
                                 style="background: linear-gradient(135deg, rgba(var(--color-primary), 0.08), rgba(var(--color-secondary), 0.08));">
                                @if ($item->cover_image_url)
                                    <img src="{{ $item->cover_image_url }}" alt="" loading="lazy"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary/8 to-secondary/8">
                                        <span class="material-symbols-outlined text-on-surface-variant/15" style="font-size: 52px;">newspaper</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="p-4 flex-1 flex flex-col">
                                <div class="flex items-center gap-2 mb-2 flex-wrap">
                                    <p class="flex items-center gap-1" style="font-size: 10px; color: #D4AF37;">
                                        <span class="material-symbols-outlined" style="font-size:10px;">calendar_today</span>
                                        <span class="text-on-surface-variant/70">{{ $item->published_date_formatted }}</span>
                                    </p>
                                    @if ($item->category)
                                        <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-full text-[9px] font-semibold"
                                              style="background: rgba(212,175,55,0.10); color: #B8960C;">
                                            <span class="material-symbols-outlined" style="font-size:9px;">{{ $item->category->icon }}</span>
                                            {{ $item->category->name }}
                                        </span>
                                    @endif
                                </div>
                                <h4 class="text-body-md font-bold text-on-surface line-clamp-3 group-hover:text-primary transition-colors leading-snug flex-1 text-lao">
                                    {{ $item->title }}
                                </h4>
                                <div class="mt-3 inline-flex items-center gap-1 text-[11px] font-bold group-hover:gap-2 transition-all duration-200"
                                     style="color: #D4AF37;">
                                    <span>{{ __('messages.read_more') }}</span>
                                    <span class="material-symbols-outlined" style="font-size:12px;">arrow_forward</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif

{{-- ══════════════════════════════════════════════════════════════
     BACK TO TOP BUTTON
     ══════════════════════════════════════════════════════════════ --}}
<div x-data="{ visible: false }"
     x-init="window.addEventListener('scroll', () => { visible = window.scrollY > 500; }, { passive: true })"
     class="fixed bottom-6 right-6 z-50">
    <button x-show="visible"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            class="w-11 h-11 rounded-full text-white shadow-lg flex items-center justify-center btn-press hover:scale-105 transition-transform"
            style="background: linear-gradient(135deg, #D4AF37 0%, #B8960C 100%); box-shadow: 0 4px 20px rgba(212,175,55,0.45);"
            aria-label="Back to top">
        <span class="material-symbols-outlined" style="font-size:20px;">keyboard_arrow_up</span>
    </button>
</div>

    {{-- ══════════════════════════════════════════════════════════════
         LIGHTBOX MODAL (Full-screen Photo Slider)
         ══════════════════════════════════════════════════════════════ --}}
    <template x-teleport="body">
        <div x-show="open"
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[99999] flex flex-col bg-black/95 backdrop-blur-md select-none"
             role="dialog"
             aria-modal="true"
             aria-label="Photo Lightbox">

            {{-- Top Bar --}}
            <div class="flex items-center justify-between px-4 sm:px-6 py-3.5 bg-gradient-to-b from-black/80 to-transparent z-30 shrink-0">
                <div class="flex items-center gap-3 min-w-0">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold text-[#F5D060] bg-[#D4AF37]/15 border border-[#D4AF37]/30 shadow-xs">
                        <span class="material-symbols-outlined text-sm">photo_library</span>
                        <span x-text="(currentIndex + 1) + ' / ' + photos.length"></span>
                    </span>
                    <p class="text-white/80 text-sm font-medium truncate max-w-xs sm:max-w-md md:max-w-xl text-lao"
                       x-text="photos[currentIndex]?.caption || ''">
                    </p>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    {{-- Open original in new tab --}}
                    <a :href="photos[currentIndex]?.url"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-all cursor-pointer"
                       title="ເປີດຮູບຕົ້ນສະບັບໃນແທັບໃໝ່">
                        <span class="material-symbols-outlined text-lg">open_in_new</span>
                    </a>

                    {{-- Close button --}}
                    <button type="button"
                            @click="closeGallery()"
                            class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/25 active:scale-95 text-white flex items-center justify-center transition-all cursor-pointer group"
                            title="ປິດ (Esc)">
                        <span class="material-symbols-outlined text-xl group-hover:rotate-90 transition-transform duration-200">close</span>
                    </button>
                </div>
            </div>

            {{-- Main Slider Area --}}
            <div class="relative flex-1 flex items-center justify-center overflow-hidden px-2 sm:px-16"
                 @click.self="closeGallery()"
                 @touchstart="handleTouchStart($event)"
                 @touchend="handleTouchEnd($event)">

                {{-- Left navigation button --}}
                <button type="button"
                        x-show="photos.length > 1"
                        @click.stop="prevPhoto()"
                        class="absolute left-3 sm:left-6 z-20 w-11 h-11 sm:w-14 sm:h-14 rounded-full bg-black/40 hover:bg-black/70 active:scale-95 text-white backdrop-blur-md flex items-center justify-center border border-white/20 hover:border-[#D4AF37]/60 shadow-2xl transition-all duration-200 cursor-pointer"
                        title="ຮູບກ່ອນໜ້າ (←)">
                    <span class="material-symbols-outlined text-2xl sm:text-3xl">chevron_left</span>
                </button>

                {{-- Image with smooth swap --}}
                <div class="relative w-full h-full flex items-center justify-center p-2 sm:p-4 pointer-events-none">
                    <template x-for="(photo, idx) in photos" :key="idx">
                        <div x-show="currentIndex === idx"
                             x-transition:enter="transition ease-out duration-250"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute inset-0 flex items-center justify-center p-2 pointer-events-auto">
                            <img :src="photo.url"
                                 :alt="photo.caption"
                                 class="max-h-[72vh] sm:max-h-[76vh] max-w-full object-contain rounded-xl shadow-2xl select-none" />
                        </div>
                    </template>
                </div>

                {{-- Right navigation button --}}
                <button type="button"
                        x-show="photos.length > 1"
                        @click.stop="nextPhoto()"
                        class="absolute right-3 sm:right-6 z-20 w-11 h-11 sm:w-14 sm:h-14 rounded-full bg-black/40 hover:bg-black/70 active:scale-95 text-white backdrop-blur-md flex items-center justify-center border border-white/20 hover:border-[#D4AF37]/60 shadow-2xl transition-all duration-200 cursor-pointer"
                        title="ຮູບຖັດໄປ (→)">
                    <span class="material-symbols-outlined text-2xl sm:text-3xl">chevron_right</span>
                </button>
            </div>

            {{-- Bottom Thumbnails strip & info --}}
            <div class="p-3 bg-black/70 backdrop-blur-md border-t border-white/10 flex flex-col items-center gap-2 shrink-0 z-30">
                <div class="flex items-center justify-center gap-2 overflow-x-auto max-w-full py-1 px-4 no-scrollbar">
                    <template x-for="(p, pIdx) in photos" :key="pIdx">
                        <button type="button"
                                @click="goTo(pIdx)"
                                class="relative h-12 w-16 sm:h-14 sm:w-20 rounded-lg overflow-hidden border-2 transition-all shrink-0 cursor-pointer"
                                :class="currentIndex === pIdx
                                    ? 'border-[#D4AF37] ring-2 ring-[#D4AF37]/50 scale-105 opacity-100'
                                    : 'border-white/20 opacity-40 hover:opacity-90'">
                            <img :src="p.url" :alt="'Thumbnail ' + (pIdx + 1)" class="w-full h-full object-cover">
                            <span x-show="p.is_cover"
                                  class="absolute top-0.5 left-0.5 px-1 py-0.2 rounded text-[8px] font-bold bg-[#D4AF37] text-white">
                                ປົກ
                            </span>
                        </button>
                    </template>
                </div>

                <div class="hidden sm:flex items-center gap-4 text-[11px] text-white/50 text-lao">
                    <span class="flex items-center gap-1">
                        <kbd class="px-1.5 py-0.5 rounded bg-white/10 text-white/80 font-mono text-[10px]">←</kbd>
                        <kbd class="px-1.5 py-0.5 rounded bg-white/10 text-white/80 font-mono text-[10px]">→</kbd>
                        <span>ເລື່ອນຮູບ</span>
                    </span>
                    <span>•</span>
                    <span class="flex items-center gap-1">
                        <kbd class="px-1.5 py-0.5 rounded bg-white/10 text-white/80 font-mono text-[10px]">Esc</kbd>
                        <span>ປິດ</span>
                    </span>
                    <span>•</span>
                    <span>ເລື່ອນປັດ (Swipe) ເທິງຈໍມືຖືໄດ້</span>
                </div>
            </div>
        </div>
    </template>

</div>

@endsection
