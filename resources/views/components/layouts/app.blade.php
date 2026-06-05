<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ $title ?? __('messages.app_name') }} — Buddhist EMS</title>
    <meta name="description" content="Buddhist Organization Enterprise Management System — ລະບົບຈັດການອົງການພຣະພຸດທະສາສະໜາ" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Phetsarath:wght@400;700&family=Noto+Sans+Lao:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
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
        <nav class="flex-1 px-3 py-5 space-y-1 overflow-y-auto">

            {{-- Dashboard --}}
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

            {{-- Personnel --}}
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

            {{-- Departments --}}
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

            {{-- Documents (coming soon) --}}
            <div class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-secondary-fixed-dim/40 cursor-not-allowed select-none">
                <span class="material-symbols-outlined text-xl shrink-0">description</span>
                <span class="text-label-md">{{ __('messages.documents') }}</span>
                <span class="ml-auto text-[9px] font-bold bg-white/10 text-white/40 px-1.5 py-0.5 rounded-full uppercase">Soon</span>
            </div>

            {{-- Reports (coming soon) --}}
            <div class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-secondary-fixed-dim/40 cursor-not-allowed select-none">
                <span class="material-symbols-outlined text-xl shrink-0">assessment</span>
                <span class="text-label-md">{{ __('messages.reports') }}</span>
                <span class="ml-auto text-[9px] font-bold bg-white/10 text-white/40 px-1.5 py-0.5 rounded-full uppercase">Soon</span>
            </div>

            {{-- Divider --}}
            <div class="border-t border-white/10 my-3"></div>

            {{-- New Entry Button --}}
            <a href="{{ route('personnel.create') }}"
               @click="sidebarOpen = false"
               class="flex items-center justify-center gap-2 w-full bg-primary hover:bg-primary-container text-white font-bold py-3 px-4 rounded-xl transition-all btn-press shadow-md">
                <span class="material-symbols-outlined text-lg">person_add</span>
                <span class="text-label-md">{{ __('messages.new_entry') }}</span>
            </a>
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

                {{-- Notifications --}}
                <button class="relative p-2 text-on-surface-variant hover:bg-surface-container rounded-full transition-colors">
                    <span class="material-symbols-outlined text-base">notifications</span>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-error rounded-full"></span>
                </button>

                {{-- Language toggle --}}
                <a href="{{ route('locale.switch', ['locale' => app()->getLocale() === 'lo' ? 'en' : 'lo']) }}"
                   class="hidden sm:flex p-2 text-on-surface-variant hover:bg-surface-container rounded-full transition-colors items-center">
                    <span class="material-symbols-outlined text-base">translate</span>
                </a>

                <div class="h-8 w-px bg-outline-variant mx-1 hidden sm:block"></div>

                {{-- User --}}
                <div class="flex items-center gap-2 sm:gap-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-label-md font-bold text-primary leading-tight">Admin</p>
                        <p class="text-[10px] text-on-surface-variant uppercase tracking-wider">Administrator</p>
                    </div>
                    <div class="w-9 h-9 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-primary text-base">person</span>
                    </div>
                </div>
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
                $bottomNav = [
                    ['href' => route('dashboard'),       'icon' => 'dashboard',  'label' => 'ຫຼັກ',     'active' => request()->routeIs('dashboard')],
                    ['href' => route('personnel.index'), 'icon' => 'group',      'label' => 'ບຸກຄະລາ',  'active' => request()->routeIs('personnel.*')],
                    ['href' => route('personnel.create'),'icon' => 'person_add', 'label' => 'ເພີ່ມ',    'active' => false, 'primary' => true],
                    ['href' => route('settings') . '?tab=departments', 'icon' => 'category', 'label' => 'ພາກສ່ວນ', 'active' => request()->routeIs('settings')],
                    ['href' => route('settings'),        'icon' => 'settings',   'label' => 'ຕັ້ງຄ່າ',  'active' => request()->routeIs('settings') && !request()->input('tab')],
                ];
            @endphp

            @foreach ($bottomNav as $item)
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
            @endforeach
        </div>
    </nav>

    @livewireScripts
</body>
</html>
