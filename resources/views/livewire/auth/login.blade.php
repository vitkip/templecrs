<div>

    {{-- ─── Mobile-only: Logo + Org Name ─── --}}
    <div class="lg:hidden text-center mb-8 fi fi-1">
        @php $logo = \App\Models\Setting::get('org_logo_url'); @endphp
        <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3 shadow-xl"
             style="background:linear-gradient(135deg,#c9a227,#edd55a);">
            @if ($logo)
                <img src="{{ Storage::url($logo) }}" class="w-full h-full object-cover rounded-full" alt="Logo" />
            @else
                <span class="material-symbols-outlined text-3xl" style="color:#0b1520;">account_balance</span>
            @endif
        </div>
        <h1 class="text-lg font-bold" style="color:var(--ink);">
            {{ \App\Models\Setting::get('org_name_lo', 'Buddhist EMS') }}
        </h1>
        <p class="text-xs mt-0.5" style="color:#9ca3af;">
            {{ \App\Models\Setting::get('org_name_en', 'Administrative Portal') }}
        </p>
    </div>

    {{-- ─── Heading ─── --}}
    <div class="mb-8 fi fi-2">
        <p style="font-family:'Cinzel',serif; font-size:10px; letter-spacing:.22em;
                  color:var(--gold); text-transform:uppercase; margin-bottom:.5rem;">
            Welcome Back
        </p>
        <h2 class="font-bold leading-tight"
            style="font-family:'Cinzel',serif; font-size:1.9rem; color:var(--ink);">
            Sign In<br>to Continue
        </h2>
        <p class="text-sm mt-2" style="color:#9ca3af;">ກະລຸນາໃສ່ຂໍ້ມູນຂອງທ່ານ</p>
    </div>

    {{-- ─── Form ─── --}}
    <form wire:submit="login" class="space-y-5">

        {{-- Email --}}
        <div class="fi fi-3">
            <label class="block text-xs font-semibold uppercase tracking-wider mb-2"
                   style="color:#4b5563;">
                ອີເມວ / Email
            </label>
            <div class="sacred-field">
                <span class="material-symbols-outlined pl-4 pr-2 shrink-0"
                      style="font-size:18px; color:var(--gold);">email</span>
                <input type="email"
                       wire:model="email"
                       placeholder="admin@temple.org"
                       autocomplete="email"
                       class="flex-1 py-3.5 pr-4 text-sm bg-transparent focus:outline-none"
                       style="color:var(--ink);" />
            </div>
            @error('email')
                <p class="flex items-center gap-1 mt-1.5 text-xs" style="color:#ef4444;">
                    <span class="material-symbols-outlined" style="font-size:13px;">error</span>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Password --}}
        <div class="fi fi-4" x-data="{ show: false }">
            <label class="block text-xs font-semibold uppercase tracking-wider mb-2"
                   style="color:#4b5563;">
                ລະຫັດຜ່ານ / Password
            </label>
            <div class="sacred-field">
                <span class="material-symbols-outlined pl-4 pr-2 shrink-0"
                      style="font-size:18px; color:var(--gold);">lock</span>
                <input :type="show ? 'text' : 'password'"
                       wire:model="password"
                       placeholder="••••••••"
                       autocomplete="current-password"
                       class="flex-1 py-3.5 text-sm bg-transparent focus:outline-none min-w-0"
                       style="color:var(--ink);" />
                <button type="button" @click="show = !show"
                        class="pr-4 transition-colors duration-200 focus:outline-none"
                        style="color:#bbb0a0;"
                        @mouseenter="$el.style.color='var(--gold)'"
                        @mouseleave="$el.style.color='#bbb0a0'">
                    <span class="material-symbols-outlined" style="font-size:18px;"
                          x-text="show ? 'visibility_off' : 'visibility'">visibility</span>
                </button>
            </div>
            @error('password')
                <p class="flex items-center gap-1 mt-1.5 text-xs" style="color:#ef4444;">
                    <span class="material-symbols-outlined" style="font-size:13px;">error</span>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Remember Me --}}
        <div class="flex items-center gap-2.5 fi fi-5">
            <input type="checkbox" wire:model="remember" id="remember"
                   class="w-4 h-4 rounded cursor-pointer"
                   style="accent-color:var(--gold);" />
            <label for="remember" class="text-sm cursor-pointer select-none" style="color:#6b7280;">
                ຈົດຈຳການເຂົ້າສູ່ລະບົບ / Remember me
            </label>
        </div>

        {{-- Submit --}}
        <div class="pt-2 fi fi-6">
            <button type="submit"
                    class="btn-gold w-full py-4 font-bold text-sm flex items-center justify-center gap-2"
                    style="letter-spacing:.08em;"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-70 cursor-not-allowed">
                <span wire:loading.remove class="flex items-center gap-2">
                    <span class="material-symbols-outlined" style="font-size:18px; color:#0b1520;">login</span>
                    <span style="color:#0b1520;">ເຂົ້າສູ່ລະບົບ &nbsp;/&nbsp; Login</span>
                </span>
                <span wire:loading class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24" style="color:#0b1520;">
                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                stroke="currentColor" stroke-width="4" fill="none"></circle>
                        <path class="opacity-75" fill="currentColor"
                              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span style="color:#0b1520;">ກຳລັງເຂົ້າສູ່ລະບົບ...</span>
                </span>
            </button>
        </div>
    </form>

    {{-- Footer --}}
    <div class="mt-10 pt-6 fi fi-6" style="border-top:1px solid #e8e2d8;">
        <p class="text-center text-xs" style="color:#c0b5a5;">
            © {{ date('Y') }} Buddhist Organization Management System
        </p>
    </div>

</div>
