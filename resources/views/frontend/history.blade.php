@extends('frontend.layout')

@section('content')

<style>
.timeline-dot { transition: transform 0.2s ease; }
.timeline-card:hover .timeline-dot { transform: scale(1.25); }
.timeline-card { transition: box-shadow 0.2s ease; }
.timeline-card:hover { box-shadow: 0 6px 28px rgba(14,21,14,0.11) !important; }
@media (prefers-reduced-motion: reduce) { .timeline-dot, .timeline-card { transition: none; } }
</style>

{{-- ════════════ HERO ════════════ --}}
<div class="relative overflow-hidden"
     style="background: linear-gradient(160deg, #0A1208 0%, #1C2C12 38%, #2C1A06 72%, #180E04 100%); min-height: 300px;">

    <div class="absolute inset-0 pointer-events-none" style="opacity:0.04;" aria-hidden="true">
        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="lph" x="0" y="0" width="80" height="80" patternUnits="userSpaceOnUse">
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
            <rect width="100%" height="100%" fill="url(#lph)"/>
        </svg>
    </div>
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true"
         style="background: radial-gradient(ellipse at 50% 90%, rgba(200,146,26,0.1) 0%, transparent 60%);"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-22 flex flex-col items-center text-center">
        <div class="mb-5" aria-hidden="true">
            <svg width="140" height="4" viewBox="0 0 140 4" fill="none">
                <line x1="0" y1="2" x2="58" y2="2" stroke="#C8921A" stroke-width="0.5" stroke-opacity="0.45"/>
                <circle cx="70" cy="2" r="2" fill="#C8921A" fill-opacity="0.7"/>
                <line x1="82" y1="2" x2="140" y2="2" stroke="#C8921A" stroke-width="0.5" stroke-opacity="0.45"/>
            </svg>
        </div>

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 mb-6" style="color:rgba(200,146,26,0.6); font-size:12px; font-weight:600; letter-spacing:0.1em;">
            <a href="{{ route('frontend.about') }}" style="color:rgba(200,146,26,0.6); text-decoration:none;"
               onmouseover="this.style.color='#C8921A'" onmouseout="this.style.color='rgba(200,146,26,0.6)'">{{ __('messages.about_nav') }}</a>
            <span class="material-symbols-outlined" style="font-size:14px;">chevron_right</span>
            <span style="color:#C8921A;">{{ __('messages.history_breadcrumb_label') }}</span>
        </div>

        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full mb-6"
             style="background:rgba(200,146,26,0.09); border:1px solid rgba(200,146,26,0.22); color:#C8921A; font-size:11px; font-weight:700; letter-spacing:0.2em; text-transform:uppercase;">
            <span class="material-symbols-outlined" style="font-size:13px;">history_edu</span>
            {{ __('messages.history_badge') }}
        </div>

        <h1 class="font-bold text-white mb-4"
            style="font-size:clamp(1.6rem,4vw,2.8rem); line-height:1.25; text-shadow:0 2px 32px rgba(0,0,0,0.5);">
            {{ __('messages.history_hero_title') }}<br>
            <span style="color:#E8B84B;">{{ __('messages.history_hero_subtitle') }}</span>
        </h1>
        <p style="color:rgba(255,255,255,0.45); font-size:0.95rem; max-width:520px; line-height:1.8;">
            {{ __('messages.history_hero_desc') }}
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

{{-- Hero → cream --}}
<div aria-hidden="true" style="background:#0E150E; line-height:0;">
    <svg viewBox="0 0 1440 52" xmlns="http://www.w3.org/2000/svg"
         style="width:100%; height:52px; display:block;" preserveAspectRatio="none">
        <path fill="#FFFBEB" d="M0,40 Q720,10 1440,40 L1440,52 L0,52 Z"/>
    </svg>
</div>

{{-- ════════════ INTRO ════════════ --}}
<div style="background:#FFFBEB;">
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 pb-10">

    <div class="flex items-center gap-4 mb-10">
        <div style="height:1px; flex:1; background:linear-gradient(to right, rgba(200,146,26,0.3), transparent);"></div>
        <span class="material-symbols-outlined" style="color:#C8921A; font-size:18px;">spa</span>
        <div style="height:1px; flex:1; background:linear-gradient(to left, rgba(200,146,26,0.3), transparent);"></div>
    </div>

    <div class="rounded-2xl p-7 lg:p-9 mb-8"
         style="background:#F7EFD8; border:1px solid rgba(200,146,26,0.18); box-shadow:0 2px 12px rgba(14,21,14,0.05);">
        <div class="flex gap-5">
            <div class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center"
                 style="background:rgba(200,146,26,0.1); border:1px solid rgba(200,146,26,0.2);">
                <span class="material-symbols-outlined" style="font-size:20px; color:#C8921A;">auto_stories</span>
            </div>
            <div>
                <h2 class="font-bold mb-3" style="color:#0E150E; font-size:1.1rem;">{{ __('messages.history_foundation_title') }}</h2>
                <p style="color:rgba(14,21,14,0.72); font-size:0.96rem; line-height:2; text-align:justify;">
                    {{ __('messages.history_foundation_body') }}
                </p>
            </div>
        </div>
    </div>

    <div class="rounded-2xl p-7 lg:p-9"
         style="background:#F7EFD8; border:1px solid rgba(200,146,26,0.18); box-shadow:0 2px 12px rgba(14,21,14,0.05);">
        <div class="flex gap-5">
            <div class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center"
                 style="background:rgba(200,146,26,0.1); border:1px solid rgba(200,146,26,0.2);">
                <span class="material-symbols-outlined" style="font-size:20px; color:#C8921A;">public</span>
            </div>
            <div>
                <h2 class="font-bold mb-3" style="color:#0E150E; font-size:1.1rem;">{{ __('messages.history_role_title') }}</h2>
                <p style="color:rgba(14,21,14,0.72); font-size:0.96rem; line-height:2; text-align:justify;">
                    {{ __('messages.history_role_body') }}
                </p>
            </div>
        </div>
    </div>
</div>
</div>

{{-- ════════════ TIMELINE (dark) ════════════ --}}

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
                <pattern id="lph2" x="0" y="0" width="80" height="80" patternUnits="userSpaceOnUse">
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
            <rect width="100%" height="100%" fill="url(#lph2)"/>
        </svg>
    </div>
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true"
         style="background:radial-gradient(ellipse at 50% 20%, rgba(200,146,26,0.07) 0%, transparent 65%);"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-20">

        <div class="text-center mb-14">
            <div style="width:48px; height:2px; background:linear-gradient(to right,#B87A14,#E8B84B,#B87A14); margin:0 auto 14px;"></div>
            <h2 class="font-bold" style="font-size:clamp(1.1rem,2.5vw,1.5rem); color:#F5E6B8;">{{ __('messages.history_timeline_title') }}</h2>
            <div style="width:48px; height:2px; background:linear-gradient(to right,#B87A14,#E8B84B,#B87A14); margin:12px auto 0;"></div>
        </div>

        @php
            $timeline = [
                ['year_key'=>'history_e1_year','icon'=>'temple_buddhist','title_key'=>'history_e1_title','body_key'=>'history_e1_body'],
                ['year_key'=>'history_e2_year','icon'=>'flag',            'title_key'=>'history_e2_title','body_key'=>'history_e2_body'],
                ['year_key'=>'history_e3_year','icon'=>'public',          'title_key'=>'history_e3_title','body_key'=>'history_e3_body'],
                ['year_key'=>'history_e4_year','icon'=>'account_balance', 'title_key'=>'history_e4_title','body_key'=>'history_e4_body'],
                ['year_key'=>'history_e5_year','icon'=>'groups',          'title_key'=>'history_e5_title','body_key'=>'history_e5_body'],
                ['year_key'=>'history_e6_year','icon'=>'rocket_launch',   'title_key'=>'history_e6_title','body_key'=>'history_e6_body'],
            ];
        @endphp

        <div class="relative">
            {{-- Vertical line --}}
            <div class="absolute left-6 top-0 bottom-0 w-px hidden sm:block"
                 style="background:linear-gradient(to bottom, transparent, rgba(200,146,26,0.3) 10%, rgba(200,146,26,0.3) 90%, transparent);"></div>

            <div class="space-y-6">
                @foreach($timeline as $i => $event)
                <div class="timeline-card sm:pl-16 relative rounded-2xl p-6"
                     style="background:rgba(255,255,255,0.03); border:1px solid rgba(200,146,26,0.12);">

                    {{-- Dot on timeline --}}
                    <div class="timeline-dot absolute left-4 top-7 w-5 h-5 rounded-full hidden sm:flex items-center justify-center"
                         style="background:#0E150E; border:2px solid #C8921A; z-index:1;">
                        <div style="width:7px; height:7px; background:#E8B84B; border-radius:50%;"></div>
                    </div>

                    {{-- Year badge --}}
                    <div class="flex items-center gap-3 mb-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold"
                              style="background:rgba(200,146,26,0.12); border:1px solid rgba(200,146,26,0.25); color:#E8B84B; letter-spacing:0.05em;">
                            <span class="material-symbols-outlined" style="font-size:13px;">{{ $event['icon'] }}</span>
                            {{ __('messages.'.$event['year_key']) }}
                        </span>
                    </div>

                    <h3 class="font-bold mb-2" style="color:#F5E6B8; font-size:1rem;">{{ __('messages.'.$event['title_key']) }}</h3>
                    <p style="color:rgba(245,230,184,0.68); font-size:0.92rem; line-height:1.85; text-align:justify;">
                        {{ __('messages.'.$event['body_key']) }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Back link --}}
        <div class="mt-12 text-center">
            <a href="{{ route('frontend.about') }}"
               class="inline-flex items-center gap-2 px-6 py-3 rounded-full font-semibold text-sm transition-all"
               style="background:rgba(200,146,26,0.1); border:1px solid rgba(200,146,26,0.25); color:#E8B84B;"
               onmouseover="this.style.background='rgba(200,146,26,0.18)'"
               onmouseout="this.style.background='rgba(200,146,26,0.1)'">
                <span class="material-symbols-outlined" style="font-size:17px;">arrow_back</span>
                {{ __('messages.history_back') }}
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
