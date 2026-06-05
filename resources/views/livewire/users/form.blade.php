<div>
    {{-- Header --}}
    <div class="flex items-center gap-4 mb-8 animate-fade-in">
        <a href="{{ route('users.index') }}"
           class="w-10 h-10 rounded-xl flex items-center justify-center text-on-surface-variant border border-outline-variant hover:bg-surface-container transition-all">
            <span class="material-symbols-outlined text-xl">arrow_back</span>
        </a>
        <div>
            <h2 class="text-headline-lg text-on-surface mb-0.5">
                {{ $editMode ? 'ແກ້ໄຂຜູ້ໃຊ້' : 'ເພີ່ມຜູ້ໃຊ້ໃໝ່' }}
            </h2>
            <p class="text-body-md text-on-surface-variant">
                {{ $editMode ? 'Edit User' : 'Create New User' }}
            </p>
        </div>
    </div>

    <form wire:submit="save" enctype="multipart/form-data"
          class="max-w-2xl space-y-6 animate-fade-in">

        {{-- ── Avatar ── --}}
        <div class="glass-card p-6 rounded-2xl border border-outline-variant">
            <h3 class="text-label-md font-bold text-on-surface-variant uppercase mb-5 flex items-center gap-2">
                <span class="material-symbols-outlined text-base">photo_camera</span>
                ຮູບໂປຣໄຟລ / Avatar
            </h3>

            <div class="flex items-center gap-6">
                {{-- Preview --}}
                <div class="shrink-0">
                    @if ($avatar)
                        <img src="{{ $avatar->temporaryUrl() }}" alt="Preview"
                             class="w-24 h-24 rounded-full object-cover border-4 border-primary/20 shadow" />
                    @elseif ($existing_avatar_url)
                        <img src="{{ Storage::url($existing_avatar_url) }}" alt="Avatar"
                             class="w-24 h-24 rounded-full object-cover border-4 border-primary/20 shadow" />
                    @else
                        <div class="w-24 h-24 rounded-full bg-primary/10 border-4 border-primary/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary text-4xl">person</span>
                        </div>
                    @endif
                </div>

                {{-- Upload --}}
                <div class="flex-1">
                    <label class="cursor-pointer inline-flex items-center gap-2 px-4 py-2.5 border-2 border-dashed border-outline-variant rounded-xl text-body-md text-on-surface-variant hover:border-primary hover:text-primary transition-all">
                        <span class="material-symbols-outlined text-xl">upload</span>
                        ອັບໂຫຼດຮູບ / Upload
                        <input type="file" wire:model="avatar" accept="image/*" class="sr-only" />
                    </label>
                    <p class="text-xs text-on-surface-variant mt-2">JPG, PNG, WEBP · ສູງສຸດ 2MB</p>
                    @error('avatar')
                        <p class="text-xs text-error mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- ── Basic Info ── --}}
        <div class="glass-card p-6 rounded-2xl border border-outline-variant">
            <h3 class="text-label-md font-bold text-on-surface-variant uppercase mb-5 flex items-center gap-2">
                <span class="material-symbols-outlined text-base">badge</span>
                ຂໍ້ມູນພື້ນຖານ / Basic Info
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Name --}}
                <div class="sm:col-span-2">
                    <label class="form-label">ຊື່ / Name <span class="text-error">*</span></label>
                    <input type="text" wire:model="name"
                           placeholder="ຊື່ ແລະ ນາມສະກຸນ"
                           class="form-input @error('name') border-error @enderror" />
                    @error('name')
                        <p class="form-error mt-1 flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs">error</span>{{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="form-label">ອີເມວ / Email <span class="text-error">*</span></label>
                    <div class="flex items-center border border-outline-variant rounded-lg bg-white focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/10 transition-all overflow-hidden @error('email') border-error @enderror">
                        <span class="material-symbols-outlined text-on-surface-variant text-[18px] pl-3 pr-2 shrink-0 select-none">email</span>
                        <input type="email" wire:model="email"
                               placeholder="user@temple.org"
                               autocomplete="off"
                               class="flex-1 py-2 pr-3 text-sm text-on-surface bg-transparent focus:outline-none min-w-0" />
                    </div>
                    @error('email')
                        <p class="form-error mt-1 flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs">error</span>{{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Phone --}}
                <div>
                    <label class="form-label">ໂທລະສັບ / Phone</label>
                    <div class="flex items-center border border-outline-variant rounded-lg bg-white focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/10 transition-all overflow-hidden @error('phone') border-error @enderror">
                        <span class="material-symbols-outlined text-on-surface-variant text-[18px] pl-3 pr-2 shrink-0 select-none">phone</span>
                        <input type="text" wire:model="phone"
                               placeholder="020 xxxx xxxx"
                               class="flex-1 py-2 pr-3 text-sm text-on-surface bg-transparent focus:outline-none min-w-0" />
                    </div>
                    @error('phone')
                        <p class="form-error mt-1 flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs">error</span>{{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- ── Role & Status ── --}}
        <div class="glass-card p-6 rounded-2xl border border-outline-variant">
            <h3 class="text-label-md font-bold text-on-surface-variant uppercase mb-5 flex items-center gap-2">
                <span class="material-symbols-outlined text-base">admin_panel_settings</span>
                ສິດທິ & ສະຖານະ / Role & Status
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Role --}}
                <div>
                    <label class="form-label">Role <span class="text-error">*</span></label>
                    <div class="relative">
                        <select wire:model="role"
                                class="form-input appearance-none pr-10 @error('role') border-error @enderror">
                            @foreach (\App\Models\User::ROLES as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-base pointer-events-none">expand_more</span>
                    </div>
                    @error('role')
                        <p class="form-error mt-1 flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs">error</span>{{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Active Status --}}
                <div class="flex flex-col justify-end">
                    <label class="form-label">ສະຖານະ / Status</label>
                    <label class="flex items-center gap-3 cursor-pointer group mt-2">
                        <div x-data="{ checked: @entangle('is_active') }"
                             class="relative shrink-0">
                            <input type="checkbox" wire:model="is_active" class="sr-only peer" />
                            <div class="w-12 h-6 bg-outline-variant rounded-full peer peer-checked:bg-primary transition-colors"></div>
                            <div class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full shadow transition-transform peer-checked:translate-x-6"></div>
                        </div>
                        <div>
                            <span class="text-body-md font-bold {{ $is_active ? 'text-green-700' : 'text-on-surface-variant' }}">
                                {{ $is_active ? 'ໃຊ້ງານ (Active)' : 'ປິດໃຊ້ງານ (Inactive)' }}
                            </span>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        {{-- ── Password ── --}}
        <div class="glass-card p-6 rounded-2xl border border-outline-variant">
            <h3 class="text-label-md font-bold text-on-surface-variant uppercase mb-1 flex items-center gap-2">
                <span class="material-symbols-outlined text-base">lock</span>
                ລະຫັດຜ່ານ / Password
            </h3>
            @if ($editMode)
                <p class="text-xs text-on-surface-variant mb-5">ຖ້າບໍ່ຕ້ອງການປ່ຽນລະຫັດ ໃຫ້ຂ້ວ່າງໄວ້ / Leave blank to keep current password</p>
            @else
                <p class="text-xs text-on-surface-variant mb-5">ຕ້ອງໃສ່ລະຫັດຜ່ານ ຢ່າງໜ້ອຍ 8 ຕົວ / Required, minimum 8 characters</p>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Password --}}
                <div x-data="{ show: false }">
                    <label class="form-label">
                        ລະຫັດຜ່ານ
                        @if (!$editMode)<span class="text-error">*</span>@endif
                    </label>
                    <div class="flex items-center border border-outline-variant rounded-lg bg-white focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/10 transition-all overflow-hidden @error('password') border-error @enderror">
                        <span class="material-symbols-outlined text-on-surface-variant text-[18px] pl-3 pr-2 shrink-0 select-none">lock</span>
                        <input :type="show ? 'text' : 'password'"
                               wire:model="password"
                               placeholder="{{ $editMode ? '(ບໍ່ປ່ຽນ)' : '••••••••' }}"
                               autocomplete="new-password"
                               class="flex-1 py-2 text-sm text-on-surface bg-transparent focus:outline-none min-w-0" />
                        <button type="button" @click="show = !show"
                                class="pr-3 text-on-surface-variant hover:text-on-surface transition-colors shrink-0">
                            <span class="material-symbols-outlined text-[18px]" x-text="show ? 'visibility_off' : 'visibility'">visibility</span>
                        </button>
                    </div>
                    @error('password')
                        <p class="form-error mt-1 flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs">error</span>{{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div x-data="{ show: false }">
                    <label class="form-label">
                        ຢືນຢັນລະຫັດ
                        @if (!$editMode)<span class="text-error">*</span>@endif
                    </label>
                    <div class="flex items-center border border-outline-variant rounded-lg bg-white focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/10 transition-all overflow-hidden">
                        <span class="material-symbols-outlined text-on-surface-variant text-[18px] pl-3 pr-2 shrink-0 select-none">lock_reset</span>
                        <input :type="show ? 'text' : 'password'"
                               wire:model="password_confirmation"
                               placeholder="{{ $editMode ? '(ບໍ່ປ່ຽນ)' : '••••••••' }}"
                               autocomplete="new-password"
                               class="flex-1 py-2 text-sm text-on-surface bg-transparent focus:outline-none min-w-0" />
                        <button type="button" @click="show = !show"
                                class="pr-3 text-on-surface-variant hover:text-on-surface transition-colors shrink-0">
                            <span class="material-symbols-outlined text-[18px]" x-text="show ? 'visibility_off' : 'visibility'">visibility</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Actions ── --}}
        <div class="flex items-center justify-end gap-3 pb-16 sm:pb-0">
            <a href="{{ route('users.index') }}"
               class="px-6 py-2.5 border border-outline-variant rounded-xl text-body-md text-on-surface-variant hover:bg-surface-container transition-all btn-press flex items-center gap-2">
                <span class="material-symbols-outlined text-base">cancel</span>
                ຍົກເລີກ / Cancel
            </a>
            <button type="submit"
                    wire:loading.attr="disabled" wire:loading.class="opacity-70"
                    class="px-8 py-2.5 bg-primary hover:bg-primary-container text-white font-bold rounded-xl transition-all shadow-md btn-press flex items-center gap-2">
                <span wire:loading.remove class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-base">{{ $editMode ? 'save' : 'person_add' }}</span>
                    {{ $editMode ? 'ບັນທຶກ / Save' : 'ເພີ່ມຜູ້ໃຊ້ / Create' }}
                </span>
                <span wire:loading class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    ກຳລັງບັນທຶກ...
                </span>
            </button>
        </div>
    </form>
</div>
