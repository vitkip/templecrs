<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ $title ?? $orgName ?? 'Buddhist EMS' }}</title>
    <meta name="description" content="{{ $orgNameEn ?? 'Buddhist Organization' }} — ລະບົບຈັດການອົງການພຣະພຸດທະສາສະໜາ" />

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
            x-data="{ scrolled: false, mobileMenu: false }"
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
                    <a href="{{ route('frontend.index') }}" class="px-4 py-2 rounded-lg text-label-md text-primary font-bold bg-primary/5 transition-all">
                        ໜ້າຫຼັກ
                    </a>
                    <a href="#news" class="px-4 py-2 rounded-lg text-label-md text-on-surface-variant hover:text-primary hover:bg-primary/5 transition-all">
                        ຂ່າວ
                    </a>
                    <a href="#personnel" class="px-4 py-2 rounded-lg text-label-md text-on-surface-variant hover:text-primary hover:bg-primary/5 transition-all">
                        ບຸກຄະລາກອນ
                    </a>
                    <a href="#documents" class="px-4 py-2 rounded-lg text-label-md text-on-surface-variant hover:text-primary hover:bg-primary/5 transition-all">
                        ເອກະສານ
                    </a>
                    <div class="h-6 w-px bg-outline-variant mx-2"></div>
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-primary text-white rounded-lg text-label-md font-bold hover:bg-primary-container transition-all btn-press">
                            <span class="material-symbols-outlined text-sm align-middle mr-1">dashboard</span>
                            Admin
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 border border-primary text-primary rounded-lg text-label-md font-bold hover:bg-primary hover:text-white transition-all">
                            <span class="material-symbols-outlined text-sm align-middle mr-1">login</span>
                            ເຂົ້າສູ່ລະບົບ
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
                <a href="{{ route('frontend.index') }}" class="block px-4 py-2.5 rounded-lg text-label-md text-primary font-bold bg-primary/5">ໜ້າຫຼັກ</a>
                <a href="#news" @click="mobileMenu = false" class="block px-4 py-2.5 rounded-lg text-label-md text-on-surface-variant hover:bg-primary/5">ຂ່າວ</a>
                <a href="#personnel" @click="mobileMenu = false" class="block px-4 py-2.5 rounded-lg text-label-md text-on-surface-variant hover:bg-primary/5">ບຸກຄະລາກອນ</a>
                <a href="#documents" @click="mobileMenu = false" class="block px-4 py-2.5 rounded-lg text-label-md text-on-surface-variant hover:bg-primary/5">ເອກະສານ</a>
                <div class="pt-2 border-t border-outline-variant">
                    @auth
                        <a href="{{ route('dashboard') }}" class="block px-4 py-2.5 rounded-lg bg-primary text-white text-label-md font-bold text-center">Admin Panel</a>
                    @else
                        <a href="{{ route('login') }}" class="block px-4 py-2.5 rounded-lg border border-primary text-primary text-label-md font-bold text-center">ເຂົ້າສູ່ລະບົບ</a>
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
    <footer class="bg-secondary text-white mt-16">
        {{-- Top Wave --}}
        <div class="relative -mt-16">
            <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" class="w-full">
                <path fill="#545f73" d="M0,40L48,36.7C96,33,192,27,288,26.7C384,27,480,33,576,43.3C672,53,768,67,864,66.7C960,67,1056,53,1152,43.3C1248,33,1344,27,1392,23.3L1440,20L1440,80L1392,80C1344,80,1248,80,1152,80C1056,80,960,80,864,80C768,80,672,80,576,80C480,80,384,80,288,80C192,80,96,80,48,80L0,80Z"/>
            </svg>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Org Info --}}
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center">
                            @if ($orgLogo ?? false)
                                <img src="{{ Storage::url($orgLogo) }}" alt="Logo" class="w-full h-full object-cover rounded-full" />
                            @else
                                <span class="material-symbols-outlined text-white text-2xl">account_balance</span>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-headline-sm text-white">{{ $orgName ?? 'ອົງການພຸດທະສາສະໜາ' }}</h3>
                            <p class="text-xs text-secondary-fixed-dim opacity-70">{{ $orgNameEn ?? '' }}</p>
                        </div>
                    </div>
                    <p class="text-sm text-secondary-fixed-dim/70 leading-relaxed">
                        ລະບົບຈັດການອົງການພຣະພຸດທະສາສະໜາ<br>
                        Buddhist Organization Management System
                    </p>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h4 class="text-label-md text-tertiary-fixed-dim uppercase tracking-widest mb-4">ລິ້ງດ່ວນ</h4>
                    <ul class="space-y-2">
                        <li><a href="#news" class="text-sm text-secondary-fixed-dim hover:text-white transition-colors flex items-center gap-2"><span class="material-symbols-outlined text-sm">newspaper</span> ຂ່າວ ແລະ ກິດຈະກຳ</a></li>
                        <li><a href="#personnel" class="text-sm text-secondary-fixed-dim hover:text-white transition-colors flex items-center gap-2"><span class="material-symbols-outlined text-sm">group</span> ບຸກຄະລາກອນ</a></li>
                        <li><a href="#documents" class="text-sm text-secondary-fixed-dim hover:text-white transition-colors flex items-center gap-2"><span class="material-symbols-outlined text-sm">description</span> ເອກະສານ</a></li>
                    </ul>
                </div>

                {{-- Contact --}}
                <div>
                    <h4 class="text-label-md text-tertiary-fixed-dim uppercase tracking-widest mb-4">ຕິດຕໍ່</h4>
                    <ul class="space-y-2 text-sm text-secondary-fixed-dim">
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">location_on</span>
                            {{ \App\Models\Setting::get('org_address', 'ນະຄອນຫຼວງວຽງຈັນ, ສ.ປ.ປ ລາວ') }}
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">phone</span>
                            {{ \App\Models\Setting::get('org_phone', '021-XXX-XXX') }}
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">mail</span>
                            {{ \App\Models\Setting::get('org_email', 'info@example.org') }}
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-white/10 mt-8 pt-6 flex flex-col sm:flex-row justify-between items-center gap-2 text-xs text-secondary-fixed-dim/50">
                <p>© {{ date('Y') }} {{ $orgNameEn ?? 'Buddhist Organization' }}. ສະຫງວນລິຂະສິດ</p>
                <p>Powered by Buddhist EMS</p>
            </div>
        </div>
    </footer>

</body>
</html>
