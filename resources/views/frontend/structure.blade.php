@extends('frontend.layout')

@section('content')

<style>
.chart-card {
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}
.chart-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 16px 32px -8px rgba(44, 26, 8, 0.15), 0 0 0 1px rgba(200, 146, 26, 0.35) !important;
}
.tree-line-v {
    width: 2px;
    background: linear-gradient(to bottom, #C8921A, #E8B84B);
    margin: 0 auto;
}
.tree-line-h {
    height: 2px;
    background: linear-gradient(to right, transparent, #C8921A 15%, #E8B84B 50%, #C8921A 85%, transparent);
}
.dept-pill {
    transition: all 0.2s ease;
}
.dept-pill:hover {
    background: rgba(200, 146, 26, 0.15);
    border-color: #C8921A;
}
@media (prefers-reduced-motion: reduce) {
    .chart-card, .chart-card:hover, .dept-pill {
        transform: none;
        transition: none;
    }
}
</style>

{{-- ════════════════════════════════════════════════════
     HERO SECTION
════════════════════════════════════════════════════ --}}
<div class="relative overflow-hidden"
     style="background: linear-gradient(160deg, #0A1208 0%, #1C2C12 38%, #2C1A06 72%, #180E04 100%); min-height: 360px;">

    {{-- Lotus repeating SVG texture --}}
    <div class="absolute inset-0 pointer-events-none" style="opacity:0.045;" aria-hidden="true">
        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="lps" x="0" y="0" width="80" height="80" patternUnits="userSpaceOnUse">
                    <g fill="none" stroke="#C8921A" stroke-width="0.65">
                        <ellipse cx="40" cy="26" rx="6" ry="16"/>
                        <ellipse cx="40" cy="26" rx="6" ry="16" transform="rotate(45 40 40)"/>
                        <ellipse cx="40" cy="26" rx="6" ry="16" transform="rotate(90 40 40)"/>
                        <ellipse cx="40" cy="26" rx="6" ry="16" transform="rotate(135 40 40)"/>
                        <ellipse cx="40" cy="26" rx="6" ry="16" transform="rotate(180 40 40)"/>
                        <ellipse cx="40" cy="26" rx="6" ry="16" transform="rotate(225 40 40)"/>
                        <ellipse cx="40" cy="26" rx="6" ry="16" transform="rotate(270 40 40)"/>
                        <ellipse cx="40" cy="26" rx="6" ry="16" transform="rotate(315 40 40)"/>
                    </g>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#lps)"/>
        </svg>
    </div>

    {{-- Golden radial glow --}}
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true"
         style="background: radial-gradient(ellipse at 50% 90%, rgba(200,146,26,0.12) 0%, transparent 65%);"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24 flex flex-col items-center text-center">

        {{-- Decorative lines --}}
        <div class="mb-5" aria-hidden="true">
            <svg width="140" height="4" viewBox="0 0 140 4" fill="none">
                <line x1="0" y1="2" x2="58" y2="2" stroke="#C8921A" stroke-width="0.5" stroke-opacity="0.45"/>
                <circle cx="70" cy="2" r="2" fill="#C8921A" fill-opacity="0.7"/>
                <line x1="82" y1="2" x2="140" y2="2" stroke="#C8921A" stroke-width="0.5" stroke-opacity="0.45"/>
            </svg>
        </div>

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 mb-6" style="color:rgba(200,146,26,0.65); font-size:12px; font-weight:600; letter-spacing:0.1em;">
            <a href="{{ route('frontend.index') }}" style="color:rgba(200,146,26,0.65); text-decoration:none;"
               onmouseover="this.style.color='#C8921A'" onmouseout="this.style.color='rgba(200,146,26,0.65)'">
                {{ __('messages.homepage') }}
            </a>
            <span class="material-symbols-outlined" style="font-size:14px;">chevron_right</span>
            <a href="{{ route('frontend.about') }}" style="color:rgba(200,146,26,0.65); text-decoration:none;"
               onmouseover="this.style.color='#C8921A'" onmouseout="this.style.color='rgba(200,146,26,0.65)'">
                {{ __('messages.about_nav') }}
            </a>
            <span class="material-symbols-outlined" style="font-size:14px;">chevron_right</span>
            <span style="color:#C8921A;">{{ __('messages.structure_breadcrumb_label') }}</span>
        </div>

        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full mb-6"
             style="background:rgba(200,146,26,0.09); border:1px solid rgba(200,146,26,0.25); color:#C8921A; font-size:11px; font-weight:700; letter-spacing:0.2em; text-transform:uppercase;">
            <span class="material-symbols-outlined" style="font-size:14px;">account_tree</span>
            {{ __('messages.structure_badge') }}
        </div>

        <h1 class="font-bold text-white mb-4"
            style="font-size:clamp(1.8rem,4.5vw,3rem); line-height:1.2; text-shadow:0 2px 32px rgba(0,0,0,0.5);">
            {{ __('messages.structure_hero_title') }}<br>
            <span style="color:#E8B84B;">{{ __('messages.structure_hero_subtitle') }}</span>
        </h1>
        <p style="color:rgba(255,255,255,0.55); font-size:1rem; max-width:620px; line-height:1.8;">
            {{ __('messages.structure_hero_desc') }}
        </p>

        {{-- Quick Stats Pills --}}
        <div class="flex flex-wrap items-center justify-center gap-3 mt-8">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold"
                 style="background:rgba(255,255,255,0.06); border:1px solid rgba(200,146,26,0.25); color:rgba(255,255,255,0.85);">
                <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                <span>{{ __('messages.structure_overview_pill') }}</span>
            </div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold"
                 style="background:rgba(255,255,255,0.06); border:1px solid rgba(200,146,26,0.25); color:rgba(255,255,255,0.85);">
                <span class="material-symbols-outlined text-amber-400" style="font-size:14px;">domain</span>
                <span>{{ $departments->count() }} ຄະນະ / ພະແນກ</span>
            </div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold"
                 style="background:rgba(255,255,255,0.06); border:1px solid rgba(200,146,26,0.25); color:rgba(255,255,255,0.85);">
                <span class="material-symbols-outlined text-amber-400" style="font-size:14px;">groups</span>
                <span>{{ $totalPersonnel }} {{ __('messages.structure_member_count') }}</span>
            </div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold"
                 style="background:rgba(255,255,255,0.06); border:1px solid rgba(200,146,26,0.25); color:rgba(255,255,255,0.85);">
                <span class="material-symbols-outlined text-amber-400" style="font-size:14px;">location_city</span>
                <span>18 ແຂວງ / ນະຄອນຫຼວງ</span>
            </div>
        </div>

        <div class="mt-8" aria-hidden="true">
            <svg width="140" height="4" viewBox="0 0 140 4" fill="none">
                <line x1="0" y1="2" x2="58" y2="2" stroke="#C8921A" stroke-width="0.5" stroke-opacity="0.45"/>
                <circle cx="70" cy="2" r="2" fill="#C8921A" fill-opacity="0.7"/>
                <line x1="82" y1="2" x2="140" y2="2" stroke="#C8921A" stroke-width="0.5" stroke-opacity="0.45"/>
            </svg>
        </div>
    </div>
</div>

{{-- Hero → cream divider --}}
<div aria-hidden="true" style="background:#0E150E; line-height:0;">
    <svg viewBox="0 0 1440 52" xmlns="http://www.w3.org/2000/svg"
         style="width:100%; height:52px; display:block;" preserveAspectRatio="none">
        <path fill="#FFFBEB" d="M0,40 Q720,10 1440,40 L1440,52 L0,52 Z"/>
    </svg>
</div>


{{-- ════════════════════════════════════════════════════
     MAIN CONTENT WITH TABS
════════════════════════════════════════════════════ --}}
<div style="background:#FFFBEB; min-height:70vh;" x-data="{ currentTab: 'chart' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">

        {{-- Tab Switcher Bar --}}
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-12 pb-6 border-b border-outline-variant/60">
            <div class="flex items-center gap-2 p-1.5 rounded-2xl bg-white shadow-sm border border-outline-variant/60">
                <button type="button"
                        @click="currentTab = 'chart'"
                        :class="currentTab === 'chart' ? 'bg-primary text-white shadow-sm font-bold' : 'text-on-surface-variant hover:text-primary hover:bg-primary/5'"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs sm:text-sm transition-all duration-200">
                    <span class="material-symbols-outlined text-base">account_tree</span>
                    <span>{{ __('messages.structure_tab_chart') }}</span>
                </button>
                <button type="button"
                        @click="currentTab = 'departments'"
                        :class="currentTab === 'departments' ? 'bg-primary text-white shadow-sm font-bold' : 'text-on-surface-variant hover:text-primary hover:bg-primary/5'"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs sm:text-sm transition-all duration-200">
                    <span class="material-symbols-outlined text-base">domain</span>
                    <span>{{ __('messages.structure_tab_departments') }}</span>
                </button>
                <button type="button"
                        @click="currentTab = 'provinces'"
                        :class="currentTab === 'provinces' ? 'bg-primary text-white shadow-sm font-bold' : 'text-on-surface-variant hover:text-primary hover:bg-primary/5'"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs sm:text-sm transition-all duration-200">
                    <span class="material-symbols-outlined text-base">map</span>
                    <span>{{ __('messages.structure_tab_provinces') }}</span>
                </button>
            </div>

            <div class="text-xs text-on-surface-variant flex items-center gap-1.5">
                <span class="material-symbols-outlined text-amber-600 text-sm">touch_app</span>
                <span>{{ __('messages.structure_explore_hint') }}</span>
            </div>
        </div>


        {{-- ════════════════════════════════════════════════
             TAB 1: HIERARCHICAL ORG CHART DIAGRAM
        ════════════════════════════════════════════════ --}}
        <div x-show="currentTab === 'chart'" x-transition:enter="transition ease-out duration-300">

            {{-- ── Tier 0: Supreme Supervising Body ── --}}
            <div class="flex flex-col items-center">
                <div class="chart-card max-w-xl w-full rounded-2xl p-6 text-center text-white relative overflow-hidden"
                     style="background: linear-gradient(135deg, #1C2B1C 0%, #2A3C2A 60%, #172417 100%); border: 1.5px solid rgba(200, 146, 26, 0.45); box-shadow: 0 12px 36px rgba(14,21,14,0.18);">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider mb-2.5"
                         style="background: rgba(200, 146, 26, 0.2); border: 1px solid rgba(200, 146, 26, 0.4); color: #E8B84B;">
                        <span class="material-symbols-outlined text-xs">temple_buddhist</span>
                        <span>ອົງການຊີ້ນຳສູງສຸດ</span>
                    </div>
                    <h2 class="text-lg sm:text-xl font-bold text-white leading-snug">
                        {{ __('messages.structure_supreme_council') }}
                    </h2>
                    <p class="text-xs mt-1.5 text-white/60">
                        {{ __('messages.structure_supreme_desc') }}
                    </p>
                </div>

                {{-- Connector Line --}}
                <div class="tree-line-v h-10"></div>
                <div class="w-3 h-3 rounded-full bg-amber-500 border-2 border-white shadow-sm -my-1.5 z-10"></div>
                <div class="tree-line-v h-8"></div>
            </div>


            {{-- ── Tier 1: Commission Leadership / Head ── --}}
            <div class="flex flex-col items-center">
                <div class="chart-card max-w-2xl w-full rounded-3xl p-7 text-center relative overflow-hidden bg-white"
                     style="border: 2px solid #C8921A; box-shadow: 0 16px 40px rgba(44, 26, 8, 0.1);">
                    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-[#B87A14] via-[#E8B84B] to-[#B87A14]"></div>

                    <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-bold text-amber-800 bg-amber-50 border border-amber-200 mb-4">
                        <span class="material-symbols-outlined text-sm text-amber-600">stars</span>
                        <span>{{ __('messages.structure_head_role') }}</span>
                    </div>

                    @php
                        $commissionHead = $departments->firstWhere('id', 9)?->head;
                        $coLeader = $departments->firstWhere('id', 9)?->personnel->firstWhere('id', '!=', $commissionHead?->id);
                    @endphp

                    <div class="flex flex-col sm:flex-row items-center justify-center gap-6 text-center sm:text-left">
                        {{-- Head Photo --}}
                        <div class="relative w-24 h-24 sm:w-28 sm:h-28 rounded-2xl overflow-hidden shrink-0 border-2 border-amber-400 p-0.5 shadow-md bg-amber-50">
                            @if($commissionHead && $commissionHead->photo_url)
                                <img src="{{ Storage::url($commissionHead->photo_url) }}" alt="{{ $commissionHead->name_lo }}"
                                     class="w-full h-full object-cover rounded-xl" />
                            @else
                                <div class="w-full h-full rounded-xl bg-amber-100 flex items-center justify-center text-amber-700">
                                    <span class="material-symbols-outlined text-4xl">person</span>
                                </div>
                            @endif
                        </div>

                        {{-- Head Info --}}
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-on-surface leading-tight">
                                {{ $commissionHead?->name_lo ?? 'ພຣະອາຈານໃຫຍ່ ບຸນທະວີ ປະສິດທິສັກ' }}
                            </h3>
                            <p class="text-xs text-amber-700 font-bold uppercase tracking-wider mt-1">
                                {{ $commissionHead?->position_lo ?? 'ຮອງປະທານສູນກາງ ອພສ, ຫົວໜ້າກັມມາທິການສາທາຣະນູປະການ' }}
                            </p>
                            <p class="text-xs text-on-surface-variant mt-2 leading-relaxed">
                                ຮັບຜິດຊອບຊີ້ນຳ-ນຳພາຮອບດ້ານ ວຽກງານກັມມາທິການສາທາຣະນູປະການ ໃນຂອບເຂດທົ່ວປະເທດ
                            </p>

                            @if($commissionHead)
                            <div class="mt-3.5">
                                <a href="{{ route('frontend.personnel.show', $commissionHead->id) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-primary bg-primary/10 hover:bg-primary hover:text-white transition-all">
                                    <span>{{ __('messages.structure_view_personnel') }}</span>
                                    <span class="material-symbols-outlined text-xs">arrow_forward</span>
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Co-leader if present --}}
                    @if($coLeader)
                    <div class="mt-5 pt-4 border-t border-outline-variant/60 flex items-center justify-between text-xs text-left bg-surface-container-low/50 px-4 py-2.5 rounded-xl">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-amber-600 text-sm">handshake</span>
                            <div>
                                <span class="font-bold text-on-surface">{{ $coLeader->name_lo }}</span>
                                <span class="text-on-surface-variant text-[11px] block">{{ $coLeader->position_lo }}</span>
                            </div>
                        </div>
                        <a href="{{ route('frontend.personnel.show', $coLeader->id) }}"
                           class="text-primary hover:underline font-semibold text-[11px] shrink-0">
                            {{ __('messages.structure_view_personnel') }}
                        </a>
                    </div>
                    @endif
                </div>

                {{-- Connector Line --}}
                <div class="tree-line-v h-10"></div>
                <div class="w-3 h-3 rounded-full bg-amber-500 border-2 border-white shadow-sm -my-1.5 z-10"></div>
            </div>


            {{-- ── Tier 2: The Two Wings (Admin vs Technical) ── --}}
            <div class="relative max-w-5xl mx-auto mt-4">
                {{-- Horizontal connecting bar --}}
                <div class="tree-line-h w-full hidden md:block"></div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">

                    {{-- Wing 1: Administration & Management --}}
                    <div class="flex flex-col items-center">
                        <div class="tree-line-v h-6 hidden md:block -mt-4"></div>
                        <div class="chart-card w-full rounded-2xl p-6 bg-white border border-amber-300/80 shadow-md relative overflow-hidden"
                             style="box-shadow: 0 10px 30px rgba(44, 26, 8, 0.07);">
                            <div class="flex items-center justify-between mb-4 pb-3 border-b border-outline-variant/50">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-base">manage_accounts</span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-blue-800">ສາຍງານທີ 1</span>
                                        <h4 class="text-sm font-bold text-on-surface">{{ __('messages.structure_deputy_admin') }}</h4>
                                    </div>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                    ບໍລິຫານ-ຄຸ້ມຄອງ
                                </span>
                            </div>

                            @php $adminHead = $adminDept?->head; @endphp
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-xl overflow-hidden shrink-0 border border-amber-300 p-0.5 bg-amber-50">
                                    @if($adminHead && $adminHead->photo_url)
                                        <img src="{{ Storage::url($adminHead->photo_url) }}" alt="{{ $adminHead->name_lo }}"
                                             class="w-full h-full object-cover rounded-lg" />
                                    @else
                                        <div class="w-full h-full rounded-lg bg-amber-100 flex items-center justify-center text-amber-700">
                                            <span class="material-symbols-outlined text-2xl">person</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h5 class="text-sm font-bold text-on-surface truncate">
                                        {{ $adminHead?->name_lo ?? 'ພຣະອາຈານໃຫຍ່ ບຸນເສີຍ ເພົາພາສິດ' }}
                                    </h5>
                                    <p class="text-[11px] text-on-surface-variant truncate mt-0.5">
                                        {{ $adminHead?->position_lo ?? 'ຮອງຫົວໜ້າກັມມາທິການ ຝ່າຍບໍລິຫານ' }}
                                    </p>
                                    <p class="text-[11px] text-amber-800 mt-1 leading-snug">
                                        ຊີ້ນຳວຽກງານ: ຫ້ອງການ, ການເງິນ-ບັນຊີ, ແລະ ປະຊາສຳພັນ
                                    </p>
                                </div>
                            </div>

                            @if($adminHead)
                            <div class="mt-4 pt-3 border-t border-outline-variant/40 flex justify-end">
                                <a href="{{ route('frontend.personnel.show', $adminHead->id) }}"
                                   class="text-xs font-bold text-primary hover:text-primary-container inline-flex items-center gap-1">
                                    <span>{{ __('messages.structure_view_personnel') }}</span>
                                    <span class="material-symbols-outlined text-xs">arrow_forward</span>
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Wing 2: Technical & Academic --}}
                    <div class="flex flex-col items-center">
                        <div class="tree-line-v h-6 hidden md:block -mt-4"></div>
                        <div class="chart-card w-full rounded-2xl p-6 bg-white border border-amber-300/80 shadow-md relative overflow-hidden"
                             style="box-shadow: 0 10px 30px rgba(44, 26, 8, 0.07);">
                            <div class="flex items-center justify-between mb-4 pb-3 border-b border-outline-variant/50">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-base">architecture</span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-800">ສາຍງານທີ 2</span>
                                        <h4 class="text-sm font-bold text-on-surface">{{ __('messages.structure_deputy_tech') }}</h4>
                                    </div>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    ວິຊາການ-ເຕັກນິກ
                                </span>
                            </div>

                            @php $techHead = $techDept?->head; @endphp
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-xl overflow-hidden shrink-0 border border-amber-300 p-0.5 bg-amber-50">
                                    @if($techHead && $techHead->photo_url)
                                        <img src="{{ Storage::url($techHead->photo_url) }}" alt="{{ $techHead->name_lo }}"
                                             class="w-full h-full object-cover rounded-lg" />
                                    @else
                                        <div class="w-full h-full rounded-lg bg-amber-100 flex items-center justify-center text-amber-700">
                                            <span class="material-symbols-outlined text-2xl">person</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h5 class="text-sm font-bold text-on-surface truncate">
                                        {{ $techHead?->name_lo ?? 'ພຣະອາຈານໃຫຍ່ ປອ ພົງສະຫວັນ ອານຸສິນ' }}
                                    </h5>
                                    <p class="text-[11px] text-on-surface-variant truncate mt-0.5">
                                        {{ $techHead?->position_lo ?? 'ຮອງຫົວໜ້າກັມມາທິການ ຝ່າຍວິຊາການ' }}
                                    </p>
                                    <p class="text-[11px] text-emerald-800 mt-1 leading-snug">
                                        ຊີ້ນຳວຽກງານ: ເຕັກນິກກໍ່ສ້າງ, ແບບແຕ້ມ, ບູລະນະ ແລະ ສາທາລະນູປະໂພກ
                                    </p>
                                </div>
                            </div>

                            @if($techHead)
                            <div class="mt-4 pt-3 border-t border-outline-variant/40 flex justify-end">
                                <a href="{{ route('frontend.personnel.show', $techHead->id) }}"
                                   class="text-xs font-bold text-primary hover:text-primary-container inline-flex items-center gap-1">
                                    <span>{{ __('messages.structure_view_personnel') }}</span>
                                    <span class="material-symbols-outlined text-xs">arrow_forward</span>
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>


            {{-- ── Tier 3: 4 Central Operational Sub-Committees ── --}}
            <div class="mt-14">
                <div class="flex items-center gap-4 mb-4">
                    <div class="tree-line-h flex-1"></div>
                    <div class="text-center px-4">
                        <span class="text-xs font-bold uppercase tracking-widest text-amber-800">
                            {{ __('messages.structure_central_units') }}
                        </span>
                        <h3 class="text-lg font-bold text-on-surface">4 ຄະນະກຳມະການປະຈຳການ (ຂັ້ນສູນກາງ)</h3>
                    </div>
                    <div class="tree-line-h flex-1"></div>
                </div>

                <p class="text-center text-xs text-on-surface-variant max-w-xl mx-auto mb-8">
                    {{ __('messages.structure_central_desc') }}
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @php
                        $deptMeta = [
                            11 => ['icon' => 'business',       'badge' => 'ຫ້ອງການ',       'color' => '#1565C0', 'bg' => '#E3F2FD'],
                            17 => ['icon' => 'account_balance','badge' => 'ການເງິນ-ບັນຊີ', 'color' => '#2E7D32', 'bg' => '#E8F5E9'],
                            13 => ['icon' => 'campaign',       'badge' => 'ປະຊາສຳພັນ',    'color' => '#E65100', 'bg' => '#FFF3E0'],
                            23 => ['icon' => 'construction',   'badge' => 'ສາທາລະນູປະໂພກ','color' => '#6A1B9A', 'bg' => '#F3E5F5'],
                        ];
                    @endphp

                    @foreach($operatingDepts as $dept)
                        @php
                            $meta = $deptMeta[$dept->id] ?? ['icon' => 'folder', 'badge' => 'ພະແນກ', 'color' => '#C8921A', 'bg' => '#FFF8E1'];
                            $head = $dept->head;
                            $count = $dept->personnel->count();
                        @endphp
                        <div class="chart-card rounded-2xl p-5 bg-white border border-outline-variant/60 shadow-sm flex flex-col justify-between"
                             style="border-top: 3px solid {{ $meta['color'] }};">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0"
                                         style="background: {{ $meta['bg'] }}; color: {{ $meta['color'] }};">
                                        <span class="material-symbols-outlined text-lg">{{ $meta['icon'] }}</span>
                                    </div>
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold"
                                          style="background: {{ $meta['bg'] }}; color: {{ $meta['color'] }};">
                                        {{ $count }} {{ __('messages.structure_member_count') }}
                                    </span>
                                </div>

                                <h4 class="font-bold text-sm text-on-surface leading-snug mb-3">
                                    {{ $dept->name_lo }}
                                </h4>

                                {{-- Head Info --}}
                                @if($head)
                                <div class="p-3 rounded-xl bg-surface-container-low/60 border border-outline-variant/40 flex items-center gap-3 mb-3">
                                    <div class="w-10 h-10 rounded-lg overflow-hidden shrink-0 border border-amber-300/80 bg-amber-50">
                                        @if($head->photo_url)
                                            <img src="{{ Storage::url($head->photo_url) }}" alt="{{ $head->name_lo }}"
                                                 class="w-full h-full object-cover rounded-md" />
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-amber-700">
                                                <span class="material-symbols-outlined text-sm">person</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <span class="text-[10px] text-on-surface-variant block uppercase font-semibold">ຫົວໜ້າຄະນະ</span>
                                        <p class="text-xs font-bold text-on-surface truncate">{{ $head->name_lo }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="pt-3 border-t border-outline-variant/40 flex items-center justify-between">
                                <button type="button"
                                        @click="currentTab = 'departments'"
                                        class="text-xs text-primary hover:text-primary-container font-semibold flex items-center gap-1">
                                    <span>ເບິ່ງສະມາຊິກ</span>
                                    <span class="material-symbols-outlined text-xs">arrow_forward</span>
                                </button>
                                @if($head)
                                <a href="{{ route('frontend.personnel.show', $head->id) }}"
                                   title="ເບິ່ງໂປຣໄຟລ໌"
                                   class="text-on-surface-variant hover:text-primary">
                                    <span class="material-symbols-outlined text-sm">contact_page</span>
                                </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>


            {{-- ── Tier 4: Local Organization Cascade (ທ້ອງຖິ່ນ) ── --}}
            <div class="mt-14 p-8 rounded-3xl bg-white border border-amber-200/80 shadow-md">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8 pb-6 border-b border-outline-variant/60">
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold text-amber-800 bg-amber-50 border border-amber-200 mb-2">
                            <span class="material-symbols-outlined text-sm text-amber-600">travel_explore</span>
                            <span>{{ __('messages.structure_local_title') }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-on-surface">ສາຍການຈັດຕັ້ງ ຂັ້ນທ້ອງຖິ່ນ ແລະ ຮາກຖານ</h3>
                        <p class="text-xs text-on-surface-variant mt-1">
                            {{ __('messages.structure_local_desc') }}
                        </p>
                    </div>

                    <button type="button"
                            @click="currentTab = 'provinces'"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-primary bg-primary/10 hover:bg-primary hover:text-white transition-all self-start md:self-auto">
                        <span class="material-symbols-outlined text-sm">map</span>
                        <span>ເບິ່ງທັງໝົດ 18 ແຂວງ ({{ $provincialCount }} ທ່ານ)</span>
                    </button>
                </div>

                {{-- 3-Step Cascade --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Provincial --}}
                    <div class="p-5 rounded-2xl bg-amber-50/60 border border-amber-200 relative">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="w-8 h-8 rounded-lg bg-amber-200/80 text-amber-900 font-bold flex items-center justify-center text-xs">01</span>
                            <div>
                                <span class="text-[10px] text-amber-800 uppercase font-bold tracking-wider">ຂັ້ນທີ 1</span>
                                <h4 class="text-sm font-bold text-on-surface">ຂັ້ນແຂວງ / ນະຄອນຫຼວງ</h4>
                            </div>
                        </div>
                        <p class="text-xs text-on-surface-variant leading-relaxed">
                            ກັມມາທິການສາທາຣະນູປະການ ອພສ ແຂວງ / ນະຄອນຫຼວງ ໃນ 18 ແຂວງທົ່ວປະເທດ ມີພາລະບົດບາດຊີ້ນຳວຽກງານຂົງເຂດສາທາຣະນູປະການພາຍໃນແຂວງ.
                        </p>
                    </div>

                    {{-- District --}}
                    <div class="p-5 rounded-2xl bg-amber-50/60 border border-amber-200 relative">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="w-8 h-8 rounded-lg bg-amber-200/80 text-amber-900 font-bold flex items-center justify-center text-xs">02</span>
                            <div>
                                <span class="text-[10px] text-amber-800 uppercase font-bold tracking-wider">ຂັ້ນທີ 2</span>
                                <h4 class="text-sm font-bold text-on-surface">ຂັ້ນເມືອງ / ເທດສະບານ</h4>
                            </div>
                        </div>
                        <p class="text-xs text-on-surface-variant leading-relaxed">
                            ກັມມາທິການສາທາຣະນູປະການ ອພສ ເມືອງ / ເທດສະບານ / ນະຄອນ ເຮັດໜ້າທີ່ຄຸ້ມຄອງ, ຕິດຕາມ ແລະ ປະສານງານກັບບັນດາວັດວາອາຮາມພາຍໃນເມືອງ.
                        </p>
                    </div>

                    {{-- Temple Level --}}
                    <div class="p-5 rounded-2xl bg-amber-50/60 border border-amber-200 relative">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="w-8 h-8 rounded-lg bg-amber-200/80 text-amber-900 font-bold flex items-center justify-center text-xs">03</span>
                            <div>
                                <span class="text-[10px] text-amber-800 uppercase font-bold tracking-wider">ຂັ້ນທີ 3</span>
                                <h4 class="text-sm font-bold text-on-surface">ວັດວາອາຮາມ (ຂັ້ນຮາກຖານ)</h4>
                            </div>
                        </div>
                        <p class="text-xs text-on-surface-variant leading-relaxed">
                            ເຈົ້າອະທິການວັດ ແລະ ຄະນະກຳມະການວັດ ເປັນຜູ້ຈັດຕັ້ງປະຕິບັດຕົວຈິງໃນການປົກປັກຮັກສາ, ບູລະນະ ແລະ ສ້າງສາສາສະນະສະຖານ.
                        </p>
                    </div>
                </div>
            </div>

        </div>


        {{-- ════════════════════════════════════════════════
             TAB 2: DEPARTMENT DETAILS & MEMBER DIRECTORY
        ════════════════════════════════════════════════ --}}
        <div x-show="currentTab === 'departments'" x-transition:enter="transition ease-out duration-300" style="display:none;">

            <div class="space-y-10">
                @foreach($departments as $dept)
                    @php
                        $head = $dept->head;
                        $members = $dept->personnel;
                    @endphp

                    <div class="rounded-3xl p-6 lg:p-8 bg-white border border-outline-variant/60 shadow-sm"
                         id="dept-{{ $dept->id }}">

                        {{-- Department Header --}}
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-outline-variant/50">
                            <div>
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-primary/10 text-primary mb-2">
                                    <span class="material-symbols-outlined text-sm">domain</span>
                                    <span>ລຳດັບທີ {{ $dept->sort_order }}</span>
                                </div>
                                <h3 class="text-xl font-bold text-on-surface">{{ $dept->name_lo }}</h3>
                                @if($dept->name_en)
                                    <p class="text-xs text-on-surface-variant mt-0.5">{{ $dept->name_en }}</p>
                                @endif
                            </div>

                            <div class="flex items-center gap-3">
                                <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                    {{ $members->count() }} {{ __('messages.structure_member_count') }}
                                </span>
                            </div>
                        </div>

                        {{-- Department Head Profile --}}
                        @if($head)
                        <div class="mt-6 p-5 rounded-2xl bg-amber-50/50 border border-amber-200 flex flex-col sm:flex-row items-center sm:items-start gap-5">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl overflow-hidden shrink-0 border-2 border-amber-400 p-0.5 bg-white shadow-sm">
                                @if($head->photo_url)
                                    <img src="{{ Storage::url($head->photo_url) }}" alt="{{ $head->name_lo }}"
                                         class="w-full h-full object-cover rounded-xl" />
                                @else
                                    <div class="w-full h-full rounded-xl bg-amber-100 flex items-center justify-center text-amber-700">
                                        <span class="material-symbols-outlined text-3xl">person</span>
                                    </div>
                                @endif
                            </div>

                            <div class="flex-1 text-center sm:text-left">
                                <div class="inline-block px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase bg-amber-200/60 text-amber-900 mb-1">
                                    {{ __('messages.structure_department_head') }}
                                </div>
                                <h4 class="text-base sm:text-lg font-bold text-on-surface">
                                    {{ $head->title_lo ? $head->title_lo . ' ' : '' }}{{ $head->name_lo }}
                                </h4>
                                <p class="text-xs text-amber-800 font-semibold mt-0.5">
                                    {{ $head->position_lo }}
                                </p>

                                @if($head->current_temple_lo || $head->phone)
                                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-4 text-xs text-on-surface-variant mt-2.5">
                                    @if($head->current_temple_lo)
                                    <span class="inline-flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm text-amber-600">temple_buddhist</span>
                                        <span>{{ $head->current_temple_lo }}</span>
                                    </span>
                                    @endif
                                    @if($head->phone)
                                    <span class="inline-flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm text-amber-600">phone</span>
                                        <span>{{ $head->phone }}</span>
                                    </span>
                                    @endif
                                </div>
                                @endif

                                <div class="mt-3.5">
                                    <a href="{{ route('frontend.personnel.show', $head->id) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-primary bg-white border border-primary/30 hover:bg-primary hover:text-white transition-all shadow-sm">
                                        <span>{{ __('messages.structure_view_personnel') }}</span>
                                        <span class="material-symbols-outlined text-xs">arrow_forward</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- Committee Members Grid --}}
                        @if($members->count() > 0)
                        <div class="mt-6">
                            <h5 class="text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-4 flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm text-amber-600">group</span>
                                <span>{{ __('messages.structure_department_members') }} ({{ $members->count() }})</span>
                            </h5>

                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach($members as $m)
                                <a href="{{ route('frontend.personnel.show', $m->id) }}"
                                   class="dept-pill flex items-center gap-3 p-3 rounded-xl bg-surface-container-low/60 border border-outline-variant/50 group transition-all">
                                    <div class="w-11 h-11 rounded-lg overflow-hidden shrink-0 border border-outline-variant/60 bg-white">
                                        @if($m->photo_url)
                                            <img src="{{ Storage::url($m->photo_url) }}" alt="{{ $m->name_lo }}"
                                                 class="w-full h-full object-cover rounded-md" />
                                        @else
                                            <div class="w-full h-full rounded-md bg-primary/5 flex items-center justify-center text-primary">
                                                <span class="material-symbols-outlined text-base">person</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-bold text-on-surface group-hover:text-primary transition-colors truncate">
                                            {{ $m->name_lo }}
                                        </p>
                                        <p class="text-[10px] text-on-surface-variant truncate mt-0.5">
                                            {{ $m->position_lo ?: ($m->affiliation_level === 'central' ? 'ຂັ້ນສູນກາງ' : 'ຂັ້ນແຂວງ') }}
                                        </p>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endif

                    </div>
                @endforeach
            </div>

        </div>


        {{-- ════════════════════════════════════════════════
             TAB 3: LOCAL PROVINCIAL STRUCTURE
        ════════════════════════════════════════════════ --}}
        <div x-show="currentTab === 'provinces'" x-transition:enter="transition ease-out duration-300" style="display:none;">

            <div class="rounded-3xl p-6 lg:p-8 bg-white border border-outline-variant/60 shadow-sm mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-outline-variant/50">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200 mb-2">
                            <span class="material-symbols-outlined text-sm text-amber-600">map</span>
                            <span>{{ __('messages.structure_provincial_level') }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-on-surface">ໂຄງສ້າງການຈັດຕັ້ງ ຂັ້ນແຂວງ ໃນ 18 ແຂວງທົ່ວປະເທດ</h3>
                        <p class="text-xs text-on-surface-variant mt-1">
                            ຂໍ້ມູນສະຖິຕິບຸກຄະລາກອນ ແລະ ຄະນະກຳມາທິການສາທາຣະນູປະການ ປະຈຳແຕ່ລະແຂວງ
                        </p>
                    </div>

                    <a href="{{ route('frontend.personnel') }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-primary bg-primary/10 hover:bg-primary hover:text-white transition-all self-start sm:self-auto">
                        <span>{{ __('messages.structure_view_all_people') }}</span>
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>

                {{-- Provinces Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-8">
                    @forelse($provincialStats as $p)
                    <div class="p-4 rounded-2xl bg-amber-50/40 border border-amber-200/80 hover:border-amber-400 transition-colors flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-amber-100/70 text-amber-800 flex items-center justify-center font-bold text-xs shrink-0">
                                <span class="material-symbols-outlined text-base">location_on</span>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-on-surface leading-tight">{{ $p->affiliation_province }}</h4>
                                <span class="text-[10px] text-on-surface-variant">ກສປ ແຂວງ</span>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-white text-amber-800 border border-amber-200/80 shadow-2xs">
                            {{ $p->count }}
                        </span>
                    </div>
                    @empty
                    <div class="col-span-full text-center py-8 text-xs text-on-surface-variant">
                        ບໍ່ພົບຂໍ້ມູນບຸກຄະລາກອນຂັ້ນແຂວງ
                    </div>
                    @endforelse
                </div>
            </div>

        </div>


        {{-- ════════════════════════════════════════════════
             RELATED PILLARS FOOTER
        ════════════════════════════════════════════════ --}}
        <div class="mt-16 pt-12 border-t border-outline-variant/60">
            <div class="flex items-center gap-4 mb-8">
                <div class="tree-line-h flex-1"></div>
                <span class="text-xs font-bold uppercase tracking-widest text-amber-800">
                    {{ __('messages.structure_related_docs') }}
                </span>
                <div class="tree-line-h flex-1"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                {{-- Duties --}}
                <a href="{{ route('frontend.duties') }}"
                   class="chart-card p-6 rounded-2xl bg-white border border-outline-variant/60 shadow-sm group">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined">assignment_ind</span>
                    </div>
                    <h4 class="text-base font-bold text-on-surface group-hover:text-primary transition-colors">
                        {{ __('messages.about_duties_title') }}
                    </h4>
                    <p class="text-xs text-on-surface-variant mt-1.5 leading-relaxed">
                        {{ __('messages.about_duties_body') }}
                    </p>
                    <div class="mt-4 flex items-center gap-1 text-xs font-bold text-primary">
                        <span>{{ __('messages.about_duties_link') }}</span>
                        <span class="material-symbols-outlined text-xs group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </div>
                </a>

                {{-- Guide / Regulations --}}
                <a href="{{ route('frontend.guide') }}"
                   class="chart-card p-6 rounded-2xl bg-white border border-outline-variant/60 shadow-sm group">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-800 flex items-center justify-center mb-4 border border-amber-200">
                        <span class="material-symbols-outlined">menu_book</span>
                    </div>
                    <h4 class="text-base font-bold text-on-surface group-hover:text-primary transition-colors">
                        {{ __('messages.about_manual_title') }}
                    </h4>
                    <p class="text-xs text-on-surface-variant mt-1.5 leading-relaxed">
                        {{ __('messages.about_manual_body') }}
                    </p>
                    <div class="mt-4 flex items-center gap-1 text-xs font-bold text-primary">
                        <span>{{ __('messages.about_manual_link') }}</span>
                        <span class="material-symbols-outlined text-xs group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </div>
                </a>

                {{-- History --}}
                <a href="{{ route('frontend.history') }}"
                   class="chart-card p-6 rounded-2xl bg-white border border-outline-variant/60 shadow-sm group">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-800 flex items-center justify-center mb-4 border border-emerald-200">
                        <span class="material-symbols-outlined">history_edu</span>
                    </div>
                    <h4 class="text-base font-bold text-on-surface group-hover:text-primary transition-colors">
                        {{ __('messages.about_history_title') }}
                    </h4>
                    <p class="text-xs text-on-surface-variant mt-1.5 leading-relaxed">
                        {{ __('messages.about_history_body') }}
                    </p>
                    <div class="mt-4 flex items-center gap-1 text-xs font-bold text-primary">
                        <span>{{ __('messages.about_history_link') }}</span>
                        <span class="material-symbols-outlined text-xs group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </div>
                </a>
            </div>
        </div>

    </div>
</div>

@endsection
