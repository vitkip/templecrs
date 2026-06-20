@extends('frontend.layout')

@section('content')

    @php
        $locale = app()->getLocale();
        $name = $person->display_name;
        $title = $person->display_title;
        $position = $person->display_position;
        $bio = $person->display_bio;
        $education = $locale === 'lo'
            ? ($person->education_lo ?? $person->education_en)
            : ($person->education_en ?? $person->education_lo);
        $currentTemple = $locale === 'lo'
            ? ($person->current_temple_lo ?? $person->current_temple_en)
            : ($person->current_temple_en ?? $person->current_temple_lo);
        $photoUrl = $person->photo_url
            ? \Illuminate\Support\Facades\Storage::url($person->photo_url)
            : null;
        $isMonk = $person->gender === 'monk';
        $isFemale = $person->gender === 'female';
        $pansa = $isMonk ? ($person->pansa ?? null) : null;
        $village = $locale === 'lo'
            ? ($person->birth_village_lo ?? $person->birth_village_en)
            : ($person->birth_village_en ?? $person->birth_village_lo);
        $district = $locale === 'lo'
            ? ($person->district_lo ?? $person->district_en)
            : ($person->district_en ?? $person->district_lo);
        $province = $locale === 'lo'
            ? ($person->province_lo ?? $person->province_en)
            : ($person->province_en ?? $person->province_lo);
        $ordinationDate = $isMonk && ($person->date_of_ordination ?? null)
            ? $person->date_of_ordination->format('d/m/Y')
            : null;
    @endphp

    <style>
        @keyframes lotus-pulse {

            0%,
            100% {
                box-shadow: 0 0 0 3px #C8953A, 0 0 0 8px rgba(200, 149, 58, 0.22), 0 6px 36px rgba(200, 149, 58, 0.14);
            }

            50% {
                box-shadow: 0 0 0 3px #C8953A, 0 0 0 16px rgba(200, 149, 58, 0.08), 0 6px 48px rgba(200, 149, 58, 0.07);
            }
        }

        @keyframes fade-up {
            from {
                opacity: 0;
                transform: translateY(18px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .ps-reveal {
            animation: fade-up 0.55s ease both;
        }

        .ps-reveal-1 {
            animation-delay: 0.05s;
        }

        .ps-reveal-2 {
            animation-delay: 0.13s;
        }

        .ps-reveal-3 {
            animation-delay: 0.21s;
        }

        .ps-reveal-4 {
            animation-delay: 0.29s;
        }

        .ps-reveal-5 {
            animation-delay: 0.37s;
        }

        @media (prefers-reduced-motion: reduce) {
            .ps-reveal {
                animation: none !important;
            }
        }
    </style>

    {{-- ════════════════════════════════════════════════════
    HERO — Centered portrait medallion
    ════════════════════════════════════════════════════ --}}
    <div class="relative overflow-hidden"
        style="background: linear-gradient(160deg, #2C1A08 0%, #3D2A12 45%, #1A2B1A 100%); min-height: 400px;">

        {{-- Lotus texture --}}
        <div class="absolute inset-0 pointer-events-none" style="opacity:0.05;" aria-hidden="true">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="lotuspat2" x="0" y="0" width="64" height="64" patternUnits="userSpaceOnUse">
                        <g transform="translate(32,32)" fill="#D4AF37">
                            <ellipse rx="4.5" ry="10" transform="rotate(0)" />
                            <ellipse rx="4.5" ry="10" transform="rotate(45)" />
                            <ellipse rx="4.5" ry="10" transform="rotate(90)" />
                            <ellipse rx="4.5" ry="10" transform="rotate(135)" />
                        </g>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#lotuspat2)" />
            </svg>
        </div>

        {{-- Subtle bottom vignette into content area --}}
        <div class="absolute bottom-0 left-0 right-0 h-28 pointer-events-none" aria-hidden="true"
            style="background: linear-gradient(to bottom, transparent, rgba(44,26,8,0.35));"></div>

        <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-8 pt-8 pb-16">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 mb-10" aria-label="Breadcrumb">
                <a href="{{ route('frontend.index') }}" class="flex items-center gap-1 transition-colors"
                    style="font-size:11px; color:rgba(255,255,255,0.38);"
                    onmouseover="this.style.color='rgba(212,175,55,0.85)'"
                    onmouseout="this.style.color='rgba(255,255,255,0.38)'">
                    <span class="material-symbols-outlined" style="font-size:12px;">home</span>
                    {{ __('messages.homepage') }}
                </a>
                <span style="color:rgba(255,255,255,0.18); font-size:11px;">›</span>
                <a href="{{ route('frontend.personnel') }}" class="transition-colors"
                    style="font-size:11px; color:rgba(255,255,255,0.38);"
                    onmouseover="this.style.color='rgba(212,175,55,0.85)'"
                    onmouseout="this.style.color='rgba(255,255,255,0.38)'">
                    {{ __('messages.personnel') }}
                </a>
                <span style="color:rgba(255,255,255,0.18); font-size:11px;">›</span>
                <span style="font-size:11px; color:rgba(255,255,255,0.65);"
                    class="truncate max-w-[180px]">{{ $name }}</span>
            </nav>

            {{-- Centered identity block --}}
            <div class="flex flex-col items-center text-center">

                {{-- Portrait medallion — the signature element --}}
                <div class="mb-6 shrink-0">
                    @if ($photoUrl)
                                <img src="{{ $photoUrl }}" alt="{{ $name }}" loading="eager"
                                    class="w-40 h-40 sm:w-48 sm:h-48 rounded-full object-cover" style="{{ $isMonk
                        ? 'animation: lotus-pulse 4.5s ease-in-out infinite;'
                        : 'box-shadow: 0 0 0 3px rgba(255,255,255,0.16), 0 6px 40px rgba(0,0,0,0.5);' }}" />
                    @else
                                <div class="w-40 h-40 sm:w-48 sm:h-48 rounded-full flex items-center justify-center"
                                    style="{{ $isMonk
                        ? 'background: radial-gradient(circle at 38% 32%, #FDE68A 0%, #C8953A 100%); animation: lotus-pulse 4.5s ease-in-out infinite;'
                        : ($isFemale
                            ? 'background: radial-gradient(circle at 38% 32%, #EDE9FE 0%, #A78BFA 100%); box-shadow: 0 0 0 3px rgba(255,255,255,0.14), 0 6px 40px rgba(0,0,0,0.4);'
                            : 'background: radial-gradient(circle at 38% 32%, #475569 0%, #334155 100%); box-shadow: 0 0 0 3px rgba(255,255,255,0.14), 0 6px 40px rgba(0,0,0,0.4);') }}">
                                    <span class="material-symbols-outlined"
                                        style="{{ $isMonk ? 'font-size:72px; color:rgba(255,255,255,0.65);' : 'font-size:72px; color:rgba(255,255,255,0.35);' }}">person</span>
                                </div>
                    @endif
                </div>

                {{-- Title eyebrow --}}
                @if ($title)
                    <p class="font-bold uppercase tracking-widest mb-2"
                        style="font-size:10px; color:{{ $isMonk ? '#C8953A' : 'rgba(255,255,255,0.38)' }}; letter-spacing:0.22em;">
                        {{ $title }}
                    </p>
                @endif

                {{-- Name --}}
                <h1 class="font-bold text-white"
                    style="font-size: clamp(28px, 5vw, 46px); line-height:1.1; letter-spacing:-0.02em; max-width:680px;">
                    {{ $name }}
                </h1>

                {{-- Position --}}
                @if ($position)
                    <p class="mt-3" style="font-size:14px; color:rgba(255,255,255,0.52); line-height:1.65; max-width:480px;">
                        {{ $position }}
                    </p>
                @endif

                {{-- Chips --}}
                <div class="flex flex-wrap justify-center gap-2 mt-4">
                    @if ($person->department)
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full"
                            style="font-size:11px; font-weight:600; background:rgba(255,255,255,0.08); color:rgba(255,255,255,0.68); border:1px solid rgba(255,255,255,0.1); backdrop-filter:blur(4px);">
                            <span class="material-symbols-outlined" style="font-size:11px;">corporate_fare</span>
                            {{ $person->department->name }}
                        </span>
                    @endif
                    @if ($person->affiliation_level === 'central')
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full"
                            style="font-size:11px; font-weight:700; background:rgba(55,48,163,0.45); color:#C7D2FE; border:1px solid rgba(99,102,241,0.28);">
                            <span class="material-symbols-outlined" style="font-size:11px;">location_city</span>
                            ສູນກາງ
                        </span>
                    @endif
                    @if ($person->affiliation_level === 'provincial')
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full"
                            style="font-size:11px; font-weight:700; background:rgba(61,90,71,0.45); color:#A7F3D0; border:1px solid rgba(61,90,71,0.35);">
                            <span class="material-symbols-outlined" style="font-size:11px;">map</span>
                            {{ $person->affiliation_province ?? 'ແຂວງ' }}
                        </span>
                    @endif
                </div>

            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════
    MAIN CONTENT
    ════════════════════════════════════════════════════ --}}
    <div style="background-color:#FAF6EF;">

        {{-- Lotus divider --}}
        <div class="flex items-center justify-center py-5" aria-hidden="true">
            <div style="height:1px; width:80px; background:linear-gradient(to right, transparent, rgba(200,149,58,0.3));">
            </div>
            <svg class="mx-4 shrink-0" width="24" height="24" viewBox="0 0 26 26" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <ellipse cx="13" cy="13" rx="3" ry="8" fill="#C8953A" opacity="0.45" transform="rotate(0 13 13)" />
                <ellipse cx="13" cy="13" rx="3" ry="8" fill="#C8953A" opacity="0.45" transform="rotate(45 13 13)" />
                <ellipse cx="13" cy="13" rx="3" ry="8" fill="#C8953A" opacity="0.45" transform="rotate(90 13 13)" />
                <ellipse cx="13" cy="13" rx="3" ry="8" fill="#C8953A" opacity="0.45" transform="rotate(135 13 13)" />
                <circle cx="13" cy="13" r="2.5" fill="#C8953A" opacity="0.65" />
            </svg>
            <div style="height:1px; width:80px; background:linear-gradient(to left, transparent, rgba(200,149,58,0.3));">
            </div>
        </div>

        <div class="max-w-5xl mx-auto px-4 sm:px-8 pb-16">

            {{-- Back button --}}
            <div class="mb-8 ps-reveal ps-reveal-1">
                <a href="{{ route('frontend.personnel') }}" class="inline-flex items-center gap-2 transition-colors"
                    style="font-size:13px; font-weight:600; color:#7C4D0F; text-decoration:none;"
                    onmouseover="this.style.color='#5D3908'" onmouseout="this.style.color='#7C4D0F'">
                    <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span>
                    ກັບໄປລາຍການ
                </a>
            </div>

            <div class="lg:flex lg:gap-12">

                {{-- ── MAIN COLUMN ── --}}
                <div class="flex-1 min-w-0 space-y-8">

                    {{-- Monk sacred stat strip (pansa / ordination date) --}}
                    @if ($isMonk && ($pansa || $ordinationDate))
                        <section class="ps-reveal ps-reveal-2 rounded-2xl overflow-hidden"
                            style="background:linear-gradient(135deg, rgba(200,149,58,0.09) 0%, rgba(200,149,58,0.03) 100%); border:1px solid rgba(200,149,58,0.22);">
                            <div class="flex"
                                style="{{ ($pansa && $ordinationDate) ? 'divide-x:1px solid rgba(200,149,58,0.15);' : '' }}">
                                @if ($pansa)
                                    <div class="flex-1 px-6 py-5 text-center"
                                        style="{{ $ordinationDate ? 'border-right:1px solid rgba(200,149,58,0.18);' : '' }}">
                                        <p class="font-bold tabular-nums" style="font-size:36px; line-height:1; color:#C8953A;">
                                            {{ $pansa }}</p>
                                        <p class="font-bold uppercase tracking-widest mt-1.5"
                                            style="font-size:9px; color:rgba(124,77,15,0.5); letter-spacing:0.16em;">ພັນສາ</p>
                                    </div>
                                @endif
                                @if ($ordinationDate)
                                    <div class="flex-1 px-6 py-5 text-center">
                                        <p class="font-bold" style="font-size:20px; line-height:1.2; color:#7C4D0F;">
                                            {{ $ordinationDate }}</p>
                                        <p class="font-bold uppercase tracking-widest mt-1.5"
                                            style="font-size:9px; color:rgba(124,77,15,0.5); letter-spacing:0.16em;">ວັນອຸປະສົມ</p>
                                    </div>
                                @endif
                            </div>
                        </section>
                    @endif

                    {{-- Bio --}}
                    @if ($bio)
                                <section class="ps-reveal ps-reveal-2">
                                    <div class="flex items-center gap-3 mb-4">
                                        <span class="material-symbols-outlined shrink-0"
                                            style="font-size:16px; color:#C8953A;">auto_stories</span>
                                        <h2 class="font-bold tracking-widest uppercase"
                                            style="font-size:11px; color:#3D2A12; letter-spacing:0.14em;">ຊີວະປະຫວັດ</h2>
                                        <div class="flex-1 h-px"
                                            style="background:linear-gradient(to right, rgba(200,149,58,0.28), transparent);"></div>
                                    </div>
                                    <div class="rounded-2xl px-5 py-5 sm:px-6" style="{{ $isMonk
                        ? 'background:rgba(200,149,58,0.055); border:1px solid rgba(200,149,58,0.18);'
                        : 'background:white; border:1px solid rgba(0,0,0,0.06);' }}">
                                        <p style="font-size:15px; line-height:2; color:#374151;">{{ $bio }}</p>
                                    </div>
                                </section>
                    @endif

                    {{-- Education --}}
                    @if ($education)
                        <section class="ps-reveal ps-reveal-3">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="material-symbols-outlined shrink-0"
                                    style="font-size:16px; color:#7C4D0F;">school</span>
                                <h2 class="font-bold tracking-widest uppercase"
                                    style="font-size:11px; color:#3D2A12; letter-spacing:0.14em;">ລະດັບການສຶກສາ</h2>
                                <div class="flex-1 h-px"
                                    style="background:linear-gradient(to right, rgba(124,77,15,0.22), transparent);"></div>
                            </div>
                            <div class="rounded-2xl px-5 py-5 sm:px-6"
                                style="background:white; border:1px solid rgba(0,0,0,0.06);">
                                <p style="font-size:15px; line-height:2; color:#374151;">{{ $education }}</p>
                            </div>
                        </section>
                    @endif

                    {{-- Current temple --}}
                    @if ($currentTemple)
                                <section class="ps-reveal ps-reveal-4">
                                    <div class="flex items-center gap-3 mb-4">
                                        <span class="material-symbols-outlined shrink-0"
                                            style="font-size:16px; color:{{ $isMonk ? '#C8953A' : '#7C4D0F' }};">temple_buddhist</span>
                                        <h2 class="font-bold tracking-widest uppercase"
                                            style="font-size:11px; color:#3D2A12; letter-spacing:0.14em;">ວັດຢູ່ປະຈຸບັນ</h2>
                                        <div class="flex-1 h-px"
                                            style="background:linear-gradient(to right, rgba(200,149,58,0.28), transparent);"></div>
                                    </div>
                                    <div class="rounded-2xl px-5 py-5 sm:px-6" style="{{ $isMonk
                        ? 'background:rgba(200,149,58,0.055); border:1px solid rgba(200,149,58,0.18);'
                        : 'background:white; border:1px solid rgba(0,0,0,0.06);' }}">
                                        <p style="font-size:15px; line-height:2; color:#374151;">{{ $currentTemple }}</p>
                                    </div>
                                </section>
                    @endif

                    @if (!$bio && !$education && !$currentTemple)
                        <div class="text-center py-16">
                            <span class="material-symbols-outlined"
                                style="font-size:44px; color:rgba(200,149,58,0.2);">person</span>
                            <p style="font-size:14px; color:#94A3B8; margin-top:10px;">ຍັງບໍ່ມີຂໍ້ມູນລາຍລະອຽດ</p>
                        </div>
                    @endif

                </div>

                {{-- ── SIDEBAR ── --}}
                <aside class="w-full lg:w-60 shrink-0 mt-10 lg:mt-0 space-y-4">

                    {{-- Contact card --}}
                    @if ($person->phone || $person->email || $person->facebook)
                                <div class="rounded-2xl p-4 ps-reveal ps-reveal-3" style="{{ $isMonk
                        ? 'background:rgba(200,149,58,0.07); border:1px solid rgba(200,149,58,0.22);'
                        : 'background:white; border:1px solid rgba(0,0,0,0.07);' }}">
                                    <p class="font-bold uppercase tracking-widest mb-3"
                                        style="font-size:9px; color:rgba(124,77,15,0.48); letter-spacing:0.16em;">ຂໍ້ມູນຕິດຕໍ່</p>
                                    <div class="space-y-3">
                                        @if ($person->phone)
                                            <a href="tel:{{ $person->phone }}" class="flex items-center gap-3 transition-colors"
                                                style="text-decoration:none;">
                                                <span class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0"
                                                    style="background:rgba(200,149,58,0.12);">
                                                    <span class="material-symbols-outlined"
                                                        style="font-size:15px; color:#C8953A;">phone</span>
                                                </span>
                                                <div>
                                                    <p
                                                        style="font-size:9px; color:rgba(124,77,15,0.42); font-weight:700; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:1px;">
                                                        ໂທລະສັບ</p>
                                                    <span
                                                        style="font-size:13px; font-weight:600; color:#7C4D0F;">{{ $person->phone }}</span>
                                                </div>
                                            </a>
                                        @endif

                                        @if ($person->email)
                                            <a href="mailto:{{ $person->email }}" class="flex items-center gap-3 transition-colors"
                                                style="text-decoration:none;">
                                                <span class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0"
                                                    style="background:rgba(200,149,58,0.12);">
                                                    <span class="material-symbols-outlined"
                                                        style="font-size:15px; color:#C8953A;">mail</span>
                                                </span>
                                                <div class="min-w-0">
                                                    <p
                                                        style="font-size:9px; color:rgba(124,77,15,0.42); font-weight:700; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:1px;">
                                                        ອີເມວ</p>
                                                    <span class="block truncate"
                                                        style="font-size:12px; font-weight:500; color:#7C4D0F;">{{ $person->email }}</span>
                                                </div>
                                            </a>
                                        @endif

                                        @if ($person->facebook)
                                            <a href="{{ $person->facebook }}" target="_blank" rel="noopener noreferrer"
                                                class="flex items-center gap-3 transition-colors" style="text-decoration:none;">
                                                <span class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0"
                                                    style="background:rgba(200,149,58,0.12);">
                                                    <span class="material-symbols-outlined"
                                                        style="font-size:15px; color:#C8953A;">share</span>
                                                </span>
                                                <div>
                                                    <p
                                                        style="font-size:9px; color:rgba(124,77,15,0.42); font-weight:700; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:1px;">
                                                        ໂຊຊຽວ</p>
                                                    <span style="font-size:13px; font-weight:600; color:#7C4D0F;">Facebook</span>
                                                </div>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                    @endif

                    {{-- Department card --}}
                    @if ($person->department)
                        <div class="rounded-2xl p-4 ps-reveal ps-reveal-4"
                            style="background:white; border:1px solid rgba(0,0,0,0.07);">
                            <p class="font-bold uppercase tracking-widest mb-3"
                                style="font-size:9px; color:rgba(124,77,15,0.48); letter-spacing:0.16em;">ພະແນກ / ກົມ</p>
                            <div class="flex items-center gap-2.5">
                                <span class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0"
                                    style="background:rgba(124,77,15,0.08);">
                                    <span class="material-symbols-outlined"
                                        style="font-size:15px; color:#7C4D0F;">corporate_fare</span>
                                </span>
                                <span
                                    style="font-size:13px; font-weight:600; color:#3D2A12;">{{ $person->department->name }}</span>
                            </div>
                        </div>
                    @endif

                    {{-- Birth location card --}}
                    @if ($village || $district || $province)
                        <div class="rounded-2xl p-4 ps-reveal ps-reveal-5"
                            style="background:white; border:1px solid rgba(0,0,0,0.07);">
                            <p class="font-bold uppercase tracking-widest mb-3"
                                style="font-size:9px; color:rgba(124,77,15,0.48); letter-spacing:0.16em;">ທີ່ຢູ່ເກີດ</p>
                            <div class="space-y-2.5">
                                @if ($village)
                                    <div class="flex items-start gap-2.5">
                                        <span class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 mt-0.5"
                                            style="background:rgba(124,77,15,0.08);">
                                            <span class="material-symbols-outlined"
                                                style="font-size:15px; color:#7C4D0F;">home_pin</span>
                                        </span>
                                        <div>
                                            <p style="font-size:9px; color:rgba(124,77,15,0.42); font-weight:700; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:1px;">ບ້ານ</p>
                                            <span style="font-size:13px; font-weight:600; color:#3D2A12;">{{ $village }}</span>
                                        </div>
                                    </div>
                                @endif
                                @if ($district)
                                    <div class="flex items-start gap-2.5">
                                        <span class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 mt-0.5"
                                            style="background:rgba(124,77,15,0.08);">
                                            <span class="material-symbols-outlined"
                                                style="font-size:15px; color:#7C4D0F;">location_city</span>
                                        </span>
                                        <div>
                                            <p style="font-size:9px; color:rgba(124,77,15,0.42); font-weight:700; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:1px;">ເມືອງ</p>
                                            <span style="font-size:13px; font-weight:600; color:#3D2A12;">{{ $district }}</span>
                                        </div>
                                    </div>
                                @endif
                                @if ($province)
                                    <div class="flex items-start gap-2.5">
                                        <span class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 mt-0.5"
                                            style="background:rgba(124,77,15,0.08);">
                                            <span class="material-symbols-outlined"
                                                style="font-size:15px; color:#7C4D0F;">map</span>
                                        </span>
                                        <div>
                                            <p style="font-size:9px; color:rgba(124,77,15,0.42); font-weight:700; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:1px;">ແຂວງ</p>
                                            <span style="font-size:13px; font-weight:600; color:#3D2A12;">{{ $province }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                </aside>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════
    OTHER PERSONNEL
    ════════════════════════════════════════════════════ --}}
    @if ($otherPersonnel->count() > 0)
        <section style="background:#F5F0E8; border-top:1px solid rgba(200,149,58,0.12);">
            <div class="max-w-5xl mx-auto px-4 sm:px-8 py-12">

                {{-- Section header --}}
                <div class="flex items-center gap-3 mb-8">
                    <div class="h-px flex-1" style="background:linear-gradient(to right, transparent, rgba(200,149,58,0.3));">
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <svg width="16" height="16" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <ellipse cx="13" cy="13" rx="3" ry="7" fill="#C8953A" opacity="0.5" transform="rotate(0 13 13)" />
                            <ellipse cx="13" cy="13" rx="3" ry="7" fill="#C8953A" opacity="0.5" transform="rotate(90 13 13)" />
                            <circle cx="13" cy="13" r="2" fill="#C8953A" opacity="0.7" />
                        </svg>
                        <h3 class="font-bold" style="font-size:13px; color:#3D2A12; letter-spacing:0.04em;">ບຸກຄະລາກອນອື່ນ</h3>
                    </div>
                    <div class="h-px flex-1" style="background:linear-gradient(to left, transparent, rgba(200,149,58,0.3));">
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach ($otherPersonnel as $p)
                        @php
                            $pPhoto = $p->photo_url ? \Illuminate\Support\Facades\Storage::url($p->photo_url) : null;
                            $pIsMonk = $p->gender === 'monk';
                        @endphp
                        <a href="{{ route('frontend.personnel.show', $p->id) }}"
                            class="flex flex-col items-center text-center rounded-2xl p-4 pt-5 transition-all duration-200 group hover:-translate-y-1 hover:shadow-lg overflow-hidden relative"
                            style="{{ $pIsMonk
                        ? 'background:#FFFCF4; border:1px solid rgba(200,149,58,0.22);'
                        : 'background:white; border:1px solid rgba(0,0,0,0.07);' }}
                                    text-decoration:none;">

                            {{-- Top accent stripe (position:absolute avoids width hacks) --}}
                            <div class="absolute top-0 left-0 right-0 h-[3px]" style="{{ $pIsMonk
                        ? 'background:linear-gradient(to right, #C8953A, #E8B455, #C8953A);'
                        : ($p->gender === 'female'
                            ? 'background:linear-gradient(to right, #7C3AED, #A78BFA);'
                            : 'background:linear-gradient(to right, #475569, #64748B);') }}">
                            </div>

                            {{-- Photo --}}
                            @if ($pPhoto)
                                <img src="{{ $pPhoto }}" alt="{{ $p->display_name }}" loading="lazy"
                                    class="w-16 h-16 rounded-full object-cover mb-3" style="{{ $pIsMonk
                                ? 'box-shadow: 0 0 0 2px #C8953A, 0 0 0 4px rgba(200,149,58,0.15);'
                                : 'box-shadow: 0 1px 6px rgba(0,0,0,0.1);' }}" />
                            @else
                                <div class="w-16 h-16 rounded-full flex items-center justify-center mb-3" style="{{ $pIsMonk
                                ? 'background:linear-gradient(135deg,#FEF3C7,#FDE68A); box-shadow:0 0 0 2px #C8953A;'
                                : 'background:linear-gradient(135deg,#F1F5F9,#E2E8F0);' }}">
                                    <span class="material-symbols-outlined"
                                        style="{{ $pIsMonk ? 'font-size:28px; color:#C8953A;' : 'font-size:28px; color:#94A3B8;' }}">person</span>
                                </div>
                            @endif

                            @if ($p->display_title)
                                <p class="font-bold uppercase tracking-widest mb-0.5"
                                    style="font-size:8px; color:{{ $pIsMonk ? '#C8953A' : '#94A3B8' }};">
                                    {{ $p->display_title }}
                                </p>
                            @endif
                            <p class="font-bold leading-snug" style="font-size:13px; color:#3D2A12; line-height:1.3;">
                                {{ $p->display_name }}
                            </p>
                            @if ($p->display_position)
                                <p class="mt-1 line-clamp-2" style="font-size:10px; color:#6B7280; line-height:1.4;">
                                    {{ $p->display_position }}
                                </p>
                            @endif

                            <div class="mt-auto pt-3 inline-flex items-center gap-1 font-bold transition-all duration-200 group-hover:gap-2"
                                style="font-size:10px; color:#C8953A;">
                                <span>ເບິ່ງລາຍລະອຽດ</span>
                                <span class="material-symbols-outlined" style="font-size:11px;">arrow_forward</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Back to top --}}
    <div x-data="{ visible: false }"
        x-init="window.addEventListener('scroll', () => { visible = window.scrollY > 400; }, { passive: true })"
        class="fixed bottom-6 right-6 z-50">
        <button x-show="visible" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90" @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            class="w-11 h-11 rounded-full text-white shadow-lg flex items-center justify-center hover:scale-105 transition-transform"
            style="background:linear-gradient(135deg, #C8953A 0%, #7C4D0F 100%); box-shadow:0 4px 20px rgba(200,149,58,0.4);"
            aria-label="ກັບຂຶ້ນດ້ານເທິງ">
            <span class="material-symbols-outlined" style="font-size:20px;">keyboard_arrow_up</span>
        </button>
    </div>

@endsection