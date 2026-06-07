<div x-data="{
    gender: @entangle('gender'),
    titleSuggestionsLo: {
        monk: ['ພຣະອາຈານໃຫຍ່', 'ພຣະ', 'ພຣະອາຈານ', 'ເຈົ້າອາວາດ', 'ສົມເດັດ'],
        male: ['ທ່ານ', 'ດຣ.', 'ທ່ານ ດຣ.'],
        female: ['ທ່ານນາງ', 'ນາງສາວ', 'ດຣ.'],
    },
    titleSuggestionsEn: {
        monk: ['Phra Ajan', 'Phra', 'Most Ven.', 'Ven.'],
        male: ['Mr.', 'Dr.'],
        female: ['Mrs.', 'Ms.', 'Dr.'],
    },
    showTitleDropdownLo: false,
    showTitleDropdownEn: false,
    get suggestedTitlesLo() { return this.titleSuggestionsLo[this.gender] || []; },
    get suggestedTitlesEn() { return this.titleSuggestionsEn[this.gender] || []; },
    selectTitleLo(title) { $wire.set('title_lo', title); this.showTitleDropdownLo = false; },
    selectTitleEn(title) { $wire.set('title_en', title); this.showTitleDropdownEn = false; },
}">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8 animate-fade-in">
        <div>
            <h2 class="text-headline-lg text-on-surface mb-1">
                {{ $editMode ? 'ແກ້ໄຂຄະນະກັມມາທິການ' : 'ເພີ່ມຄະນະກັມມາທິການໃໝ່' }}
            </h2>
            <p class="text-body-md text-on-surface-variant">
                {{ $editMode ? 'Edit Personnel Record' : 'Add New Personnel' }}
            </p>
        </div>
        <a href="{{ route('personnel.index') }}"
            class="px-4 py-2 border border-outline-variant rounded-lg text-body-md font-bold text-on-surface-variant hover:bg-surface-container transition-all flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            {{ __('messages.back_to_list') }}
        </a>
    </div>

    <form wire:submit="save" class="space-y-8">
        <!-- ═══════════════════════════════════════════════
             Section 1: Person Type & Gender
             ═══════════════════════════════════════════════ -->
        <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm animate-fade-in">
            <h3 class="text-headline-sm text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">badge</span>
                ປະເພດບຸກຄົນ / Person Type
            </h3>

            <div class="flex gap-4">
                <!-- Monk -->
                <label class="flex-1 cursor-pointer">
                    <input type="radio" wire:model.blur="gender" value="monk" class="hidden peer" />
                    <div
                        class="peer-checked:border-badge-monk peer-checked:bg-amber-50 border-2 border-outline-variant rounded-xl p-4 text-center transition-all hover:border-badge-monk/50">
                        <span class="material-symbols-outlined text-3xl mb-2 block"
                            :class="gender === 'monk' ? 'text-badge-monk' : 'text-on-surface-variant'">temple_buddhist</span>
                        <span class="font-bold block text-body-md">ພຣະ</span>
                        <span class="text-xs text-on-surface-variant">Monk</span>
                    </div>
                </label>

                <!-- Male -->
                <label class="flex-1 cursor-pointer">
                    <input type="radio" wire:model.blur="gender" value="male" class="hidden peer" />
                    <div
                        class="peer-checked:border-badge-male peer-checked:bg-slate-50 border-2 border-outline-variant rounded-xl p-4 text-center transition-all hover:border-badge-male/50">
                        <span class="material-symbols-outlined text-3xl mb-2 block"
                            :class="gender === 'male' ? 'text-badge-male' : 'text-on-surface-variant'">person</span>
                        <span class="font-bold block text-body-md">ທ່ານ</span>
                        <span class="text-xs text-on-surface-variant">Male</span>
                    </div>
                </label>

                <!-- Female -->
                <label class="flex-1 cursor-pointer">
                    <input type="radio" wire:model.blur="gender" value="female" class="hidden peer" />
                    <div
                        class="peer-checked:border-badge-female peer-checked:bg-violet-50 border-2 border-outline-variant rounded-xl p-4 text-center transition-all hover:border-badge-female/50">
                        <span class="material-symbols-outlined text-3xl mb-2 block"
                            :class="gender === 'female' ? 'text-badge-female' : 'text-on-surface-variant'">person</span>
                        <span class="font-bold block text-body-md">ທ່ານນາງ</span>
                        <span class="text-xs text-on-surface-variant">Female</span>
                    </div>
                </label>
            </div>
            @error('gender') <p class="form-error mt-2">{{ $message }}</p> @enderror
        </div>

        <!-- ═══════════════════════════════════════════════
             Section 2: Name & Title (Bilingual)
             ═══════════════════════════════════════════════ -->
        <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm animate-fade-in">
            <h3 class="text-headline-sm text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">person</span>
                ຊື່ ແລະ ຄຳນຳໜ້າ / Name & Title
            </h3>

            <!-- Title / Honorific -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="relative bilingual-required">
                    <label class="form-label">
                        ຄຳນຳໜ້າ <span class="text-xs text-on-surface-variant">(ລາວ)</span>
                    </label>
                    <input type="text" wire:model.blur="title_lo" @focus="showTitleDropdownLo = true"
                        @click.away="showTitleDropdownLo = false" placeholder="ພຣະ / ທ່ານ / ທ່ານນາງ ..."
                        class="form-input" />
                    <!-- Title suggestions dropdown -->
                    <div x-show="showTitleDropdownLo && suggestedTitlesLo.length > 0" x-transition
                        class="absolute z-10 w-full mt-1 bg-white border border-outline-variant rounded-lg shadow-lg">
                        <template x-for="title in suggestedTitlesLo" :key="title">
                            <button type="button" @click="selectTitleLo(title)"
                                class="w-full text-left px-3 py-2 hover:bg-surface-container-low text-body-md transition-colors"
                                x-text="title"></button>
                        </template>
                    </div>
                </div>
                <div class="relative">
                    <label class="form-label">
                        Honorific <span class="text-xs text-on-surface-variant">(English)</span>
                    </label>
                    <input type="text" wire:model.blur="title_en" @focus="showTitleDropdownEn = true"
                        @click.away="showTitleDropdownEn = false" placeholder="Phra / Mr. / Mrs. ..."
                        class="form-input" />
                    <div x-show="showTitleDropdownEn && suggestedTitlesEn.length > 0" x-transition
                        class="absolute z-10 w-full mt-1 bg-white border border-outline-variant rounded-lg shadow-lg">
                        <template x-for="title in suggestedTitlesEn" :key="title">
                            <button type="button" @click="selectTitleEn(title)"
                                class="w-full text-left px-3 py-2 hover:bg-surface-container-low text-body-md transition-colors"
                                x-text="title"></button>
                        </template>
                    </div>
                </div>
            </div>

            <!-- First Name -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="bilingual-required">
                    <label class="form-label">ຊື່ <span class="text-xs text-on-surface-variant">(ລາວ)</span></label>
                    <input type="text" wire:model.blur="first_name_lo" placeholder="ຊື່..." class="form-input" />
                </div>
                <div>
                    <label class="form-label">First Name <span
                            class="text-xs text-on-surface-variant">(English)</span></label>
                    <input type="text" wire:model.blur="first_name_en" placeholder="First name..." class="form-input" />
                </div>
            </div>

            <!-- Last Name -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="bilingual-required">
                    <label class="form-label">ນາມສະກຸນ <span
                            class="text-xs text-on-surface-variant">(ລາວ)</span></label>
                    <input type="text" wire:model.blur="last_name_lo" placeholder="ນາມສະກຸນ..." class="form-input" />
                </div>
                <div>
                    <label class="form-label">Last Name <span
                            class="text-xs text-on-surface-variant">(English)</span></label>
                    <input type="text" wire:model.blur="last_name_en" placeholder="Last name..." class="form-input" />
                </div>
            </div>

            <!-- Full Name (auto-generated) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bilingual-required">
                    <label class="form-label">
                        ຊື່ເຕັມ <span class="text-xs text-on-surface-variant">(ລາວ)</span>
                        <span class="text-error">*</span>
                    </label>
                    <input type="text" wire:model="name_lo" placeholder="ພຣະ / ທ່ານ / ທ່ານນາງ ..."
                        class="form-input bg-surface-container-low" />
                    @error('name_lo') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">
                        Full Name <span class="text-xs text-on-surface-variant">(English)</span>
                    </label>
                    <input type="text" wire:model="name_en" placeholder="Phra / Mr. / Mrs. ..." class="form-input" />
                </div>
            </div>
        </div>

        <!-- ═══════════════════════════════════════════════
             Section 3: Position & Department
             ═══════════════════════════════════════════════ -->
        <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm animate-fade-in">
            <h3 class="text-headline-sm text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">work</span>
                ຕຳແໜ່ງ ແລະ ພະແນກ / Position & Department
            </h3>

            <!-- Position -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="bilingual-required">
                    <label class="form-label">
                        ຕຳແໜ່ງ <span class="text-xs text-on-surface-variant">(ລາວ)</span>
                        <span class="text-error">*</span>
                    </label>
                    <input type="text" wire:model="position_lo" placeholder="ຕຳແໜ່ງ..." class="form-input" />
                    @error('position_lo') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Position <span
                            class="text-xs text-on-surface-variant">(English)</span></label>
                    <input type="text" wire:model="position_en" placeholder="Position..." class="form-input" />
                </div>
            </div>

            <!-- Department -->
            <div class="max-w-md">
                <label class="form-label">ພະແນກ / Department</label>
                <select wire:model="department_id" class="form-input">
                    <option value="">— ເລືອກພະແນກ / Select Department —</option>
                    @foreach ($departments as $dept)
                        <option value="{{ $dept->id }}">
                            {{ $dept->name_lo }} {{ $dept->name_en ? '(' . $dept->name_en . ')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Term of Service -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="form-label">ປີເລີ່ມຕົ້ນ / Term Start</label>
                    <input type="number" wire:model="term_start" placeholder="2024" min="1900" max="2100"
                        class="form-input" />
                </div>
                <div>
                    <label class="form-label">ປີສິ້ນສຸດ / Term End</label>
                    <input type="number" wire:model="term_end" placeholder="2028" min="1900" max="2100"
                        class="form-input" />
                </div>
            </div>
        </div>

        <!-- ═══════════════════════════════════════════════
             Section 4: Monk-Specific Fields (Conditional)
             ═══════════════════════════════════════════════ -->
        <div x-show="gender === 'monk'" x-transition class="monk-section rounded-xl p-6 shadow-sm animate-fade-in">
            <h3 class="text-headline-sm text-amber-800 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-badge-monk">temple_buddhist</span>
                🏛️ ຂໍ້ມູນພຣະ / Monk Information
            </h3>

            <!-- Temple -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="form-label">ວັດທີ່ຢູ່ <span
                            class="text-xs text-on-surface-variant">(ລາວ)</span></label>
                    <input type="text" wire:model="current_temple_lo" placeholder="ຊື່ວັດ..." class="form-input" />
                </div>
                <div>
                    <label class="form-label">Current Temple <span
                            class="text-xs text-on-surface-variant">(English)</span></label>
                    <input type="text" wire:model="current_temple_en" placeholder="Temple name..." class="form-input" />
                </div>
            </div>

            <!-- Ordination & Pansa -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">ວັນທີ່ອຸປະສົມ (Ordination Date)</label>
                    <input type="date" wire:model.blur="date_of_ordination" class="form-input" />
                    @if ($date_of_ordination)
                        @php
                            $ord = \Carbon\Carbon::parse($date_of_ordination);
                            $buddhistYear = $ord->year + 543;
                        @endphp
                        <p class="text-xs text-on-surface-variant mt-1 flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs">calendar_month</span>
                            ປີພຸດທະສັກກະລາດ: <strong class="text-primary ml-1">ພ.ສ. {{ $buddhistYear }}</strong>
                        </p>
                    @endif
                </div>

                <div>
                    <label class="form-label flex items-center gap-2">
                        ພັນສາ (Pansa / Vassa)
                        @if ($pansaAutoCalc !== null)
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded-full">
                                <span class="material-symbols-outlined" style="font-size:10px">auto_awesome</span>
                                ອັດຕະໂນມັດ
                            </span>
                        @endif
                    </label>

                    <input type="number" wire:model="pansa" min="0" max="100" placeholder="0" class="form-input" />

                    {{-- Auto-calculated hint --}}
                    @if ($pansaAutoCalc !== null)
                        <div class="mt-2 flex items-start gap-2 p-2.5 bg-amber-50 border border-amber-200 rounded-lg">
                            <span class="material-symbols-outlined text-amber-600 text-base shrink-0 mt-0.5">calculate</span>
                            <div class="text-xs leading-relaxed">
                                <p class="font-bold text-amber-800">
                                    ຄຳນວນອັດຕະໂນມັດ: <span class="text-lg text-amber-600">{{ $pansaAutoCalc }}</span> ພັນສາ
                                </p>
                                <p class="text-amber-700 mt-0.5">
                                    @if ($pansaAutoCalc === 0)
                                        ຍັງບໍ່ຄົບພັນສາທຳອິດ (ນ້ອຍກວ່າ 1 ພັນສາ)
                                    @else
                                        ນັບຈາກ ເຂົ້າພັນສາ ຫາ ອອກພັນສາ ປີ {{ \Carbon\Carbon::today()->year }}
                                    @endif
                                </p>
                                <p class="text-amber-600/70 mt-0.5">
                                    ສາມາດແກ້ໄຂດ້ວຍຕົນເອງໄດ້ຖ້າບໍ່ຖືກຕ້ອງ
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- ═══════════════════════════════════════════════
             Section 5: Contact & Photo
             ═══════════════════════════════════════════════ -->
        <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm animate-fade-in">
            <h3 class="text-headline-sm text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">contact_phone</span>
                ການຕິດຕໍ່ / Contact Information
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="form-label">ອີເມວ / Email</label>
                    <input type="email" wire:model="email" placeholder="email@example.com" class="form-input" />
                    @error('email') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">ໂທລະສັບ / Phone</label>
                    <input type="text" wire:model="phone" placeholder="+856 20 xxxxxxxx" class="form-input" />
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Facebook</label>
                <input type="text" wire:model="facebook" placeholder="https://facebook.com/..." class="form-input" />
            </div>

            <!-- Photo Upload -->
            <div>
                <label class="form-label">ຮູບພາບ / Photo</label>
                <div class="flex items-center gap-6">
                    <!-- Preview -->
                    <div
                        class="w-24 h-24 rounded-full overflow-hidden bg-surface-container-low border-2 border-outline-variant flex items-center justify-center">
                        @if ($photo)
                            <img src="{{ $photo->temporaryUrl() }}" alt="Preview" class="w-full h-full object-cover" />
                        @elseif ($existing_photo_url)
                            <img src="{{ Storage::url($existing_photo_url) }}" alt="Current"
                                class="w-full h-full object-cover" />
                        @else
                            <span class="material-symbols-outlined text-4xl text-on-surface-variant/30">add_a_photo</span>
                        @endif
                    </div>
                    <div>
                        <input type="file" wire:model="photo" accept="image/*" class="block w-full text-sm text-on-surface-variant
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-lg file:border-0
                                      file:text-sm file:font-bold
                                      file:bg-primary/10 file:text-primary
                                      hover:file:bg-primary/20
                                      cursor-pointer" />
                        <p class="text-xs text-on-surface-variant mt-1">JPG, PNG, WebP · Max 2MB</p>
                        @error('photo') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══════════════════════════════════════════════
             Section 6: Location & Bio
             ═══════════════════════════════════════════════ -->
        <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm animate-fade-in">
            <h3 class="text-headline-sm text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">location_on</span>
                ທີ່ຢູ່ ແລະ ຊີວະປະຫວັດ / Location & Biography
            </h3>

            <!-- Birth Village -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="form-label">ບ້ານເກີດ <span
                            class="text-xs text-on-surface-variant">(ລາວ)</span></label>
                    <input type="text" wire:model="birth_village_lo" placeholder="ບ້ານ..." class="form-input" />
                </div>
                <div>
                    <label class="form-label">Birth Village <span
                            class="text-xs text-on-surface-variant">(English)</span></label>
                    <input type="text" wire:model="birth_village_en" placeholder="Village..." class="form-input" />
                </div>
            </div>

            <!-- District & Province -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="form-label">ເມືອງ / District</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="text" wire:model="district_lo" placeholder="ເມືອງ (ລາວ)" class="form-input" />
                        <input type="text" wire:model="district_en" placeholder="District (EN)" class="form-input" />
                    </div>
                </div>
                <div>
                    <label class="form-label">ແຂວງ / Province</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="text" wire:model="province_lo" placeholder="ແຂວງ (ລາວ)" class="form-input" />
                        <input type="text" wire:model="province_en" placeholder="Province (EN)" class="form-input" />
                    </div>
                </div>
            </div>

            <!-- Date of Birth -->
            <div class="max-w-md mb-4">
                <label class="form-label">ວັນເດືອນປີເກີດ / Date of Birth</label>
                <input type="date" wire:model="date_of_birth" class="form-input" />
            </div>

            <!-- Education -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="form-label">ການສຶກສາ <span
                            class="text-xs text-on-surface-variant">(ລາວ)</span></label>
                    <input type="text" wire:model="education_lo" placeholder="ການສຶກສາ..." class="form-input" />
                </div>
                <div>
                    <label class="form-label">Education <span
                            class="text-xs text-on-surface-variant">(English)</span></label>
                    <input type="text" wire:model="education_en" placeholder="Education..." class="form-input" />
                </div>
            </div>

            <!-- Bio -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">ຊີວະປະຫວັດຫຍໍ້ <span
                            class="text-xs text-on-surface-variant">(ລາວ)</span></label>
                    <textarea wire:model="bio_lo" rows="4" placeholder="ຊີວະປະຫວັດ..." class="form-input"></textarea>
                </div>
                <div>
                    <label class="form-label">Biography <span
                            class="text-xs text-on-surface-variant">(English)</span></label>
                    <textarea wire:model="bio_en" rows="4" placeholder="Short biography..."
                        class="form-input"></textarea>
                </div>
            </div>
        </div>

        <!-- ═══════════════════════════════════════════════
             Section 7: Display Control
             ═══════════════════════════════════════════════ -->
        <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm animate-fade-in">
            <h3 class="text-headline-sm text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">tune</span>
                ການຕັ້ງຄ່າ / Settings
            </h3>

            <div class="flex items-center gap-8">
                <div>
                    <label class="form-label">ລຳດັບ / Sort Order</label>
                    <input type="number" wire:model="sort_order" min="0" class="form-input w-24" />
                </div>
                <div class="flex items-center gap-3">
                    <label class="toggle-switch">
                        <input type="checkbox" wire:model="is_active" />
                        <span class="toggle-slider"></span>
                    </label>
                    <span class="form-label">{{ __('messages.active') }}</span>
                </div>
            </div>
        </div>

        <!-- ═══════════════════════════════════════════════
             Submit Buttons
             ═══════════════════════════════════════════════ -->
        <div class="flex justify-end gap-4 animate-fade-in">
            <a href="{{ route('personnel.index') }}"
                class="px-6 py-3 border border-outline-variant rounded-lg text-body-md font-bold text-on-surface-variant hover:bg-surface-container transition-all btn-press">
                {{ __('messages.cancel') }}
            </a>
            <button type="submit"
                class="px-8 py-3 bg-primary text-white rounded-lg font-bold flex items-center gap-2 hover:bg-primary-container transition-all shadow-md btn-press"
                wire:loading.attr="disabled" wire:loading.class="opacity-50">
                <span wire:loading.remove>
                    <span class="material-symbols-outlined text-sm">save</span>
                    {{ $editMode ? __('messages.update') : __('messages.save') }}
                </span>
                <span wire:loading class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"
                            fill="none"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z">
                        </path>
                    </svg>
                    {{ __('messages.saving') }}
                </span>
            </button>
        </div>
    </form>
</div>