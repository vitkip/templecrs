@extends('frontend.layout')

@section('content')

<style>
.duty-card { transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease; }
.duty-card:hover { transform: translateY(-2px); box-shadow: 0 8px 32px rgba(14,21,14,0.13) !important; }
@media (prefers-reduced-motion: reduce) {
    .duty-card, .duty-card:hover { transform: none; transition: none; }
}
</style>

{{-- ════════════ HERO ════════════ --}}
<div class="relative overflow-hidden"
     style="background: linear-gradient(160deg, #0A1208 0%, #1C2C12 38%, #2C1A06 72%, #180E04 100%); min-height: 320px;">

    <div class="absolute inset-0 pointer-events-none" style="opacity:0.04;" aria-hidden="true">
        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="lpd" x="0" y="0" width="80" height="80" patternUnits="userSpaceOnUse">
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
            <rect width="100%" height="100%" fill="url(#lpd)"/>
        </svg>
    </div>
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true"
         style="background: radial-gradient(ellipse at 50% 90%, rgba(200,146,26,0.1) 0%, transparent 60%);"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24 flex flex-col items-center text-center">
        <div class="mb-5" aria-hidden="true">
            <svg width="140" height="4" viewBox="0 0 140 4" fill="none">
                <line x1="0" y1="2" x2="58" y2="2" stroke="#C8921A" stroke-width="0.5" stroke-opacity="0.45"/>
                <circle cx="70" cy="2" r="2" fill="#C8921A" fill-opacity="0.7"/>
                <line x1="82" y1="2" x2="140" y2="2" stroke="#C8921A" stroke-width="0.5" stroke-opacity="0.45"/>
            </svg>
        </div>

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 mb-6" style="color:rgba(200,146,26,0.6); font-size:12px; font-weight:600; letter-spacing:0.12em;">
            <a href="{{ route('frontend.about') }}"
               style="color:rgba(200,146,26,0.6); text-decoration:none;"
               onmouseover="this.style.color='#C8921A'" onmouseout="this.style.color='rgba(200,146,26,0.6)'">
                {{ __('messages.about_nav') }}
            </a>
            <span class="material-symbols-outlined" style="font-size:14px;">chevron_right</span>
            <span style="color:#C8921A;">{{ __('messages.duties_breadcrumb_label') }}</span>
        </div>

        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full mb-6"
             style="background:rgba(200,146,26,0.09); border:1px solid rgba(200,146,26,0.22); color:#C8921A; font-size:11px; font-weight:700; letter-spacing:0.2em; text-transform:uppercase;">
            <span class="material-symbols-outlined" style="font-size:13px;">assignment_ind</span>
            {{ __('messages.duties_badge') }}
        </div>

        <h1 class="font-bold text-white mb-4"
            style="font-size:clamp(1.6rem,4vw,2.8rem); line-height:1.25; text-shadow:0 2px 32px rgba(0,0,0,0.5);">
            {{ __('messages.duties_hero_title') }}<br>
            <span style="color:#E8B84B;">{{ __('messages.duties_hero_subtitle') }}</span>
        </h1>
        <p style="color:rgba(255,255,255,0.45); font-size:0.95rem; max-width:480px; line-height:1.8;">
            {{ __('messages.duties_hero_desc') }}
        </p>

        <div class="mt-7" aria-hidden="true">
            <svg width="140" height="4" viewBox="0 0 140 4" fill="none">
                <line x1="0" y1="2" x2="58" y2="2" stroke="#C8921A" stroke-width="0.5" stroke-opacity="0.45"/>
                <circle cx="70" cy="2" r="2" fill="#C8921A" fill-opacity="0.7"/>
                <line x1="82" y1="2" x2="140" y2="2" stroke="#C8921A" stroke-width="0.5" stroke-opacity="0.45"/>
            </svg>
        </div>
    </div>
</div>

{{-- Hero → cream wave --}}
<div aria-hidden="true" style="background:#0E150E; line-height:0;">
    <svg viewBox="0 0 1440 52" xmlns="http://www.w3.org/2000/svg"
         style="width:100%; height:52px; display:block;" preserveAspectRatio="none">
        <path fill="#FFFBEB" d="M0,40 Q720,10 1440,40 L1440,52 L0,52 Z"/>
    </svg>
</div>


{{-- ════════════ INTRO CARDS ════════════ --}}
<div style="background:#FFFBEB;">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-18">

        <div class="flex items-center gap-4 mb-10">
            <div style="height:1px; flex:1; background:linear-gradient(to right, rgba(200,146,26,0.3), transparent);"></div>
            <span class="material-symbols-outlined" style="color:#C8921A; font-size:18px;">spa</span>
            <div style="height:1px; flex:1; background:linear-gradient(to left, rgba(200,146,26,0.3), transparent);"></div>
        </div>

        <div class="space-y-5">
            {{-- Intro 1 --}}
            <div class="rounded-2xl p-6 lg:p-7"
                 style="background:#F7EFD8; border:1px solid rgba(200,146,26,0.18); box-shadow:0 2px 12px rgba(14,21,14,0.05);">
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-9 h-9 rounded-lg flex items-center justify-center"
                         style="background:rgba(200,146,26,0.1); border:1px solid rgba(200,146,26,0.2);">
                        <span class="material-symbols-outlined" style="font-size:18px; color:#C8921A;">info</span>
                    </div>
                    <p style="color:rgba(14,21,14,0.75); font-size:0.96rem; line-height:2; text-align:justify;">
                        {{ __('messages.duties_intro1') }}
                    </p>
                </div>
            </div>

            {{-- Intro 2 --}}
            <div class="rounded-2xl p-6 lg:p-7"
                 style="background:#F7EFD8; border:1px solid rgba(200,146,26,0.18); box-shadow:0 2px 12px rgba(14,21,14,0.05);">
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-9 h-9 rounded-lg flex items-center justify-center"
                         style="background:rgba(200,146,26,0.1); border:1px solid rgba(200,146,26,0.2);">
                        <span class="material-symbols-outlined" style="font-size:18px; color:#C8921A;">badge</span>
                    </div>
                    <p style="color:rgba(14,21,14,0.75); font-size:0.96rem; line-height:2; text-align:justify;">
                        {{ __('messages.duties_intro2_before') }}
                        <strong style="color:#0E150E;">"ກສປ"</strong>,
                        {{ __('messages.duties_intro2_after') }} <strong style="color:#0E150E;">"ກສປ"</strong>.
                    </p>
                </div>
            </div>

            {{-- Intro 3 --}}
            <div class="rounded-2xl p-6 lg:p-7"
                 style="background:#F7EFD8; border:1px solid rgba(200,146,26,0.18); box-shadow:0 2px 12px rgba(14,21,14,0.05);">
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-9 h-9 rounded-lg flex items-center justify-center"
                         style="background:rgba(200,146,26,0.1); border:1px solid rgba(200,146,26,0.2);">
                        <span class="material-symbols-outlined" style="font-size:18px; color:#C8921A;">account_tree</span>
                    </div>
                    <p style="color:rgba(14,21,14,0.75); font-size:0.96rem; line-height:2; text-align:justify;">
                        {{ __('messages.duties_intro3') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- ════════════ DUTIES (dark section) ════════════ --}}

{{-- Cream → dark wave --}}
<div aria-hidden="true" style="background:#FFFBEB; line-height:0;">
    <svg viewBox="0 0 1440 52" xmlns="http://www.w3.org/2000/svg"
         style="width:100%; height:52px; display:block;" preserveAspectRatio="none">
        <path fill="#0E150E" d="M0,12 Q720,42 1440,12 L1440,52 L0,52 Z"/>
    </svg>
</div>

<div style="background:#0E150E; position:relative; overflow:hidden;">
    <div class="absolute inset-0 pointer-events-none" style="opacity:0.035;" aria-hidden="true">
        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="lpd2" x="0" y="0" width="80" height="80" patternUnits="userSpaceOnUse">
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
            <rect width="100%" height="100%" fill="url(#lpd2)"/>
        </svg>
    </div>
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true"
         style="background:radial-gradient(ellipse at 50% 20%, rgba(200,146,26,0.07) 0%, transparent 65%);"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-20">

        {{-- Section label --}}
        <div class="text-center mb-12">
            <div style="width:48px; height:2px; background:linear-gradient(to right,#B87A14,#E8B84B,#B87A14); margin:0 auto 16px;"></div>
            <h2 class="font-bold" style="font-size:clamp(1.2rem,2.5vw,1.6rem); color:#F5E6B8; letter-spacing:0.01em;">
                {{ __('messages.duties_section_title') }}
            </h2>
            <div style="width:48px; height:2px; background:linear-gradient(to right,#B87A14,#E8B84B,#B87A14); margin:12px auto 0;"></div>
        </div>

        @php
            $duties = [
                ['no'=>'1','icon'=>'manage_accounts','title_key'=>'duties_d1_title','text_key'=>'duties_d1_text'],
                ['no'=>'2','icon'=>'gavel',          'title_key'=>'duties_d2_title','text_key'=>'duties_d2_text'],
                ['no'=>'3','icon'=>'account_balance','title_key'=>'duties_d3_title','text_key'=>'duties_d3_text'],
                ['no'=>'4','icon'=>'foundation',     'title_key'=>'duties_d4_title','text_key'=>'duties_d4_text'],
                ['no'=>'5','icon'=>'collections_bookmark','title_key'=>'duties_d5_title','text_key'=>'duties_d5_text'],
                ['no'=>'6','icon'=>'eco',            'title_key'=>'duties_d6_title','text_key'=>'duties_d6_text'],
            ];
        @endphp

        <div class="space-y-4">
            @foreach($duties as $duty)
            <div class="duty-card flex gap-4 rounded-2xl p-5 lg:p-6"
                 style="background:rgba(255,255,255,0.03); border:1px solid rgba(200,146,26,0.12);">

                {{-- Number badge --}}
                <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center font-bold"
                     style="background:rgba(200,146,26,0.12); border:1.5px solid rgba(200,146,26,0.3); color:#E8B84B; font-size:0.95rem; min-width:2.5rem; min-height:2.5rem;">
                    {{ $duty['no'] }}
                </div>

                <div class="flex flex-col gap-1 min-w-0">
                    {{-- Title row --}}
                    <div class="flex items-center gap-2 mb-1">
                        <span class="material-symbols-outlined flex-shrink-0"
                              style="font-size:17px; color:rgba(200,146,26,0.55);">{{ $duty['icon'] }}</span>
                        <span class="font-semibold" style="color:#E8B84B; font-size:0.88rem; letter-spacing:0.03em;">
                            {{ __('messages.'.$duty['title_key']) }}
                        </span>
                    </div>
                    {{-- Body --}}
                    <p style="color:rgba(245,230,184,0.78); font-size:0.95rem; line-height:1.9; text-align:justify;">
                        {{ __('messages.'.$duty['text_key']) }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Back link --}}
        <div class="mt-12 text-center">
            <a href="{{ route('frontend.about') }}"
               class="inline-flex items-center gap-2 px-6 py-3 rounded-full font-semibold text-sm transition-all"
               style="background:rgba(200,146,26,0.1); border:1px solid rgba(200,146,26,0.25); color:#E8B84B;"
               onmouseover="this.style.background='rgba(200,146,26,0.18)'"
               onmouseout="this.style.background='rgba(200,146,26,0.1)'">
                <span class="material-symbols-outlined" style="font-size:17px;">arrow_back</span>
                {{ __('messages.duties_back') }}
            </a>
        </div>

    </div>

    <div style="height:1px; background:linear-gradient(to right, transparent, rgba(200,146,26,0.35), transparent);"></div>
</div>

{{-- Dark → cream wave --}}
<div aria-hidden="true" style="background:#0E150E; line-height:0;">
    <svg viewBox="0 0 1440 52" xmlns="http://www.w3.org/2000/svg"
         style="width:100%; height:52px; display:block;" preserveAspectRatio="none">
        <path fill="#FFFBEB" d="M0,40 Q720,10 1440,40 L1440,52 L0,52 Z"/>
    </svg>
</div>

@endsection
