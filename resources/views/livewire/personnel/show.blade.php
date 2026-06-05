<div>
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8 animate-fade-in">
        <div>
            <h2 class="text-headline-lg text-on-surface mb-1">{{ $personnel->name_lo }}</h2>
            @if ($personnel->name_en)
                <p class="text-body-md text-on-surface-variant">{{ $personnel->name_en }}</p>
            @endif
        </div>
        <div class="flex gap-3">
            <a href="{{ route('personnel.edit', $personnel->id) }}"
               class="px-4 py-2 bg-primary text-white rounded-lg font-bold flex items-center gap-2 hover:bg-primary-container transition-all btn-press">
                <span class="material-symbols-outlined text-sm">edit</span>
                {{ __('messages.edit') }}
            </a>
            <a href="{{ route('personnel.index') }}"
               class="px-4 py-2 border border-outline-variant rounded-lg text-body-md font-bold text-on-surface-variant hover:bg-surface-container transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                {{ __('messages.back_to_list') }}
            </a>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6">
        <!-- Left Column: Photo & Contact -->
        <div class="col-span-12 lg:col-span-4 space-y-6">
            <!-- Photo Card -->
            <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm text-center animate-fade-in">
                <div class="w-32 h-32 rounded-full mx-auto mb-4 overflow-hidden border-4 border-outline-variant/30">
                    @if ($personnel->photo_url)
                        <img src="{{ Storage::url($personnel->photo_url) }}" alt="{{ $personnel->name_lo }}" class="w-full h-full object-cover" />
                    @else
                        <div class="w-full h-full bg-surface-container-low flex items-center justify-center">
                            <span class="material-symbols-outlined text-5xl text-on-surface-variant/30">person</span>
                        </div>
                    @endif
                </div>

                <!-- Gender Badge -->
                @php $badge = $personnel->gender_badge; @endphp
                <span class="{{ $badge['class'] }} px-3 py-1 rounded-full text-xs font-bold uppercase">
                    {{ $badge['label'] }}
                </span>

                <h3 class="text-headline-sm text-on-surface mt-3">{{ $personnel->name_lo }}</h3>
                @if ($personnel->name_en)
                    <p class="text-body-md text-on-surface-variant">{{ $personnel->name_en }}</p>
                @endif

                <p class="text-body-md text-primary font-bold mt-2">{{ $personnel->position_lo }}</p>
                @if ($personnel->position_en)
                    <p class="text-xs text-on-surface-variant">{{ $personnel->position_en }}</p>
                @endif

                <!-- Status -->
                <div class="mt-4 flex items-center justify-center gap-2">
                    <span class="w-2 h-2 rounded-full {{ $personnel->is_active ? 'bg-green-500' : 'bg-red-400' }}"></span>
                    <span class="text-xs font-bold {{ $personnel->is_active ? 'text-green-600' : 'text-red-500' }}">
                        {{ $personnel->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>

            <!-- Contact Card -->
            <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm animate-fade-in">
                <h4 class="text-label-md text-on-surface-variant uppercase tracking-wider mb-4">ການຕິດຕໍ່ / Contact</h4>

                @if ($personnel->email)
                    <div class="flex items-center gap-3 mb-3">
                        <span class="material-symbols-outlined text-primary text-lg">email</span>
                        <a href="mailto:{{ $personnel->email }}" class="text-body-md text-primary hover:underline">{{ $personnel->email }}</a>
                    </div>
                @endif

                @if ($personnel->phone)
                    <div class="flex items-center gap-3 mb-3">
                        <span class="material-symbols-outlined text-primary text-lg">phone</span>
                        <span class="text-body-md">{{ $personnel->phone }}</span>
                    </div>
                @endif

                @if ($personnel->facebook)
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-lg">link</span>
                        <a href="{{ $personnel->facebook }}" target="_blank" class="text-body-md text-primary hover:underline">Facebook</a>
                    </div>
                @endif

                @if (!$personnel->email && !$personnel->phone && !$personnel->facebook)
                    <p class="text-on-surface-variant text-sm">ບໍ່ມີຂໍ້ມູນ / No contact info</p>
                @endif
            </div>
        </div>

        <!-- Right Column: Details -->
        <div class="col-span-12 lg:col-span-8 space-y-6">
            <!-- Department & Term -->
            <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm animate-fade-in">
                <h4 class="text-label-md text-on-surface-variant uppercase tracking-wider mb-4">ພະແນກ ແລະ ຕຳແໜ່ງ / Department & Position</h4>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-on-surface-variant mb-1">ພະແນກ / Department</p>
                        <p class="text-body-md font-bold">
                            {{ $personnel->department ? $personnel->department->name_lo : '—' }}
                            @if ($personnel->department && $personnel->department->name_en)
                                <span class="text-on-surface-variant font-normal text-xs block">{{ $personnel->department->name_en }}</span>
                            @endif
                        </p>
                    </div>
                    @if ($personnel->term_start || $personnel->term_end)
                        <div>
                            <p class="text-xs text-on-surface-variant mb-1">ໄລຍະດຳລົງຕຳແໜ່ງ / Term</p>
                            <p class="text-body-md font-bold">{{ $personnel->term_start ?? '?' }} — {{ $personnel->term_end ?? 'ປັດຈຸບັນ' }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Monk Information -->
            @if ($personnel->isMonk())
                <div class="monk-section rounded-xl p-6 shadow-sm animate-fade-in">
                    <h4 class="text-label-md text-amber-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-badge-monk text-lg">temple_buddhist</span>
                        🏛️ ຂໍ້ມູນພຣະ / Monk Information
                    </h4>

                    <div class="grid grid-cols-2 gap-4">
                        @if ($personnel->current_temple_lo || $personnel->current_temple_en)
                            <div>
                                <p class="text-xs text-on-surface-variant mb-1">ວັດທີ່ຢູ່ / Current Temple</p>
                                <p class="text-body-md font-bold">{{ $personnel->current_temple_lo }}</p>
                                @if ($personnel->current_temple_en)
                                    <p class="text-xs text-on-surface-variant">{{ $personnel->current_temple_en }}</p>
                                @endif
                            </div>
                        @endif

                        @if ($personnel->date_of_ordination)
                            <div>
                                <p class="text-xs text-on-surface-variant mb-1">ວັນທີ່ອຸປະສົມ / Ordination Date</p>
                                <p class="text-body-md font-bold">{{ $personnel->date_of_ordination->format('d/m/Y') }}</p>
                            </div>
                        @endif

                        @if ($personnel->pansa !== null)
                            <div>
                                <p class="text-xs text-on-surface-variant mb-1">ພັນສາ / Pansa (Vassa)</p>
                                <p class="text-body-md font-bold text-badge-monk">{{ $personnel->pansa }} ພັນສາ</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Location -->
            @if ($personnel->birth_village_lo || $personnel->district_lo || $personnel->province_lo)
                <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm animate-fade-in">
                    <h4 class="text-label-md text-on-surface-variant uppercase tracking-wider mb-4">ທີ່ຢູ່ / Location</h4>

                    <div class="grid grid-cols-3 gap-4">
                        @if ($personnel->birth_village_lo)
                            <div>
                                <p class="text-xs text-on-surface-variant mb-1">ບ້ານ / Village</p>
                                <p class="text-body-md font-bold">{{ $personnel->birth_village_lo }}</p>
                                @if ($personnel->birth_village_en)
                                    <p class="text-xs text-on-surface-variant">{{ $personnel->birth_village_en }}</p>
                                @endif
                            </div>
                        @endif
                        @if ($personnel->district_lo)
                            <div>
                                <p class="text-xs text-on-surface-variant mb-1">ເມືອງ / District</p>
                                <p class="text-body-md font-bold">{{ $personnel->district_lo }}</p>
                                @if ($personnel->district_en)
                                    <p class="text-xs text-on-surface-variant">{{ $personnel->district_en }}</p>
                                @endif
                            </div>
                        @endif
                        @if ($personnel->province_lo)
                            <div>
                                <p class="text-xs text-on-surface-variant mb-1">ແຂວງ / Province</p>
                                <p class="text-body-md font-bold">{{ $personnel->province_lo }}</p>
                                @if ($personnel->province_en)
                                    <p class="text-xs text-on-surface-variant">{{ $personnel->province_en }}</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Bio & Education -->
            @if ($personnel->bio_lo || $personnel->bio_en || $personnel->education_lo)
                <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm animate-fade-in">
                    <h4 class="text-label-md text-on-surface-variant uppercase tracking-wider mb-4">ຊີວະປະຫວັດ / Biography</h4>

                    @if ($personnel->education_lo || $personnel->education_en)
                        <div class="mb-4">
                            <p class="text-xs text-on-surface-variant mb-1">ການສຶກສາ / Education</p>
                            <p class="text-body-md">{{ $personnel->education_lo }}</p>
                            @if ($personnel->education_en)
                                <p class="text-xs text-on-surface-variant mt-1">{{ $personnel->education_en }}</p>
                            @endif
                        </div>
                    @endif

                    @if ($personnel->bio_lo)
                        <div class="mb-3">
                            <p class="text-xs text-on-surface-variant mb-1">ຊີວະປະຫວັດ (ລາວ)</p>
                            <div class="text-body-md text-lao leading-relaxed">{{ $personnel->bio_lo }}</div>
                        </div>
                    @endif

                    @if ($personnel->bio_en)
                        <div>
                            <p class="text-xs text-on-surface-variant mb-1">Biography (English)</p>
                            <div class="text-body-md leading-relaxed">{{ $personnel->bio_en }}</div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
