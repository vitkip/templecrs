@extends('frontend.layout')

@section('content')

@php
    $locale       = app()->getLocale();
    $name         = $person->display_name;
    $title        = $person->display_title;
    $position     = $person->display_position;
    $bio          = $person->display_bio;
    $education    = $locale === 'lo'
        ? ($person->education_lo ?? $person->education_en)
        : ($person->education_en ?? $person->education_lo);
    $currentTemple = $locale === 'lo'
        ? ($person->current_temple_lo ?? $person->current_temple_en)
        : ($person->current_temple_en ?? $person->current_temple_lo);
    $photoUrl     = $person->photo_url
        ? \Illuminate\Support\Facades\Storage::url($person->photo_url)
        : null;
    $isMonk       = $person->gender === 'monk';
    $isFemale     = $person->gender === 'female';
@endphp

{{-- ════════════════════════════════════════════════════
HERO
════════════════════════════════════════════════════ --}}
<div class="relative overflow-hidden"
    style="background: linear-gradient(150deg, #2C1A08 0%, #3D2A12 40%, #1C2B1C 100%); min-height: 220px;">

    {{-- Lotus texture --}}
    <div class="absolute inset-0 pointer-events-none" style="opacity: 0.055;" aria-hidden="true">
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

    <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-8 pt-10 pb-14">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 mb-8" aria-label="Breadcrumb">
            <a href="{{ route('frontend.index') }}" class="flex items-center gap-1 transition-colors"
                style="font-size:11px; color: rgba(255,255,255,0.45);"
                onmouseover="this.style.color='rgba(212,175,55,0.85)'"
                onmouseout="this.style.color='rgba(255,255,255,0.45)'">
                <span class="material-symbols-outlined" style="font-size:12px;">home</span>
                {{ __('messages.homepage') }}
            </a>
            <span style="color: rgba(255,255,255,0.2); font-size:11px;">›</span>
            <a href="{{ route('frontend.personnel') }}" class="transition-colors"
                style="font-size:11px; color: rgba(255,255,255,0.45);"
                onmouseover="this.style.color='rgba(212,175,55,0.85)'"
                onmouseout="this.style.color='rgba(255,255,255,0.45)'">
                {{ __('messages.personnel') }}
            </a>
            <span style="color: rgba(255,255,255,0.2); font-size:11px;">›</span>
            <span style="font-size:11px; color: rgba(255,255,255,0.7);" class="truncate max-w-[200px]">{{ $name }}</span>
        </nav>

        {{-- Identity block --}}
        <div class="flex items-end gap-6">

            {{-- Photo --}}
            <div class="shrink-0">
                @if ($photoUrl)
                    <img src="{{ $photoUrl }}" alt="{{ $name }}" loading="eager"
                        class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl object-cover"
                        style="{{ $isMonk
                            ? 'box-shadow: 0 0 0 3px #C8953A, 0 0 0 6px rgba(200,149,58,0.25);'
                            : 'box-shadow: 0 0 0 3px rgba(255,255,255,0.2), 0 4px 24px rgba(0,0,0,0.4);' }}" />
                @else
                    <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl flex items-center justify-center"
                        style="{{ $isMonk
                            ? 'background:linear-gradient(135deg,#FEF3C7,#FDE68A); box-shadow:0 0 0 3px #C8953A,0 0 0 6px rgba(200,149,58,0.2);'
                            : ($isFemale
                                ? 'background:linear-gradient(135deg,#EDE9FE,#DDD6FE); box-shadow:0 0 0 3px rgba(255,255,255,0.15);'
                                : 'background:linear-gradient(135deg,#334155,#475569); box-shadow:0 0 0 3px rgba(255,255,255,0.15);') }}">
                        <span class="material-symbols-outlined"
                            style="{{ $isMonk ? 'font-size:44px; color:#C8953A;' : 'font-size:44px; color:rgba(255,255,255,0.5);' }}">person</span>
                    </div>
                @endif
            </div>

            {{-- Name + position --}}
            <div class="pb-1">
                @if ($title)
                    <p class="font-bold uppercase tracking-widest mb-1"
                        style="font-size:10px; color: {{ $isMonk ? '#C8953A' : 'rgba(255,255,255,0.45)' }};">
                        {{ $title }}
                    </p>
                @endif
                <h1 class="font-bold text-white leading-tight mb-2"
                    style="font-size: clamp(20px, 4vw, 32px);">
                    {{ $name }}
                </h1>
                @if ($position)
                    <p style="font-size:13px; color:rgba(255,255,255,0.6); line-height:1.5;">{{ $position }}</p>
                @endif

                {{-- Chips --}}
                <div class="flex flex-wrap gap-2 mt-3">
                    @if ($person->department)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full"
                            style="font-size:11px; font-weight:600; background:rgba(255,255,255,0.1); color:rgba(255,255,255,0.75); backdrop-filter:blur(4px);">
                            <span class="material-symbols-outlined" style="font-size:11px;">corporate_fare</span>
                            {{ $person->department->name }}
                        </span>
                    @endif
                    @if ($person->affiliation_level === 'central')
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full"
                            style="font-size:11px; font-weight:700; background:rgba(55,48,163,0.6); color:#C7D2FE;">
                            <span class="material-symbols-outlined" style="font-size:11px;">location_city</span>
                            ສູນກາງ
                        </span>
                    @endif
                    @if ($person->affiliation_level === 'provincial')
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full"
                            style="font-size:11px; font-weight:700; background:rgba(61,90,71,0.6); color:#A7F3D0;">
                            <span class="material-symbols-outlined" style="font-size:11px;">map</span>
                            {{ $person->affiliation_province ?? 'ແຂວງ' }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════
MAIN CONTENT
════════════════════════════════════════════════════ --}}
<div style="background-color: #FAF6EF;">

    {{-- Lotus divider --}}
    <div class="flex items-center justify-center py-5" aria-hidden="true">
        <div style="height:1px; width:96px; background: linear-gradient(to right, transparent, rgba(200,149,58,0.3));"></div>
        <svg class="mx-4 shrink-0" width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
            <ellipse cx="13" cy="13" rx="3" ry="8" fill="#C8953A" opacity="0.45" transform="rotate(0 13 13)" />
            <ellipse cx="13" cy="13" rx="3" ry="8" fill="#C8953A" opacity="0.45" transform="rotate(45 13 13)" />
            <ellipse cx="13" cy="13" rx="3" ry="8" fill="#C8953A" opacity="0.45" transform="rotate(90 13 13)" />
            <ellipse cx="13" cy="13" rx="3" ry="8" fill="#C8953A" opacity="0.45" transform="rotate(135 13 13)" />
            <circle cx="13" cy="13" r="2.5" fill="#C8953A" opacity="0.65" />
        </svg>
        <div style="height:1px; width:96px; background: linear-gradient(to left, transparent, rgba(200,149,58,0.3));"></div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-8 pb-16">

        {{-- Back button --}}
        <div class="mb-8">
            <a href="{{ route('frontend.personnel') }}"
                class="inline-flex items-center gap-2 transition-colors"
                style="font-size:13px; font-weight:600; color:#7C4D0F; text-decoration:none;"
                onmouseover="this.style.color='#5D3908'" onmouseout="this.style.color='#7C4D0F'">
                <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span>
                ກັບໄປລາຍການ
            </a>
        </div>

        <div class="lg:flex lg:gap-12">

            {{-- ── MAIN COLUMN ── --}}
            <div class="flex-1 min-w-0 space-y-8">

                {{-- Bio --}}
                @if ($bio)
                    <section>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0"
                                style="{{ $isMonk ? 'background:rgba(200,149,58,0.15);' : 'background:rgba(124,77,15,0.1);' }}">
                                <span class="material-symbols-outlined" style="font-size:16px; color:#C8953A;">person</span>
                            </div>
                            <h2 class="font-bold" style="font-size:15px; color:#3D2A12; letter-spacing:-0.01em;">ຊີວະປະຫວັດ</h2>
                        </div>
                        <div class="rounded-2xl p-5"
                            style="{{ $isMonk
                                ? 'background:rgba(200,149,58,0.06); border:1px solid rgba(200,149,58,0.2);'
                                : 'background:white; border:1px solid rgba(0,0,0,0.07);' }}">
                            <p style="font-size:15px; line-height:1.85; color:#374151;">{{ $bio }}</p>
                        </div>
                    </section>
                @endif

                {{-- Education --}}
                @if ($education)
                    <section>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0"
                                style="background:rgba(124,77,15,0.1);">
                                <span class="material-symbols-outlined" style="font-size:16px; color:#7C4D0F;">school</span>
                            </div>
                            <h2 class="font-bold" style="font-size:15px; color:#3D2A12; letter-spacing:-0.01em;">ລະດັບການສຶກສາ</h2>
                        </div>
                        <div class="rounded-2xl p-5" style="background:white; border:1px solid rgba(0,0,0,0.07);">
                            <p style="font-size:15px; line-height:1.85; color:#374151;">{{ $education }}</p>
                        </div>
                    </section>
                @endif

                {{-- Current temple --}}
                @if ($currentTemple)
                    <section>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0"
                                style="{{ $isMonk ? 'background:rgba(200,149,58,0.15);' : 'background:rgba(124,77,15,0.1);' }}">
                                <span class="material-symbols-outlined"
                                    style="font-size:16px; color:{{ $isMonk ? '#C8953A' : '#7C4D0F' }};">temple_buddhist</span>
                            </div>
                            <h2 class="font-bold" style="font-size:15px; color:#3D2A12; letter-spacing:-0.01em;">ວັດຢູ່ປະຈຸບັນ</h2>
                        </div>
                        <div class="rounded-2xl p-5"
                            style="{{ $isMonk
                                ? 'background:rgba(200,149,58,0.06); border:1px solid rgba(200,149,58,0.2);'
                                : 'background:white; border:1px solid rgba(0,0,0,0.07);' }}">
                            <p style="font-size:15px; line-height:1.85; color:#374151;">{{ $currentTemple }}</p>
                        </div>
                    </section>
                @endif

                @if (!$bio && !$education && !$currentTemple)
                    <div class="text-center py-16">
                        <span class="material-symbols-outlined" style="font-size:40px; color:rgba(200,149,58,0.25);">person</span>
                        <p style="font-size:14px; color:#94A3B8; margin-top:8px;">ຍັງບໍ່ມີຂໍ້ມູນລາຍລະອຽດ</p>
                    </div>
                @endif

            </div>

            {{-- ── SIDEBAR ── --}}
            <aside class="w-full lg:w-64 shrink-0 mt-10 lg:mt-0 space-y-4">

                {{-- Photo card (desktop) --}}
                @if ($photoUrl)
                    <div class="hidden lg:block rounded-2xl overflow-hidden"
                        style="{{ $isMonk
                            ? 'box-shadow: 0 0 0 2px #C8953A, 0 4px 24px rgba(200,149,58,0.2);'
                            : 'box-shadow: 0 2px 16px rgba(0,0,0,0.1);' }}">
                        <img src="{{ $photoUrl }}" alt="{{ $name }}" class="w-full object-cover" style="max-height:320px;" />
                    </div>
                @endif

                {{-- Contact card --}}
                @if ($person->phone || $person->email || $person->facebook)
                    <div class="rounded-2xl p-4"
                        style="{{ $isMonk
                            ? 'background:rgba(200,149,58,0.07); border:1px solid rgba(200,149,58,0.25);'
                            : 'background:white; border:1px solid rgba(0,0,0,0.08);' }}">
                        <p class="font-bold uppercase tracking-widest mb-3"
                            style="font-size:9px; color:rgba(124,77,15,0.5); letter-spacing:0.15em;">ຂໍ້ມູນຕິດຕໍ່</p>
                        <div class="space-y-3">
                            @if ($person->phone)
                                <a href="tel:{{ $person->phone }}"
                                    class="flex items-center gap-3 transition-colors group/link"
                                    style="text-decoration:none;">
                                    <span class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0"
                                        style="background:rgba(200,149,58,0.15);">
                                        <span class="material-symbols-outlined" style="font-size:15px; color:#C8953A;">phone</span>
                                    </span>
                                    <div>
                                        <p style="font-size:9px; color:rgba(124,77,15,0.45); font-weight:700; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:1px;">ໂທລະສັບ</p>
                                        <span style="font-size:13px; font-weight:600; color:#7C4D0F;">{{ $person->phone }}</span>
                                    </div>
                                </a>
                            @endif

                            @if ($person->email)
                                <a href="mailto:{{ $person->email }}"
                                    class="flex items-center gap-3 transition-colors"
                                    style="text-decoration:none;">
                                    <span class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0"
                                        style="background:rgba(200,149,58,0.15);">
                                        <span class="material-symbols-outlined" style="font-size:15px; color:#C8953A;">mail</span>
                                    </span>
                                    <div class="min-w-0">
                                        <p style="font-size:9px; color:rgba(124,77,15,0.45); font-weight:700; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:1px;">ອີເມວ</p>
                                        <span class="block truncate" style="font-size:12px; font-weight:500; color:#7C4D0F;">{{ $person->email }}</span>
                                    </div>
                                </a>
                            @endif

                            @if ($person->facebook)
                                <a href="{{ $person->facebook }}" target="_blank" rel="noopener noreferrer"
                                    class="flex items-center gap-3 transition-colors"
                                    style="text-decoration:none;">
                                    <span class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0"
                                        style="background:rgba(200,149,58,0.15);">
                                        <span class="material-symbols-outlined" style="font-size:15px; color:#C8953A;">share</span>
                                    </span>
                                    <div>
                                        <p style="font-size:9px; color:rgba(124,77,15,0.45); font-weight:700; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:1px;">ໂຊຊຽວ</p>
                                        <span style="font-size:13px; font-weight:600; color:#7C4D0F;">Facebook</span>
                                    </div>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Department card --}}
                @if ($person->department)
                    <div class="rounded-2xl p-4" style="background:white; border:1px solid rgba(0,0,0,0.08);">
                        <p class="font-bold uppercase tracking-widest mb-3"
                            style="font-size:9px; color:rgba(124,77,15,0.5); letter-spacing:0.15em;">ພະແນກ / ກົມ</p>
                        <div class="flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0"
                                style="background:rgba(124,77,15,0.08);">
                                <span class="material-symbols-outlined" style="font-size:15px; color:#7C4D0F;">corporate_fare</span>
                            </span>
                            <span style="font-size:13px; font-weight:600; color:#3D2A12;">{{ $person->department->name }}</span>
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
    <section style="background: linear-gradient(to bottom, transparent, rgba(200,149,58,0.04), transparent);">
        <div class="max-w-5xl mx-auto px-4 sm:px-8 py-14">

            {{-- Section header --}}
            <div class="flex items-center gap-3 mb-8">
                <div class="h-px flex-1" style="background: linear-gradient(to right, transparent, rgba(200,149,58,0.35));"></div>
                <div class="flex items-center gap-2 shrink-0">
                    <svg width="18" height="18" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <ellipse cx="13" cy="13" rx="3" ry="7" fill="#C8953A" opacity="0.5" transform="rotate(0 13 13)" />
                        <ellipse cx="13" cy="13" rx="3" ry="7" fill="#C8953A" opacity="0.5" transform="rotate(90 13 13)" />
                        <circle cx="13" cy="13" r="2" fill="#C8953A" opacity="0.7" />
                    </svg>
                    <h3 class="font-bold" style="font-size:14px; color:#3D2A12;">ບຸກຄະລາກອນອື່ນ</h3>
                </div>
                <div class="h-px flex-1" style="background: linear-gradient(to left, transparent, rgba(200,149,58,0.35));"></div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-4">
                @foreach ($otherPersonnel as $p)
                    @php
                        $pPhoto = $p->photo_url ? \Illuminate\Support\Facades\Storage::url($p->photo_url) : null;
                        $pIsMonk = $p->gender === 'monk';
                    @endphp
                    <a href="{{ route('frontend.personnel.show', $p->id) }}"
                        class="flex flex-col items-center text-center rounded-2xl p-4 transition-all duration-200 group hover:-translate-y-1 hover:shadow-lg"
                        style="{{ $pIsMonk
                            ? 'background:#FFFCF4; border:1px solid rgba(200,149,58,0.25);'
                            : 'background:white; border:1px solid rgba(0,0,0,0.07);' }}"
                        style="text-decoration:none;">

                        {{-- Top stripe --}}
                        <div class="w-full h-[2px] rounded-t-2xl mb-3 -mt-4 -mx-4" style="width:calc(100% + 32px);
                            {{ $pIsMonk
                                ? 'background: linear-gradient(to right, #C8953A, #E8B455, #C8953A);'
                                : ($p->gender === 'female'
                                    ? 'background: linear-gradient(to right, #7C3AED, #A78BFA);'
                                    : 'background: linear-gradient(to right, #475569, #64748B);') }}">
                        </div>

                        {{-- Photo --}}
                        @if ($pPhoto)
                            <img src="{{ $pPhoto }}" alt="{{ $p->display_name }}" loading="lazy"
                                class="w-16 h-16 rounded-xl object-cover mb-3"
                                style="{{ $pIsMonk
                                    ? 'box-shadow: 0 0 0 2px #C8953A, 0 0 0 4px rgba(200,149,58,0.15);'
                                    : 'box-shadow: 0 1px 6px rgba(0,0,0,0.1);' }}" />
                        @else
                            <div class="w-16 h-16 rounded-xl flex items-center justify-center mb-3"
                                style="{{ $pIsMonk
                                    ? 'background:linear-gradient(135deg,#FEF3C7,#FDE68A); box-shadow:0 0 0 2px #C8953A;'
                                    : 'background:linear-gradient(135deg,#F1F5F9,#E2E8F0);' }}">
                                <span class="material-symbols-outlined"
                                    style="{{ $pIsMonk ? 'font-size:28px; color:#C8953A;' : 'font-size:28px; color:#94A3B8;' }}">person</span>
                            </div>
                        @endif

                        @if ($p->display_title)
                            <p class="font-bold uppercase tracking-widest mb-0.5"
                                style="font-size:8px; color: {{ $pIsMonk ? '#C8953A' : '#94A3B8' }};">
                                {{ $p->display_title }}
                            </p>
                        @endif
                        <p class="font-bold leading-snug"
                            style="font-size:13px; color:#3D2A12; line-height:1.3;">
                            {{ $p->display_name }}
                        </p>
                        @if ($p->display_position)
                            <p class="mt-1 line-clamp-2"
                                style="font-size:10px; color:#6B7280; line-height:1.4;">
                                {{ $p->display_position }}
                            </p>
                        @endif

                        <div class="mt-3 inline-flex items-center gap-1 font-bold transition-all duration-200 group-hover:gap-2"
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
    <button x-show="visible"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90"
        @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
        class="w-11 h-11 rounded-full text-white shadow-lg flex items-center justify-center hover:scale-105 transition-transform"
        style="background: linear-gradient(135deg, #C8953A 0%, #7C4D0F 100%); box-shadow: 0 4px 20px rgba(200,149,58,0.45);"
        aria-label="Back to top">
        <span class="material-symbols-outlined" style="font-size:20px;">keyboard_arrow_up</span>
    </button>
</div>

@endsection
