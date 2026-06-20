<div x-data="{ showDeleteModal: false, deleteId: null, deleteName: '' }">
    <!-- Page Header -->
    <div class="flex justify-between items-end mb-8 animate-fade-in">
        <div>
            <h2 class="text-headline-lg text-on-surface mb-1">ລາຍຊື່ຄະນະກັມມາທິການ</h2>
            <p class="text-body-md text-on-surface-variant">Personnel Management Directory</p>
        </div>
        <a href="{{ route('personnel.create') }}"
           class="bg-primary text-white px-6 py-3 rounded-lg font-bold flex items-center gap-2 hover:bg-primary-container transition-all shadow-md btn-press">
            <span class="material-symbols-outlined">person_add</span>
            {{ __('messages.add_personnel') }}
        </a>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-12 gap-6 mb-8 animate-fade-in">
        <div class="col-span-12 lg:col-span-4 glass-card p-6 rounded-xl border border-outline-variant flex items-center gap-6">
            <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                <span class="material-symbols-outlined text-4xl filled">group</span>
            </div>
            <div>
                <p class="text-label-md text-on-surface-variant uppercase tracking-widest">TOTAL PERSONNEL</p>
                <h3 class="text-headline-md text-on-surface">{{ number_format($stats['total']) }}</h3>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-3 glass-card p-6 rounded-xl border border-outline-variant flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-badge-monk/10 flex items-center justify-center text-badge-monk">
                <span class="material-symbols-outlined text-2xl">temple_buddhist</span>
            </div>
            <div>
                <p class="text-label-md text-on-surface-variant">Monks & Novices</p>
                <h3 class="text-headline-sm text-on-surface">{{ number_format($stats['monks']) }}</h3>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-3 glass-card p-6 rounded-xl border border-outline-variant flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-secondary/10 flex items-center justify-center text-secondary">
                <span class="material-symbols-outlined text-2xl">person</span>
            </div>
            <div>
                <p class="text-label-md text-on-surface-variant">Laypersons</p>
                <h3 class="text-headline-sm text-on-surface">{{ number_format($stats['laypersons']) }}</h3>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-2 glass-card p-6 rounded-xl border border-outline-variant flex flex-col justify-center">
            <p class="text-label-md text-on-surface-variant">Active Ratio</p>
            <h3 class="text-headline-sm text-primary">{{ $stats['active_ratio'] }}%</h3>
            <div class="w-full bg-outline-variant h-1 rounded-full mt-2 overflow-hidden">
                <div class="bg-primary h-full transition-all duration-500" style="width: {{ $stats['active_ratio'] }}%"></div>
            </div>
        </div>
    </div>

    <!-- Filters Bar (Livewire server-side filters) -->
    <div class="bg-surface-container-low p-4 rounded-xl border border-outline-variant mb-6 flex flex-wrap items-center gap-4 animate-fade-in">
        <div class="flex-1 flex gap-4 min-w-[300px]">
            <div class="flex-1">
                <label class="block text-[10px] font-bold text-on-surface-variant mb-1 uppercase">
                    DEPARTMENT / ພາກສ່ວນ
                </label>
                <select wire:model.live="departmentFilter"
                        class="w-full bg-white border border-outline-variant rounded-lg p-2 text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20">
                    <option value="">{{ __('messages.all_departments') }}</option>
                    @foreach ($departments as $dept)
                        <option value="{{ $dept->id }}">
                            {{ app()->getLocale() === 'lo' ? $dept->name_lo : ($dept->name_en ?? $dept->name_lo) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-48">
                <label class="block text-[10px] font-bold text-on-surface-variant mb-1 uppercase">
                    TYPE / ປະເພດ
                </label>
                <select wire:model.live="gender"
                        class="w-full bg-white border border-outline-variant rounded-lg p-2 text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20">
                    <option value="">All Types</option>
                    <option value="monk">Monk (ພຣະສົງ)</option>
                    <option value="male">Male (ທ່ານ)</option>
                    <option value="female">Female (ທ່ານນາງ)</option>
                </select>
            </div>

            <div class="w-40">
                <label class="block text-[10px] font-bold text-on-surface-variant mb-1 uppercase">
                    STATUS / ສະຖານະ
                </label>
                <select wire:model.live="statusFilter"
                        class="w-full bg-white border border-outline-variant rounded-lg p-2 text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20">
                    <option value="">All</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>

        <button wire:click="clearFilters"
                class="px-4 py-2 border border-outline-variant rounded-lg text-body-md font-bold text-on-surface-variant hover:bg-surface-container transition-all flex items-center gap-2 btn-press">
            <span class="material-symbols-outlined text-sm">filter_list</span>
            Clear Filters
        </button>
    </div>

    <!-- Data Table (DataTables handles search / sort / pagination / responsive) -->
    <div class="animate-fade-in w-full overflow-x-auto">
        <table id="personnel-table" class="w-full border-collapse text-left">
            <thead>
                <tr class="bg-secondary text-white text-label-md">
                    <th class="p-3 w-16">Photo</th>
                    <th class="p-3">Name / ຊື່</th>
                    <th class="p-3">Position / ຕຳແໜ່ງ</th>
                    <th class="p-3">Type / ປະເພດ</th>
                    <th class="p-3">Department / ພາກສ່ວນ</th>
                    <th class="p-3 text-center">Status</th>
                    <th class="p-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="text-table-data">
                @foreach ($personnel as $person)
                    <tr class="border-b border-outline-variant table-row-hover {{ $person->isMonk() ? 'monk-accent' : '' }}"
                        wire:key="person-{{ $person->id }}">

                        <td class="p-3">
                            @if ($person->photo_url)
                                <img src="{{ Storage::url($person->photo_url) }}"
                                     alt="{{ $person->name_lo }}"
                                     class="w-10 h-10 rounded-full object-cover" />
                            @else
                                <div class="w-10 h-10 rounded-full bg-outline-variant/30 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-on-surface-variant text-lg">person</span>
                                </div>
                            @endif
                        </td>

                        <td class="p-3">
                            <div class="flex flex-col">
                                <a href="{{ route('personnel.show', $person->id) }}"
                                   class="font-bold text-on-surface text-body-md hover:text-primary transition-colors">
                                    {{ $person->name_lo }}
                                </a>
                                @if ($person->name_en)
                                    <span class="text-on-surface-variant opacity-70 text-xs">{{ $person->name_en }}</span>
                                @endif
                            </div>
                        </td>

                        <td class="p-3">
                            <div class="flex flex-col">
                                <span class="font-bold">{{ $person->position_lo }}</span>
                                @if ($person->position_en)
                                    <span class="text-xs text-on-surface-variant">{{ $person->position_en }}</span>
                                @endif
                            </div>
                        </td>

                        <td class="p-3">
                            @php $badge = $person->gender_badge; @endphp
                            <span class="{{ $badge['class'] }} px-2 py-0.5 rounded text-[10px] font-bold uppercase">
                                {{ $badge['label'] }}
                            </span>
                        </td>

                        <td class="p-3 text-on-surface-variant">
                            {{ $person->department
                                ? (app()->getLocale() === 'lo' ? $person->department->name_lo : ($person->department->name_en ?? $person->department->name_lo))
                                : '—' }}
                        </td>

                        <td class="p-3">
                            <div class="flex justify-center">
                                <label class="toggle-switch">
                                    <input type="checkbox"
                                           {{ $person->is_active ? 'checked' : '' }}
                                           wire:click="toggleActive({{ $person->id }})" />
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                        </td>

                        <td class="p-3 text-right">
                            <a href="{{ route('personnel.edit', $person->id) }}"
                               class="p-1 hover:text-primary transition-colors inline-block">
                                <span class="material-symbols-outlined text-lg">edit</span>
                            </a>
                            <button @click="deleteId = {{ $person->id }}; deleteName = {{ json_encode($person->name_lo) }}; showDeleteModal = true"
                                    type="button"
                                    class="p-1 hover:text-error transition-colors"
                                    title="ລຶບ / Delete">
                                <span class="material-symbols-outlined text-lg">delete</span>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Delete Confirmation Modal -->
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
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-outline-variant"
             @click.away="showDeleteModal = false">
            <div class="flex items-center gap-3 text-error mb-4">
                <div class="w-12 h-12 rounded-full bg-error/10 flex items-center justify-center text-error">
                    <span class="material-symbols-outlined text-2xl">warning</span>
                </div>
                <h3 class="text-headline-sm font-bold text-on-surface">ຢືນຢັນການລຶບ / Confirm Delete</h3>
            </div>

            <div class="space-y-3 mb-6">
                <p class="text-body-md text-on-surface-variant leading-relaxed">
                    ທ່ານແນ່ໃຈບໍ່ວ່າຕ້ອງການລຶບຂໍ້ມູນຄະນະກັມມາທິການນີ້? ການດຳເນີນການນີ້ບໍ່ສາມາດກັບຄືນໄດ້.
                    <br>
                    <span class="text-xs opacity-75">Are you sure you want to delete this personnel record? This action cannot be undone.</span>
                </p>
                <div class="bg-surface-container-low p-3 rounded-lg border border-outline-variant/50">
                    <p class="text-label-md text-on-surface-variant">ຄະນະກັມມາທິການ / Personnel:</p>
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
                        @click="$wire.deletePersonnel(deleteId); showDeleteModal = false"
                        class="px-4 py-2.5 rounded-lg bg-error hover:bg-error/90 text-white font-bold text-label-md transition-all shadow-md btn-press">
                    ລຶບຂໍ້ມູນ / Confirm Delete
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    let dt = null;

    function initDT() {
        const el = document.getElementById('personnel-table');
        if (!el) return;

        if (dt) {
            dt.destroy();
            dt = null;
        }

        dt = new DataTable('#personnel-table', {
            responsive: true,
            pageLength: 15,
            lengthMenu: [10, 15, 25, 50, 100],
            autoWidth:  false,
            columnDefs: [
                // ບໍ່ sortable: Photo, Status, Actions
                { orderable: false, targets: [0, 5, 6] },
                // Priority: Name + Actions ສະແດງສະເໝີ
                { responsivePriority: 1,     targets: [1, 6] },
                // Status ສຳຄັນ
                { responsivePriority: 2,     targets: [5] },
                // Position
                { responsivePriority: 3,     targets: [2] },
                // Type
                { responsivePriority: 4,     targets: [3] },
                // Department
                { responsivePriority: 5,     targets: [4] },
                // Photo ເຊື່ອງກ່ອນໃນໜ້າຈໍນ້ອຍ
                { responsivePriority: 10001, targets: [0] },
            ],
            order: [[1, 'asc']],
            language: {
                search:            'ຄົ້ນຫາ:',
                searchPlaceholder: 'ຄົ້ນຫາຊື່, ຕຳແໜ່ງ...',
                lengthMenu:        'ສະແດງ _MENU_ ລາຍການ',
                info:              'ສະແດງ _START_ ຫາ _END_ ຈາກ _TOTAL_ ລາຍການ',
                infoEmpty:         'ສະແດງ 0 ລາຍການ',
                infoFiltered:      '(ກັ່ນຕອງຈາກ _MAX_ ລາຍການ)',
                zeroRecords:       'ບໍ່ພົບຂໍ້ມູນທີ່ຄົ້ນຫາ',
                emptyTable:        'ບໍ່ມີຂໍ້ມູນ',
                paginate: {
                    first:    '«',
                    last:     '»',
                    next:     '›',
                    previous: '‹'
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', initDT);
    document.addEventListener('livewire:updated',  initDT);
})();
</script>
@endpush
