<div class="bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in">

    {{-- Header --}}
    <div class="bg-secondary px-8 py-8 text-center">
        <div class="w-20 h-20 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4">
            @php $logo = \App\Models\Setting::get('org_logo_url'); @endphp
            @if ($logo)
                <img src="{{ Storage::url($logo) }}" class="w-full h-full object-cover rounded-full" />
            @else
                <span class="material-symbols-outlined text-white text-4xl">account_balance</span>
            @endif
        </div>
        <h1 class="text-headline-sm text-white font-bold">
            {{ \App\Models\Setting::get('org_name_lo', 'Buddhist EMS') }}
        </h1>
        <p class="text-label-md text-white/60 mt-1">
            {{ \App\Models\Setting::get('org_name_en', 'Administrative Portal') }}
        </p>
    </div>

    {{-- Form --}}
    <div class="px-8 py-8">
        <h2 class="text-headline-sm text-on-surface mb-6 text-center">ເຂົ້າສູ່ລະບົບ</h2>

        <form wire:submit="login" class="space-y-5">

            {{-- Email --}}
            <div>
                <label class="form-label">ອີເມວ / Email</label>
                <div class="flex items-center border border-outline-variant rounded-lg bg-surface-container-low focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/10 transition-all overflow-hidden">
                    <span class="material-symbols-outlined text-on-surface-variant text-[18px] pl-3 pr-2 shrink-0">email</span>
                    <input type="email"
                           wire:model="email"
                           placeholder="admin@temple.org"
                           autocomplete="email"
                           class="flex-1 py-2.5 pr-3 text-sm bg-transparent focus:outline-none min-w-0" />
                </div>
                @error('email')
                    <p class="form-error flex items-center gap-1 mt-1">
                        <span class="material-symbols-outlined text-xs">error</span>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Password --}}
            <div x-data="{ show: false }">
                <label class="form-label">ລະຫັດຜ່ານ / Password</label>
                <div class="flex items-center border border-outline-variant rounded-lg bg-surface-container-low focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/10 transition-all overflow-hidden">
                    <span class="material-symbols-outlined text-on-surface-variant text-[18px] pl-3 pr-2 shrink-0">lock</span>
                    <input :type="show ? 'text' : 'password'"
                           wire:model="password"
                           placeholder="••••••••"
                           autocomplete="current-password"
                           class="flex-1 py-2.5 text-sm bg-transparent focus:outline-none min-w-0" />
                    <button type="button" @click="show = !show"
                            class="pr-3 text-on-surface-variant hover:text-on-surface transition-colors">
                        <span class="material-symbols-outlined text-[18px]" x-text="show ? 'visibility_off' : 'visibility'">visibility</span>
                    </button>
                </div>
                @error('password')
                    <p class="form-error flex items-center gap-1 mt-1">
                        <span class="material-symbols-outlined text-xs">error</span>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Remember Me --}}
            <div class="flex items-center gap-2">
                <input type="checkbox" wire:model="remember" id="remember"
                       class="w-4 h-4 rounded accent-primary cursor-pointer" />
                <label for="remember" class="text-body-md text-on-surface-variant cursor-pointer select-none">
                    ຈົດຈຳການເຂົ້າສູ່ລະບົບ / Remember me
                </label>
            </div>

            {{-- Submit --}}
            <button type="submit"
                    class="w-full bg-primary hover:bg-primary-container text-white font-bold py-3 rounded-xl transition-all shadow-md btn-press flex items-center justify-center gap-2"
                    wire:loading.attr="disabled" wire:loading.class="opacity-70">
                <span wire:loading.remove class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-base">login</span>
                    ເຂົ້າສູ່ລະບົບ / Login
                </span>
                <span wire:loading class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    ກຳລັງເຂົ້າສູ່ລະບົບ...
                </span>
            </button>
        </form>

        <p class="text-center text-xs text-on-surface-variant mt-6">
            © {{ date('Y') }} Buddhist Organization Management System
        </p>
    </div>
</div>
