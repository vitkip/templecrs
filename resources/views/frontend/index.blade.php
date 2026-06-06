@extends('frontend.layout')

@section('content')

{{-- ══════════════════════════════════════════════════════════════
     HERO SECTION
══════════════════════════════════════════════════════════════ --}}
@if ($slides->count() > 0)
    {{-- Image Slider Carousel (AlpineJS) --}}
    <section x-data="{ 
        activeSlide: 0, 
        slidesCount: {{ $slides->count() }},
        autoplayInterval: null,
        startAutoplay() {
            this.autoplayInterval = setInterval(() => {
                this.next();
            }, 6000);
        },
        stopAutoplay() {
            if (this.autoplayInterval) {
                clearInterval(this.autoplayInterval);
            }
        },
        next() {
            this.activeSlide = (this.activeSlide + 1) % this.slidesCount;
        },
        prev() {
            this.activeSlide = (this.activeSlide - 1 + this.slidesCount) % this.slidesCount;
        }
    }" 
    x-init="if (slidesCount > 1) { startAutoplay() }"
    @mouseenter="stopAutoplay()"
    @mouseleave="if (slidesCount > 1) { startAutoplay() }"
    class="relative overflow-hidden h-[450px] lg:h-[600px]">
        
        <div class="h-full w-full relative">
            @foreach ($slides as $index => $slide)
                <div x-show="activeSlide === {{ $index }}"
                     x-cloak
                     x-transition:enter="transition ease-out duration-1000 transform"
                     x-transition:enter-start="opacity-0 scale-[1.02]"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-800 transform"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-[0.98]"
                     class="absolute inset-0 w-full h-full bg-cover bg-center"
                     style="background-image: url('{{ $slide->image_url }}');">
                     
                    {{-- Dark overlay for text readability --}}
                    <div class="absolute inset-0 bg-black/45"></div>

                    {{-- Slide Content --}}
                    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex flex-col justify-center items-center text-center">
                        {{-- Logo --}}
                        <div class="w-20 h-20 lg:w-24 lg:h-24 mx-auto mb-4 bg-white/10 rounded-full flex items-center justify-center backdrop-blur-sm border border-white/20 shadow-xl">
                            @if ($orgLogo)
                                <img src="{{ Storage::url($orgLogo) }}" alt="Logo" class="w-full h-full object-cover rounded-full" />
                            @else
                                <span class="material-symbols-outlined text-white text-4xl lg:text-5xl">account_balance</span>
                            @endif
                        </div>

                        {{-- Title --}}
                        @if ($slide->title)
                            <h1 class="text-headline-lg lg:text-[44px] lg:leading-[58px] font-bold text-white mb-3 max-w-4xl">
                                {{ $slide->title }}
                            </h1>
                        @endif

                        {{-- Subtitle --}}
                        @if ($slide->subtitle)
                            <p class="text-body-lg lg:text-xl text-white/85 mb-8 max-w-3xl leading-relaxed">
                                {{ $slide->subtitle }}
                            </p>
                        @endif

                        {{-- Button --}}
                        @if ($slide->button_link)
                            <div>
                                <a href="{{ $slide->button_link }}"
                                   class="px-8 py-3.5 bg-primary text-white rounded-xl font-bold text-label-md inline-flex items-center justify-center gap-2 hover:bg-primary-container transition-all shadow-lg btn-press">
                                    {{ $slide->button_text ?: 'ອ່ານເພີ່ມເຕີມ' }}
                                    <span class="material-symbols-outlined text-lg">arrow_forward</span>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if ($slides->count() > 1)
            {{-- Left Arrow --}}
            <button @click="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-full bg-white/10 text-white hover:bg-white/20 flex items-center justify-center backdrop-blur-sm border border-white/10 transition-all cursor-pointer">
                <span class="material-symbols-outlined">chevron_left</span>
            </button>
            {{-- Right Arrow --}}
            <button @click="next()" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-full bg-white/10 text-white hover:bg-white/20 flex items-center justify-center backdrop-blur-sm border border-white/10 transition-all cursor-pointer">
                <span class="material-symbols-outlined">chevron_right</span>
            </button>

            {{-- Indicators --}}
            <div class="absolute bottom-10 left-1/2 -translate-x-1/2 z-20 flex gap-2">
                @foreach ($slides as $index => $_)
                    <button @click="activeSlide = {{ $index }}"
                            :class="activeSlide === {{ $index }} ? 'w-8 bg-primary' : 'w-2 bg-white/50 hover:bg-white'"
                            class="h-2 rounded-full transition-all duration-300 cursor-pointer"></button>
                @endforeach
            </div>
        @endif

        {{-- Bottom Wave --}}
        <div class="absolute bottom-0 left-0 w-full z-20">
            <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" class="w-full">
                <path fill="#FFFBEB" d="M0,32L60,37.3C120,43,240,53,360,53.3C480,53,600,43,720,42.7C840,43,960,53,1080,53.3C1200,53,1320,43,1380,37.3L1440,32L1440,80L1380,80C1320,80,1200,80,1080,80C960,80,840,80,720,80C600,80,480,80,360,80C240,80,120,80,60,80L0,80Z"/>
            </svg>
        </div>
    </section>
@else
    {{-- Fallback Static Gradient Hero Section --}}
    <section class="relative overflow-hidden" style="background: linear-gradient(135deg, #8d4b00 0%, #545f73 50%, #765700 100%);">
        {{-- Decorative Pattern Overlay --}}
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-96 h-96 bg-white/10 rounded-full -translate-x-1/2 -translate-y-1/2 blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-80 h-80 bg-white/10 rounded-full translate-x-1/3 translate-y-1/3 blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 w-64 h-64 bg-tertiary/20 rounded-full -translate-x-1/2 -translate-y-1/2 blur-2xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32 relative z-10">
            <div class="text-center">
                {{-- Logo --}}
                <div class="w-24 h-24 lg:w-32 lg:h-32 mx-auto mb-6 bg-white/15 rounded-full flex items-center justify-center backdrop-blur-sm border border-white/20 shadow-2xl animate-fade-in">
                    @if ($orgLogo)
                        <img src="{{ Storage::url($orgLogo) }}" alt="Logo" class="w-full h-full object-cover rounded-full" />
                    @else
                        <span class="material-symbols-outlined text-white text-6xl lg:text-7xl">account_balance</span>
                    @endif
                </div>

                {{-- Title --}}
                <h1 class="text-headline-lg lg:text-[42px] lg:leading-[56px] font-bold text-white mb-3 animate-fade-in" style="animation-delay: 0.1s;">
                    {{ $orgName }}
                </h1>
                <p class="text-body-lg lg:text-xl text-white/80 mb-8 animate-fade-in" style="animation-delay: 0.2s;">
                    {{ $orgNameEn }}
                </p>

                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fade-in" style="animation-delay: 0.3s;">
                    <a href="#news" class="px-8 py-3.5 bg-white text-primary rounded-xl font-bold text-label-md flex items-center justify-center gap-2 hover:bg-primary-fixed transition-all shadow-lg btn-press">
                        <span class="material-symbols-outlined text-lg">newspaper</span>
                        ຂ່າວລ່າສຸດ
                    </a>
                    <a href="#documents" class="px-8 py-3.5 bg-white/10 text-white border border-white/30 rounded-xl font-bold text-label-md flex items-center justify-center gap-2 hover:bg-white/20 transition-all backdrop-blur-sm btn-press">
                        <span class="material-symbols-outlined text-lg">description</span>
                        ເອກະສານ
                    </a>
                </div>
            </div>
        </div>

        {{-- Bottom Wave --}}
        <div class="absolute bottom-0 left-0 w-full">
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
        <div class="glass-card p-5 rounded-xl border border-outline-variant text-center shadow-md hover:shadow-lg transition-shadow">
            <span class="material-symbols-outlined text-3xl text-primary mb-2 block">newspaper</span>
            <p class="text-headline-md text-on-surface">{{ $news->count() }}</p>
            <p class="text-[10px] text-on-surface-variant uppercase tracking-widest">ຂ່າວ / News</p>
        </div>
        <div class="glass-card p-5 rounded-xl border border-outline-variant text-center shadow-md hover:shadow-lg transition-shadow">
            <span class="material-symbols-outlined text-3xl text-tertiary mb-2 block">group</span>
            <p class="text-headline-md text-on-surface">{{ $personnel->count() }}</p>
            <p class="text-[10px] text-on-surface-variant uppercase tracking-widest">ບຸກຄະລາກອນ</p>
        </div>
        <div class="glass-card p-5 rounded-xl border border-outline-variant text-center shadow-md hover:shadow-lg transition-shadow">
            <span class="material-symbols-outlined text-3xl text-secondary mb-2 block">description</span>
            <p class="text-headline-md text-on-surface">{{ $documents->count() }}</p>
            <p class="text-[10px] text-on-surface-variant uppercase tracking-widest">ເອກະສານ</p>
        </div>
        <div class="glass-card p-5 rounded-xl border border-outline-variant text-center shadow-md hover:shadow-lg transition-shadow">
            <span class="material-symbols-outlined text-3xl text-green-600 mb-2 block">verified</span>
            <p class="text-headline-md text-on-surface">{{ $personnel->where('gender', 'monk')->count() }}</p>
            <p class="text-[10px] text-on-surface-variant uppercase tracking-widest">ພຣະສົງ / Monks</p>
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
                <span class="text-[10px] font-bold text-primary uppercase tracking-widest">News & Activities</span>
            </div>
            <h2 class="text-headline-lg text-on-surface">ຂ່າວ ແລະ ກິດຈະກຳ</h2>
        </div>
    </div>

    @if ($news->count() > 0)
        {{-- Featured + Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Featured Card (Large) --}}
            @php $featured = $featuredNews->first() ?? $news->first(); @endphp
            <div class="lg:col-span-2 group">
                <div class="relative bg-white rounded-2xl border border-outline-variant overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">
                    {{-- Image --}}
                    <div class="relative h-64 lg:h-80 overflow-hidden">
                        @if ($featured->cover_image_url)
                            <img src="{{ $featured->cover_image_url }}" alt="{{ $featured->title_lo }}"
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
                                    ແນະນຳ
                                </span>
                            </div>
                        @endif

                        {{-- Title Overlay --}}
                        <div class="absolute bottom-0 left-0 right-0 p-6">
                            <p class="text-xs text-white/80 mb-1">
                                <span class="material-symbols-outlined text-xs align-middle">calendar_today</span>
                                {{ $featured->published_date_formatted }}
                            </p>
                            <h3 class="text-headline-sm text-white leading-tight">{{ $featured->title_lo }}</h3>
                            @if ($featured->title_en)
                                <p class="text-sm text-white/70 mt-1">{{ $featured->title_en }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Excerpt --}}
                    @if ($featured->excerpt_lo || $featured->excerpt_en)
                        <div class="p-5">
                            <p class="text-body-md text-on-surface-variant line-clamp-2">
                                {{ $featured->excerpt_lo ?? $featured->excerpt_en }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Small Cards --}}
            <div class="space-y-4">
                @foreach ($news->skip(1)->take(3) as $item)
                    <div class="group bg-white rounded-xl border border-outline-variant overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300">
                        <div class="flex gap-4 p-4">
                            {{-- Thumbnail --}}
                            <div class="w-20 h-16 rounded-lg overflow-hidden shrink-0">
                                @if ($item->cover_image_url)
                                    <img src="{{ $item->cover_image_url }}" alt="" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
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
                                    {{ $item->title_lo }}
                                </h4>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="text-center py-16 bg-white rounded-2xl border border-outline-variant">
            <span class="material-symbols-outlined text-6xl text-on-surface-variant/20 mb-4 block">newspaper</span>
            <p class="text-body-lg text-on-surface-variant">ຍັງບໍ່ມີຂ່າວ / No news yet</p>
        </div>
    @endif
</section>

{{-- ══════════════════════════════════════════════════════════════
     PERSONNEL SECTION
══════════════════════════════════════════════════════════════ --}}
<section id="personnel" class="bg-white py-20 scroll-mt-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section Header --}}
        <div class="text-center mb-12">
            <div class="flex items-center gap-2 justify-center mb-2">
                <span class="w-8 h-1 bg-tertiary rounded-full"></span>
                <span class="text-[10px] font-bold text-tertiary uppercase tracking-widest">Our People</span>
                <span class="w-8 h-1 bg-tertiary rounded-full"></span>
            </div>
            <h2 class="text-headline-lg text-on-surface mb-2">ບຸກຄະລາກອນ</h2>
            <p class="text-body-md text-on-surface-variant">Personnel & Leadership</p>
        </div>

        @if ($personnel->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($personnel as $person)
                    <div class="group relative bg-surface-container-lowest rounded-2xl border border-outline-variant overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                        {{-- Photo --}}
                        <div class="relative h-48 overflow-hidden">
                            @if ($person->photo_url)
                                <img src="{{ Storage::url($person->photo_url) }}" alt="{{ $person->display_name }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-surface-container to-surface-container-high flex items-center justify-center">
                                    <span class="material-symbols-outlined text-6xl text-on-surface-variant/20">person</span>
                                </div>
                            @endif

                            {{-- Gender Badge --}}
                            <div class="absolute top-3 right-3">
                                @php $badge = $person->gender_badge; @endphp
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold {{ $badge['class'] }} shadow-sm">
                                    {{ $badge['label'] }}
                                </span>
                            </div>

                            {{-- Gradient Overlay --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        </div>

                        {{-- Info --}}
                        <div class="p-4 text-center">
                            @if ($person->display_title)
                                <p class="text-[10px] text-primary font-bold uppercase tracking-widest mb-0.5">{{ $person->display_title }}</p>
                            @endif
                            <h3 class="text-body-md font-bold text-on-surface leading-tight mb-1 group-hover:text-primary transition-colors">
                                {{ $person->display_name }}
                            </h3>
                            @if ($person->display_position)
                                <p class="text-xs text-on-surface-variant">{{ $person->display_position }}</p>
                            @endif
                            @if ($person->department)
                                <p class="text-[10px] text-primary/80 mt-1 flex items-center justify-center gap-1">
                                    <span class="material-symbols-outlined text-[10px]">corporate_fare</span>
                                    {{ $person->department->name }}
                                </p>
                            @endif
                        </div>

                        {{-- Monk accent --}}
                        @if ($person->isMonk())
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-badge-monk"></div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                <span class="material-symbols-outlined text-6xl text-on-surface-variant/20 mb-4 block">group</span>
                <p class="text-body-lg text-on-surface-variant">ຍັງບໍ່ມີບຸກຄະລາກອນ / No personnel yet</p>
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
            <span class="text-[10px] font-bold text-secondary uppercase tracking-widest">Document Library</span>
            <span class="w-8 h-1 bg-secondary rounded-full"></span>
        </div>
        <h2 class="text-headline-lg text-on-surface mb-2">ເອກະສານ</h2>
        <p class="text-body-md text-on-surface-variant">Document Management System (DMS)</p>
    </div>

    @if ($documents->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($documents as $doc)
                <div class="group bg-white rounded-xl border border-outline-variant p-5 shadow-sm hover:shadow-lg transition-all duration-300 hover:border-primary/30 flex items-start gap-4">
                    {{-- File Icon --}}
                    <div class="w-14 h-14 rounded-xl bg-primary/8 flex items-center justify-center shrink-0 group-hover:bg-primary/15 transition-colors">
                        <span class="material-symbols-outlined text-primary text-3xl">{{ $doc->file_icon }}</span>
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-3 mb-1">
                            <div class="min-w-0">
                                <h4 class="text-body-md font-bold text-on-surface group-hover:text-primary transition-colors truncate">
                                    {{ $doc->title_lo }}
                                </h4>
                                @if ($doc->title_en)
                                    <p class="text-xs text-on-surface-variant truncate">{{ $doc->title_en }}</p>
                                @endif
                            </div>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold border shrink-0 {{ $doc->category_color }}">
                                <span class="material-symbols-outlined text-[10px]">{{ $doc->category_icon }}</span>
                                {{ $doc->category_label }}
                            </span>
                        </div>

                        <div class="flex items-center gap-3 text-xs text-on-surface-variant mt-2">
                            @if ($doc->doc_number)
                                <span class="font-mono">{{ $doc->doc_number }}</span>
                                <span class="w-px h-3 bg-outline-variant"></span>
                            @endif
                            @if ($doc->issued_date)
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[10px]">calendar_today</span>
                                    {{ $doc->issued_date->format('d/m/Y') }}
                                </span>
                            @endif
                            @if ($doc->file_path)
                                <span class="w-px h-3 bg-outline-variant"></span>
                                <span>{{ $doc->file_size_formatted }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- Download --}}
                    @if ($doc->file_url)
                        <a href="{{ $doc->file_url }}" target="_blank"
                           class="shrink-0 w-10 h-10 rounded-xl bg-primary/5 text-primary flex items-center justify-center hover:bg-primary hover:text-white transition-all shadow-sm btn-press"
                           title="ດາວໂຫລດ">
                            <span class="material-symbols-outlined text-lg">download</span>
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-16 bg-white rounded-2xl border border-outline-variant">
            <span class="material-symbols-outlined text-6xl text-on-surface-variant/20 mb-4 block">folder_off</span>
            <p class="text-body-lg text-on-surface-variant">ຍັງບໍ່ມີເອກະສານ / No documents yet</p>
        </div>
    @endif
</section>

@endsection
