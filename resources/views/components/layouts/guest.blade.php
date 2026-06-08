<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $title ?? 'ເຂົ້າສູ່ລະບົບ' }} — Buddhist EMS</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/material-symbols@latest/outlined.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        :root {
            --gold:       #c9a227;
            --gold-dim:   rgba(201, 162, 39, 0.32);
            --gold-faint: rgba(201, 162, 39, 0.09);
            --deep:       #0b1520;
            --ink:        #1c1c2e;
            --cream:      #faf7f0;
        }

        /* ── Entrance animations ── */
        @keyframes panel-reveal   { from { opacity:0; }                       to { opacity:1; } }
        @keyframes form-slide     { from { opacity:0; transform:translateX(28px); } to { opacity:1; transform:translateX(0); } }
        @keyframes field-rise     { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:translateY(0); } }

        /* ── Mandala motion ── */
        @keyframes spin-cw        { to { transform:translate(-50%,-50%) rotate(360deg);  } }
        @keyframes spin-ccw       { to { transform:translate(-50%,-50%) rotate(-360deg); } }
        @keyframes glow-breathe   { 0%,100%{ opacity:.45; transform:translate(-50%,-50%) scale(1);    }
                                    50%    { opacity:.9;  transform:translate(-50%,-50%) scale(1.18); } }

        /* ── Floating particles ── */
        @keyframes particle-rise  { 0%  { transform:translateY(110%) scale(0); opacity:0; }
                                    6%  { opacity:.75; }
                                    94% { opacity:.75; }
                                    100%{ transform:translateY(-8%) scale(1);  opacity:0; } }

        /* ══════ Sacred left panel ══════ */
        .sacred-panel {
            background: var(--deep);
            overflow: hidden;
            animation: panel-reveal 1.4s ease both;
        }

        /* Subtle grid lines */
        .sacred-panel::before {
            content:'';
            position:absolute; inset:0;
            background:repeating-linear-gradient(
                0deg,
                transparent, transparent 38px,
                rgba(201,162,39,.04) 38px, rgba(201,162,39,.04) 39px
            );
            pointer-events:none;
        }

        /* ── Mandala rings ── */
        .mring {
            position:absolute; border-radius:50%;
            top:50%; left:50%;
        }
        .r1 { width: 68px;  height: 68px;  border:1.5px solid rgba(201,162,39,.78);
              animation:spin-cw   6s  linear infinite; }
        .r2 { width:124px;  height:124px;  border:1px   dashed rgba(201,162,39,.55);
              animation:spin-ccw 13s  linear infinite; }
        .r3 { width:188px;  height:188px;  border:1.5px solid rgba(201,162,39,.38);
              animation:spin-cw  21s  linear infinite; }
        .r4 { width:262px;  height:262px;  border:1px   dashed rgba(201,162,39,.25);
              animation:spin-ccw 33s  linear infinite; }
        .r5 { width:344px;  height:344px;  border:1px   solid rgba(201,162,39,.15);
              animation:spin-cw  47s  linear infinite; }
        .r6 { width:438px;  height:438px;  border:1px   dashed rgba(201,162,39,.08);
              animation:spin-ccw 68s  linear infinite; }

        /* ── Mandala radial glow ── */
        .m-glow {
            position:absolute; top:50%; left:50%;
            width:260px; height:260px; border-radius:50%;
            background:radial-gradient(circle, rgba(201,162,39,.13) 0%, transparent 70%);
            animation:glow-breathe 5s ease-in-out infinite;
            pointer-events:none;
        }

        /* ── Lotus petals ── */
        .lotus-wrap {
            position:absolute; top:50%; left:50%;
            width:80px; height:80px;
            transform:translate(-40px,-40px);
            z-index:6;
        }
        .lpetal {
            position:absolute;
            width:14px; height:30px;
            left:33px;  /* (80-14)/2 */
            top:10px;   /* 40-30 */
            border-radius:50% 50% 35% 35% / 62% 62% 28% 28%;
            background:linear-gradient(180deg, rgba(201,162,39,.58) 0%, rgba(201,162,39,.1) 100%);
            border:1px solid rgba(201,162,39,.52);
            transform-origin:7px 30px; /* center-x=7, bottom=30 */
        }
        .lpetal:nth-child(1){ transform:rotate(0deg);   }
        .lpetal:nth-child(2){ transform:rotate(45deg);  }
        .lpetal:nth-child(3){ transform:rotate(90deg);  }
        .lpetal:nth-child(4){ transform:rotate(135deg); }
        .lpetal:nth-child(5){ transform:rotate(180deg); }
        .lpetal:nth-child(6){ transform:rotate(225deg); }
        .lpetal:nth-child(7){ transform:rotate(270deg); }
        .lpetal:nth-child(8){ transform:rotate(315deg); }

        .lotus-core {
            position:absolute; top:50%; left:50%;
            width:18px; height:18px; border-radius:50%;
            transform:translate(-9px,-9px);
            background:radial-gradient(circle at 38% 38%, #edd55a, #c9a227);
            box-shadow:0 0 14px rgba(201,162,39,.75), 0 0 32px rgba(201,162,39,.3);
            z-index:7;
        }

        /* ── Corner ornaments ── */
        .crnr {
            position:absolute; width:52px; height:52px;
            border-color:rgba(201,162,39,.28); border-style:solid;
        }
        .crnr-tl { top:22px;    left:22px;    border-width:1.5px 0 0 1.5px; }
        .crnr-tr { top:22px;    right:22px;   border-width:1.5px 1.5px 0 0; }
        .crnr-bl { bottom:22px; left:22px;    border-width:0 0 1.5px 1.5px; }
        .crnr-br { bottom:22px; right:22px;   border-width:0 1.5px 1.5px 0; }

        /* ── Gold accent stripes ── */
        .g-stripe {
            position:absolute; left:0; right:0; height:2px;
            background:linear-gradient(90deg, transparent 0%, rgba(201,162,39,.45) 40%,
                                              rgba(201,162,39,.45) 60%, transparent 100%);
        }

        /* ── Floating particles ── */
        .particle {
            position:absolute; width:3px; height:3px;
            border-radius:50%; background:var(--gold);
            opacity:0; animation:particle-rise linear infinite;
        }

        /* ══════ Right form panel ══════ */
        .form-panel {
            background:var(--cream);
            position:relative;
            animation:form-slide .85s cubic-bezier(.22,1,.36,1) .15s both;
        }

        /* Subtle warm grain overlay */
        .form-panel::after {
            content:'';
            position:absolute; inset:0; pointer-events:none; opacity:.022;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='180' height='180'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.85' numOctaves='3'/%3E%3C/filter%3E%3Crect width='180' height='180' filter='url(%23n)'/%3E%3C/svg%3E");
            background-size:180px;
        }

        /* ── Input fields ── */
        .sacred-field {
            display:flex; align-items:center;
            border:1.5px solid #e2dbd0;
            background:white; border-radius:14px;
            transition:border-color .2s ease, box-shadow .2s ease;
            overflow:hidden;
        }
        .sacred-field:focus-within {
            border-color:var(--gold);
            box-shadow:0 0 0 3px rgba(201,162,39,.12);
        }

        /* ── Gold shimmer button ── */
        .btn-gold {
            background:linear-gradient(110deg, #c9a227 0%, #edd55a 48%, #c9a227 100%);
            background-size:200% auto;
            color:#0b1520; border-radius:14px;
            transition:background-position .4s ease, box-shadow .25s ease, transform .15s ease;
        }
        .btn-gold:hover:not(:disabled) {
            background-position:right center;
            box-shadow:0 6px 24px rgba(201,162,39,.38);
            transform:translateY(-1px);
        }
        .btn-gold:active:not(:disabled) { transform:translateY(0); }
        .btn-gold:disabled               { opacity:.65; cursor:not-allowed; }

        /* ── Stagger helpers ── */
        .fi { animation:field-rise .6s cubic-bezier(.22,1,.36,1) both; }
        .fi-1 { animation-delay:.32s; }
        .fi-2 { animation-delay:.42s; }
        .fi-3 { animation-delay:.52s; }
        .fi-4 { animation-delay:.62s; }
        .fi-5 { animation-delay:.72s; }
        .fi-6 { animation-delay:.82s; }
    </style>
</head>

<body class="min-h-screen flex antialiased" style="background:var(--deep); overflow-x:hidden;">

{{-- ═══════════════════════════════════════════════════════════
     LEFT — Sacred Decorative Panel (desktop only)
═══════════════════════════════════════════════════════════ --}}
<aside class="sacred-panel hidden lg:flex lg:w-[44%] xl:w-[42%] flex-col shrink-0 relative select-none">

    {{-- Corner ornaments --}}
    <div class="crnr crnr-tl"></div>
    <div class="crnr crnr-tr"></div>
    <div class="crnr crnr-bl"></div>
    <div class="crnr crnr-br"></div>

    {{-- Top / bottom accent stripes --}}
    <div class="g-stripe" style="top:0;"></div>
    <div class="g-stripe" style="bottom:0;"></div>

    {{-- Floating particles --}}
    <div class="particle" style="left:10%; animation-duration:9s;  animation-delay:0s;"></div>
    <div class="particle" style="left:26%; animation-duration:13s; animation-delay:2.4s; width:2px;height:2px;"></div>
    <div class="particle" style="left:46%; animation-duration:10s; animation-delay:1.1s;"></div>
    <div class="particle" style="left:62%; animation-duration:8s;  animation-delay:3.7s; width:4px;height:4px;"></div>
    <div class="particle" style="left:77%; animation-duration:11s; animation-delay:0.5s;"></div>
    <div class="particle" style="left:90%; animation-duration:7s;  animation-delay:4.3s; width:2px;height:2px;"></div>

    {{-- ── Mandala art area (flex-1 so it fills the top portion) ── --}}
    <div class="flex-1 relative min-h-0 flex items-center justify-center">

        {{-- Radial glow --}}
        <div class="m-glow"></div>

        {{-- Concentric rings --}}
        <div class="mring r6"></div>
        <div class="mring r5"></div>
        <div class="mring r4"></div>
        <div class="mring r3"></div>
        <div class="mring r2"></div>
        <div class="mring r1"></div>

        {{-- Sri Yantra geometric overlay --}}
        <svg style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);
                    width:230px;height:230px;opacity:.2;"
             viewBox="0 0 230 230" fill="none" xmlns="http://www.w3.org/2000/svg">
            <polygon points="115,16  208,178  22,178"  stroke="#c9a227" stroke-width="1.5" fill="none"/>
            <polygon points="115,214  22,52  208,52"   stroke="#c9a227" stroke-width="1.5" fill="none"/>
            <polygon points="115,36  196,172  34,172"  stroke="#c9a227" stroke-width=".7"  fill="none" opacity=".65"/>
            <polygon points="115,194  34,58  196,58"   stroke="#c9a227" stroke-width=".7"  fill="none" opacity=".65"/>
            <circle cx="115" cy="115" r="28"           stroke="#c9a227" stroke-width="1"   fill="none" opacity=".85"/>
            <rect x="88" y="88" width="54" height="54" transform="rotate(45 115 115)"
                  stroke="#c9a227" stroke-width=".8" fill="none" opacity=".55"/>
        </svg>

        {{-- Lotus flower --}}
        <div class="lotus-wrap">
            <div class="lpetal"></div>
            <div class="lpetal"></div>
            <div class="lpetal"></div>
            <div class="lpetal"></div>
            <div class="lpetal"></div>
            <div class="lpetal"></div>
            <div class="lpetal"></div>
            <div class="lpetal"></div>
            <div class="lotus-core"></div>
        </div>
    </div>

    {{-- ── Text content (pinned below mandala) ── --}}
    <div class="relative z-10 text-center px-10 pb-12 shrink-0">

        {{-- Badge pill --}}
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full mb-5"
             style="border:1px solid rgba(201,162,39,.32); background:rgba(201,162,39,.07);">
            <span class="w-1.5 h-1.5 rounded-full" style="background:var(--gold);"></span>
            <span style="font-family:'Cinzel',serif; font-size:10px; letter-spacing:.22em; color:rgba(201,162,39,.82); text-transform:uppercase;">
                Buddhist EMS
            </span>
        </div>

        {{-- Heading --}}
        <h1 class="mb-3 leading-snug"
            style="font-family:'Cinzel',serif; font-size:1.55rem; font-weight:600;
                   color:var(--gold); text-shadow:0 0 40px rgba(201,162,39,.22); letter-spacing:.025em;">
            Sacred<br>Administration<br>Portal
        </h1>

        {{-- Subtitle --}}
        <p class="text-sm leading-relaxed"
           style="color:rgba(255,255,255,.36); max-width:210px; margin:0 auto;">
            ແພລດຟອມດິຈິຕອລສຳລັບ<br>ການຄຸ້ມຄອງອົງການ
        </p>

        {{-- Separator --}}
        <div class="flex items-center justify-center gap-3 my-5">
            <div class="h-px w-10" style="background:linear-gradient(90deg,transparent,rgba(201,162,39,.48));"></div>
            <div class="w-1 h-1 rounded-full" style="background:rgba(201,162,39,.6);"></div>
            <div class="h-px w-10" style="background:linear-gradient(90deg,rgba(201,162,39,.48),transparent);"></div>
        </div>

        {{-- Three pillars --}}
        <div class="flex items-center justify-center gap-5">
            <div class="text-center">
                <div class="text-xs font-semibold mb-0.5"
                     style="font-family:'Cinzel',serif; color:rgba(201,162,39,.72);">ສາດສະໜາ</div>
                <div style="font-size:9px; color:rgba(255,255,255,.24);">Dhamma</div>
            </div>
            <div class="h-7 w-px" style="background:rgba(201,162,39,.2);"></div>
            <div class="text-center">
                <div class="text-xs font-semibold mb-0.5"
                     style="font-family:'Cinzel',serif; color:rgba(201,162,39,.72);">ຂໍ້ມູນ</div>
                <div style="font-size:9px; color:rgba(255,255,255,.24);">Digital</div>
            </div>
            <div class="h-7 w-px" style="background:rgba(201,162,39,.2);"></div>
            <div class="text-center">
                <div class="text-xs font-semibold mb-0.5"
                     style="font-family:'Cinzel',serif; color:rgba(201,162,39,.72);">ບໍລິຫານ</div>
                <div style="font-size:9px; color:rgba(255,255,255,.24);">Manage</div>
            </div>
        </div>

        {{-- Year watermark --}}
        <p class="mt-8" style="font-family:'Cinzel',serif; font-size:10px;
                                letter-spacing:.28em; color:rgba(255,255,255,.14);">MMXXVI</p>
    </div>
</aside>


{{-- ═══════════════════════════════════════════════════════════
     RIGHT — Form Panel
═══════════════════════════════════════════════════════════ --}}
<main class="form-panel flex-1 flex items-center justify-center min-h-screen px-6 py-12 overflow-y-auto">

    {{-- Ambient blobs (decorative, do not interfere with content) --}}
    <div style="position:absolute;top:-90px;right:-90px;width:300px;height:300px;border-radius:50%;
                background:radial-gradient(circle,rgba(201,162,39,.07),transparent);
                pointer-events:none;"></div>
    <div style="position:absolute;bottom:-70px;left:-70px;width:220px;height:220px;border-radius:50%;
                background:radial-gradient(circle,rgba(201,162,39,.05),transparent);
                pointer-events:none;"></div>

    <div class="w-full max-w-sm relative z-10">
        {{ $slot }}
    </div>
</main>

@livewireScripts
</body>
</html>
