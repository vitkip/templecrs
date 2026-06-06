<div x-data="{ showDeleteModal: false, deleteId: null, deleteName: '' }">
    {{-- Header --}}
    <div class="flex justify-between items-end mb-8 animate-fade-in">
        <div>
            <h2 class="text-headline-lg text-on-surface mb-1">ຈັດການຜູ້ໃຊ້</h2>
            <p class="text-body-md text-on-surface-variant">User Management</p>
        </div>
        <a href="{{ route('users.create') }}"
           class="bg-primary text-white px-6 py-3 rounded-lg font-bold flex items-center gap-2 hover:bg-primary-container transition-all shadow-md btn-press">
            <span class="material-symbols-outlined">person_add</span>
            ເພີ່ມຜູ້ໃຊ້ / Add User
        </a>
    </div>

    {{-- Flash --}}
    @if (session('message'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 flex items-center gap-3 animate-fade-in">
            <span class="material-symbols-outlined text-green-600">check_circle</span>
            <span>{{ session('message') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 flex items-center gap-3 animate-fade-in">
            <span class="material-symbols-outlined text-red-500">error</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-8 animate-fade-in">
        @php
            $statCards = [
                ['label' => 'ທັງໝົດ',      'value' => $stats['total'],       'icon' => 'group',          'color' => 'text-secondary'],
                ['label' => 'ໃຊ້ງານ',       'value' => $stats['active'],      'icon' => 'check_circle',   'color' => 'text-green-600'],
                ['label' => 'Super Admin', 'value' => $stats['super_admin'], 'icon' => 'shield_person',  'color' => 'text-purple-600'],
                ['label' => 'Admin',       'value' => $stats['admin'],       'icon' => 'admin_panel_settings','color' => 'text-primary'],
                ['label' => 'Manager',     'value' => $stats['manager'],     'icon' => 'manage_accounts', 'color' => 'text-secondary'],
                ['label' => 'Staff',       'value' => $stats['staff'],       'icon' => 'person',          'color' => 'text-on-surface-variant'],
            ];
        @endphp
        @foreach ($statCards as $card)
            <div class="glass-card p-4 rounded-xl border border-outline-variant flex flex-col items-center text-center gap-2">
                <span class="material-symbols-outlined {{ $card['color'] }} text-2xl">{{ $card['icon'] }}</span>
                <span class="text-headline-sm text-on-surface">{{ $card['value'] }}</span>
                <span class="text-label-md text-on-surface-variant">{{ $card['label'] }}</span>
            </div>
        @endforeach
    </div>

    {{-- Filters --}}
    <div class="bg-surface-container-low p-4 rounded-xl border border-outline-variant mb-6 flex flex-wrap items-end gap-4 animate-fade-in">
        {{-- Search --}}
        <div class="flex-1 min-w-[200px]">
            <label class="block text-[10px] font-bold text-on-surface-variant mb-1 uppercase">ຄົ້ນຫາ / Search</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-base">search</span>
                <input type="text" wire:model.live.debounce.300ms="search"
                       placeholder="ຊື່, ອີເມວ, ໂທ..."
                       class="w-full pl-9 pr-4 py-2 bg-white border border-outline-variant rounded-lg text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all" />
            </div>
        </div>

        {{-- Role --}}
        <div class="w-48">
            <label class="block text-[10px] font-bold text-on-surface-variant mb-1 uppercase">Role</label>
            <select wire:model.live="roleFilter"
                    class="w-full bg-white border border-outline-variant rounded-lg py-2 px-3 text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20">
                <option value="">ທຸກ Role</option>
                @foreach (\App\Models\User::ROLES as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>

        {{-- Status --}}
        <div class="w-40">
            <label class="block text-[10px] font-bold text-on-surface-variant mb-1 uppercase">ສະຖານະ</label>
            <select wire:model.live="statusFilter"
                    class="w-full bg-white border border-outline-variant rounded-lg py-2 px-3 text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20">
                <option value="">ທັງໝົດ</option>
                <option value="active">ໃຊ້ງານ</option>
                <option value="inactive">ບໍ່ໃຊ້ງານ</option>
            </select>
        </div>

        {{-- Clear --}}
        <button wire:click="clearFilters"
                class="px-4 py-2 border border-outline-variant rounded-lg text-body-md text-on-surface-variant hover:bg-surface-container transition-all flex items-center gap-2 btn-press">
            <span class="material-symbols-outlined text-sm">filter_list_off</span>
            ລ້າງ
        </button>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-outline-variant overflow-hidden animate-fade-in">
        <table class="w-full border-collapse text-left">
            <thead>
                <tr class="bg-secondary text-white text-label-md">
                    <th class="p-3 w-12">ຮູບ</th>
                    <th class="p-3 cursor-pointer hover:bg-white/10 transition-colors" wire:click="sortBy('name')">
                        ຊື່ / Name
                        @if ($sortBy === 'name')
                            <span class="material-symbols-outlined text-xs align-middle">{{ $sortDir === 'asc' ? 'arrow_upward' : 'arrow_downward' }}</span>
                        @endif
                    </th>
                    <th class="p-3">ອີເມວ / Email</th>
                    <th class="p-3">Role</th>
                    <th class="p-3 hidden md:table-cell">ໂທ</th>
                    <th class="p-3 text-center cursor-pointer hover:bg-white/10" wire:click="sortBy('created_at')">
                        ວັນທີ່
                        @if ($sortBy === 'created_at')
                            <span class="material-symbols-outlined text-xs align-middle">{{ $sortDir === 'asc' ? 'arrow_upward' : 'arrow_downward' }}</span>
                        @endif
                    </th>
                    <th class="p-3 text-center">ສະຖານະ</th>
                    <th class="p-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    @php $badge = $user->role_badge; @endphp
                    <tr class="border-b border-outline-variant table-row-hover {{ !$user->is_active ? 'opacity-50' : '' }} {{ $user->id === auth()->id() ? 'bg-primary/3' : '' }}"
                        wire:key="user-{{ $user->id }}">

                        {{-- Avatar --}}
                        <td class="p-3">
                            @if ($user->avatar_url)
                                <img src="{{ Storage::url($user->avatar_url) }}" alt="{{ $user->name }}"
                                     class="w-10 h-10 rounded-full object-cover" />
                            @else
                                <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-primary text-base">person</span>
                                </div>
                            @endif
                        </td>

                        {{-- Name --}}
                        <td class="p-3">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-on-surface">{{ $user->name }}</span>
                                @if ($user->id === auth()->id())
                                    <span class="text-[9px] bg-primary/10 text-primary font-bold px-1.5 py-0.5 rounded-full">ທ່ານ</span>
                                @endif
                            </div>
                        </td>

                        {{-- Email --}}
                        <td class="p-3 text-on-surface-variant">{{ $user->email }}</td>

                        {{-- Role Badge --}}
                        <td class="p-3">
                            <span class="{{ $badge['class'] }} px-2 py-0.5 rounded text-[10px] font-bold uppercase">
                                {{ $badge['label'] }}
                            </span>
                        </td>

                        {{-- Phone --}}
                        <td class="p-3 text-on-surface-variant hidden md:table-cell">{{ $user->phone ?? '—' }}</td>

                        {{-- Date --}}
                        <td class="p-3 text-center text-xs text-on-surface-variant">
                            {{ $user->created_at->format('d/m/Y') }}
                        </td>

                        {{-- Active Toggle --}}
                        <td class="p-3">
                            <div class="flex justify-center">
                                <label class="toggle-switch">
                                    <input type="checkbox"
                                           {{ $user->is_active ? 'checked' : '' }}
                                           wire:click="toggleActive({{ $user->id }})" />
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                        </td>

                        {{-- Actions --}}
                        <td class="p-3 text-right">
                            <a href="{{ route('users.edit', $user->id) }}"
                               class="p-1 hover:text-primary transition-colors inline-block" title="ແກ້ໄຂ">
                                <span class="material-symbols-outlined text-lg">edit</span>
                            </a>
                            @if ($user->id !== auth()->id())
                                <button @click="deleteId = {{ $user->id }}; deleteName = {{ json_encode($user->name) }}; showDeleteModal = true"
                                        type="button"
                                        class="p-1 hover:text-error transition-colors" title="ລຶບ">
                                    <span class="material-symbols-outlined text-lg">delete</span>
                                </button>
                            @else
                                <span class="p-1 text-on-surface-variant/30 inline-block" title="ບໍ່ສາມາດລຶບຕົນເອງ">
                                    <span class="material-symbols-outlined text-lg">delete</span>
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="p-12 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-5xl mb-4 block opacity-30">manage_accounts</span>
                            <p class="text-lg">ບໍ່ພົບຜູ້ໃຊ້</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if ($users->hasPages())
            <div class="p-4 flex justify-between items-center bg-surface-container-low border-t border-outline-variant">
                <p class="text-label-md text-on-surface-variant">
                    ສະແດງ {{ $users->firstItem() }}–{{ $users->lastItem() }} ຈາກ {{ $users->total() }} ລາຍການ
                </p>
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- Custom Deletion Confirmation Modal -->
    <div x-show="showDeleteModal"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         style="display: none;"
         x-cloak>
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-outline-variant transform transition-all"
             @click.away="showDeleteModal = false">
            <div class="flex items-center gap-3 text-error mb-4">
                <div class="w-12 h-12 rounded-full bg-error/10 flex items-center justify-center text-error">
                    <span class="material-symbols-outlined text-2xl">warning</span>
                </div>
                <h3 class="text-headline-sm font-bold text-on-surface">ຢືນຢັນການລຶບ / Confirm Delete</h3>
            </div>
            
            <div class="space-y-3 mb-6">
                <p class="text-body-md text-on-surface-variant leading-relaxed">
                    ທ່ານແນ່ໃຈບໍ່ວ່າຕ້ອງການລຶບຜູ້ໃຊ້ນີ້? ການດຳເນີນການນີ້ບໍ່ສາມາດກັບຄືນໄດ້.
                    <br>
                    <span class="text-xs opacity-75">Are you sure you want to delete this user? This action cannot be undone.</span>
                </p>
                <div class="bg-surface-container-low p-3 rounded-lg border border-outline-variant/50">
                    <p class="text-label-md text-on-surface-variant">ຊື່ຜູ້ໃຊ້ / User Name:</p>
                    <p class="text-body-md font-bold text-primary text-left" x-text="deleteName"></p>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button"
                        @click="showDeleteModal = false"
                        class="px-4 py-2.5 rounded-lg border border-outline-variant text-label-md font-bold text-on-surface-variant hover:bg-surface-container transition-all">
                    ຍົກເລີກ / Cancel
                </button>
                <button type="button"
                        @click="$wire.deleteUser(deleteId); showDeleteModal = false"
                        class="px-4 py-2.5 rounded-lg bg-error hover:bg-error/90 text-white font-bold text-label-md transition-all shadow-md btn-press">
                    ລຶບຂໍ້ມູນ / Confirm Delete
                </button>
            </div>
        </div>
    </div>
</div>
