<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ $title ?? __('messages.app_name') }} — Buddhist EMS</title>
    <meta name="description" content="ອົງການພຸດທະສາສະໜາສຳພັນ ແຫ່ງ ສປປ ລາວ — ລະບົບຈັດການອົງການພຣະພຸດທະສາສະໜາ, ກຳມາທິການພຸດທະສາສະໜາ, ການສຶກສາສົງ, ເຜີຍແຜ່ສີລະທຳ, ກຳມະຖານ, ທັມມະ, ວັດທະນະທຳ" />
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
    <meta property="og:title" content="{{ $title ?? __('messages.app_name') }} — ອົງການພຸດທະສາສະໜາສຳພັນ ແຫ່ງ ສປປ ລາວ" />
    <meta property="og:description" content="ລະບົບຈັດການອົງການພຣະພຸດທະສາສະໜາ — ກຳມາທິການ, ການສຶກສາສົງ, ເຜີຍແຜ່ສີລະທຳ, ກຳມະຖານ, ທັມມະ, ວັດທະນະທຳ, ພຣະສົງລາວ" />
    <meta property="og:locale" content="lo_LA" />
    <meta property="og:locale:alternate" content="en_US" />

    @php $faviconLogo = \App\Models\Setting::get('org_logo_url'); @endphp
    @if ($faviconLogo)
        <link rel="icon" type="image/png" href="{{ Storage::url($faviconLogo) }}" />
        <link rel="apple-touch-icon" href="{{ Storage::url($faviconLogo) }}" />
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}" />
    @endif

    <!-- Phetsarath + Noto: self-hosted via build | Material Symbols: jsDelivr CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/material-symbols@latest/outlined.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @stack('head')
</head>
<body class="text-body-md text-on-surface antialiased"
      x-data="{ sidebarOpen: false }"
      @keydown.escape.window="sidebarOpen = false">

    {{-- ══════════════════════════════════════════
         OVERLAY — mobile/tablet only
    ══════════════════════════════════════════ --}}
    <div x-show="sidebarOpen"
         x-transition:enter="transition-opacity duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/50 z-40 lg:hidden"
         style="display:none;"></div>

    {{-- ══════════════════════════════════════════
         SIDEBAR
    ══════════════════════════════════════════ --}}
    @php
        $navItems = [
            ['route' => 'dashboard',       'icon' => 'dashboard',       'label' => __('messages.dashboard'),   'match' => 'dashboard'],
            ['route' => 'personnel.index', 'icon' => 'group',           'label' => __('messages.personnel'),   'match' => 'personnel.*'],
            ['route' => 'settings',        'icon' => 'category',        'label' => __('messages.departments'), 'match' => null, 'query' => '?tab=departments', 'active_if' => request()->routeIs('settings') && request()->input('tab') === 'departments'],
            ['route' => null,              'icon' => 'description',     'label' => __('messages.documents'),   'match' => 'documents*'],
            ['route' => null,              'icon' => 'assessment',      'label' => __('messages.reports'),     'match' => 'reports*'],
        ];
    @endphp

    <aside id="sidebar"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="fixed left-0 top-0 h-full flex flex-col z-50 bg-secondary w-[280px] shadow-xl
                  transform transition-transform duration-300 ease-in-out">

        {{-- Brand + Close --}}
        <div class="relative p-6 flex flex-col items-center border-b border-white/10 shrink-0">
            {{-- Close button — mobile only --}}
            <button @click="sidebarOpen = false"
                    class="lg:hidden absolute top-4 right-4 text-white/60 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors">
                <span class="material-symbols-outlined text-xl">close</span>
            </button>

            <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mb-3">
                @php $logoUrl = \App\Models\Setting::get('org_logo_url'); @endphp
                @if ($logoUrl)
                    <img src="{{ Storage::url($logoUrl) }}" alt="Logo" class="w-full h-full object-cover rounded-full" />
                @else
                    <span class="material-symbols-outlined text-white text-4xl">account_balance</span>
                @endif
            </div>
            <h1 class="text-headline-sm font-bold text-white leading-tight text-center">
                {{ \App\Models\Setting::get('org_name_lo', 'Buddhist EMS') }}
            </h1>
            <p class="text-label-md text-secondary-fixed-dim opacity-70 mt-0.5">
                {{ \App\Models\Setting::get('org_name_en', 'Administrative Portal') }}
            </p>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-4 overflow-y-auto space-y-0.5">

            {{-- Dashboard — all roles --}}
            @php $isDashboard = request()->routeIs('dashboard'); @endphp
            <a href="{{ route('dashboard') }}"
               @click="sidebarOpen = false"
               class="group flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200
                      {{ $isDashboard ? 'bg-white/15 text-tertiary-fixed-dim font-bold border-l-4 border-tertiary-fixed-dim sidebar-active-glow' : 'text-secondary-fixed-dim hover:bg-white/8 hover:text-white' }}">
                <span class="material-symbols-outlined text-xl shrink-0 {{ $isDashboard ? 'filled' : '' }}">dashboard</span>
                <span class="text-label-md">{{ __('messages.dashboard') }}</span>
                @if ($isDashboard)
                    <span class="ml-auto w-1.5 h-1.5 rounded-full bg-tertiary-fixed-dim"></span>
                @endif
            </a>

            {{-- Section: ເນື້ອຫາ — admin + superadmin (personnel/docs/news) or manager (finance) --}}
            @if (auth()->user()->isAdmin() || auth()->user()->isManager())
                <div class="pt-4 pb-1 px-3">
                    <p class="text-[9px] font-bold uppercase tracking-widest text-white/25">ເນື້ອຫາ</p>
                </div>
            @endif

            {{-- Personnel — superadmin + admin --}}
            @if (auth()->user()->isAdmin())
                @php $isPersonnel = request()->routeIs('personnel.*'); @endphp
                <a href="{{ route('personnel.index') }}"
                   @click="sidebarOpen = false"
                   class="group flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200
                          {{ $isPersonnel ? 'bg-white/15 text-tertiary-fixed-dim font-bold border-l-4 border-tertiary-fixed-dim sidebar-active-glow' : 'text-secondary-fixed-dim hover:bg-white/8 hover:text-white' }}">
                    <span class="material-symbols-outlined text-xl shrink-0 {{ $isPersonnel ? 'filled' : '' }}">group</span>
                    <span class="text-label-md">{{ __('messages.personnel') }}</span>
                    @if ($isPersonnel)
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-tertiary-fixed-dim"></span>
                    @endif
                </a>
            @endif

            {{-- Documents — superadmin + admin --}}
            @if (auth()->user()->isAdmin())
                @php $isDocs = request()->routeIs('documents.*'); @endphp
                <a href="{{ route('documents.index') }}"
                   @click="sidebarOpen = false"
                   class="group flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200
                          {{ $isDocs ? 'bg-white/15 text-tertiary-fixed-dim font-bold border-l-4 border-tertiary-fixed-dim sidebar-active-glow' : 'text-secondary-fixed-dim hover:bg-white/8 hover:text-white' }}">
                    <span class="material-symbols-outlined text-xl shrink-0 {{ $isDocs ? 'filled' : '' }}">description</span>
                    <span class="text-label-md">{{ __('messages.documents') }}</span>
                    @if ($isDocs)
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-tertiary-fixed-dim"></span>
                    @endif
                </a>
            @endif

            {{-- News — superadmin + admin --}}
            @if (auth()->user()->isAdmin())
                @php $isNews = request()->routeIs('news.*'); @endphp
                <a href="{{ route('news.index') }}"
                   @click="sidebarOpen = false"
                   class="group flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200
                          {{ $isNews ? 'bg-white/15 text-tertiary-fixed-dim font-bold border-l-4 border-tertiary-fixed-dim sidebar-active-glow' : 'text-secondary-fixed-dim hover:bg-white/8 hover:text-white' }}">
                    <span class="material-symbols-outlined text-xl shrink-0 {{ $isNews ? 'filled' : '' }}">newspaper</span>
                    <span class="text-label-md">{{ __('messages.news') }}</span>
                    @if ($isNews)
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-tertiary-fixed-dim"></span>
                    @endif
                </a>

                {{-- News › Categories sub-link --}}
                @if ($isNews)
                    @php $isNewsCat = request()->routeIs('news.categories.*'); @endphp
                    <a href="{{ route('news.categories.index') }}"
                       @click="sidebarOpen = false"
                       class="group flex items-center gap-3 pl-9 pr-3 py-2 rounded-xl transition-all duration-200
                              {{ $isNewsCat ? 'bg-white/10 text-tertiary-fixed-dim font-bold' : 'text-secondary-fixed-dim/70 hover:bg-white/8 hover:text-white' }}">
                        <span class="material-symbols-outlined text-base shrink-0">category</span>
                        <span class="text-label-sm">ໝວດຂ່າວ / Categories</span>
                    </a>
                @endif
            @endif

            {{-- Finance — superadmin + manager --}}
            @if (auth()->user()->isSuperAdmin() || auth()->user()->isManager())
                @php $isFinance = request()->routeIs('finance.*'); @endphp
                <a href="{{ route('finance.index') }}"
                   @click="sidebarOpen = false"
                   class="group flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200
                          {{ $isFinance ? 'bg-white/15 text-tertiary-fixed-dim font-bold border-l-4 border-tertiary-fixed-dim sidebar-active-glow' : 'text-secondary-fixed-dim hover:bg-white/8 hover:text-white' }}">
                    <span class="material-symbols-outlined text-xl shrink-0 {{ $isFinance ? 'filled' : '' }}">account_balance_wallet</span>
                    <span class="text-label-md">{{ __('messages.finance') }}</span>
                    @if ($isFinance)
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-tertiary-fixed-dim"></span>
                    @endif
                </a>
            @endif

            {{-- Section: ຈັດການລະບົບ — superadmin + admin --}}
            @if (auth()->user()->isAdmin())
                <div class="pt-4 pb-1 px-3">
                    <p class="text-[9px] font-bold uppercase tracking-widest text-white/25">ຈັດການລະບົບ</p>
                </div>

                {{-- Hero Slides — superadmin + admin --}}
                @php $isHeroSlides = request()->routeIs('hero-slides.*'); @endphp
                <a href="{{ route('hero-slides.index') }}"
                   @click="sidebarOpen = false"
                   class="group flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200
                          {{ $isHeroSlides ? 'bg-white/15 text-tertiary-fixed-dim font-bold border-l-4 border-tertiary-fixed-dim sidebar-active-glow' : 'text-secondary-fixed-dim hover:bg-white/8 hover:text-white' }}">
                    <span class="material-symbols-outlined text-xl shrink-0 {{ $isHeroSlides ? 'filled' : '' }}">photo_library</span>
                    <span class="text-label-md">{{ __('messages.hero_slides') }}</span>
                    @if ($isHeroSlides)
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-tertiary-fixed-dim"></span>
                    @endif
                </a>

                {{-- Departments — superadmin only --}}
                @if (auth()->user()->isSuperAdmin())
                    @php $isDepts = request()->routeIs('settings') && request()->input('tab', '') === 'departments'; @endphp
                    <a href="{{ route('settings') }}?tab=departments"
                       @click="sidebarOpen = false"
                       class="group flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200
                              {{ $isDepts ? 'bg-white/15 text-tertiary-fixed-dim font-bold border-l-4 border-tertiary-fixed-dim sidebar-active-glow' : 'text-secondary-fixed-dim hover:bg-white/8 hover:text-white' }}">
                        <span class="material-symbols-outlined text-xl shrink-0 {{ $isDepts ? 'filled' : '' }}">category</span>
                        <span class="text-label-md">{{ __('messages.departments') }}</span>
                        @if ($isDepts)
                            <span class="ml-auto w-1.5 h-1.5 rounded-full bg-tertiary-fixed-dim"></span>
                        @endif
                    </a>

                    {{-- Users — superadmin only --}}
                    @php $isUsers = request()->routeIs('users.*'); @endphp
                    <a href="{{ route('users.index') }}"
                       @click="sidebarOpen = false"
                       class="group flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200
                              {{ $isUsers ? 'bg-white/15 text-tertiary-fixed-dim font-bold border-l-4 border-tertiary-fixed-dim sidebar-active-glow' : 'text-secondary-fixed-dim hover:bg-white/8 hover:text-white' }}">
                        <span class="material-symbols-outlined text-xl shrink-0 {{ $isUsers ? 'filled' : '' }}">manage_accounts</span>
                        <span class="text-label-md">{{ __('messages.users') }}</span>
                        @if ($isUsers)
                            <span class="ml-auto w-1.5 h-1.5 rounded-full bg-tertiary-fixed-dim"></span>
                        @endif
                    </a>

                    {{-- Reports — superadmin only (coming soon) --}}
                    <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-secondary-fixed-dim/40 cursor-not-allowed select-none">
                        <span class="material-symbols-outlined text-xl shrink-0">assessment</span>
                        <span class="text-label-md">{{ __('messages.reports') }}</span>
                        <span class="ml-auto text-[9px] font-bold bg-white/10 text-white/40 px-1.5 py-0.5 rounded-full uppercase">Soon</span>
                    </div>
                @endif
            @endif

            {{-- Divider --}}
            <div class="border-t border-white/10 my-3"></div>

            {{-- New Entry Button — superadmin + admin --}}
            @if (auth()->user()->isAdmin())
                <a href="{{ route('personnel.create') }}"
                   @click="sidebarOpen = false"
                   class="flex items-center justify-center gap-2 w-full bg-primary hover:bg-primary-container text-white font-bold py-3 px-4 rounded-xl transition-all btn-press shadow-md">
                    <span class="material-symbols-outlined text-lg">person_add</span>
                    <span class="text-label-md">{{ __('messages.new_entry') }}</span>
                </a>
            @endif
        </nav>

        {{-- Footer --}}
        <div class="px-3 py-3 border-t border-white/10 space-y-1 shrink-0">
            {{-- Language Switch --}}
            <a href="{{ route('locale.switch', ['locale' => app()->getLocale() === 'lo' ? 'en' : 'lo']) }}"
               @click="sidebarOpen = false"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-secondary-fixed-dim hover:bg-white/8 hover:text-white transition-all duration-200">
                <span class="material-symbols-outlined text-xl shrink-0">language</span>
                <span class="text-label-md">
                    {{ app()->getLocale() === 'lo' ? '🇱🇦 ພາສາລາວ' : '🇬🇧 English' }}
                </span>
                <span class="ml-auto text-[10px] font-bold bg-white/10 px-1.5 py-0.5 rounded text-white/60">
                    {{ strtoupper(app()->getLocale()) }}
                </span>
            </a>

            {{-- Settings --}}
            @php $isSettings = request()->routeIs('settings') && !request()->input('tab'); @endphp
            <a href="{{ route('settings') }}"
               @click="sidebarOpen = false"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200
                      {{ $isSettings ? 'bg-white/15 text-tertiary-fixed-dim font-bold border-l-4 border-tertiary-fixed-dim sidebar-active-glow' : 'text-secondary-fixed-dim hover:bg-white/8 hover:text-white' }}">
                <span class="material-symbols-outlined text-xl shrink-0 {{ $isSettings ? 'filled' : '' }}">settings</span>
                <span class="text-label-md">{{ __('messages.settings') }}</span>
                @if ($isSettings)
                    <span class="ml-auto w-1.5 h-1.5 rounded-full bg-tertiary-fixed-dim"></span>
                @endif
            </a>
        </div>
    </aside>

    {{-- ══════════════════════════════════════════
         MAIN CONTENT
    ══════════════════════════════════════════ --}}
    <main class="lg:ml-[280px] min-h-screen flex flex-col">

        {{-- Top App Bar --}}
        <header class="flex items-center w-full px-4 lg:px-8 h-16 bg-surface-bright border-b border-outline-variant sticky top-0 z-30 gap-3">

            {{-- Hamburger — mobile/tablet --}}
            <button @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden flex items-center justify-center w-10 h-10 rounded-xl text-on-surface-variant hover:bg-surface-container transition-colors shrink-0"
                    :aria-label="sidebarOpen ? 'Close menu' : 'Open menu'">
                <span class="material-symbols-outlined" x-text="sidebarOpen ? 'close' : 'menu'">menu</span>
            </button>

            {{-- Page title (mobile) / Search (desktop) --}}
            <div class="flex-1 flex items-center gap-4">
                {{-- Search bar — hidden on small mobile, visible md+ --}}
                <div class="relative w-full max-w-md hidden sm:block">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-base">search</span>
                    <input type="text"
                           class="w-full pl-10 pr-4 py-2 bg-surface-container-low border border-outline-variant rounded-full text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all"
                           placeholder="{{ __('messages.search_placeholder') }}" />
                </div>

                {{-- App name — tiny screens only --}}
                <span class="sm:hidden text-label-md font-bold text-on-surface">Buddhist EMS</span>
            </div>

            {{-- Right actions --}}
            <div class="flex items-center gap-1 sm:gap-2">
                {{-- Search icon (mobile only) --}}
                <button class="sm:hidden p-2 text-on-surface-variant hover:bg-surface-container rounded-full transition-colors">
                    <span class="material-symbols-outlined text-base">search</span>
                </button>

                {{-- View Frontend --}}
                <a href="{{ route('frontend.index') }}" target="_blank"
                   title="ເບີ່ງໜ້າ Frontend"
                   class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-label-sm text-on-surface-variant hover:bg-surface-container hover:text-primary border border-outline-variant transition-all">
                    <span class="material-symbols-outlined text-base">open_in_new</span>
                    <span class="hidden md:inline">{{ __('messages.view_frontend') }}</span>
                </a>

                {{-- Notifications --}}
                <button class="relative p-2 text-on-surface-variant hover:bg-surface-container rounded-full transition-colors">
                    <span class="material-symbols-outlined text-base">notifications</span>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-error rounded-full"></span>
                </button>

                {{-- Language toggle --}}
                <a href="{{ route('locale.switch', ['locale' => app()->getLocale() === 'lo' ? 'en' : 'lo']) }}"
                   title="{{ app()->getLocale() === 'lo' ? 'Switch to English' : 'ປ່ຽນເປັນລາວ' }}"
                   class="hidden sm:flex p-2 text-on-surface-variant hover:bg-surface-container rounded-full transition-colors items-center">
                    <span class="material-symbols-outlined text-base">translate</span>
                </a>

                <div class="h-8 w-px bg-outline-variant mx-1 hidden sm:block"></div>

                {{-- User + Logout --}}
                @auth
                <div class="flex items-center gap-2 sm:gap-3" x-data="{ open: false }" @click.outside="open = false">
                    <div class="text-right hidden sm:block">
                        <p class="text-label-md font-bold text-primary leading-tight">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-on-surface-variant uppercase tracking-wider">{{ auth()->user()->role_label ?? auth()->user()->role }}</p>
                    </div>

                    {{-- Avatar button (dropdown trigger) --}}
                    <div class="relative">
                        <button @click="open = !open"
                                class="w-9 h-9 rounded-full bg-primary/10 flex items-center justify-center shrink-0 overflow-hidden ring-2 ring-transparent hover:ring-primary/30 transition-all focus:outline-none focus:ring-primary/50">
                            @if (auth()->user()->avatar_url)
                                <img src="{{ Storage::url(auth()->user()->avatar_url) }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover" />
                            @else
                                <span class="material-symbols-outlined text-primary text-base">person</span>
                            @endif
                        </button>

                        {{-- Dropdown --}}
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-52 bg-surface-bright border border-outline-variant rounded-2xl shadow-xl z-50 overflow-hidden"
                             style="display:none;">
                            <div class="px-4 py-3 border-b border-outline-variant">
                                <p class="text-label-md font-bold text-on-surface truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-on-surface-variant truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <div class="p-1.5 space-y-0.5">
                                <a href="{{ route('profile') }}"
                                   wire:navigate
                                   @click="open = false"
                                   class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-body-md text-on-surface hover:bg-surface-container transition-colors">
                                    <span class="material-symbols-outlined text-base text-on-surface-variant">manage_accounts</span>
                                    ໂປຣໄຟລ / Profile
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-body-md text-error hover:bg-error/5 transition-colors">
                                        <span class="material-symbols-outlined text-base">logout</span>
                                        ອອກຈາກລະບົບ / Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endauth
            </div>
        </header>

        {{-- Page Content --}}
        <div class="p-4 sm:p-6 lg:p-8 flex-1">
            @if (session('message'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 flex items-center gap-3 animate-fade-in">
                    <span class="material-symbols-outlined text-green-600">check_circle</span>
                    <span>{{ session('message') }}</span>
                </div>
            @endif

            {{ $slot }}
        </div>

        {{-- Footer --}}
        <footer class="w-full py-4 px-4 sm:px-8 flex flex-col sm:flex-row justify-between items-center gap-2 text-xs bg-surface-container-low border-t border-outline-variant">
            <p class="text-on-surface-variant text-center sm:text-left">
                © {{ date('Y') }} Buddhist Organization Management System | ສະຫງວນລິຂະສິດ
            </p>
            <div class="flex gap-4 sm:gap-6">
                <a href="#" class="text-on-surface-variant hover:text-primary transition-colors">Audit Log</a>
                <a href="#" class="text-on-surface-variant hover:text-primary transition-colors">Privacy Policy</a>
                <a href="#" class="text-on-surface-variant hover:text-primary transition-colors">System Status</a>
            </div>
        </footer>
    </main>

    {{-- ══════════════════════════════════════════
         MOBILE BOTTOM NAV
    ══════════════════════════════════════════ --}}
    <nav class="sm:hidden fixed bottom-0 left-0 right-0 z-30 bg-white border-t border-outline-variant safe-area-bottom">
        <div class="flex">
            @php
                $user = auth()->user();
                $bottomNav = [
                    ['href' => route('dashboard'), 'icon' => 'dashboard', 'label' => 'ຫຼັກ', 'active' => request()->routeIs('dashboard'), 'show' => true],
                ];

                if ($user->isAdmin()) {
                    $bottomNav[] = ['href' => route('personnel.index'),  'icon' => 'group',       'label' => 'ບຸກຄະລາ', 'active' => request()->routeIs('personnel.*'), 'show' => true];
                    $bottomNav[] = ['href' => route('personnel.create'), 'icon' => 'person_add',  'label' => 'ເພີ່ມ',   'active' => false, 'primary' => true, 'show' => true];
                    $bottomNav[] = ['href' => route('news.index'),       'icon' => 'newspaper',   'label' => 'ຂ່າວ',    'active' => request()->routeIs('news.*'), 'show' => true];
                    $bottomNav[] = ['href' => route('documents.index'),  'icon' => 'description', 'label' => 'ເອກະສານ', 'active' => request()->routeIs('documents.*'), 'show' => true];
                } elseif ($user->isManager()) {
                    $bottomNav[] = ['href' => route('finance.index'), 'icon' => 'account_balance_wallet', 'label' => 'ການເງິນ', 'active' => request()->routeIs('finance.*'), 'show' => true];
                }
            @endphp

            @foreach ($bottomNav as $item)
                @if ($item['show'] ?? false)
                    <a href="{{ $item['href'] }}"
                       class="flex-1 flex flex-col items-center justify-center py-2 gap-0.5 transition-colors
                              {{ $item['active'] ?? false ? 'text-primary' : 'text-on-surface-variant' }}
                              {{ $item['primary'] ?? false ? 'relative -mt-4' : '' }}">
                        @if ($item['primary'] ?? false)
                            <span class="w-12 h-12 rounded-full bg-primary flex items-center justify-center shadow-lg">
                                <span class="material-symbols-outlined text-white text-xl">{{ $item['icon'] }}</span>
                            </span>
                        @else
                            <span class="material-symbols-outlined text-xl {{ ($item['active'] ?? false) ? 'filled' : '' }}">{{ $item['icon'] }}</span>
                            <span class="text-[9px] font-medium leading-none">{{ $item['label'] }}</span>
                        @endif
                    </a>
                @endif
            @endforeach
        </div>
    </nav>

    @livewireScripts
    @stack('scripts')
</body>
</html>
