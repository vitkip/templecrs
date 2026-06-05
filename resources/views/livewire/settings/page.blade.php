<div x-data="{ tab: @entangle('activeTab') }">

    {{-- Page Header --}}
    <div class="flex justify-between items-end mb-8 animate-fade-in">
        <div>
            <h2 class="text-headline-lg text-on-surface mb-1">ການຕັ້ງຄ່າ</h2>
            <p class="text-body-md text-on-surface-variant">System Settings</p>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session('settings_message'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 flex items-center gap-3 animate-fade-in">
            <span class="material-symbols-outlined text-green-600">check_circle</span>
            <span class="text-body-md">{{ session('settings_message') }}</span>
        </div>
    @endif

    @if (session('settings_error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 flex items-center gap-3 animate-fade-in">
            <span class="material-symbols-outlined text-red-500">error</span>
            <span class="text-body-md">{{ session('settings_error') }}</span>
        </div>
    @endif

    {{-- Tab Navigation --}}
    <div class="flex gap-1 mb-8 bg-surface-container-low p-1 rounded-xl w-fit border border-outline-variant animate-fade-in">
        <button type="button"
                @click="tab = 'organization'; $wire.set('activeTab', 'organization')"
                :class="tab === 'organization' ? 'bg-white text-primary shadow-sm font-bold' : 'text-on-surface-variant hover:text-on-surface'"
                class="flex items-center gap-2 px-5 py-2.5 rounded-lg text-body-md transition-all">
            <span class="material-symbols-outlined text-base" :class="tab === 'organization' ? 'filled' : ''">account_balance</span>
            ອົງການ / Organization
        </button>
        <button type="button"
                @click="tab = 'system'; $wire.set('activeTab', 'system')"
                :class="tab === 'system' ? 'bg-white text-primary shadow-sm font-bold' : 'text-on-surface-variant hover:text-on-surface'"
                class="flex items-center gap-2 px-5 py-2.5 rounded-lg text-body-md transition-all">
            <span class="material-symbols-outlined text-base" :class="tab === 'system' ? 'filled' : ''">tune</span>
            ລະບົບ / System
        </button>
        <button type="button"
                @click="tab = 'departments'; $wire.set('activeTab', 'departments')"
                :class="tab === 'departments' ? 'bg-white text-primary shadow-sm font-bold' : 'text-on-surface-variant hover:text-on-surface'"
                class="flex items-center gap-2 px-5 py-2.5 rounded-lg text-body-md transition-all">
            <span class="material-symbols-outlined text-base" :class="tab === 'departments' ? 'filled' : ''">category</span>
            ພາກສ່ວນ / Departments
            <span class="bg-primary/10 text-primary text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $departments->count() }}</span>
        </button>
    </div>

    {{-- ═══════════════════════════════════════════════
         TAB 1: Organization Info
         ═══════════════════════════════════════════════ --}}
    <div x-show="tab === 'organization'" x-transition class="animate-fade-in">
        <form wire:submit="saveOrganization" class="space-y-6">

            {{-- Logo + Basic Info --}}
            <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm">
                <h3 class="text-headline-sm text-on-surface mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">account_balance</span>
                    ຂໍ້ມູນອົງການ / Organization Information
                </h3>

                {{-- Logo Upload --}}
                <div class="flex items-center gap-6 mb-6 pb-6 border-b border-outline-variant">
                    <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-outline-variant flex items-center justify-center bg-surface-container-low shrink-0">
                        @if ($org_logo)
                            <img src="{{ $org_logo->temporaryUrl() }}" alt="Logo preview" class="w-full h-full object-cover" />
                        @elseif ($existing_logo_url)
                            <img src="{{ Storage::url($existing_logo_url) }}" alt="Logo" class="w-full h-full object-cover" />
                        @else
                            <span class="material-symbols-outlined text-4xl text-on-surface-variant/30">account_balance</span>
                        @endif
                    </div>
                    <div>
                        <p class="form-label mb-2">ໂລໂກ້ອົງການ / Organization Logo</p>
                        <input type="file" wire:model="org_logo" accept="image/*"
                               class="block text-sm text-on-surface-variant
                                      file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                                      file:text-sm file:font-bold file:bg-primary/10 file:text-primary
                                      hover:file:bg-primary/20 cursor-pointer" />
                        <p class="text-xs text-on-surface-variant mt-1">JPG, PNG, SVG · ສູງສຸດ 2MB</p>
                        @error('org_logo') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Name --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="bilingual-required">
                        <label class="form-label">ຊື່ອົງການ <span class="text-xs text-on-surface-variant">(ລາວ)</span> <span class="text-error">*</span></label>
                        <input type="text" wire:model="org_name_lo" placeholder="ອົງການພຣະພຸດທະສາສະໜາ..." class="form-input" />
                        @error('org_name_lo') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Organization Name <span class="text-xs text-on-surface-variant">(English)</span></label>
                        <input type="text" wire:model="org_name_en" placeholder="Buddhist Organization..." class="form-input" />
                        @error('org_name_en') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Address --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="form-label">ທີ່ຢູ່ <span class="text-xs text-on-surface-variant">(ລາວ)</span></label>
                        <textarea wire:model="org_address_lo" rows="3" placeholder="ທີ່ຢູ່..." class="form-input"></textarea>
                        @error('org_address_lo') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Address <span class="text-xs text-on-surface-variant">(English)</span></label>
                        <textarea wire:model="org_address_en" rows="3" placeholder="Address..." class="form-input"></textarea>
                        @error('org_address_en') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Contact --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="form-label">ໂທລະສັບ / Phone</label>
                        <div class="flex items-center border border-outline-variant rounded-lg bg-white focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/10 transition-all overflow-hidden">
                            <span class="material-symbols-outlined text-on-surface-variant text-[18px] pl-3 pr-2 shrink-0 select-none">phone</span>
                            <input type="text" wire:model="org_phone" placeholder="+856 21 xxxxxx"
                                   class="flex-1 py-2 pr-3 text-sm text-on-surface bg-transparent focus:outline-none min-w-0" />
                        </div>
                        @error('org_phone') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">ອີເມວ / Email</label>
                        <div class="flex items-center border border-outline-variant rounded-lg bg-white focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/10 transition-all overflow-hidden">
                            <span class="material-symbols-outlined text-on-surface-variant text-[18px] pl-3 pr-2 shrink-0 select-none">email</span>
                            <input type="email" wire:model="org_email" placeholder="info@example.org"
                                   class="flex-1 py-2 pr-3 text-sm text-on-surface bg-transparent focus:outline-none min-w-0" />
                        </div>
                        @error('org_email') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">ເວັບໄຊ / Website</label>
                        <div class="flex items-center border border-outline-variant rounded-lg bg-white focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/10 transition-all overflow-hidden">
                            <span class="material-symbols-outlined text-on-surface-variant text-[18px] pl-3 pr-2 shrink-0 select-none">language</span>
                            <input type="text" wire:model="org_website" placeholder="https://..."
                                   class="flex-1 py-2 pr-3 text-sm text-on-surface bg-transparent focus:outline-none min-w-0" />
                        </div>
                        @error('org_website') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Established Year --}}
                <div class="max-w-xs">
                    <label class="form-label">ປີກໍ່ຕັ້ງ / Established Year</label>
                    <input type="number" wire:model="org_established_year" placeholder="ຕົວຢ່າງ: 1975" min="1800" max="2100" class="form-input" />
                    @error('org_established_year') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="px-8 py-3 bg-primary text-white rounded-lg font-bold flex items-center gap-2 hover:bg-primary-container transition-all shadow-md btn-press"
                        wire:loading.attr="disabled" wire:loading.class="opacity-60">
                    <span wire:loading.remove class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">save</span>
                        ບັນທຶກ / Save
                    </span>
                    <span wire:loading class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        ກຳລັງບັນທຶກ...
                    </span>
                </button>
            </div>
        </form>
    </div>

    {{-- ═══════════════════════════════════════════════
         TAB 2: System Settings
         ═══════════════════════════════════════════════ --}}
    <div x-show="tab === 'system'" x-transition class="animate-fade-in">
        <form wire:submit="saveSystem" class="space-y-6">

            <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm">
                <h3 class="text-headline-sm text-on-surface mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">tune</span>
                    ການຕັ້ງຄ່າລະບົບ / System Preferences
                </h3>

                <div class="space-y-6 max-w-lg">
                    {{-- Default Language --}}
                    <div>
                        <label class="form-label mb-2">ພາສາເລີ່ມຕົ້ນ / Default Language</label>
                        <div class="flex gap-3">
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" wire:model="default_locale" value="lo" class="hidden peer" />
                                <div class="peer-checked:border-primary peer-checked:bg-primary/5 border-2 border-outline-variant rounded-xl p-4 text-center transition-all hover:border-primary/40">
                                    <span class="text-2xl mb-1 block">🇱🇦</span>
                                    <span class="font-bold text-body-md block">ລາວ</span>
                                    <span class="text-xs text-on-surface-variant">Lao</span>
                                </div>
                            </label>
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" wire:model="default_locale" value="en" class="hidden peer" />
                                <div class="peer-checked:border-primary peer-checked:bg-primary/5 border-2 border-outline-variant rounded-xl p-4 text-center transition-all hover:border-primary/40">
                                    <span class="text-2xl mb-1 block">🇬🇧</span>
                                    <span class="font-bold text-body-md block">English</span>
                                    <span class="text-xs text-on-surface-variant">English</span>
                                </div>
                            </label>
                        </div>
                        @error('default_locale') <p class="form-error mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Per Page --}}
                    <div>
                        <label class="form-label">ຈຳນວນລາຍການຕໍ່ໜ້າ / Items Per Page</label>
                        <p class="text-xs text-on-surface-variant mb-2">ຈຳນວນລາຍການທີ່ສະແດງໃນຕາຕະລາງ</p>
                        <select wire:model="per_page" class="form-input max-w-xs">
                            @foreach ([10, 15, 20, 25, 50] as $n)
                                <option value="{{ $n }}">{{ $n }} ລາຍການ</option>
                            @endforeach
                        </select>
                        @error('per_page') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Show English Names --}}
                    <div class="flex items-center justify-between p-4 bg-surface-container-low rounded-xl border border-outline-variant">
                        <div>
                            <p class="text-body-md font-bold">ສະແດງຊື່ພາສາອັງກິດ / Show English Names</p>
                            <p class="text-xs text-on-surface-variant">ສະແດງຊື່ ແລະ ຕຳແໜ່ງທັງສອງພາສາໃນຕາຕະລາງ</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" wire:model="show_english_names" />
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="px-8 py-3 bg-primary text-white rounded-lg font-bold flex items-center gap-2 hover:bg-primary-container transition-all shadow-md btn-press"
                        wire:loading.attr="disabled" wire:loading.class="opacity-60">
                    <span wire:loading.remove class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">save</span>
                        ບັນທຶກ / Save
                    </span>
                    <span wire:loading class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        ກຳລັງບັນທຶກ...
                    </span>
                </button>
            </div>
        </form>
    </div>

    {{-- ═══════════════════════════════════════════════
         TAB 3: Department Management
         ═══════════════════════════════════════════════ --}}
    <div x-show="tab === 'departments'" x-transition class="animate-fade-in">

        {{-- Department Form (inline) --}}
        @if ($showDeptForm)
            <div class="bg-white rounded-xl border-2 border-primary/30 p-6 shadow-sm mb-6 animate-fade-in">
                <h3 class="text-headline-sm text-on-surface mb-5 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">{{ $editDeptId ? 'edit' : 'add_circle' }}</span>
                    {{ $editDeptId ? 'ແກ້ໄຂພາກສ່ວນ / Edit Department' : 'ເພີ່ມພາກສ່ວນໃໝ່ / New Department' }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="bilingual-required">
                        <label class="form-label">ຊື່ພາກສ່ວນ <span class="text-xs text-on-surface-variant">(ລາວ)</span> <span class="text-error">*</span></label>
                        <input type="text" wire:model="dept_name_lo" placeholder="ຊື່ພາກສ່ວນ..." class="form-input" />
                        @error('dept_name_lo') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Department Name <span class="text-xs text-on-surface-variant">(English)</span></label>
                        <input type="text" wire:model="dept_name_en" placeholder="Department name..." class="form-input" />
                        @error('dept_name_en') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="form-label">ລາຍລະອຽດ <span class="text-xs text-on-surface-variant">(ລາວ)</span></label>
                        <textarea wire:model="dept_description_lo" rows="2" placeholder="ລາຍລະອຽດ..." class="form-input"></textarea>
                    </div>
                    <div>
                        <label class="form-label">Description <span class="text-xs text-on-surface-variant">(English)</span></label>
                        <textarea wire:model="dept_description_en" rows="2" placeholder="Description..." class="form-input"></textarea>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label class="form-label">ຫົວໜ້າ / Department Head</label>
                        <select wire:model="dept_head_id" class="form-input">
                            <option value="">— ບໍ່ໄດ້ຕັ້ງ / None —</option>
                            @foreach ($personnelList as $person)
                                <option value="{{ $person->id }}">{{ $person->name_lo }} — {{ $person->position_lo }}</option>
                            @endforeach
                        </select>
                        @error('dept_head_id') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">ລຳດັບ / Sort Order</label>
                        <input type="number" wire:model="dept_sort_order" min="0" max="999" class="form-input" />
                    </div>
                    <div class="flex items-end pb-1">
                        <div class="flex items-center gap-3">
                            <label class="toggle-switch">
                                <input type="checkbox" wire:model="dept_is_active" />
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="form-label">ໃຊ້ງານ / Active</span>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="button" wire:click="saveDept"
                            class="px-6 py-2.5 bg-primary text-white rounded-lg font-bold flex items-center gap-2 hover:bg-primary-container transition-all btn-press"
                            wire:loading.attr="disabled">
                        <span class="material-symbols-outlined text-sm">save</span>
                        {{ $editDeptId ? 'ອັບເດດ / Update' : 'ເພີ່ມ / Add' }}
                    </button>
                    <button type="button" wire:click="cancelDeptForm"
                            class="px-6 py-2.5 border border-outline-variant rounded-lg text-body-md font-bold text-on-surface-variant hover:bg-surface-container transition-all btn-press">
                        ຍົກເລີກ / Cancel
                    </button>
                </div>
            </div>
        @endif

        {{-- Department List Header --}}
        <div class="flex justify-between items-center mb-4">
            <div>
                <h3 class="text-headline-sm text-on-surface">ລາຍການພາກສ່ວນ / Departments</h3>
                <p class="text-xs text-on-surface-variant">ທັງໝົດ {{ $departments->count() }} ພາກສ່ວນ</p>
            </div>
            @if (!$showDeptForm)
                <button type="button" wire:click="openAddDept"
                        class="bg-primary text-white px-5 py-2.5 rounded-lg font-bold flex items-center gap-2 hover:bg-primary-container transition-all shadow-md btn-press">
                    <span class="material-symbols-outlined text-sm">add</span>
                    ເພີ່ມພາກສ່ວນ / Add Department
                </button>
            @endif
        </div>

        {{-- Department Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-outline-variant overflow-hidden">
            <table class="w-full border-collapse text-left">
                <thead>
                    <tr class="bg-secondary text-white text-label-md">
                        <th class="p-3 w-10">#</th>
                        <th class="p-3">ຊື່ / Name</th>
                        <th class="p-3">ຫົວໜ້າ / Head</th>
                        <th class="p-3 text-center">ບຸກຄະລາກອນ</th>
                        <th class="p-3 text-center">ໃຊ້ງານ</th>
                        <th class="p-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($departments as $dept)
                        <tr class="border-b border-outline-variant table-row-hover {{ !$dept->is_active ? 'opacity-50' : '' }}"
                            wire:key="dept-{{ $dept->id }}">
                            <td class="p-3 text-on-surface-variant text-xs font-mono">{{ $dept->sort_order }}</td>
                            <td class="p-3">
                                <div class="flex flex-col">
                                    <span class="font-bold text-on-surface text-body-md">{{ $dept->name_lo }}</span>
                                    @if ($dept->name_en)
                                        <span class="text-xs text-on-surface-variant">{{ $dept->name_en }}</span>
                                    @endif
                                    @if ($dept->description_lo)
                                        <span class="text-xs text-on-surface-variant/70 mt-0.5 line-clamp-1">{{ $dept->description_lo }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="p-3">
                                @if ($dept->head)
                                    <div class="flex flex-col">
                                        <span class="text-body-md font-medium">{{ $dept->head->name_lo }}</span>
                                        <span class="text-xs text-on-surface-variant">{{ $dept->head->position_lo }}</span>
                                    </div>
                                @else
                                    <span class="text-on-surface-variant/50 text-xs">— ບໍ່ໄດ້ຕັ້ງ —</span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                <span class="bg-primary/10 text-primary text-xs font-bold px-2 py-0.5 rounded-full">
                                    {{ $dept->active_count }} / {{ $dept->total_count }}
                                </span>
                            </td>
                            <td class="p-3">
                                <div class="flex justify-center">
                                    <label class="toggle-switch">
                                        <input type="checkbox"
                                               {{ $dept->is_active ? 'checked' : '' }}
                                               wire:click="toggleDeptActive({{ $dept->id }})" />
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>
                            </td>
                            <td class="p-3 text-right">
                                <button type="button" wire:click="editDept({{ $dept->id }})"
                                        class="p-1 hover:text-primary transition-colors inline-block"
                                        title="ແກ້ໄຂ">
                                    <span class="material-symbols-outlined text-lg">edit</span>
                                </button>
                                <button type="button"
                                        wire:click="deleteDept({{ $dept->id }})"
                                        wire:confirm="ທ່ານແນ່ໃຈບໍ່ທີ່ຕ້ອງການລຶບພາກສ່ວນນີ້?"
                                        class="p-1 hover:text-error transition-colors inline-block"
                                        title="ລຶບ">
                                    <span class="material-symbols-outlined text-lg">delete</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center text-on-surface-variant">
                                <span class="material-symbols-outlined text-5xl mb-4 block opacity-30">category</span>
                                <p class="text-lg">ຍັງບໍ່ມີພາກສ່ວນ / No departments yet</p>
                                <button type="button" wire:click="openAddDept"
                                        class="text-primary hover:underline mt-2 inline-block text-body-md">
                                    ເພີ່ມພາກສ່ວນທຳອິດ / Add first department
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Stats summary --}}
        <div class="mt-4 flex gap-4 text-xs text-on-surface-variant">
            <span>ໃຊ້ງານ: <strong class="text-green-600">{{ $departments->where('is_active', true)->count() }}</strong></span>
            <span>ບໍ່ໃຊ້ງານ: <strong class="text-red-500">{{ $departments->where('is_active', false)->count() }}</strong></span>
            <span>ທັງໝົດ: <strong class="text-on-surface">{{ $departments->count() }}</strong></span>
        </div>
    </div>

</div>
