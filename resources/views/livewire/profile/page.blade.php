<div>
    {{-- Header --}}
    <div class="flex items-center gap-4 mb-8 animate-fade-in">
        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center shrink-0 overflow-hidden">
            @if ($existing_avatar_url)
                <img src="{{ Storage::url($existing_avatar_url) }}" alt="{{ $name }}" class="w-full h-full object-cover" />
            @else
                <span class="material-symbols-outlined text-primary text-2xl">person</span>
            @endif
        </div>
        <div>
            <h2 class="text-headline-lg text-on-surface mb-0.5">ໂປຣໄຟລຂອງຂ້ອຍ</h2>
            <p class="text-body-md text-on-surface-variant">My Profile</p>
        </div>
    </div>

    {{-- Success Banners --}}
    @if ($profileUpdated)
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 flex items-center gap-3 animate-fade-in">
            <span class="material-symbols-outlined text-green-600">check_circle</span>
            <span>ອັບເດດຂໍ້ມູນສ່ວນຕົວສຳເລັດ / Profile updated successfully.</span>
        </div>
    @endif
    @if ($passwordUpdated)
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 flex items-center gap-3 animate-fade-in">
            <span class="material-symbols-outlined text-green-600">check_circle</span>
            <span>ປ່ຽນລະຫັດຜ່ານສຳເລັດ / Password changed successfully.</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Profile Card --}}
        <div class="lg:col-span-1">
            <div class="glass-card p-6 rounded-2xl border border-outline-variant text-center sticky top-24">
                {{-- Avatar preview --}}
                <div class="flex justify-center mb-4">
                    <div class="relative">
                        @if ($avatar)
                            <img src="{{ $avatar->temporaryUrl() }}" alt="Preview"
                                 class="w-28 h-28 rounded-full object-cover border-4 border-primary/20 shadow-lg" />
                        @elseif ($existing_avatar_url)
                            <img src="{{ Storage::url($existing_avatar_url) }}" alt="{{ $name }}"
                                 class="w-28 h-28 rounded-full object-cover border-4 border-primary/20 shadow-lg" />
                        @else
                            <div class="w-28 h-28 rounded-full bg-primary/10 border-4 border-primary/20 flex items-center justify-center">
                                <span class="material-symbols-outlined text-primary text-5xl">person</span>
                            </div>
                        @endif
                    </div>
                </div>

                <p class="text-title-md font-bold text-on-surface">{{ auth()->user()->name }}</p>
                <p class="text-body-sm text-on-surface-variant mt-0.5">{{ auth()->user()->email }}</p>

                {{-- Role badge --}}
                @php $badge = auth()->user()->role_badge; @endphp
                <span class="inline-block mt-3 px-3 py-1 rounded-full text-label-sm font-bold {{ $badge['class'] }}">
                    {{ $badge['label'] }}
                </span>

                @if (auth()->user()->phone)
                    <p class="flex items-center justify-center gap-1.5 text-body-sm text-on-surface-variant mt-4">
                        <span class="material-symbols-outlined text-base">phone</span>
                        {{ auth()->user()->phone }}
                    </p>
                @endif

                <p class="text-xs text-on-surface-variant mt-4 opacity-60">
                    ສະມາຊິກຕັ້ງແຕ່ / Member since {{ auth()->user()->created_at->format('d M Y') }}
                </p>
            </div>
        </div>

        {{-- Right: Edit Forms --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- ── Profile Info Form ── --}}
            <form wire:submit="saveProfile" enctype="multipart/form-data" class="animate-fade-in">

                {{-- Avatar Upload --}}
                <div class="glass-card p-6 rounded-2xl border border-outline-variant mb-6">
                    <h3 class="text-label-md font-bold text-on-surface-variant uppercase mb-5 flex items-center gap-2">
                        <span class="material-symbols-outlined text-base">photo_camera</span>
                        ຮູບໂປຣໄຟລ / Avatar
                    </h3>

                    <div class="flex items-center gap-6">
                        <div class="shrink-0">
                            @if ($avatar)
                                <img src="{{ $avatar->temporaryUrl() }}" alt="Preview"
                                     class="w-20 h-20 rounded-full object-cover border-4 border-primary/20 shadow" />
                            @elseif ($existing_avatar_url)
                                <img src="{{ Storage::url($existing_avatar_url) }}" alt="Avatar"
                                     class="w-20 h-20 rounded-full object-cover border-4 border-primary/20 shadow" />
                            @else
                                <div class="w-20 h-20 rounded-full bg-primary/10 border-4 border-primary/20 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-primary text-3xl">person</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <label class="cursor-pointer inline-flex items-center gap-2 px-4 py-2.5 border-2 border-dashed border-outline-variant rounded-xl text-body-md text-on-surface-variant hover:border-primary hover:text-primary transition-all">
                                <span class="material-symbols-outlined text-xl">upload</span>
                                ອັບໂຫຼດຮູບ / Upload
                                <input type="file" wire:model="avatar" accept="image/*" class="sr-only" />
                            </label>
                            <p class="text-xs text-on-surface-variant mt-2">JPG, PNG, WEBP · ສູງສຸດ 2MB</p>
                            @error('avatar')
                                <p class="text-xs text-error mt-1 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-xs">error</span>{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Basic Info --}}
                <div class="glass-card p-6 rounded-2xl border border-outline-variant mb-6">
                    <h3 class="text-label-md font-bold text-on-surface-variant uppercase mb-5 flex items-center gap-2">
                        <span class="material-symbols-outlined text-base">badge</span>
                        ຂໍ້ມູນສ່ວນຕົວ / Personal Info
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
                                       autocomplete="email"
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
                            <div class="flex items-center border border-outline-variant rounded-lg bg-white focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/10 transition-all overflow-hidden">
                                <span class="material-symbols-outlined text-on-surface-variant text-[18px] pl-3 pr-2 shrink-0 select-none">phone</span>
                                <input type="text" wire:model="phone"
                                       placeholder="020 xxxx xxxx"
                                       class="flex-1 py-2 pr-3 text-sm text-on-surface bg-transparent focus:outline-none min-w-0" />
                            </div>
                        </div>

                        {{-- Role (read-only) --}}
                        <div class="sm:col-span-2">
                            <label class="form-label">ສິດທິ / Role</label>
                            <div class="flex items-center border border-outline-variant rounded-lg bg-surface-container-low px-3 py-2 gap-2 cursor-not-allowed">
                                <span class="material-symbols-outlined text-on-surface-variant text-[18px] shrink-0">admin_panel_settings</span>
                                <span class="text-sm text-on-surface-variant">{{ auth()->user()->role_label }}</span>
                                <span class="ml-auto text-xs text-on-surface-variant/60">(ບໍ່ສາມາດປ່ຽນໄດ້)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                            wire:loading.attr="disabled" wire:loading.class="opacity-70"
                            class="px-8 py-2.5 bg-primary hover:bg-primary-container text-white font-bold rounded-xl transition-all shadow-md btn-press flex items-center gap-2">
                        <span wire:loading.remove wire:target="saveProfile" class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-base">save</span>
                            ບັນທຶກ / Save Profile
                        </span>
                        <span wire:loading wire:target="saveProfile" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            ກຳລັງບັນທຶກ...
                        </span>
                    </button>
                </div>
            </form>

            {{-- ── Change Password Form ── --}}
            <form wire:submit="savePassword" class="animate-fade-in">
                <div class="glass-card p-6 rounded-2xl border border-outline-variant mb-4">
                    <h3 class="text-label-md font-bold text-on-surface-variant uppercase mb-1 flex items-center gap-2">
                        <span class="material-symbols-outlined text-base">lock</span>
                        ປ່ຽນລະຫັດຜ່ານ / Change Password
                    </h3>
                    <p class="text-xs text-on-surface-variant mb-5">ຕ້ອງໃສ່ລະຫັດຜ່ານຢ່າງໜ້ອຍ 8 ຕົວ / Minimum 8 characters</p>

                    <div class="space-y-4">

                        {{-- Current Password --}}
                        <div x-data="{ show: false }">
                            <label class="form-label">ລະຫັດຜ່ານປັດຈຸບັນ / Current Password <span class="text-error">*</span></label>
                            <div class="flex items-center border border-outline-variant rounded-lg bg-white focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/10 transition-all overflow-hidden @error('current_password') border-error @enderror">
                                <span class="material-symbols-outlined text-on-surface-variant text-[18px] pl-3 pr-2 shrink-0 select-none">lock_open</span>
                                <input :type="show ? 'text' : 'password'"
                                       wire:model="current_password"
                                       placeholder="••••••••"
                                       autocomplete="current-password"
                                       class="flex-1 py-2 text-sm text-on-surface bg-transparent focus:outline-none min-w-0" />
                                <button type="button" @click="show = !show"
                                        class="pr-3 text-on-surface-variant hover:text-on-surface transition-colors shrink-0">
                                    <span class="material-symbols-outlined text-[18px]" x-text="show ? 'visibility_off' : 'visibility'">visibility</span>
                                </button>
                            </div>
                            @error('current_password')
                                <p class="form-error mt-1 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-xs">error</span>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            {{-- New Password --}}
                            <div x-data="{ show: false }">
                                <label class="form-label">ລະຫັດຜ່ານໃໝ່ / New Password <span class="text-error">*</span></label>
                                <div class="flex items-center border border-outline-variant rounded-lg bg-white focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/10 transition-all overflow-hidden @error('new_password') border-error @enderror">
                                    <span class="material-symbols-outlined text-on-surface-variant text-[18px] pl-3 pr-2 shrink-0 select-none">lock</span>
                                    <input :type="show ? 'text' : 'password'"
                                           wire:model="new_password"
                                           placeholder="••••••••"
                                           autocomplete="new-password"
                                           class="flex-1 py-2 text-sm text-on-surface bg-transparent focus:outline-none min-w-0" />
                                    <button type="button" @click="show = !show"
                                            class="pr-3 text-on-surface-variant hover:text-on-surface transition-colors shrink-0">
                                        <span class="material-symbols-outlined text-[18px]" x-text="show ? 'visibility_off' : 'visibility'">visibility</span>
                                    </button>
                                </div>
                                @error('new_password')
                                    <p class="form-error mt-1 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-xs">error</span>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Confirm New Password --}}
                            <div x-data="{ show: false }">
                                <label class="form-label">ຢືນຢັນລະຫັດໃໝ່ / Confirm Password <span class="text-error">*</span></label>
                                <div class="flex items-center border border-outline-variant rounded-lg bg-white focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/10 transition-all overflow-hidden">
                                    <span class="material-symbols-outlined text-on-surface-variant text-[18px] pl-3 pr-2 shrink-0 select-none">lock_reset</span>
                                    <input :type="show ? 'text' : 'password'"
                                           wire:model="new_password_confirmation"
                                           placeholder="••••••••"
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
                </div>

                <div class="flex justify-end pb-16 sm:pb-0">
                    <button type="submit"
                            wire:loading.attr="disabled" wire:loading.class="opacity-70"
                            class="px-8 py-2.5 bg-secondary hover:bg-secondary-container text-white font-bold rounded-xl transition-all shadow-md btn-press flex items-center gap-2">
                        <span wire:loading.remove wire:target="savePassword" class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-base">key</span>
                            ປ່ຽນລະຫັດຜ່ານ / Change Password
                        </span>
                        <span wire:loading wire:target="savePassword" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            ກຳລັງປ່ຽນ...
                        </span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
