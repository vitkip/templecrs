@extends('frontend.layout')

@section('content')

<style>
.article-card { transition: box-shadow 0.2s ease; }
.article-card:hover { box-shadow: 0 6px 28px rgba(14,21,14,0.11) !important; }
.article-item { transition: background 0.18s ease; }
.article-item:hover { background: rgba(200,146,26,0.05) !important; }
details > summary { cursor: pointer; list-style: none; }
details > summary::-webkit-details-marker { display: none; }
details[open] .chevron { transform: rotate(180deg); }
.chevron { transition: transform 0.2s ease; }
@media (prefers-reduced-motion: reduce) { .article-card, .article-item { transition: none; } }
</style>

{{-- ════════════ HERO ════════════ --}}
<div class="relative overflow-hidden"
     style="background: linear-gradient(160deg, #0A1208 0%, #1C2C12 38%, #2C1A06 72%, #180E04 100%); min-height: 300px;">

    <div class="absolute inset-0 pointer-events-none" style="opacity:0.04;" aria-hidden="true">
        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="lpg" x="0" y="0" width="80" height="80" patternUnits="userSpaceOnUse">
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
            <rect width="100%" height="100%" fill="url(#lpg)"/>
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
            <span style="color:#C8921A;">{{ __('messages.guide_breadcrumb_label') }}</span>
        </div>

        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full mb-6"
             style="background:rgba(200,146,26,0.09); border:1px solid rgba(200,146,26,0.22); color:#C8921A; font-size:11px; font-weight:700; letter-spacing:0.2em; text-transform:uppercase;">
            <span class="material-symbols-outlined" style="font-size:13px;">menu_book</span>
            {{ __('messages.guide_badge') }}
        </div>

        <h1 class="font-bold text-white mb-4"
            style="font-size:clamp(1.6rem,4vw,2.8rem); line-height:1.25; text-shadow:0 2px 32px rgba(0,0,0,0.5);">
            {{ __('messages.guide_hero_title') }}<br>
            <span style="color:#E8B84B;">{{ __('messages.guide_hero_subtitle') }}</span>
        </h1>
        <p style="color:rgba(255,255,255,0.45); font-size:0.95rem; max-width:520px; line-height:1.8;">
            {{ __('messages.guide_hero_desc') }}
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

{{-- ════════════ CONTENT ════════════ --}}
<div style="background:#FFFBEB; min-height:60vh;">
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-20">

    {{-- Article index chips --}}
    <div class="flex flex-wrap gap-2 mb-12">
        @php
            $chipKeys = [
                1 => 'guide_a1_chip',
                2 => 'guide_a2_chip',
                3 => 'guide_a3_chip',
                4 => 'guide_a4_chip',
                5 => 'guide_a5_chip',
                6 => 'guide_a6_chip',
                7 => 'guide_a7_chip',
                8 => 'guide_a8_chip',
                9 => 'guide_a9_chip',
                10 => 'guide_a10_chip',
            ];
        @endphp
        @foreach($chipKeys as $n => $key)
        <a href="#article-{{ $n }}"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold transition-colors"
           style="background:rgba(200,146,26,0.08); border:1px solid rgba(200,146,26,0.2); color:#9A6E10;"
           onmouseover="this.style.background='rgba(200,146,26,0.18)'" onmouseout="this.style.background='rgba(200,146,26,0.08)'">
            <span style="background:#C8921A; color:#fff; border-radius:50%; width:16px; height:16px; display:inline-flex; align-items:center; justify-content:center; font-size:10px; flex-shrink:0;">{{ $n }}</span>
            {{ __('messages.'.$key) }}
        </a>
        @endforeach
    </div>

    @php
        $articles = [
            [
                'no'    => 1,
                'title_key' => 'guide_a1_title',
                'icon'  => 'flag',
                'body'  => null,
                'text'  => 'ຂໍ້ກຳນົດສະບັບນີ້ ກຳນົດຫຼັກການ, ລະບຽບການ ແລະ ມາດຕະການ ກ່ຽວກັບການຈັດຕັ້ງ ແລະການເຄື່ອນໄຫວ ຂອງກັມມາທິການສາທາຣະນູປະການ ອົງການພຸດທະສາສະໜາສຳພັນແຫ່ງ ສປປ ລາວ ແນໃສ່ເຮັດໃຫ້ການກຳນົດໂຄງປະກອບກົນໄກ, ພາລະບົດບາດ, ສິດ, ໜ້າທີ່ ແລະ ແບບແຜນວິທີເຮັດວຽກ ມີຄວາມສອດຄ່ອງ ແລະ ມີປະສິດທິພາບສູງ; ທັງນີ້ ກໍ່ເພື່ອຮັບປະກັນການຜັນຂະຫຍາຍຫຼັກພຣະທັມມະວິນັຍ, ທັມມະນູນສົງລາວ, ລັດຖະທຳມະນູນ, ກົດໝາຍ, ລະບຽບການ, ຮີດຄອງປະເພນີ ແລະ ວັດທະນະທຳອັນດີງາມ ເຂົ້າໃນວຽກງານຂົງເຂດສາທາຣະນູປະການ ໃຫ້ມີຄວາມເປັນເອກະພາບໃນທົ່ວປະເທດ ແລະ ແທດເໝາະກັບສະພາບຄວາມເປັນຈິງໃນແຕ່ລະໄລຍະ.',
                'items' => [],
            ],
            [
                'no'    => 2,
                'title_key' => 'guide_a2_title',
                'icon'  => 'account_tree',
                'body'  => 'ກັມມາທິການສາທາຣະນູປະການ ສູນກາງອົງການພຸດທະສາສະໜາສຳພັນ ແຫ່ງ ສປປ ລາວ ປະກອບດ້ວຍ ຫ້ອງການກຳມາທິການ, ບັນດາຄະນະກຳມະການຝ່າຍຕ່າງໆ ດັ່ງນີ້:',
                'text'  => null,
                'items' => [
                    'ຄະນະກໍາມະການຫ້ອງການກັມມາທິການ;',
                    'ຄະນະກໍາມະການຮັບຜິດຊອບການເງິນ ແລະ ການບັນຊີ;',
                    'ຄະນະກໍາມະການຮັບຜິດຊອບປະຊາສຳພັນ;',
                    'ຄະນະກຳມະການຮັບຜິດຊອບສາທາຣະນູປະໂພກ.',
                ],
            ],
            [
                'no'    => 3,
                'title_key' => 'guide_a3_title',
                'icon'  => 'manage_accounts',
                'body'  => null,
                'text'  => null,
                'items' => [
                    'ຮັບຜິດຊອບ ແລະ ຈັດຕັ້ງປະຕິບັດໜ້າທີ່ແທນ ຫົວໜ້າກັມມາທິການ ຕາມການມອບໝາຍ ໃນກໍລະນີທີ່ຫົວໜ້າຕິດພາລະກິດທາງສາສະໜາ ຫຼື ບໍ່ສາມາດປະຕິບັດໜ້າທີ່ໄດ້;',
                    'ບໍລິຫານ-ຈັດການ ແລະ ຊີ້ນຳ-ນຳພາ ວຽກງານບໍລິຫານລວມ ຂອງກັມມາທິການສາທາຣະນູປະການ ໃຫ້ມີຄວາມຄ່ອງຕົວ ແລະ ສອດຄ່ອງຕາມພາລະບົດບາດ;',
                    'ພິຈາລະນາ ແລະ ລົງລາຍເຊັນ ເອກະສານທາງການດ້ານຕ່າງໆ ພາຍໃຕ້ຂອບເຂດສິດ ແລະ ຄວາມຮັບຜິດຊອບ ຂອງກັມມາທິການ;',
                    'ວາງແຜນ ແລະ ຈັດສັນວຽກງານບໍລິຫານພາຍໃນ ໃຫ້ມີຄວາມເໝາະສົມ, ແທດເໝາະກັບສະພາບຄວາມເປັນຈິງ;',
                    'ຊີ້ນຳ ແລະ ຮັບຜິດຊອບການເຄື່ອນໄຫວວຽກງານ ຂອງບັນດາຄະນະກຳມະການ ແລະ ໂຄງການຕ່າງໆ ທີ່ຢູ່ໃນຄວາມຮັບຜິດຊອບ ຂອງຝ່າຍບໍລິຫານ;',
                    'ຄຸ້ມຄອງວຽກງານບຸກຄະລາກອນ (ພຣະສົງ ແລະ ຄະລືຫັດ), ຈັດວາງບຸກຄະລາກອນລົງຊ່ວຍວຽກ ພ້ອມທັງຕິດຕາມ ແລະ ປະເມີນຜົນ;',
                    'ເປັນໃຈກາງໃນການພົວພັນ ແລະ ປະສານສົມທົບ ກັບບັນດາກະຊວງ, ອົງການທຽບເທົ່າ ແລະ ພາກສ່ວນທີ່ກ່ຽວຂ້ອງ;',
                    'ຊີ້ນຳ ແລະ ຮັບຜິດຊອບວຽກງານເອກະສານຂາເຂົ້າ-ຂາອອກ, ການຄົ້ນຄວ້າ ແລະ ສັງລວມເນື້ອໃນເອກະສານ;',
                    'ຄົ້ນຄວ້າ, ວາງແຜນ ແລະ ຄຸ້ມຄອງການນຳໃຊ້ງົບປະມານ ໃຫ້ມີຄວາມໂປ່ງໃສ, ປະຢັດ ແລະ ມີປະສິດທິຜົນ;',
                    'ຊີ້ນຳວຽກງານຈັດຊື້-ຈັດຈ້າງ, ການບຳລຸງຮັກສາຊັບສິນລວມ, ບູລະນະຫ້ອງການ;',
                    'ຄຸ້ມຄອງສາງວັດຖຸອຸປະກອນ ແລະ ຊີ້ນຳການແຈກຢາຍ ໃຫ້ແກ່ວັດ ຫຼື ສະຖານທີ່ຂາດເຂີນ;',
                    'ກະກຽມກອງປະຊຸມ, ສັງລວມ ແລະ ສ້າງບົດສະຫຼຸບຜົນການປະຕິບັດວຽກງານ ຢ່າງເປັນປົກກະຕິ;',
                    'ປະຕິບັດສິດ ແລະ ໜ້າທີ່ອື່ນໆ ຕາມພຣະທັມມະວິນັຍ ແລະ ຕາມການມອບໝາຍ.',
                ],
            ],
            [
                'no'    => 4,
                'title_key' => 'guide_a4_title',
                'icon'  => 'school',
                'body'  => null,
                'text'  => null,
                'items' => [
                    'ຮັບຜິດຊອບ ແລະ ຈັດຕັ້ງປະຕິບັດໜ້າທີ່ແທນ ຫົວໜ້າກັມມາທິການ ຕາມການມອບໝາຍ;',
                    'ຮັບຜິດຊອບ ແລະ ຊີ້ນຳວຽກງານ ຂອງບັນດາຄະນະກໍາມະການ ແລະ ໂຄງການຕ່າງໆ ຂອງຝ່າຍວິຊາການ;',
                    'ພິຈາລະນາ ແລະ ລົງລາຍເຊັນ ເອກະສານທາງການ ພາຍໃຕ້ຂອບເຂດສິດ;',
                    'ຄົ້ນຄວ້າ, ສ້າງແຜນດຳເນີນງານ, ຂໍ້ກຳນົດ, ກົດລະບຽບ, ຄູ່ມື-ຫຼັກສູດ;',
                    'ຮັບຜິດຊອບຈັດຕັ້ງໜ່ວຍງານຝຶກອົບຮົມ, ສຶກສາ, ເຜີຍແຜ່ ໃຫ້ໄດ້ທັງປະລິມານ ແລະ ຄຸນນະພາບ;',
                    'ສັງລວມ, ສະຫຼຸບ ແລະ ລາຍງານການປະຕິບັດວຽກງານ ຕໍ່ຄະນະບໍລິຫານ ຢ່າງເປັນປົກກະຕິ;',
                    'ຄົ້ນຄວ້າ ແລະ ກຳນົດລະບຽບການ, ມາດຕະຖານເຕັກນິກ ກ່ຽວກັບການກໍ່ສ້າງ ແລະ ບູລະນະປະຕິສັງຂອນວັດ;',
                    'ໃຫ້ຄຳປຶກສາດ້ານການອອກແບບສິລະປະວັດທະນະທຳພຸດທະສາສະໜາລາວ ໃຫ້ຖືກຕ້ອງຕາມຮີດຄອງ;',
                    'ສ້າງຄູ່ມື ແລະ ຈັດຝຶກອົບຮົມວິຊາການ ໃຫ້ແກ່ພຣະສົງ-ສາມະເນນ, ຄາລະວາດ;',
                    'ກວດກາ ແລະ ຢັ້ງຢືນແບບແຕ້ມ (Blueprints) ຂອງສິມ, ກຸຕິ, ທາດ ແລະ ສິ່ງກໍ່ສ້າງ;',
                    'ສ້າງຖານຂໍ້ມູນບັນຊີລາຍຊື່ ວັດວາອາຮາມ ທີ່ເປັນມໍລະດົກວັດທະນະທຳ ເພື່ອວາງແຜນການອະນຸລັກ;',
                    'ນຳໃຊ້ສິດ ແລະ ປະຕິບັດໜ້າທີ່ອື່ນ ຕາມການມອບໝາຍ.',
                ],
            ],
            [
                'no'    => 5,
                'title_key' => 'guide_a5_title',
                'icon'  => 'business',
                'body'  => 'ມີພາລະບົດບາດເປັນເສນາທິການຮອບດ້ານ ໃຫ້ແກ່ຄະນະບໍລິຫານງານກັມມາທິການ ໃນການປະສານສົມທົບ ແລະ ອຳນວຍຄວາມສະດວກ ໂດຍມີໜ້າທີ່ດັ່ງນີ້:',
                'text'  => null,
                'items' => [
                    'ເປັນເສນາທິການສັງລວມ, ຮ່າງແຜນການ ແລະ ສະຫຼຸບລາຍງານ (3 ເດືອນ, 6 ເດືອນ ແລະ 1 ປີ);',
                    'ຕິດຕາມ, ຊຸກຍູ້ ແລະ ປະເມີນຜົນ ພ້ອມທັງບັນທຶກມະຕິ ແລະ ຄຳສັ່ງຈາກກອງປະຊຸມ;',
                    'ພົວພັນປະສານງານກັບອົງການຈັດຕັ້ງພຣະສົງ, ກະຊວງ ແລະ ພາກສ່ວນກ່ຽວຂ້ອງ;',
                    'ຄຸ້ມຄອງວຽກງານພິທີການ, ຮັບຮອງ ແລະ ຕ້ອນຮັບພຣະເຖລານຸເຖລະ ທັງພາຍໃນ ແລະ ຕ່າງປະເທດ;',
                    'ຄຸ້ມຄອງລະບົບເອກະສານຂາເຂົ້າ-ຂາອອກ, ການສຳເນົາ ແລະ ຈ້ຳຕາປະທັບ ໃຫ້ເປັນລະບົບ;',
                    'ຮ່າງ ແລະ ພິມເອກະສານທາງການ: ຄຳສັ່ງ, ແຈ້ງການ, ຂໍ້ຕົກລົງ, ໃບຍ້ອງຍໍ;',
                    'ຈັດຕັ້ງສະຖານທີ່ເຮັດວຽກ ແລະ ຄຸ້ມຄອງຊັບສິນ, ອຸປະກອນຮັບໃຊ້ຫ້ອງການ.',
                ],
            ],
            [
                'no'    => 6,
                'title_key' => 'guide_a6_title',
                'icon'  => 'account_balance_wallet',
                'body'  => 'ມີພາລະບົດບາດເປັນເສນາທິການທາງດ້ານການເງິນ ແລະ ການບັນຊີ ໂດຍແບ່ງອອກເປັນ 2 ດ້ານ:',
                'text'  => null,
                'subsections' => [
                    [
                        'label' => 'ກ. ວຽກງານບັນຊີ',
                        'items' => [
                            'ສັງລວມການສ້າງແຜນງົບປະມານ, ຕິດຕາມ ແລະ ກວດກາການນຳໃຊ້ງົບ;',
                            'ກວດກາ ແລະ ບັນທຶກລາຍຮັບ-ລາຍຈ່າຍ ເຂົ້າລະບົບບັນຊີ ຢ່າງຄົບຖ້ວນ;',
                            'ກວດກາແຜນການເບີກຈ່າຍ ພາຍໃນ 3–5 ວັນລັດຖະການ;',
                            'ສະຫຼຸບລາຍງານໜີ້ຕ້ອງຮັບ, ໜີ້ຕ້ອງຈ່າຍ ເປັນລາຍເດືອນ;',
                            'ລາຍງານການເງິນ-ບັນຊີ ເດືອນ, 3 ເດືອນ, 6 ເດືອນ ແລະ ປະຈຳປີ;',
                            'ສ້າງ ແລະ ພັດທະນາລະບົບບັນຊີກອງທຶນ ໃຫ້ໂປ່ງໃສ ກວດສອບໄດ້.',
                        ],
                    ],
                    [
                        'label' => 'ຂ. ວຽກງານການເງິນ',
                        'items' => [
                            'ຄຸ້ມຄອງການຮັບເງິນສົດ ແລະ ເງິນບໍລິຈາກ ໃຫ້ເຂົ້າບັນຊີຊ້າສຸດ 3–5 ວັນ;',
                            'ຈັດຕັ້ງການເບີກຈ່າຍງົບ ໃຫ້ທັນ ແລະ ຖືກຕ້ອງ;',
                            'ຄຸ້ມຄອງລາຍຮັບ-ລາຍຈ່າຍ ໃຫ້ລະອຽດ, ສົມເຫດ ແລະ ໂປ່ງໃສ;',
                            'ຕິດຕາມ ແລະ ຄຸ້ມຄອງການນຳໃຊ້ປັດໄຈ ໃຫ້ຖືກຕ້ອງຕາມຈຸດປະສົງ;',
                            'ປະສານງານກັບທະນາຄານ ໃນການຄຸ້ມຄອງບັນຊີ;',
                            'ຈັດເກັບ ແລະ ຮັກສາໃບສຳຄັນຮັບ-ຈ່າຍ ໃຫ້ເປັນລະບົບ;',
                            'ລາຍງານສະພາບກະແສເງິນສົດ ແລະ ງົບຄົງເຫຼືອ ຢ່າງເປັນປົກກະຕິ.',
                        ],
                    ],
                ],
                'items' => [],
            ],
            [
                'no'    => 7,
                'title_key' => 'guide_a7_title',
                'icon'  => 'campaign',
                'body'  => 'ມີພາລະບົດບາດເປັນເສນາທິການທາງດ້ານການປະຊາສຳພັນ ໂດຍມີໜ້າທີ່ ດັ່ງນີ້:',
                'text'  => null,
                'items' => [
                    'ຈັດຕັ້ງການເຜີຍແຜ່ ຜ່ານ Social Media ແລະ ສື່ມວນຊົນທຸກຂະແໜງ;',
                    'ຮັບຜິດຊອບເປັນໂຄສົກ ຖະແຫຼງຂ່າວ ແລະ ຕອບຂໍ້ຊັກຖາມ ຢ່າງເປັນທາງການ;',
                    'ວາງແຜນ ແລະ ດຳເນີນການຜະລິດສື່, ວິດີໂອ ແລະ ຮູບພາບວິຊາການ;',
                    'ສ້າງສັນລາຍການ, ຄລິບ (ບອກບຸນ) ແລະ ກິດຈະກຳລະດົມທຶນ ໂປ່ງໃສ;',
                    'ຄຸ້ມຄອງ Facebook Page, ເວັບໄຊ, ຖ່າຍທຳ, ຕັດຕໍ່ ແລະ ອັບໂຫຼດ;',
                    'ສ້າງສາຍພົວພັນ ກັບສັດທາຍາດ ເພື່ອລະດົມການຊ່ວຍເຫຼືອ;',
                    'ລາຍງານຜົນ ຕໍ່ຄະນະຫົວໜ້າກັມມາທິການ ຢ່າງເປັນປົກກະຕິ.',
                ],
            ],
            [
                'no'    => 8,
                'title_key' => 'guide_a8_title',
                'icon'  => 'handyman',
                'body'  => 'ມີພາລະບົດບາດເປັນເສນາທິການທາງດ້ານສາທາຣະນູປະໂພກ ໂດຍມີໜ້າທີ່ ດັ່ງນີ້:',
                'text'  => null,
                'items' => [
                    'ຈັດຕັ້ງ ແລະ ບໍລິຫານສະຖານທີ່, ຈັດວາງໂຕະ-ຕັ່ງ ແລະ ອຸປະກອນກອງປະຊຸມ;',
                    'ຕິດຕາມ ແລະ ດູແລຮັກສາຄວາມສະອາດ ສະຖານທີ່ ແລະ ທີ່ພັກ;',
                    'ຈັດຫາ ແລະ ຄຸ້ມຄອງເຄື່ອງໃຊ້, ອາຫານ, ນ້ຳ ສຳລັບແຕ່ລະກິດຈະກຳ;',
                    'ບໍລິການ ແລະ ອຳນວຍຄວາມສະດວກ ໃຫ້ຄະນະ, ແຂກ ແລະ ຜູ້ເຂົ້າຮ່ວມ;',
                    'ຄຸ້ມຄອງ ແລະ ດູແລເຄື່ອງໃຊ້ສ່ວນລວມ ໃຫ້ພ້ອມ ແລະ ເປັນລະບຽບ.',
                ],
            ],
            [
                'no'    => 9,
                'title_key' => 'guide_a9_title',
                'icon'  => 'task_alt',
                'body'  => null,
                'text'  => 'ບັນດາຄະນະກໍາມະການຮັບຜິດຊອບ, ຫ້ອງການ ແລະ ພາກສ່ວນທີ່ກ່ຽວຂ້ອງ ຈົ່ງຮັບຊາບ ແລະ ພ້ອມກັນຈັດຕັ້ງປະຕິບັດຂໍ້ກໍານົດສະບັບນີ້ ໃຫ້ມີປະສິດທິຜົນສູງ.',
                'items' => [],
            ],
            [
                'no'    => 10,
                'title_key' => 'guide_a10_title',
                'icon'  => 'verified',
                'body'  => null,
                'text'  => 'ຂໍ້ກໍານົດສະບັບນີ້ ມີຜົນສັກສິດນັບແຕ່ວັນລົງລາຍເຊັນເປັນຕົ້ນໄປ.',
                'items' => [],
            ],
        ];
    @endphp

    <div class="space-y-5">
        @foreach($articles as $article)
        <div id="article-{{ $article['no'] }}" class="article-card rounded-2xl overflow-hidden scroll-mt-24"
             style="background:#F7EFD8; border:1px solid rgba(200,146,26,0.18); box-shadow:0 2px 12px rgba(14,21,14,0.05);">

            {{-- Gold top bar --}}
            <div style="height:3px; background:linear-gradient(to right, #B87A14, #E8B84B, #B87A14);"></div>

            <details {{ $article['no'] <= 2 ? 'open' : '' }}>
                <summary class="flex items-center gap-4 px-6 py-5 select-none">
                    {{-- Article badge --}}
                    <div class="flex-shrink-0 w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm"
                         style="background:rgba(200,146,26,0.12); border:1.5px solid rgba(200,146,26,0.3); color:#C8921A; min-width:2.25rem;">
                        {{ $article['no'] }}
                    </div>
                    {{-- Icon --}}
                    <span class="material-symbols-outlined flex-shrink-0" style="font-size:20px; color:rgba(14,21,14,0.35);">{{ $article['icon'] }}</span>
                    {{-- Title --}}
                    <div class="flex-1 min-w-0">
                        <div style="color:rgba(14,21,14,0.38); font-size:9px; font-weight:700; letter-spacing:0.18em; text-transform:uppercase; margin-bottom:2px;">
                            {{ __('messages.guide_article_label') }} {{ $article['no'] }}
                        </div>
                        <h3 class="font-bold" style="color:#0E150E; font-size:1rem; line-height:1.3;">
                            {{ __('messages.'.$article['title_key']) }}
                        </h3>
                    </div>
                    {{-- Chevron --}}
                    <span class="material-symbols-outlined chevron flex-shrink-0" style="font-size:20px; color:rgba(14,21,14,0.3);">expand_more</span>
                </summary>

                <div class="px-6 pb-6 pt-1">
                    <div style="height:1px; background:rgba(200,146,26,0.15); margin-bottom:18px;"></div>

                    @if($article['body'])
                    <p class="mb-4 text-sm leading-relaxed" style="color:rgba(14,21,14,0.7);">{{ $article['body'] }}</p>
                    @endif

                    @if($article['text'])
                    <p class="text-sm leading-relaxed" style="color:rgba(14,21,14,0.72); line-height:1.9; text-align:justify;">{{ $article['text'] }}</p>
                    @endif

                    @if(!empty($article['items']))
                    <ol class="space-y-2 mt-2">
                        @foreach($article['items'] as $i => $item)
                        <li class="article-item flex gap-3 rounded-xl px-4 py-3 text-sm"
                            style="background:rgba(200,146,26,0.04); border:1px solid rgba(200,146,26,0.1);">
                            <span class="flex-shrink-0 font-bold" style="color:#C8921A; min-width:1.25rem;">{{ $i+1 }}.</span>
                            <span style="color:rgba(14,21,14,0.72); line-height:1.8;">{{ $item }}</span>
                        </li>
                        @endforeach
                    </ol>
                    @endif

                    @if(!empty($article['subsections']))
                    @foreach($article['subsections'] as $sub)
                    <div class="mt-5">
                        <div class="inline-flex items-center gap-2 mb-3 px-3 py-1 rounded-full"
                             style="background:rgba(200,146,26,0.1); color:#9A6E10; font-size:12px; font-weight:700; letter-spacing:0.08em;">
                            {{ $sub['label'] }}
                        </div>
                        <ol class="space-y-2">
                            @foreach($sub['items'] as $i => $item)
                            <li class="article-item flex gap-3 rounded-xl px-4 py-3 text-sm"
                                style="background:rgba(200,146,26,0.04); border:1px solid rgba(200,146,26,0.1);">
                                <span class="flex-shrink-0 font-bold" style="color:#C8921A; min-width:1.25rem;">{{ $i+1 }}.</span>
                                <span style="color:rgba(14,21,14,0.72); line-height:1.8;">{{ $item }}</span>
                            </li>
                            @endforeach
                        </ol>
                    </div>
                    @endforeach
                    @endif
                </div>
            </details>
        </div>
        @endforeach
    </div>

    {{-- Back link --}}
    <div class="mt-12 text-center">
        <a href="{{ route('frontend.about') }}"
           class="inline-flex items-center gap-2 px-6 py-3 rounded-full font-semibold text-sm"
           style="background:rgba(200,146,26,0.1); border:1px solid rgba(200,146,26,0.25); color:#9A6E10;"
           onmouseover="this.style.background='rgba(200,146,26,0.18)'" onmouseout="this.style.background='rgba(200,146,26,0.1)'">
            <span class="material-symbols-outlined" style="font-size:17px;">arrow_back</span>
            {{ __('messages.guide_back') }}
        </a>
    </div>

</div>
</div>

@endsection
