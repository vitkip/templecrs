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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/material-symbols@latest/outlined.css" />

    <!-- Alpine.js CDN for public frontend page interactive components -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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
                        $isPersonnelPage = request()->routeIs('frontend.personnel');
                        $isDocumentsPage = request()->routeIs('frontend.documents');
                        $isAboutPage     = request()->routeIs('frontend.about');
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
                    <a href="{{ route('frontend.about') }}"
                       class="{{ $isAboutPage ? $navActive : $navInactive }}">
                        {{ __('messages.about_nav') }}
                    </a>
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
                <a href="{{ route('frontend.about') }}" @click="mobileMenu = false"
                   class="{{ $isAboutPage ? $mobileActive : $mobileInactive }}">{{ __('messages.about_nav') }}</a>
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

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 lg:gap-14 pb-12">

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
