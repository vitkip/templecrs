@extends('frontend.layout')

@section('content')

    @php
        $locale = app()->getLocale();

        $personnelJson = $personnel->map(function ($p) use ($locale) {
            return [
                'id' => $p->id,
                'name' => $p->display_name,
                'title' => $p->display_title,
                'position' => $p->display_position,
                'gender' => $p->gender,
                'gender_badge' => $p->gender_badge,
                'photo_url' => $p->photo_url ? \Illuminate\Support\Facades\Storage::url($p->photo_url) : null,
                'dept_id' => (string) ($p->department_id ?? ''),
                'dept_name' => $p->department?->name ?? '',
                'bio' => $p->display_bio,
                'education' => $locale === 'lo' ? ($p->education_lo ?? $p->education_en) : ($p->education_en ?? $p->education_lo),
                'current_temple' => $locale === 'lo' ? ($p->current_temple_lo ?? $p->current_temple_en) : ($p->current_temple_en ?? $p->current_temple_lo),
                'date_of_ordination' => $p->date_of_ordination?->format('d/m/Y'),
                'pansa' => $p->pansa,
                'email' => $p->email,
                'phone' => $p->phone,
                'facebook' => $p->facebook,
                'affiliation_level' => $p->affiliation_level,
                'affiliation_province' => $p->affiliation_province,
                'search_text' => strtolower(implode(' ', array_filter([
                    $p->name_lo,
                    $p->name_en,
                    $p->position_lo,
                    $p->position_en,
                    $p->title_lo,
                    $p->title_en,
                    $p->department?->name_lo,
                    $p->department?->name_en,
                    $p->affiliation_province,
                ]))),
            ];
        })->values()->toArray();

        $totalCount = $personnel->count();
        $monkCount = $personnel->where('gender', 'monk')->count();
        $deptCount = $departments->count();
        $centralCount = $personnel->where('affiliation_level', 'central')->count();
        $provincialCount = $personnel->where('affiliation_level', 'provincial')->count();
    @endphp

    {{-- ════════════════════════════════════════════════════
    HERO — Deep temple palette + lotus texture
    ════════════════════════════════════════════════════ --}}
    <div class="relative overflow-hidden"
        style="background: linear-gradient(150deg, #2C1A08 0%, #3D2A12 40%, #1C2B1C 100%); min-height: 260px;">

        {{-- Lotus 8-petal repeating SVG texture --}}
        <div class="absolute inset-0 pointer-events-none" style="opacity: 0.055;" aria-hidden="true">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="lotuspat" x="0" y="0" width="64" height="64" patternUnits="userSpaceOnUse">
                        <g transform="translate(32,32)" fill="#D4AF37">
                            <ellipse rx="4.5" ry="10" transform="rotate(0)" />
                            <ellipse rx="4.5" ry="10" transform="rotate(45)" />
                            <ellipse rx="4.5" ry="10" transform="rotate(90)" />
                            <ellipse rx="4.5" ry="10" transform="rotate(135)" />
                        </g>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#lotuspat)" />
            </svg>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-20">

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
                <span style="font-size:11px; color: rgba(255,255,255,0.7);">{{ __('messages.personnel') }}</span>
            </nav>

            <div class="flex flex-col lg:flex-row lg:items-end gap-8 lg:gap-16">

                {{-- Title block --}}
                <div class="flex-1">
                    <p class="flex items-center gap-3 mb-4"
                        style="font-size:10px; font-weight:700; color:#C8953A; letter-spacing:0.22em; text-transform:uppercase;">
                        <span
                            style="width:32px; height:1px; background: rgba(200,149,58,0.45); display:inline-block;"></span>
                        {{ __('messages.our_people') }}
                        <span
                            style="width:32px; height:1px; background: rgba(200,149,58,0.45); display:inline-block;"></span>
                    </p>
                    <h1 class="font-bold text-white leading-tight mb-3"
                        style="font-size: clamp(26px, 5vw, 44px); letter-spacing: -0.015em;">
                        {{ __('messages.personnel_directory') }}
                    </h1>
                    <p style="font-size:14px; line-height:1.65; color:rgba(255,255,255,0.5); max-width:460px;">
                        {{ __('messages.personnel_dir_subtitle') }}
                    </p>
                </div>

                {{-- Stats — editorial numbers --}}
                <div class="flex items-end gap-8 shrink-0 pb-1">
                    <div class="text-center">
                        <p class="font-bold text-white tabular-nums" style="font-size:42px; line-height:1;">
                            {{ $totalCount }}</p>
                        <p
                            style="font-size:10px; letter-spacing:0.1em; color:rgba(255,255,255,0.4); margin-top:4px; text-transform:uppercase;">
                            {{ __('messages.personnel') }}</p>
                    </div>
                    <div style="width:1px; height:48px; background:rgba(255,255,255,0.08); align-self:center;"></div>
                    <div class="text-center">
                        <p class="font-bold tabular-nums" style="font-size:42px; line-height:1; color:#C8953A;">
                            {{ $monkCount }}</p>
                        <p
                            style="font-size:10px; letter-spacing:0.1em; color:rgba(255,255,255,0.4); margin-top:4px; text-transform:uppercase;">
                            {{ __('messages.stat_monks') }}</p>
                    </div>
                    @if($deptCount > 0)
                        <div style="width:1px; height:48px; background:rgba(255,255,255,0.08); align-self:center;"></div>
                        <div class="text-center">
                            <p class="font-bold text-white tabular-nums" style="font-size:42px; line-height:1;">{{ $deptCount }}
                            </p>
                            <p
                                style="font-size:10px; letter-spacing:0.1em; color:rgba(255,255,255,0.4); margin-top:4px; text-transform:uppercase;">
                                {{ __('messages.departments') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════
    MAIN CONTENT AREA — Alpine component
    ════════════════════════════════════════════════════ --}}
    <div style="background-color: #FAF6EF;" x-data="{
            search: '',
            activeDept: 'all',
            activeAffiliation: 'all',
            activeProvince: 'all',
            personnel: @js($personnelJson),
            provinces: [
                'ນະຄອນຫຼວງວຽງຈັນ','ແຂວງຜົ້ງສາລີ','ແຂວງຫຼວງນ້ຳທາ','ແຂວງອຸດົມໄຊ',
                'ແຂວງບໍ່ແກ້ວ','ແຂວງຫຼວງພຣະບາງ','ແຂວງຫົວພັນ','ແຂວງໄຊຍະບູລີ',
                'ແຂວງຊຽງຂວາງ','ແຂວງວຽງຈັນ','ແຂວງບໍລິຄຳໄຊ','ແຂວງຄຳມ່ວນ',
                'ແຂວງສະຫວັນນະເຂດ','ແຂວງສາລະວັນ','ແຂວງເຊກອງ','ແຂວງຈຳປາສັກ',
                'ແຂວງອັດຕະປື','ແຂວງໄຊສົມບູນ'
            ],
            perPage: 9,
            currentPage: 1,
            perPageOptions: [9, 18, 27],
            get filtered() {
                const q = this.search.toLowerCase().trim();
                return this.personnel.filter(p => {
                    const deptOk   = this.activeDept === 'all' || p.dept_id === this.activeDept;
                    const affOk    = this.activeAffiliation === 'all' || p.affiliation_level === this.activeAffiliation;
                    const provOk   = this.activeProvince === 'all' || p.affiliation_province === this.activeProvince;
                    const searchOk = !q || p.search_text.includes(q);
                    return deptOk && affOk && provOk && searchOk;
                });
            },
            get totalPages() { return Math.max(1, Math.ceil(this.filtered.length / this.perPage)); },
            get paginated() {
                const start = (this.currentPage - 1) * this.perPage;
                return this.filtered.slice(start, start + this.perPage);
            },
            get pageNumbers() {
                const total = this.totalPages, curr = this.currentPage;
                if (total <= 7) return Array.from({length: total}, (_, i) => i + 1);
                if (curr <= 4) return [1, 2, 3, 4, 5, '...', total];
                if (curr >= total - 3) return [1, '...', total-4, total-3, total-2, total-1, total];
                return [1, '...', curr-1, curr, curr+1, '...', total];
            },
            countByAffiliation(level) { return this.personnel.filter(p => p.affiliation_level === level).length; },
            countByProvince(prov) {
                return this.personnel.filter(p => p.affiliation_level === 'provincial' && p.affiliation_province === prov).length;
            },
            get provincesWithData() { return this.provinces.filter(prov => this.countByProvince(prov) > 0); },
            get hasAffiliationData() { return this.personnel.some(p => p.affiliation_level); },
            get hasActiveFilters() {
                return this.search !== '' || this.activeDept !== 'all' || this.activeAffiliation !== 'all' || this.activeProvince !== 'all';
            },
            clearAll() { this.search = ''; this.activeDept = 'all'; this.activeAffiliation = 'all'; this.activeProvince = 'all'; },
            init() {
                this.$watch('search',            () => { this.currentPage = 1; });
                this.$watch('activeDept',        () => { this.currentPage = 1; });
                this.$watch('activeAffiliation', () => { this.currentPage = 1; this.activeProvince = 'all'; });
                this.$watch('activeProvince',    () => { this.currentPage = 1; });
                this.$watch('perPage',           () => { this.currentPage = 1; });
            }
         }">

        {{-- Lotus divider — signature transition element --}}
        <div class="flex items-center justify-center py-5" aria-hidden="true">
            <div style="height:1px; width:96px; background: linear-gradient(to right, transparent, rgba(200,149,58,0.3));">
            </div>
            <svg class="mx-4 shrink-0" width="26" height="26" viewBox="0 0 26 26" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <ellipse cx="13" cy="13" rx="3" ry="8" fill="#C8953A" opacity="0.45" transform="rotate(0 13 13)" />
                <ellipse cx="13" cy="13" rx="3" ry="8" fill="#C8953A" opacity="0.45" transform="rotate(45 13 13)" />
                <ellipse cx="13" cy="13" rx="3" ry="8" fill="#C8953A" opacity="0.45" transform="rotate(90 13 13)" />
                <ellipse cx="13" cy="13" rx="3" ry="8" fill="#C8953A" opacity="0.45" transform="rotate(135 13 13)" />
                <circle cx="13" cy="13" r="2.5" fill="#C8953A" opacity="0.65" />
            </svg>
            <div style="height:1px; width:96px; background: linear-gradient(to left, transparent, rgba(200,149,58,0.3));">
            </div>
        </div>

        {{-- ── Sticky Filter Bar ──────────────────────────────── --}}
        <div class="sticky top-16 lg:top-20 z-30 border-b"
            style="background: rgba(250,246,239,0.97); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border-color: rgba(200,149,58,0.18);">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2.5 flex flex-wrap gap-2.5 items-center">

                {{-- Search --}}
                <div class="relative min-w-[160px] flex-1 max-w-xs">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined pointer-events-none"
                        style="font-size:15px; color: rgba(124,77,15,0.4);">search</span>
                    <input x-model="search" type="text" placeholder="ຄົ້ນຫາຊື່, ຕຳແໜ່ງ..."
                        class="w-full pl-8 pr-8 py-2 rounded-lg border text-sm focus:outline-none transition-all"
                        style="background:white; border-color:rgba(200,149,58,0.22); font-size:12px; color:#1C1208;"
                        onfocus="this.style.borderColor='rgba(200,149,58,0.55)'; this.style.boxShadow='0 0 0 3px rgba(200,149,58,0.1)'"
                        onblur="this.style.borderColor='rgba(200,149,58,0.22)'; this.style.boxShadow='none'" />
                    <button x-show="search" @click="search=''"
                        class="absolute right-2 top-1/2 -translate-y-1/2 transition-colors"
                        style="color: rgba(124,77,15,0.4);" onmouseover="this.style.color='rgba(124,77,15,0.8)'"
                        onmouseout="this.style.color='rgba(124,77,15,0.4)'">
                        <span class="material-symbols-outlined" style="font-size:14px;">close</span>
                    </button>
                </div>

                {{-- Separator --}}
                <div class="hidden sm:block" style="width:1px; height:24px; background:rgba(200,149,58,0.15);"></div>

                {{-- Department filter --}}
                @if($departments->count() > 0)
                    <div class="flex flex-wrap gap-1.5 items-center">
                        <span class="hidden sm:inline"
                            style="font-size:9px; font-weight:700; letter-spacing:0.15em; color:rgba(124,77,15,0.45); text-transform:uppercase;">ພະແນກ</span>
                        <button @click="activeDept = 'all'" :class="activeDept === 'all' ? 'font-bold' : ''" :style="activeDept === 'all'
                                    ? 'background:#7C4D0F; color:white; border-color:#7C4D0F;'
                                    : 'background:white; color:#6B7280; border-color:rgba(200,149,58,0.2);'"
                            class="px-2.5 py-1 rounded-full border transition-all" style="font-size:11px;">
                            ທັງໝົດ
                            <span x-text="personnel.length" class="ml-0.5 opacity-70" style="font-size:10px;"></span>
                        </button>
                        @foreach($departments as $dept)
                            @php $dCount = $personnel->where('department_id', $dept->id)->count(); @endphp
                            <button @click="activeDept = '{{ $dept->id }}'"
                                :class="activeDept === '{{ $dept->id }}' ? 'font-bold' : ''" :style="activeDept === '{{ $dept->id }}'
                                        ? 'background:#7C4D0F; color:white; border-color:#7C4D0F;'
                                        : 'background:white; color:#6B7280; border-color:rgba(200,149,58,0.2);'"
                                class="px-2.5 py-1 rounded-full border transition-all" style="font-size:11px;">
                                {{ $dept->name }}
                                <span class="ml-0.5 opacity-70" style="font-size:10px;">{{ $dCount }}</span>
                            </button>
                        @endforeach
                    </div>
                @endif

                {{-- Affiliation filter --}}
                <div x-show="hasAffiliationData" class="flex gap-1.5 items-center sm:ml-auto">
                    <span class="hidden sm:inline"
                        style="font-size:9px; font-weight:700; letter-spacing:0.15em; color:rgba(124,77,15,0.45); text-transform:uppercase;">ແຕ່ລະສັງກັດ</span>

                    <button @click="activeAffiliation = 'all'" :style="activeAffiliation === 'all'
                                ? 'background:#3D3525; color:white; border-color:#3D3525;'
                                : 'background:white; color:#6B7280; border-color:rgba(200,149,58,0.2);'"
                        class="px-2.5 py-1 rounded-full border transition-all font-medium" style="font-size:11px;">
                        ທັງໝົດ
                    </button>

                    <button @click="activeAffiliation = 'central'" :style="activeAffiliation === 'central'
                                ? 'background:#3730A3; color:white; border-color:#3730A3;'
                                : 'background:white; color:#6B7280; border-color:rgba(200,149,58,0.2);'"
                        class="px-2.5 py-1 rounded-full border transition-all flex items-center gap-1"
                        style="font-size:11px;">
                        <span class="material-symbols-outlined" style="font-size:11px;">location_city</span>
                        ສູນກາງ
                        <span x-text="countByAffiliation('central')"
                            :class="activeAffiliation === 'central' ? 'opacity-70' : 'opacity-50'"
                            style="font-size:9px;"></span>
                    </button>

                    <button @click="activeAffiliation = 'provincial'" :style="activeAffiliation === 'provincial'
                                ? 'background:#3D5A47; color:white; border-color:#3D5A47;'
                                : 'background:white; color:#6B7280; border-color:rgba(200,149,58,0.2);'"
                        class="px-2.5 py-1 rounded-full border transition-all flex items-center gap-1"
                        style="font-size:11px;">
                        <span class="material-symbols-outlined" style="font-size:11px;">map</span>
                        ແຂວງ
                        <span x-text="countByAffiliation('provincial')"
                            :class="activeAffiliation === 'provincial' ? 'opacity-70' : 'opacity-50'"
                            style="font-size:9px;"></span>
                    </button>
                </div>

                {{-- Results count + Clear all --}}
                <div class="flex items-center gap-2">
                    <span style="font-size:11px; color:rgba(124,77,15,0.5);">
                        <span x-text="filtered.length" class="font-bold" style="color:#7C4D0F;"></span> ອົງ/ທ່ານ
                    </span>
                    <button x-show="hasActiveFilters" @click="clearAll()"
                        class="flex items-center gap-0.5 transition-colors"
                        style="font-size:10px; color:rgba(124,77,15,0.55); text-decoration:underline; text-underline-offset:2px;"
                        onmouseover="this.style.color='rgba(124,77,15,0.9)'"
                        onmouseout="this.style.color='rgba(124,77,15,0.55)'">
                        <span class="material-symbols-outlined" style="font-size:12px;">restart_alt</span>
                        ລ້າງທຸກ
                    </button>
                </div>
            </div>
        </div>

        {{-- ── Province Sub-filter panel ───────────────────────── --}}
        <div x-show="activeAffiliation === 'provincial' && provincesWithData.length > 0"
            x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100"
            x-transition:leave-end="opacity-0" class="border-b"
            style="background:#EFF7F2; border-color:rgba(61,90,71,0.18);">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2.5 flex flex-wrap gap-1.5 items-center">
                <span class="material-symbols-outlined shrink-0" style="font-size:13px; color:#3D5A47;">pin_drop</span>

                {{-- All provinces --}}
                <button @click="activeProvince = 'all'" :style="activeProvince === 'all'
                            ? 'background:#3D5A47; color:white; border-color:#3D5A47;'
                            : 'background:white; color:#3D5A47; border-color:rgba(61,90,71,0.25);'"
                    class="px-2.5 py-1 rounded-full border transition-all flex items-center gap-1"
                    style="font-size:11px; font-weight:600;">
                    ທຸກແຂວງ
                    <span x-text="countByAffiliation('provincial')"
                        :class="activeProvince === 'all' ? 'opacity-65' : 'opacity-50'" style="font-size:9px;"></span>
                </button>

                {{-- Per-province buttons --}}
                <template x-for="prov in provincesWithData" :key="prov">
                    <button @click="activeProvince = prov" :style="activeProvince === prov
                                ? 'background:#3D5A47; color:white; border-color:#3D5A47;'
                                : 'background:white; color:#3D5A47; border-color:rgba(61,90,71,0.25);'"
                        class="px-2.5 py-1 rounded-full border transition-all flex items-center gap-1"
                        style="font-size:11px;">
                        <span x-text="prov.replace('ແຂວງ', '').trim()"></span>
                        <span x-text="countByProvince(prov)" :class="activeProvince === prov ? 'opacity-65' : 'opacity-50'"
                            style="font-size:9px;"></span>
                    </button>
                </template>

                <button x-show="activeProvince !== 'all'" @click="activeProvince = 'all'"
                    class="ml-auto flex items-center gap-0.5 transition-colors"
                    style="font-size:10px; color:rgba(61,90,71,0.55); text-decoration:underline; text-underline-offset:2px;"
                    onmouseover="this.style.color='rgba(61,90,71,0.9)'" onmouseout="this.style.color='rgba(61,90,71,0.55)'">
                    <span class="material-symbols-outlined" style="font-size:12px;">close</span> ລ້າງ
                </button>
            </div>
        </div>

        {{-- ── Card Grid ─────────────────────────────────────── --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-16">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <template x-for="person in paginated" :key="person.id">

                    <div class="group flex flex-col rounded-xl overflow-hidden border transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
                        :style="person.gender === 'monk'
                            ? 'background:#FFFCF4; border-color:rgba(200,149,58,0.3);'
                            : 'background:white; border-color:rgba(0,0,0,0.08);'"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0">

                        {{-- Top accent stripe — colour taxonomy by type --}}
                        <div class="h-[3px] shrink-0" :style="person.gender === 'monk'
                                ? 'background: linear-gradient(to right, #C8953A, #E8B455, #C8953A)'
                                : person.gender === 'female'
                                ? 'background: linear-gradient(to right, #7C3AED, #A78BFA)'
                                : 'background: linear-gradient(to right, #475569, #64748B)'">
                        </div>

                        <div class="flex flex-col flex-1 p-4 gap-3.5">

                            {{-- ── Avatar + Identity ─── --}}
                            <div class="flex items-start gap-3.5">

                                {{-- Photo --}}
                                <div class="shrink-0 relative">
                                    <template x-if="person.photo_url">
                                        <img :src="person.photo_url" :alt="person.name" loading="lazy"
                                            class="w-[68px] h-[68px] rounded-xl object-cover transition-shadow duration-300"
                                            :style="person.gender === 'monk'
                                                ? 'box-shadow: 0 0 0 2px #C8953A, 0 0 0 4px rgba(200,149,58,0.15);'
                                                : 'box-shadow: 0 1px 4px rgba(0,0,0,0.1);'" />
                                    </template>
                                    <template x-if="!person.photo_url">
                                        <div class="w-[68px] h-[68px] rounded-xl flex items-center justify-center"
                                            :style="person.gender === 'monk'
                                                ? 'background:linear-gradient(135deg,#FEF3C7,#FDE68A); box-shadow:0 0 0 2px #C8953A,0 0 0 4px rgba(200,149,58,0.12);'
                                                : person.gender === 'female'
                                                ? 'background:linear-gradient(135deg,#EDE9FE,#DDD6FE); box-shadow:0 1px 4px rgba(0,0,0,0.08);'
                                                : 'background:linear-gradient(135deg,#F1F5F9,#E2E8F0); box-shadow:0 1px 4px rgba(0,0,0,0.08);'">
                                            <span class="material-symbols-outlined"
                                                :style="person.gender === 'monk' ? 'font-size:30px;color:#C8953A;' : 'font-size:30px;color:#94A3B8;'">
                                                person
                                            </span>
                                        </div>
                                    </template>
                                </div>

                                {{-- Name · Position · Chips --}}
                                <div class="flex-1 min-w-0 pt-0.5">
                                    <p x-show="person.title" x-text="person.title"
                                        class="font-bold uppercase tracking-widest truncate" :style="person.gender === 'monk'
                                           ? 'font-size:9px; color:#C8953A; margin-bottom:2px;'
                                           : 'font-size:9px; color:#94A3B8; margin-bottom:2px;'"></p>
                                    <a :href="'{{ url('/committee') }}/' + person.id"
                                        x-text="person.name"
                                        class="block font-bold leading-snug transition-colors duration-200 hover:underline"
                                        style="text-decoration:none;"
                                        :style="person.gender === 'monk'
                                            ? 'font-size:15px; color:#3D2A12; line-height:1.3;'
                                            : 'font-size:15px; color:#1C1208; line-height:1.3;'"></a>
                                    <p x-show="person.position" x-text="person.position" class="line-clamp-3 mt-1"
                                        style="font-size:12px; line-height:1.5; color:#5A6A80;"></p>

                                    {{-- Tags --}}
                                    <div class="flex flex-wrap gap-1 mt-2">
                                        <template x-if="person.dept_name">
                                            <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded"
                                                style="font-size:10px; font-weight:500; background:rgba(0,0,0,0.04); color:#6B7280;">
                                                <span class="material-symbols-outlined"
                                                    style="font-size:10px;">corporate_fare</span>
                                                <span x-text="person.dept_name"></span>
                                            </span>
                                        </template>
                                        <template x-if="person.affiliation_level === 'central'">
                                            <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded"
                                                style="font-size:10px; font-weight:700; background:#EEF2FF; color:#3730A3;">
                                                <span class="material-symbols-outlined"
                                                    style="font-size:10px;">location_city</span>
                                                ສູນກາງ
                                            </span>
                                        </template>
                                        <template x-if="person.affiliation_level === 'provincial'">
                                            <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded"
                                                style="font-size:10px; font-weight:700; background:#EFF7F2; color:#3D5A47;">
                                                <span class="material-symbols-outlined" style="font-size:10px;">map</span>
                                                <span x-text="person.affiliation_province || 'ແຂວງ'"></span>
                                            </span>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            {{-- ── Detail section (conditional) ─── --}}
                            <template
                                x-if="person.bio || person.education || person.current_temple"
                                <div>
                                    <div class="mb-3"
                                        :style="person.gender === 'monk' ? 'border-top:1px solid rgba(200,149,58,0.2);' : 'border-top:1px solid rgba(0,0,0,0.06);'">
                                    </div>

                                    {{-- Bio --}}
                                    <p x-show="person.bio" x-text="person.bio" class="line-clamp-2 mb-2"
                                        style="font-size:11.5px; line-height:1.55; color:#64748B;"></p>

                                    {{-- Education --}}
                                    <div x-show="person.education" class="flex items-start gap-1.5 mb-2">
                                        <span class="material-symbols-outlined shrink-0"
                                            style="font-size:14px; color:#7C4D0F; margin-top:1px;">school</span>
                                        <div class="min-w-0">
                                            <p style="font-size:9px; font-weight:700; color:rgba(124,77,15,0.5); letter-spacing:0.12em; text-transform:uppercase; margin-bottom:1px;">ລະດັບການສຶກສາ</p>
                                            <span x-text="person.education" class="line-clamp-2"
                                                style="font-size:11.5px; color:#5A6A80;"></span>
                                        </div>
                                    </div>

                                    {{-- Current temple — shown for ALL personnel --}}
                                    <div x-show="person.current_temple" class="flex items-start gap-1.5 mb-2">
                                        <span class="material-symbols-outlined shrink-0"
                                            :style="person.gender === 'monk' ? 'font-size:14px; color:#C8953A; margin-top:1px;' : 'font-size:14px; color:#7C4D0F; margin-top:1px;'">temple_buddhist</span>
                                        <div class="min-w-0">
                                            <p style="font-size:9px; font-weight:700; color:rgba(124,77,15,0.5); letter-spacing:0.12em; text-transform:uppercase; margin-bottom:1px;">ວັດຢູ່ປະຈຸບັນ</p>
                                            <span x-text="person.current_temple" class="line-clamp-2"
                                                style="font-size:11.5px; color:#5A6A80;"></span>
                                        </div>
                                    </div>

                                </div>
                            </template>

                            {{-- ── View detail link ─── --}}
                            <div class="mt-auto">
                                <a :href="'{{ url('/committee') }}/' + person.id"
                                    class="flex items-center gap-1 mt-3 font-bold transition-all duration-200"
                                    style="font-size:11px; text-decoration:none;"
                                    :style="person.gender === 'monk' ? 'color:#C8953A;' : 'color:#7C4D0F;'"
                                    onmouseover="this.querySelector('.arr').style.transform='translateX(3px)'"
                                    onmouseout="this.querySelector('.arr').style.transform='translateX(0)'">
                                    ເບິ່ງລາຍລະອຽດ
                                    <span class="material-symbols-outlined arr" style="font-size:13px; transition:transform 0.15s;">arrow_forward</span>
                                </a>
                            </div>

                            {{-- ── Contact row (pinned to bottom) ─── --}}
                            <div x-show="person.email || person.phone || person.facebook">
                                <div class="pt-3"
                                    :style="person.gender === 'monk' ? 'border-top:1px solid rgba(200,149,58,0.18);' : 'border-top:1px solid rgba(0,0,0,0.06);'">
                                    <p style="font-size:9px; font-weight:700; color:rgba(124,77,15,0.45); letter-spacing:0.12em; text-transform:uppercase; margin-bottom:6px;">ຂໍ້ມູນຕິດຕໍ່</p>
                                    <div class="space-y-1.5">
                                        <a x-show="person.phone" :href="'tel:' + (person.phone || '')" @click.stop
                                            class="flex items-center gap-2 transition-colors"
                                            style="text-decoration:none;"
                                            onmouseover="this.querySelector('span.val').style.textDecoration='underline'"
                                            onmouseout="this.querySelector('span.val').style.textDecoration='none'">
                                            <span class="material-symbols-outlined shrink-0"
                                                style="font-size:15px; color:#C8953A;">phone</span>
                                            <span class="val font-semibold" x-text="person.phone"
                                                style="font-size:13px; color:#7C4D0F;"></span>
                                        </a>
                                        <a x-show="person.email" :href="'mailto:' + (person.email || '')" @click.stop
                                            class="flex items-center gap-2 transition-colors min-w-0"
                                            style="text-decoration:none;"
                                            onmouseover="this.querySelector('span.val').style.textDecoration='underline'"
                                            onmouseout="this.querySelector('span.val').style.textDecoration='none'">
                                            <span class="material-symbols-outlined shrink-0"
                                                style="font-size:15px; color:#C8953A;">mail</span>
                                            <span class="val truncate" x-text="person.email"
                                                style="font-size:11.5px; color:#7C4D0F; min-width:0;"></span>
                                        </a>
                                        <a x-show="person.facebook" :href="person.facebook || '#'" target="_blank"
                                            rel="noopener noreferrer" @click.stop
                                            class="flex items-center gap-2 transition-colors"
                                            style="text-decoration:none;"
                                            onmouseover="this.querySelector('span.val').style.textDecoration='underline'"
                                            onmouseout="this.querySelector('span.val').style.textDecoration='none'">
                                            <span class="material-symbols-outlined shrink-0"
                                                style="font-size:15px; color:#C8953A;">share</span>
                                            <span class="val font-semibold"
                                                style="font-size:12px; color:#7C4D0F;">Facebook</span>
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </template>
            </div>

            {{-- ── Empty State ──────────────────────────────── --}}
            <div x-show="filtered.length === 0" x-transition.opacity class="text-center py-20">
                <div class="w-18 h-18 mx-auto mb-6 rounded-full flex items-center justify-center"
                    style="width:72px; height:72px; background:rgba(200,149,58,0.07);">
                    <span class="material-symbols-outlined"
                        style="font-size:38px; color:rgba(200,149,58,0.3);">manage_search</span>
                </div>
                <p class="font-semibold mb-1.5" style="font-size:16px; color:#3D2A12;">ບໍ່ພົບຜູ້ໃດ</p>
                <p class="mb-6" style="font-size:13px; color:#94A3B8;">ລອງປ່ຽນຄຳຄົ້ນຫາ ຫຼື ລ້າງ filter ທີ່ໃຊ້ຢູ່</p>
                <button @click="clearAll()"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold transition-all"
                    style="font-size:13px; background:#7C4D0F; color:white;" onmouseover="this.style.background='#5D3908'"
                    onmouseout="this.style.background='#7C4D0F'">
                    <span class="material-symbols-outlined" style="font-size:15px;">restart_alt</span>
                    ລ້າງທຸກ filter
                </button>
            </div>

            {{-- ── Pagination ────────────────────────────── --}}
            <div x-show="totalPages > 1 && filtered.length > 0" class="mt-10 flex justify-center">
                <div class="inline-flex items-center gap-1 px-3 py-2 bg-white rounded-2xl border shadow-sm"
                    style="border-color:rgba(200,149,58,0.18);">

                    <button @click="if(currentPage>1) currentPage--" :disabled="currentPage===1"
                        :class="currentPage===1 ? 'opacity-25 cursor-not-allowed' : 'cursor-pointer'"
                        class="w-8 h-8 flex items-center justify-center rounded-full transition-all"
                        :style="currentPage===1 ? '' : ''"
                        onmouseover="if(!this.disabled) this.style.background='rgba(200,149,58,0.1)'"
                        onmouseout="this.style.background='transparent'" style="color:#7C4D0F;">
                        <span class="material-symbols-outlined" style="font-size:16px; line-height:1;">chevron_left</span>
                    </button>

                    <template x-for="(page, idx) in pageNumbers" :key="idx">
                        <div class="contents">
                            <button x-show="page !== '...'" @click="currentPage = page"
                                :style="currentPage === page ? 'background:#7C4D0F; color:white;' : 'color:#4B3A2A;'"
                                class="w-8 h-8 flex items-center justify-center rounded-full text-sm font-semibold transition-all"
                                onmouseover="if(this.style.background !== 'rgb(124, 77, 15)') this.style.background='rgba(200,149,58,0.1)'"
                                onmouseout="if(this.style.background !== 'rgb(124, 77, 15)') this.style.background='transparent'">
                                <span x-text="page"></span>
                            </button>
                            <span x-show="page === '...'"
                                class="w-8 h-8 flex items-center justify-center text-sm select-none"
                                style="color:rgba(0,0,0,0.2);">···</span>
                        </div>
                    </template>

                    <button @click="if(currentPage<totalPages) currentPage++" :disabled="currentPage===totalPages"
                        :class="currentPage===totalPages ? 'opacity-25 cursor-not-allowed' : 'cursor-pointer'"
                        class="w-8 h-8 flex items-center justify-center rounded-full transition-all"
                        onmouseover="if(!this.disabled) this.style.background='rgba(200,149,58,0.1)'"
                        onmouseout="this.style.background='transparent'" style="color:#7C4D0F;">
                        <span class="material-symbols-outlined" style="font-size:16px; line-height:1;">chevron_right</span>
                    </button>

                    <div class="ml-1 relative">
                        <select x-model.number="perPage"
                            class="pl-3 pr-6 py-1.5 rounded-full border text-xs appearance-none cursor-pointer focus:outline-none"
                            style="border-color:rgba(200,149,58,0.25); color:#7C4D0F; background:white;">
                            <template x-for="opt in perPageOptions" :key="opt">
                                <option :value="opt" x-text="opt + ' / ໜ້າ'"></option>
                            </template>
                        </select>
                        <span
                            class="pointer-events-none absolute right-1.5 top-1/2 -translate-y-1/2 material-symbols-outlined"
                            style="font-size:11px; color:#7C4D0F;">expand_more</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection