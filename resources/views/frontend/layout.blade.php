<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ $title ?? $orgName ?? 'ອົງການພຸດທະສາສະໜາສຳພັນ ແຫ່ງ ສປປ ລາວ' }}</title>
    <meta name="description" content="{{ $orgNameEn ?? 'ອົງການພຸດທະສາສະໜາສຳພັນ ແຫ່ງ ສປປ ລາວ' }} — ກຳມາທິການພຸດທະສາສະໜາ, ການສຶກສາສົງ, ເຜີຍແຜ່ສີລະທຳ, ກຳມະຖານ, ທັມມະ, ວັດທະນະທຳ, ພຣະສົງລາວ" />
    <meta name="keywords" content="
        ອົງການພຸດທະສາສະໜາສຳພັນ, ສປປ ລາວ, ພຸດທະສາສະໜາ, ກຳມາທິການ,
        ການສຶກສາສົງ, ເຜີຍແຜ່ສີລະທຳ, ກຳມະຖານ, ທຳມະ, ທັມມະ,
        ພຣະສົງລາວ, ວັດທະນະທຳ, ປະຕິບັດທຳ,
        ວັດ, ສາດສະໜາ, ສາດສະໜາລາວ, ຊາວພຸດ, ພຣະພຸດທ໌, ພຣະທຳ, ພຣະສົງຄ໌,
        ສີນ, ສະມາທິ, ປັນຍາ, ທານ, ບຸນ, ກຸສົນ,
        ໃຫ້ທານ, ບິນທະບາດ, ບວດ, ສາມະເນນ, ອຸປະສົມບົດ,
        ພຣະວິໄນ, ພຣະໄຕປິດົກ, ຄຳສອນ, ໄຕຣສະລະນາຄົມ,
        ໂຮງຮຽນສົງ, ການສຶກສາທາງສາດສະໜາ, ອົງກອນສົງ, ອາຮາມ,
        ມໍລະດົກວັດທະນະທຳ, ທ່ອງທ່ຽວທາງສາດສະໜາ, ວັດລາວ,
        Buddhist Organization Laos, Lao Buddhism, Sangha Education,
        Dhamma, Tipitaka, Vinaya, Meditation, Samadhi, Vipassana,
        Buddhist Culture, Lao Monks, Moral Teaching, Buddhist Committee,
        Lao PDR Buddhism, Buddhist Temple Laos, Merit Making,
        Buddhist Ceremony, Religious Organization, Buddhist Association,
        Ordination, Novice Monk, Buddhist Heritage, Buddhist Practice,
        Almsgiving, Buddhist Education, Lao Sangha
    " />
    <meta name="robots" content="index, follow" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ $title ?? $orgName ?? 'ອົງການພຸດທະສາສະໜາສຳພັນ ແຫ່ງ ສປປ ລາວ' }}" />
    <meta property="og:description" content="ອົງການພຸດທະສາສະໜາສຳພັນ ແຫ່ງ ສປປ ລາວ — ກຳມາທິການ, ການສຶກສາສົງ, ເຜີຍແຜ່ສີລະທຳ, ກຳມະຖານ, ທັມມະ, ວັດທະນະທຳ, ພຣະສົງລາວ, ປະຕິບັດທຳ" />
    @if ($orgLogo ?? false)
        <meta property="og:image" content="{{ Storage::url($orgLogo) }}" />
    @endif
    <meta property="og:locale" content="lo_LA" />
    <meta property="og:locale:alternate" content="en_US" />

    @if ($orgLogo ?? false)
        <link rel="icon" type="image/png" href="{{ Storage::url($orgLogo) }}" />
        <link rel="apple-touch-icon" href="{{ Storage::url($orgLogo) }}" />
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}" />
    @endif

    {{-- ══ Performance: preconnect ══ --}}
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin />
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net" />

    {{-- Material Symbols: pinned version + async (non-render-blocking) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/material-symbols@0.44.12/outlined.css"
          media="print" onload="this.media='all'" />
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/material-symbols@0.44.12/outlined.css" /></noscript>

    <!-- Alpine.js CDN for public frontend page interactive components -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.9/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased text-on-surface" style="background-color: #FFFBEB;">

    {{-- ══════════════════════════════════════════
         NAVIGATION BAR
    ══════════════════════════════════════════ --}}
    <header class="sticky top-0 z-50 transition-all duration-300"
            x-data="{
                scrolled: false,
                mobileMenu: false,
                activeSection: 'home',
                initObserver() {
                    const sections = ['news', 'personnel', 'documents'];
                    const observer = new IntersectionObserver(entries => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) this.activeSection = entry.target.id;
                        });
                    }, { rootMargin: '-30% 0px -60% 0px', threshold: 0 });

                    sections.forEach(id => {
                        const el = document.getElementById(id);
                        if (el) observer.observe(el);
                    });

                    window.addEventListener('scroll', () => {
                        if (window.scrollY < 200) this.activeSection = 'home';
                    }, { passive: true });
                }
            }"
            x-init="initObserver()"
            @scroll.window="scrolled = window.scrollY > 40"
            :class="scrolled ? 'bg-white/95 backdrop-blur-lg shadow-md' : 'bg-transparent'">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-20">
                {{-- Logo + Name --}}
                <a href="{{ route('frontend.index') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-full bg-primary/10 flex items-center justify-center shrink-0 overflow-hidden transition-transform group-hover:scale-105">
                        @if ($orgLogo ?? false)
                            <img src="{{ Storage::url($orgLogo) }}" alt="Logo" class="w-full h-full object-cover rounded-full" />
                        @else
                            <span class="material-symbols-outlined text-primary text-2xl lg:text-3xl">account_balance</span>
                        @endif
                    </div>
                    <div class="hidden sm:block">
                        <h1 class="text-headline-sm text-on-surface leading-tight">{{ $orgName ?? 'ອົງການພຸດທະສາສະໜາ' }}</h1>
                        <p class="text-[10px] text-on-surface-variant tracking-wider uppercase">{{ $orgNameEn ?? 'Buddhist Organization' }}</p>
                    </div>
                </a>

                {{-- Desktop Nav --}}
                <nav class="hidden lg:flex items-center gap-1">
                    @php
                        $navActive       = 'px-4 py-2 rounded-lg text-label-md text-primary font-bold bg-primary/5 transition-all';
                        $navInactive     = 'px-4 py-2 rounded-lg text-label-md text-on-surface-variant hover:text-primary hover:bg-primary/5 transition-all';
                        $isHomePage      = request()->routeIs('frontend.index');
                        $isNewsPage      = request()->routeIs('frontend.news', 'frontend.news.show');
                        $isPersonnelPage = request()->routeIs('frontend.personnel', 'frontend.personnel.show');
                        $isDocumentsPage = request()->routeIs('frontend.documents');
                        $isAboutPage     = request()->routeIs('frontend.about', 'frontend.structure', 'frontend.history', 'frontend.duties', 'frontend.guide');
                        $isStructurePage = request()->routeIs('frontend.structure');
                        $isHistoryPage   = request()->routeIs('frontend.history');
                        $isDutiesPage    = request()->routeIs('frontend.duties');
                        $isGuidePage     = request()->routeIs('frontend.guide');
                    @endphp

                    <a href="{{ route('frontend.index') }}"
                       @if($isHomePage)
                           :class="activeSection === 'home' ? '{{ $navActive }}' : '{{ $navInactive }}'"
                       @else
                           class="{{ $navInactive }}"
                       @endif>
                        {{ __('messages.homepage') }}
                    </a>
                    <a href="{{ route('frontend.personnel') }}"
                       @if($isPersonnelPage)
                           class="{{ $navActive }}"
                       @elseif($isHomePage)
                           :class="activeSection === 'personnel' ? '{{ $navActive }}' : '{{ $navInactive }}'"
                       @else
                           class="{{ $navInactive }}"
                       @endif>
                        {{ __('messages.personnel') }}
                    </a>
                    <a href="{{ route('frontend.documents') }}"
                       @if($isDocumentsPage)
                           class="{{ $navActive }}"
                       @elseif($isHomePage)
                           :class="activeSection === 'documents' ? '{{ $navActive }}' : '{{ $navInactive }}'"
                       @else
                           class="{{ $navInactive }}"
                       @endif>
                        {{ __('messages.documents_nav') }}
                    </a>
                    <a href="{{ route('frontend.news') }}"
                       @if($isNewsPage)
                           class="{{ $navActive }}"
                       @elseif($isHomePage)
                           :class="activeSection === 'news' ? '{{ $navActive }}' : '{{ $navInactive }}'"
                       @else
                           class="{{ $navInactive }}"
                       @endif>
                        {{ __('messages.news') }}
                    </a>

                    {{-- About Dropdown Menu --}}
                    <div class="relative" x-data="{ aboutOpen: false }" @mouseenter="aboutOpen = true" @mouseleave="aboutOpen = false">
                        <a href="{{ route('frontend.about') }}"
                           class="flex items-center gap-1 {{ $isAboutPage ? $navActive : $navInactive }}">
                            <span>{{ __('messages.about_nav') }}</span>
                            <span class="material-symbols-outlined text-base transition-transform duration-200"
                                  :class="aboutOpen ? 'rotate-180 text-primary' : 'text-on-surface-variant/70'">expand_more</span>
                        </a>

                        {{-- Dropdown Card --}}
                        <div x-show="aboutOpen"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                             class="absolute left-0 mt-1 w-64 rounded-2xl bg-white shadow-xl border border-outline-variant/60 py-2 z-50 overflow-hidden"
                             style="display:none; box-shadow: 0 18px 40px -6px rgba(44, 26, 8, 0.16), 0 0 0 1px rgba(200, 146, 26, 0.14);">

                            {{-- ໂຄງຮ່າງການຈັດຕັ້ງ (Primary Submenu) --}}
                            <a href="{{ route('frontend.structure') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-xs text-on-surface hover:bg-amber-500/10 hover:text-amber-800 transition-colors {{ $isStructurePage ? 'bg-amber-500/15 font-bold text-amber-900' : '' }}">
                                <span class="w-8 h-8 rounded-lg bg-amber-500/15 text-amber-700 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-base">account_tree</span>
                                </span>
                                <div>
                                    <div class="font-bold leading-tight">{{ __('messages.structure_nav') }}</div>
                                    <div class="text-[10px] text-on-surface-variant font-normal">ຜັງໂຄງຮ່າງ ແລະ ສາຍການຈັດຕັ້ງ</div>
                                </div>
                            </a>

                            {{-- ປະຫວັດອົງການ --}}
                            <a href="{{ route('frontend.history') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-xs text-on-surface hover:bg-emerald-500/10 hover:text-emerald-800 transition-colors {{ $isHistoryPage ? 'bg-emerald-500/15 font-bold text-emerald-900' : '' }}">
                                <span class="w-8 h-8 rounded-lg bg-emerald-500/15 text-emerald-700 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-base">history_edu</span>
                                </span>
                                <div>
                                    <div class="font-bold leading-tight">{{ __('messages.about_history_title') }}</div>
                                    <div class="text-[10px] text-on-surface-variant font-normal">ຄວາມເປັນມາ ແລະ ການພັດທະນາ</div>
                                </div>
                            </a>

                            {{-- ສິດໜ້າທີ່ ວຽກງານ --}}
                            <a href="{{ route('frontend.duties') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-xs text-on-surface hover:bg-blue-500/10 hover:text-blue-800 transition-colors {{ $isDutiesPage ? 'bg-blue-500/15 font-bold text-blue-900' : '' }}">
                                <span class="w-8 h-8 rounded-lg bg-blue-500/15 text-blue-700 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-base">assignment_ind</span>
                                </span>
                                <div>
                                    <div class="font-bold leading-tight">{{ __('messages.about_duties_title') }}</div>
                                    <div class="text-[10px] text-on-surface-variant font-normal">ພາລະບົດບາດ, ສິດ ແລະ ໜ້າທີ່</div>
                                </div>
                            </a>

                            {{-- ຄູ່ມື ແລະ ລະບຽບການ --}}
                            <a href="{{ route('frontend.guide') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-xs text-on-surface hover:bg-amber-600/10 hover:text-amber-900 transition-colors {{ $isGuidePage ? 'bg-amber-600/15 font-bold text-amber-900' : '' }}">
                                <span class="w-8 h-8 rounded-lg bg-amber-600/15 text-amber-800 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-base">menu_book</span>
                                </span>
                                <div>
                                    <div class="font-bold leading-tight">{{ __('messages.about_manual_title') }}</div>
                                    <div class="text-[10px] text-on-surface-variant font-normal">ຂໍ້ກຳນົດ ແລະ ຄູ່ມືການດຳເນີນງານ</div>
                                </div>
                            </a>

                            <div class="my-1 border-t border-outline-variant/40"></div>

                            {{-- ພາບລວມ / Overview --}}
                            <a href="{{ route('frontend.about') }}"
                               class="flex items-center gap-3 px-4 py-2 text-xs text-on-surface hover:bg-primary/5 hover:text-primary transition-colors {{ request()->routeIs('frontend.about') ? 'text-primary font-bold bg-primary/10' : '' }}">
                                <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-base">info</span>
                                </span>
                                <div>
                                    <div class="font-bold leading-tight">{{ __('messages.about_nav') }} (ພາບລວມ)</div>
                                    <div class="text-[10px] text-on-surface-variant font-normal">ສະຫຼຸບຂໍ້ມູນອົງການ ແລະ ຊ່ອງທາງບໍລິຈາກ</div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="h-6 w-px bg-outline-variant mx-2"></div>

                    {{-- Language Toggle --}}
                    <a href="{{ route('locale.switch', ['locale' => app()->getLocale() === 'lo' ? 'en' : 'lo']) }}"
                       title="{{ app()->getLocale() === 'lo' ? __('messages.switch_to_en') : __('messages.switch_to_lo') }}"
                       class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-label-md text-on-surface-variant hover:text-primary hover:bg-primary/5 transition-all">
                        <span class="material-symbols-outlined text-base">language</span>
                        <span class="text-xs font-bold uppercase tracking-wide">{{ app()->getLocale() === 'lo' ? 'EN' : 'ລາວ' }}</span>
                    </a>

                    <div class="h-6 w-px bg-outline-variant mx-2"></div>
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-primary text-white rounded-lg text-label-md font-bold hover:bg-primary-container transition-all btn-press">
                            <span class="material-symbols-outlined text-sm align-middle mr-1">dashboard</span>
                            {{ __('messages.admin_panel') }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 border border-primary text-primary rounded-lg text-label-md font-bold hover:bg-primary hover:text-white transition-all">
                            <span class="material-symbols-outlined text-sm align-middle mr-1">login</span>
                            {{ __('messages.login') }}
                        </a>
                    @endauth
                </nav>

                {{-- Mobile Hamburger --}}
                <button @click="mobileMenu = !mobileMenu" class="lg:hidden p-2 text-on-surface-variant hover:bg-surface-container rounded-lg transition-colors">
                    <span class="material-symbols-outlined text-2xl" x-text="mobileMenu ? 'close' : 'menu'">menu</span>
                </button>
            </div>

            {{-- Mobile Menu --}}
            <div x-show="mobileMenu" x-transition.opacity class="lg:hidden py-4 border-t border-outline-variant space-y-1" style="display:none;">
                @php
                    $mobileActive   = 'block px-4 py-2.5 rounded-lg text-label-md text-primary font-bold bg-primary/5';
                    $mobileInactive = 'block px-4 py-2.5 rounded-lg text-label-md text-on-surface-variant hover:bg-primary/5';
                @endphp
                <a href="{{ route('frontend.index') }}"
                   @if($isHomePage)
                       :class="activeSection === 'home' ? '{{ $mobileActive }}' : '{{ $mobileInactive }}'"
                   @else
                       class="{{ $mobileInactive }}"
                   @endif>{{ __('messages.homepage') }}</a>
                <a href="{{ route('frontend.personnel') }}" @click="mobileMenu = false"
                   @if($isPersonnelPage) class="{{ $mobileActive }}"
                   @elseif($isHomePage) :class="activeSection === 'personnel' ? '{{ $mobileActive }}' : '{{ $mobileInactive }}'"
                   @else class="{{ $mobileInactive }}" @endif>{{ __('messages.personnel') }}</a>
                <a href="{{ route('frontend.documents') }}" @click="mobileMenu = false"
                   @if($isDocumentsPage) class="{{ $mobileActive }}"
                   @elseif($isHomePage) :class="activeSection === 'documents' ? '{{ $mobileActive }}' : '{{ $mobileInactive }}'"
                   @else class="{{ $mobileInactive }}" @endif>{{ __('messages.documents_nav') }}</a>
                <a href="{{ route('frontend.news') }}" @click="mobileMenu = false"
                   @if($isNewsPage) class="{{ $mobileActive }}"
                   @elseif($isHomePage) :class="activeSection === 'news' ? '{{ $mobileActive }}' : '{{ $mobileInactive }}'"
                   @else class="{{ $mobileInactive }}" @endif>{{ __('messages.news') }}</a>

                {{-- Mobile About Submenu Accordion --}}
                <div x-data="{ aboutOpen: {{ $isAboutPage ? 'true' : 'false' }} }">
                    <div class="flex items-center justify-between {{ $isAboutPage ? $mobileActive : $mobileInactive }}">
                        <a href="{{ route('frontend.about') }}" @click="mobileMenu = false" class="flex-1">
                            {{ __('messages.about_nav') }}
                        </a>
                        <button type="button" @click.stop="aboutOpen = !aboutOpen" class="p-1 hover:bg-black/5 rounded">
                            <span class="material-symbols-outlined text-base transition-transform duration-200"
                                  :class="aboutOpen ? 'rotate-180' : ''">expand_more</span>
                        </button>
                    </div>
                    <div x-show="aboutOpen" x-transition class="pl-4 pr-2 py-1 space-y-1">
                        <a href="{{ route('frontend.structure') }}" @click="mobileMenu = false"
                           class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs {{ $isStructurePage ? 'text-amber-900 font-bold bg-amber-500/15' : 'text-on-surface-variant hover:bg-primary/5' }}">
                            <span class="material-symbols-outlined text-sm text-amber-600">account_tree</span>
                            <span class="font-bold">{{ __('messages.structure_nav') }}</span>
                        </a>
                        <a href="{{ route('frontend.history') }}" @click="mobileMenu = false"
                           class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs {{ $isHistoryPage ? 'text-emerald-900 font-bold bg-emerald-500/15' : 'text-on-surface-variant hover:bg-primary/5' }}">
                            <span class="material-symbols-outlined text-sm text-emerald-600">history_edu</span>
                            <span>{{ __('messages.about_history_title') }}</span>
                        </a>
                        <a href="{{ route('frontend.duties') }}" @click="mobileMenu = false"
                           class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs {{ $isDutiesPage ? 'text-blue-900 font-bold bg-blue-500/15' : 'text-on-surface-variant hover:bg-primary/5' }}">
                            <span class="material-symbols-outlined text-sm text-blue-600">assignment_ind</span>
                            <span>{{ __('messages.about_duties_title') }}</span>
                        </a>
                        <a href="{{ route('frontend.guide') }}" @click="mobileMenu = false"
                           class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs {{ $isGuidePage ? 'text-amber-900 font-bold bg-amber-600/15' : 'text-on-surface-variant hover:bg-primary/5' }}">
                            <span class="material-symbols-outlined text-sm text-amber-700">menu_book</span>
                            <span>{{ __('messages.about_manual_title') }}</span>
                        </a>
                        <a href="{{ route('frontend.about') }}" @click="mobileMenu = false"
                           class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs {{ request()->routeIs('frontend.about') ? 'text-primary font-bold bg-primary/10' : 'text-on-surface-variant hover:bg-primary/5' }}">
                            <span class="material-symbols-outlined text-sm text-primary">info</span>
                            <span>{{ __('messages.about_nav') }} (ພາບລວມ)</span>
                        </a>
                    </div>
                </div>
                <div class="pt-2 border-t border-outline-variant space-y-1">
                    {{-- Language Toggle --}}
                    <a href="{{ route('locale.switch', ['locale' => app()->getLocale() === 'lo' ? 'en' : 'lo']) }}"
                       class="flex items-center gap-2 px-4 py-2.5 rounded-lg text-label-md text-on-surface-variant hover:bg-primary/5">
                        <span class="material-symbols-outlined text-base">language</span>
                        <span>{{ app()->getLocale() === 'lo' ? __('messages.switch_to_en') : __('messages.switch_to_lo') }}</span>
                    </a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="block px-4 py-2.5 rounded-lg bg-primary text-white text-label-md font-bold text-center">{{ __('messages.admin_panel') }}</a>
                    @else
                        <a href="{{ route('login') }}" class="block px-4 py-2.5 rounded-lg border border-primary text-primary text-label-md font-bold text-center">{{ __('messages.login') }}</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    {{-- ══════════════════════════════════════════
         MAIN CONTENT
    ══════════════════════════════════════════ --}}
    <main>
        @yield('content')
    </main>

    {{-- ══════════════════════════════════════════
         FOOTER
    ══════════════════════════════════════════ --}}
    <footer class="relative text-white mt-16 overflow-hidden" style="background: linear-gradient(to bottom, #1e2d3d 0%, #141c27 55%, #0e1520 100%);">

        {{-- Subtle gold dot-matrix texture --}}
        <div class="absolute inset-0 pointer-events-none" style="background-image: radial-gradient(circle, rgba(212,175,55,0.055) 1px, transparent 1px); background-size: 30px 30px;"></div>

        {{-- Top Wave --}}
        <div class="relative -mt-16">
            <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" class="w-full block" preserveAspectRatio="none" style="height:80px;">
                <path fill="#1e2d3d" d="M0,44L60,40C120,36,240,28,360,29.3C480,31,600,41,720,46.7C840,52,960,50,1080,44C1200,38,1320,29,1380,25.3L1440,22L1440,80L1380,80C1320,80,1200,80,1080,80C960,80,840,80,720,80C600,80,480,80,360,80C240,80,120,80,60,80L0,80Z"/>
            </svg>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Sacred gold divider --}}
            <div class="flex justify-center -mt-1 mb-10">
                <div class="flex items-center gap-4">
                    <div class="h-px w-24 bg-gradient-to-r from-transparent to-[#D4AF37]"></div>
                    <span class="material-symbols-outlined text-[#D4AF37]" style="font-size:20px; filter:drop-shadow(0 0 6px rgba(212,175,55,0.6));">spa</span>
                    <div class="h-px w-24 bg-gradient-to-l from-transparent to-[#D4AF37]"></div>
                </div>
            </div>

            @php $orgFacebookPage = \App\Models\Setting::get('org_facebook_page'); @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 {{ $orgFacebookPage ? 'lg:grid-cols-4' : 'lg:grid-cols-3' }} gap-10 lg:gap-14 pb-12">

                {{-- Org Info --}}
                <div class="space-y-4">
                    <div class="flex items-center gap-4">
                        <div class="relative w-14 h-14 flex-shrink-0">
                            <div class="absolute inset-0 rounded-full border border-[#D4AF37]/25 scale-110"></div>
                            <div class="relative w-full h-full rounded-full border-2 border-[#D4AF37]/50 bg-white/5 flex items-center justify-center overflow-hidden" style="box-shadow: 0 0 20px rgba(212,175,55,0.15);">
                                @if ($orgLogo ?? false)
                                    <img src="{{ Storage::url($orgLogo) }}" alt="Logo" loading="lazy" class="w-full h-full object-cover rounded-full" />
                                @else
                                    <span class="material-symbols-outlined text-[#D4AF37]" style="font-size:26px;">account_balance</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-white leading-tight" style="font-size:15px;">{{ $orgName ?? 'ອົງການພຸດທະສາສະໜາ' }}</h3>
                            <p class="text-[#D4AF37]/60 mt-1 tracking-wide" style="font-size:11px;">{{ $orgNameEn ?? '' }}</p>
                        </div>
                    </div>
                    <p class="text-white/45 leading-relaxed" style="font-size:13px;">
                        {{ __('messages.app_name') }}
                    </p>
                    <div class="w-10 h-0.5 rounded-full" style="background: linear-gradient(to right, #D4AF37, transparent);"></div>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h4 class="font-bold text-[#D4AF37] uppercase mb-5 flex items-center gap-2.5" style="font-size:11px; letter-spacing:0.18em;">
                        <span class="h-px w-5 rounded-full bg-[#D4AF37] inline-block"></span>
                        {{ __('messages.quick_links') }}
                    </h4>
                    <ul class="space-y-2.5">
                        <li>
                            <a href="{{ route('frontend.news') }}"
                               class="group flex items-center gap-3 text-white/50 hover:text-[#D4AF37] transition-colors duration-200"
                               style="font-size:13px;">
                                <span class="w-6 h-6 rounded border border-white/10 bg-white/5 group-hover:border-[#D4AF37]/40 group-hover:bg-[#D4AF37]/10 flex items-center justify-center flex-shrink-0 transition-all duration-200">
                                    <span class="material-symbols-outlined" style="font-size:13px;">newspaper</span>
                                </span>
                                <span class="relative">
                                    {{ __('messages.news_activities') }}
                                    <span class="absolute -bottom-px left-0 h-px w-0 bg-[#D4AF37]/60 group-hover:w-full transition-all duration-300 rounded-full"></span>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('frontend.personnel') }}"
                               class="group flex items-center gap-3 text-white/50 hover:text-[#D4AF37] transition-colors duration-200"
                               style="font-size:13px;">
                                <span class="w-6 h-6 rounded border border-white/10 bg-white/5 group-hover:border-[#D4AF37]/40 group-hover:bg-[#D4AF37]/10 flex items-center justify-center flex-shrink-0 transition-all duration-200">
                                    <span class="material-symbols-outlined" style="font-size:13px;">group</span>
                                </span>
                                <span class="relative">
                                    {{ __('messages.personnel') }}
                                    <span class="absolute -bottom-px left-0 h-px w-0 bg-[#D4AF37]/60 group-hover:w-full transition-all duration-300 rounded-full"></span>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('frontend.documents') }}"
                               class="group flex items-center gap-3 text-white/50 hover:text-[#D4AF37] transition-colors duration-200"
                               style="font-size:13px;">
                                <span class="w-6 h-6 rounded border border-white/10 bg-white/5 group-hover:border-[#D4AF37]/40 group-hover:bg-[#D4AF37]/10 flex items-center justify-center flex-shrink-0 transition-all duration-200">
                                    <span class="material-symbols-outlined" style="font-size:13px;">description</span>
                                </span>
                                <span class="relative">
                                    {{ __('messages.documents_nav') }}
                                    <span class="absolute -bottom-px left-0 h-px w-0 bg-[#D4AF37]/60 group-hover:w-full transition-all duration-300 rounded-full"></span>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('frontend.structure') }}"
                               class="group flex items-center gap-3 text-white/50 hover:text-[#D4AF37] transition-colors duration-200"
                               style="font-size:13px;">
                                <span class="w-6 h-6 rounded border border-white/10 bg-white/5 group-hover:border-[#D4AF37]/40 group-hover:bg-[#D4AF37]/10 flex items-center justify-center flex-shrink-0 transition-all duration-200">
                                    <span class="material-symbols-outlined" style="font-size:13px;">account_tree</span>
                                </span>
                                <span class="relative">
                                    {{ __('messages.structure_nav') }}
                                    <span class="absolute -bottom-px left-0 h-px w-0 bg-[#D4AF37]/60 group-hover:w-full transition-all duration-300 rounded-full"></span>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('frontend.about') }}"
                               class="group flex items-center gap-3 text-white/50 hover:text-[#D4AF37] transition-colors duration-200"
                               style="font-size:13px;">
                                <span class="w-6 h-6 rounded border border-white/10 bg-white/5 group-hover:border-[#D4AF37]/40 group-hover:bg-[#D4AF37]/10 flex items-center justify-center flex-shrink-0 transition-all duration-200">
                                    <span class="material-symbols-outlined" style="font-size:13px;">info</span>
                                </span>
                                <span class="relative">
                                    {{ __('messages.about_nav') }}
                                    <span class="absolute -bottom-px left-0 h-px w-0 bg-[#D4AF37]/60 group-hover:w-full transition-all duration-300 rounded-full"></span>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Contact --}}
                <div>
                    <h4 class="font-bold text-[#D4AF37] uppercase mb-5 flex items-center gap-2.5" style="font-size:11px; letter-spacing:0.18em;">
                        <span class="h-px w-5 rounded-full bg-[#D4AF37] inline-block"></span>
                        {{ __('messages.contact') }}
                    </h4>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3 text-white/50" style="font-size:13px;">
                            <span class="w-7 h-7 rounded-lg border border-[#D4AF37]/20 bg-[#D4AF37]/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="material-symbols-outlined text-[#D4AF37]" style="font-size:14px;">location_on</span>
                            </span>
                            <span class="leading-relaxed pt-0.5">{{ \App\Models\Setting::get('org_address', 'ນະຄອນຫຼວງວຽງຈັນ, ສ.ປ.ປ ລາວ') }}</span>
                        </li>
                        <li class="flex items-center gap-3 text-white/50" style="font-size:13px;">
                            <span class="w-7 h-7 rounded-lg border border-[#D4AF37]/20 bg-[#D4AF37]/10 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-[#D4AF37]" style="font-size:14px;">phone</span>
                            </span>
                            {{ \App\Models\Setting::get('org_phone', '021-XXX-XXX') }}
                        </li>
                        <li class="flex items-center gap-3 text-white/50" style="font-size:13px;">
                            <span class="w-7 h-7 rounded-lg border border-[#D4AF37]/20 bg-[#D4AF37]/10 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-[#D4AF37]" style="font-size:14px;">mail</span>
                            </span>
                            {{ \App\Models\Setting::get('org_email', 'info@example.org') }}
                        </li>
                    </ul>
                </div>
                {{-- Facebook Page URL --}}
                @if ($orgFacebookPage)
                    <div>
                        <h4 class="font-bold text-[#D4AF37] uppercase mb-5 flex items-center gap-2.5" style="font-size:11px; letter-spacing:0.18em;">
                            <span class="h-px w-5 rounded-full bg-[#D4AF37] inline-block"></span>
                            {{ __('messages.follow_us') }}
                        </h4>
                        <a href="{{ $orgFacebookPage }}"
                           target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-2.5 rounded-xl border border-white/10 bg-white/5 hover:bg-[#1877F2]/10 hover:border-[#1877F2]/40 px-4 py-2.5 text-white/70 hover:text-white transition-all duration-200"
                           style="font-size:13px;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#1877F2" style="width:16px; height:16px;">
                                <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.891h-2.33v6.987C18.343 21.128 22 16.991 22 12z"/>
                            </svg>
                            {{ __('messages.view_facebook_page') }}
                        </a>
                    </div>
                @endif
            </div>

            {{-- Bottom bar --}}
            <div class="border-t pt-5 pb-7 flex flex-col sm:flex-row justify-between items-center gap-3" style="border-color: rgba(212,175,55,0.15);">
                <p class="text-white/30" style="font-size:11px;">
                    &copy; {{ date('Y') }}
                    <span class="text-[#D4AF37]/50">{{ $orgNameEn ?? 'Buddhist Organization' }}</span>.
                    ສະຫງວນລິຂະສິດ
                </p>
                <p class="text-white/30 flex items-center gap-1.5" style="font-size:11px;">
                    <span class="material-symbols-outlined text-[#D4AF37]/40" style="font-size:12px;">bolt</span>
                    {{ __('messages.powered_by') }}
                </p>
            </div>
        </div>
    </footer>

</body>
</html>
