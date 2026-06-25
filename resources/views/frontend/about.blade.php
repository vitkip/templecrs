@extends('frontend.layout')

@section('content')

<style>
.pillar-card { transition: transform 0.22s ease, box-shadow 0.22s ease; }
.pillar-card:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(14,21,14,0.13), 0 2px 8px rgba(14,21,14,0.07) !important; }
@media (prefers-reduced-motion: reduce) {
    .pillar-card, .pillar-card:hover { transform: none; transition: none; }
}
</style>

{{-- ════════════════════════════════════════════════════
HERO
════════════════════════════════════════════════════ --}}
<div class="relative overflow-hidden"
     style="background: linear-gradient(160deg, #0A1208 0%, #1C2C12 38%, #2C1A06 72%, #180E04 100%); min-height: 360px;">

    {{-- Lotus background pattern --}}
    <div class="absolute inset-0 pointer-events-none" style="opacity:0.04;" aria-hidden="true">
        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="lp1" x="0" y="0" width="80" height="80" patternUnits="userSpaceOnUse">
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
            <rect width="100%" height="100%" fill="url(#lp1)"/>
        </svg>
    </div>

    {{-- Gold radial glow --}}
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true"
         style="background: radial-gradient(ellipse at 50% 90%, rgba(200,146,26,0.1) 0%, transparent 60%);"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28 flex flex-col items-center text-center">

        {{-- Decorative lines --}}
        <div class="mb-6" aria-hidden="true">
            <svg width="140" height="4" viewBox="0 0 140 4" fill="none">
                <line x1="0" y1="2" x2="58" y2="2" stroke="#C8921A" stroke-width="0.5" stroke-opacity="0.45"/>
                <circle cx="70" cy="2" r="2" fill="#C8921A" fill-opacity="0.7"/>
                <line x1="82" y1="2" x2="140" y2="2" stroke="#C8921A" stroke-width="0.5" stroke-opacity="0.45"/>
            </svg>
        </div>

        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full mb-6"
             style="background:rgba(200,146,26,0.09); border:1px solid rgba(200,146,26,0.22); color:#C8921A; font-size:11px; font-weight:700; letter-spacing:0.2em; text-transform:uppercase;">
            <span class="material-symbols-outlined" style="font-size:13px;">spa</span>
            {{ __('messages.about_nav') }}
        </div>

        <h1 class="font-bold text-white mb-5"
            style="font-size:clamp(2rem,5vw,3.5rem); line-height:1.15; text-shadow:0 2px 32px rgba(0,0,0,0.5); letter-spacing:-0.01em;">
            {{ __('messages.about_title') }}
        </h1>
        <p style="color:rgba(255,255,255,0.48); font-size:1.05rem; max-width:520px; line-height:1.8;">
            {{ __('messages.about_subtitle') }}
        </p>

        <div class="mt-8" aria-hidden="true">
            <svg width="140" height="4" viewBox="0 0 140 4" fill="none">
                <line x1="0" y1="2" x2="58" y2="2" stroke="#C8921A" stroke-width="0.5" stroke-opacity="0.45"/>
                <circle cx="70" cy="2" r="2" fill="#C8921A" fill-opacity="0.7"/>
                <line x1="82" y1="2" x2="140" y2="2" stroke="#C8921A" stroke-width="0.5" stroke-opacity="0.45"/>
            </svg>
        </div>
    </div>
</div>


{{-- ════════════════════════════════════════════════════
STATS BAND — dark lacquer with scroll-triggered counters
════════════════════════════════════════════════════ --}}
<div style="background:#0E150E; position:relative; overflow:hidden;">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true"
         style="background:radial-gradient(ellipse at 50% 50%, rgba(200,146,26,0.04) 0%, transparent 70%);"></div>

    <div style="height:1px; background:linear-gradient(to right, transparent, rgba(200,146,26,0.35), transparent);"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-16">
        @php
            $stats = [
                ['icon' => 'group',       'value' => $statsPersonnelCount,  'label' => __('messages.personnel')],
                ['icon' => 'description', 'value' => $statsDocumentsCount,  'label' => __('messages.documents_nav')],
                ['icon' => 'newspaper',   'value' => $statsNewsCount,        'label' => __('messages.news')],
            ];
        @endphp

        <div class="grid grid-cols-3">
            @foreach($stats as $stat)
            <div class="py-6 text-center px-4 lg:px-10"
                 @if(!$loop->first) style="border-left:1px solid rgba(200,146,26,0.12);" @endif
                 x-data="{
                     count: 0,
                     target: {{ (int) $stat['value'] }},
                     started: false,
                     init() {
                         const obs = new IntersectionObserver(entries => {
                             if (entries[0].isIntersecting && !this.started) {
                                 this.started = true;
                                 const dur = 1600, t0 = performance.now();
                                 const tick = now => {
                                     const p = Math.min((now - t0) / dur, 1);
                                     this.count = Math.round(this.target * (1 - Math.pow(1 - p, 3)));
                                     if (p < 1) requestAnimationFrame(tick);
                                 };
                                 requestAnimationFrame(tick);
                                 obs.disconnect();
                             }
                         }, { threshold: 0.3 });
                         obs.observe(this.$el);
                     }
                 }">
                <span class="material-symbols-outlined block mb-3"
                      style="color:rgba(200,146,26,0.38); font-size:18px;">{{ $stat['icon'] }}</span>
                <div class="font-bold tabular-nums"
                     style="font-size:clamp(2.4rem,5vw,3.6rem); color:#C8921A; line-height:1; letter-spacing:-0.04em;"
                     x-text="count.toLocaleString()">0</div>
                <div class="mt-2.5 font-medium uppercase"
                     style="color:rgba(255,255,255,0.3); font-size:10px; letter-spacing:0.2em;">{{ $stat['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <div style="height:1px; background:linear-gradient(to right, transparent, rgba(200,146,26,0.35), transparent);"></div>
</div>

{{-- Stats → cream wave --}}
<div aria-hidden="true" style="background:#0E150E; line-height:0;">
    <svg viewBox="0 0 1440 52" xmlns="http://www.w3.org/2000/svg"
         style="width:100%; height:52px; display:block;" preserveAspectRatio="none">
        <path fill="#FFFBEB" d="M0,40 Q720,10 1440,40 L1440,52 L0,52 Z"/>
    </svg>
</div>


{{-- ════════════════════════════════════════════════════
PILLARS — ປະຫວັດ / ຄູ່ມື / ສິດໜ້າທີ່
════════════════════════════════════════════════════ --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">

    <div class="flex items-center gap-4 mb-12">
        <div style="height:1px; flex:1; background:linear-gradient(to right, rgba(200,146,26,0.25), transparent);"></div>
        <span style="color:#C8921A; font-size:10px; font-weight:700; letter-spacing:0.22em; text-transform:uppercase;">
            {{ __('messages.about_nav') }}
        </span>
        <div style="height:1px; flex:1; background:linear-gradient(to left, rgba(200,146,26,0.25), transparent);"></div>
    </div>

    @php
        $pillars = [
            [
                'icon'  => 'history_edu',
                'title' => __('messages.about_history_title'),
                'body'  => __('messages.about_history_body'),
                'link'  => route('frontend.history'),
                'label' => __('messages.about_history_link'),
            ],
            [
                'icon'  => 'menu_book',
                'title' => __('messages.about_manual_title'),
                'body'  => __('messages.about_manual_body'),
                'link'  => route('frontend.guide'),
                'label' => __('messages.about_manual_link'),
            ],
            [
                'icon'  => 'assignment_ind',
                'title' => __('messages.about_duties_title'),
                'body'  => __('messages.about_duties_body'),
                'link'  => route('frontend.duties'),
                'label' => __('messages.about_duties_link'),
            ],
        ];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($pillars as $pillar)
        <div class="pillar-card relative flex flex-col rounded-2xl overflow-hidden"
             style="background:#F7EFD8; border:1px solid rgba(200,146,26,0.18); box-shadow:0 4px 24px rgba(14,21,14,0.07), 0 1px 3px rgba(14,21,14,0.05);">
            {{-- Gold top accent bar --}}
            <div style="height:3px; background:linear-gradient(to right, #B87A14, #E8B84B, #B87A14);"></div>
            <div class="flex flex-col flex-1 p-7">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center mb-5 flex-shrink-0"
                     style="background:rgba(14,21,14,0.055); border:1px solid rgba(200,146,26,0.14);">
                    <span class="material-symbols-outlined" style="font-size:21px; color:#0E150E;">{{ $pillar['icon'] }}</span>
                </div>
                <h3 class="font-bold text-lg mb-3" style="color:#0E150E; line-height:1.3;">{{ $pillar['title'] }}</h3>
                <p class="text-sm leading-relaxed flex-1" style="color:rgba(14,21,14,0.58);">{{ $pillar['body'] }}</p>
                <a href="{{ $pillar['link'] }}"
                   class="mt-6 inline-flex items-center gap-2 text-sm font-semibold group"
                   style="color:#C8921A;">
                    {{ $pillar['label'] }}
                    <span class="material-symbols-outlined transition-transform group-hover:translate-x-1"
                          style="font-size:16px;">arrow_forward</span>
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>


{{-- ════════════════════════════════════════════════════
DONATION — 4 currency accounts
════════════════════════════════════════════════════ --}}
@php
    $hasAnyDonation = collect($donationAccounts)->contains(fn($a) => $a['bank_name'] || $a['account_no']);
@endphp

@if($hasAnyDonation)

{{-- Cream → crimson wave --}}
<div aria-hidden="true" style="background:#FFFBEB; line-height:0;">
    <svg viewBox="0 0 1440 52" xmlns="http://www.w3.org/2000/svg"
         style="width:100%; height:52px; display:block;" preserveAspectRatio="none">
        <path fill="#4A1010" d="M0,12 Q720,42 1440,12 L1440,52 L0,52 Z"/>
    </svg>
</div>

<div style="background:#4A1010; position:relative; overflow:hidden;"
     x-data="{ copied: null }">

    {{-- Lotus overlay --}}
    <div class="absolute inset-0 pointer-events-none" style="opacity:0.035;" aria-hidden="true">
        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="lp2" x="0" y="0" width="80" height="80" patternUnits="userSpaceOnUse">
                    <g fill="none" stroke="#E8B84B" stroke-width="0.65">
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
            <rect width="100%" height="100%" fill="url(#lp2)"/>
        </svg>
    </div>

    {{-- Gold radial glow --}}
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true"
         style="background:radial-gradient(ellipse at 50% 50%, rgba(232,184,75,0.07) 0%, transparent 70%);"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-20">

        {{-- Heading --}}
        <div class="text-center mb-12">
            <span class="material-symbols-outlined block mx-auto mb-4"
                  style="font-size:38px; color:#E8B84B; filter:drop-shadow(0 0 14px rgba(232,184,75,0.35));">volunteer_activism</span>
            <h2 class="font-bold mb-3"
                style="font-size:clamp(1.5rem,3vw,2.1rem); color:#F5E6B8; line-height:1.2;">
                {{ __('messages.donation_section') }}
            </h2>
            <p style="color:rgba(245,230,184,0.5); font-size:0.9rem; max-width:460px; margin:0 auto; line-height:1.75;">
                {{ __('messages.donation_subtitle') }}
            </p>
        </div>

        {{-- 4 Currency Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
            @foreach($donationAccounts as $acc)
            @if($acc['bank_name'] || $acc['account_no'])
            <div class="rounded-2xl overflow-hidden flex flex-col"
                 style="background:rgba(0,0,0,0.28); border:1px solid rgba(232,184,75,0.16);">

                {{-- Card Header --}}
                <div class="px-5 pt-5 pb-4 flex items-center gap-3"
                     style="border-bottom:1px solid rgba(232,184,75,0.1);">
                    <span style="font-size:1.8rem; line-height:1;">{{ $acc['flag'] }}</span>
                    <div>
                        <div class="font-bold" style="color:#F5E6B8; font-size:1.05rem; line-height:1.2;">{{ $acc['label_lo'] }}</div>
                        <div style="color:rgba(232,184,75,0.45); font-size:10px; font-weight:600; letter-spacing:0.12em;">{{ $acc['label_en'] }}</div>
                    </div>
                </div>

                {{-- QR Code --}}
                @if($acc['qr_url'])
                <div class="flex justify-center px-5 pt-5">
                    <div class="rounded-xl overflow-hidden"
                         style="background:#fff; padding:8px; width:140px; height:140px; display:flex; align-items:center; justify-content:center; box-shadow:0 2px 16px rgba(0,0,0,0.4);">
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($acc['qr_url']) }}"
                             alt="QR {{ $acc['label_lo'] }}"
                             style="width:100%; height:100%; object-fit:contain;" />
                    </div>
                </div>
                @endif

                {{-- Account Info --}}
                <div class="px-5 py-4 flex flex-col gap-3 flex-1">
                    @if($acc['bank_name'])
                    <div>
                        <div style="color:rgba(232,184,75,0.45); font-size:9px; font-weight:700; letter-spacing:0.18em; text-transform:uppercase; margin-bottom:3px;">
                            {{ __('messages.donate_bank_name') }}
                        </div>
                        <div style="color:#F5E6B8; font-size:0.92rem; font-weight:600;">{{ $acc['bank_name'] }}</div>
                    </div>
                    @endif

                    @if($acc['account_name'])
                    <div>
                        <div style="color:rgba(232,184,75,0.45); font-size:9px; font-weight:700; letter-spacing:0.18em; text-transform:uppercase; margin-bottom:3px;">
                            {{ __('messages.donate_account_name') }}
                        </div>
                        <div style="color:#F5E6B8; font-size:0.92rem; font-weight:600;">{{ $acc['account_name'] }}</div>
                    </div>
                    @endif

                    @if($acc['account_no'])
                    @php $copyKey = 'no_' . $acc['key']; @endphp
                    <div class="relative mt-auto">
                        <div style="color:rgba(232,184,75,0.45); font-size:9px; font-weight:700; letter-spacing:0.18em; text-transform:uppercase; margin-bottom:3px;">
                            {{ __('messages.donate_account_no') }}
                        </div>
                        <button type="button"
                                class="w-full flex items-center justify-between gap-2 rounded-lg px-3 py-2.5 text-left transition-colors"
                                style="background:rgba(232,184,75,0.07); border:1px solid rgba(232,184,75,0.15); cursor:pointer;"
                                @click="navigator.clipboard.writeText('{{ $acc['account_no'] }}'); copied='{{ $copyKey }}'; setTimeout(()=>copied=null,2000);"
                                title="{{ __('messages.donate_copy_hint') }}">
                            <span class="font-bold tracking-wider tabular-nums" style="color:#E8B84B; font-size:0.95rem;">{{ $acc['account_no'] }}</span>
                            <span class="material-symbols-outlined flex-shrink-0 transition-colors"
                                  style="font-size:16px;"
                                  :style="copied === '{{ $copyKey }}' ? 'color:#E8B84B' : 'color:rgba(232,184,75,0.4)'"
                                  x-text="copied === '{{ $copyKey }}' ? 'check_circle' : 'content_copy'"></span>
                        </button>
                        <div x-show="copied === '{{ $copyKey }}'"
                             x-transition.opacity
                             class="absolute -top-7 left-1/2 -translate-x-1/2 text-xs rounded-full px-3 py-1 whitespace-nowrap pointer-events-none"
                             style="background:#E8B84B; color:#2C1A04; font-weight:600; display:none;">
                            {{ __('messages.donate_copied') }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
            @endforeach
        </div>

    </div>
</div>

{{-- Crimson → cream wave --}}
<div aria-hidden="true" style="background:#4A1010; line-height:0;">
    <svg viewBox="0 0 1440 52" xmlns="http://www.w3.org/2000/svg"
         style="width:100%; height:52px; display:block;" preserveAspectRatio="none">
        <path fill="#FFFBEB" d="M0,40 Q720,10 1440,40 L1440,52 L0,52 Z"/>
    </svg>
</div>

@endif


{{-- ════════════════════════════════════════════════════
CONTACT
════════════════════════════════════════════════════ --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">

    <div class="flex items-center gap-4 mb-12">
        <div style="height:1px; flex:1; background:linear-gradient(to right, rgba(200,146,26,0.25), transparent);"></div>
        <span style="color:#C8921A; font-size:10px; font-weight:700; letter-spacing:0.22em; text-transform:uppercase;">
            {{ __('messages.contact_us') }}
        </span>
        <div style="height:1px; flex:1; background:linear-gradient(to left, rgba(200,146,26,0.25), transparent);"></div>
    </div>

    @php
        $contactItems = [
            ['icon' => 'location_on', 'label' => __('messages.address'), 'value' => \App\Models\Setting::get('org_address', 'ນະຄອນຫຼວງວຽງຈັນ, ສ.ປ.ປ ລາວ')],
            ['icon' => 'phone',       'label' => __('messages.phone'),   'value' => \App\Models\Setting::get('org_phone', '—')],
            ['icon' => 'mail',        'label' => __('messages.email'),   'value' => \App\Models\Setting::get('org_email', '—')],
        ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        @foreach($contactItems as $item)
        <div class="flex items-start gap-4 rounded-2xl p-6"
             style="background:#F7EFD8; border:1px solid rgba(200,146,26,0.15); box-shadow:0 2px 12px rgba(14,21,14,0.05);">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0"
                 style="background:rgba(14,21,14,0.055); border:1px solid rgba(200,146,26,0.14);">
                <span class="material-symbols-outlined" style="font-size:20px; color:#C8921A;">{{ $item['icon'] }}</span>
            </div>
            <div class="min-w-0">
                <div style="color:rgba(14,21,14,0.38); font-size:10px; font-weight:700; letter-spacing:0.18em; text-transform:uppercase; margin-bottom:6px;">
                    {{ $item['label'] }}
                </div>
                <div class="text-sm font-medium leading-relaxed" style="color:#0E150E; word-break:break-word;">
                    {{ $item['value'] ?: '—' }}
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection
