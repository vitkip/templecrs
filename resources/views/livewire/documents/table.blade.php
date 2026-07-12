<div x-data="{ showDeleteModal: false, deleteId: null, deleteName: '' }">
    {{-- Page Header --}}
    <div class="flex justify-between items-end mb-8 animate-fade-in">
        <div>
            <h2 class="text-headline-lg text-on-surface mb-1">ຄັງເອກະສານ</h2>
            <p class="text-body-md text-on-surface-variant">Document Management System (DMS)</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('documents.categories.index') }}"
               class="border border-outline-variant text-on-surface-variant px-4 py-2.5 rounded-lg font-bold flex items-center gap-2 hover:bg-surface-container transition-all btn-press">
                <span class="material-symbols-outlined text-base">category</span>
                ໝວດເອກະສານ
            </a>
            <a href="{{ route('documents.create') }}"
               class="bg-primary text-white px-6 py-3 rounded-lg font-bold flex items-center gap-2 hover:bg-primary-container transition-all shadow-md btn-press">
                <span class="material-symbols-outlined">upload_file</span>
                ອັບໂຫລດເອກະສານ
            </a>
        </div>
    </div>

    {{-- Stats Overview --}}
    <div class="grid grid-cols-12 gap-6 mb-8 animate-fade-in">
        {{-- Total --}}
        <div class="col-span-12 lg:col-span-4 glass-card p-6 rounded-xl border border-outline-variant flex items-center gap-6">
            <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                <span class="material-symbols-outlined text-4xl filled">folder_open</span>
            </div>
            <div>
                <p class="text-label-md text-on-surface-variant uppercase tracking-widest">TOTAL DOCUMENTS</p>
                <h3 class="text-headline-md text-on-surface">{{ number_format($stats['total']) }}</h3>
            </div>
        </div>

        {{-- Active --}}
        <div class="col-span-12 lg:col-span-3 glass-card p-6 rounded-xl border border-outline-variant flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-700">
                <span class="material-symbols-outlined text-2xl">check_circle</span>
            </div>
            <div>
                <p class="text-label-md text-on-surface-variant">Active / ໃຊ້ງານ</p>
                <h3 class="text-headline-sm text-on-surface">{{ number_format($stats['active']) }}</h3>
            </div>
        </div>

        {{-- This Month --}}
        <div class="col-span-12 lg:col-span-3 glass-card p-6 rounded-xl border border-outline-variant flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-secondary/10 flex items-center justify-center text-secondary">
                <span class="material-symbols-outlined text-2xl">calendar_month</span>
            </div>
            <div>
                <p class="text-label-md text-on-surface-variant">ເດືອນນີ້ / This Month</p>
                <h3 class="text-headline-sm text-on-surface">{{ number_format($stats['this_month']) }}</h3>
            </div>
        </div>

        {{-- Total Downloads --}}
        <div class="col-span-12 lg:col-span-2 glass-card p-6 rounded-xl border border-outline-variant flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700">
                <span class="material-symbols-outlined text-2xl">download</span>
            </div>
            <div>
                <p class="text-label-md text-on-surface-variant">ດາວໂຫລດທັງໝົດ</p>
                <h3 class="text-headline-sm text-on-surface">{{ number_format($stats['total_downloads']) }}</h3>
            </div>
        </div>
    </div>

    {{-- Filters Bar --}}
    <div class="bg-surface-container-low p-4 rounded-xl border border-outline-variant mb-6 flex flex-wrap items-end gap-4 animate-fade-in">
        <div class="flex-1 flex flex-wrap gap-4 min-w-[300px]">
            {{-- Category Filter --}}
            <div class="w-52">
                <label class="block text-[10px] font-bold text-on-surface-variant mb-1 uppercase">
                    CATEGORY / ໝວດ
                </label>
                <select wire:model.live="category"
                        class="w-full bg-white border border-outline-variant rounded-lg p-2 text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20">
                    <option value="">ທຸກໝວດ / All</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->slug }}">{{ $cat->name_lo }}{{ $cat->name_en ? ' (' . $cat->name_en . ')' : '' }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Department Filter --}}
            <div class="w-52">
                <label class="block text-[10px] font-bold text-on-surface-variant mb-1 uppercase">
                    DEPARTMENT / ພາກສ່ວນ
                </label>
                <select wire:model.live="departmentFilter"
                        class="w-full bg-white border border-outline-variant rounded-lg p-2 text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20">
                    <option value="">ທຸກພາກສ່ວນ</option>
                    @foreach ($departments as $dept)
                        <option value="{{ $dept->id }}">
                            {{ app()->getLocale() === 'lo' ? $dept->name_lo : ($dept->name_en ?? $dept->name_lo) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Status Filter --}}
            <div class="w-36">
                <label class="block text-[10px] font-bold text-on-surface-variant mb-1 uppercase">
                    STATUS / ສະຖານະ
                </label>
                <select wire:model.live="statusFilter"
                        class="w-full bg-white border border-outline-variant rounded-lg p-2 text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20">
                    <option value="">ທັງໝົດ</option>
                    <option value="active">ໃຊ້ງານ</option>
                    <option value="inactive">ບໍ່ໃຊ້ງານ</option>
                </select>
            </div>

            {{-- Year Filter --}}
            <div class="w-32">
                <label class="block text-[10px] font-bold text-on-surface-variant mb-1 uppercase">
                    YEAR / ປີ
                </label>
                <select wire:model.live="yearFilter"
                        class="w-full bg-white border border-outline-variant rounded-lg p-2 text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20">
                    <option value="">ທຸກປີ</option>
                    @foreach ($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <button wire:click="clearFilters"
                class="px-4 py-2 border border-outline-variant rounded-lg text-body-md font-bold text-on-surface-variant hover:bg-surface-container transition-all flex items-center gap-2 btn-press">
            <span class="material-symbols-outlined text-sm">filter_list_off</span>
            ລ້າງ
        </button>
    </div>

    {{-- Search Bar --}}
    <div class="mb-6 animate-fade-in">
        <div class="relative max-w-md">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
            <input type="text"
                   wire:model.live.debounce.300ms="search"
                   class="w-full pl-10 pr-4 py-2.5 bg-white border border-outline-variant rounded-lg text-body-md focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all"
                   placeholder="ຄົ້ນຫາຊື່, ເລກທີ, ເນື້ອໃນ..." />
        </div>
    </div>

    {{-- Category Quick Filters --}}
    <div class="flex flex-wrap gap-2 mb-6 animate-fade-in">
        <button wire:click="$set('category', '')"
                class="px-3 py-1.5 rounded-full text-label-md border transition-all
                       {{ $category === '' ? 'bg-primary text-white border-primary' : 'border-outline-variant text-on-surface-variant hover:border-primary/50' }}">
            ທຸກໝວດ
        </button>
        @foreach ($categories as $cat)
            <button wire:click="$set('category', '{{ $cat->slug }}')"
                    class="px-3 py-1.5 rounded-full text-label-md border transition-all flex items-center gap-1
                           {{ $category === $cat->slug ? 'bg-primary text-white border-primary' : 'border-outline-variant text-on-surface-variant hover:border-primary/50' }}">
                <span class="material-symbols-outlined text-xs">{{ $cat->icon }}</span>
                {{ $cat->name_lo }}
            </button>
        @endforeach
    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-outline-variant overflow-hidden animate-fade-in">
        <table class="w-full border-collapse text-left">
            <thead>
                <tr class="bg-secondary text-white text-label-md">
                    <th class="p-3">ໝວດ / Type</th>
                    <th class="p-3 cursor-pointer hover:bg-white/10 transition-colors" wire:click="sortBy('title_lo')">
                        ຊື່ເອກະສານ / Title
                        @if ($sortBy === 'title_lo')
                            <span class="material-symbols-outlined text-xs align-middle">{{ $sortDir === 'asc' ? 'arrow_upward' : 'arrow_downward' }}</span>
                        @endif
                    </th>
                    <th class="p-3">ເລກທີ / No.</th>
                    <th class="p-3">ພາກສ່ວນ</th>
                    <th class="p-3 cursor-pointer hover:bg-white/10 transition-colors" wire:click="sortBy('issued_date')">
                        ວັນທີ
                        @if ($sortBy === 'issued_date')
                            <span class="material-symbols-outlined text-xs align-middle">{{ $sortDir === 'asc' ? 'arrow_upward' : 'arrow_downward' }}</span>
                        @endif
                    </th>
                    <th class="p-3">ໄຟລ໌</th>
                    <th class="p-3 cursor-pointer hover:bg-white/10 transition-colors text-center" wire:click="sortBy('download_count')">
                        ດາວໂຫລດ
                        @if ($sortBy === 'download_count')
                            <span class="material-symbols-outlined text-xs align-middle">{{ $sortDir === 'asc' ? 'arrow_upward' : 'arrow_downward' }}</span>
                        @endif
                    </th>
                    <th class="p-3 text-center">ສະຖານະ</th>
                    <th class="p-3 text-right">ດຳເນີນການ</th>
                </tr>
            </thead>
            <tbody class="text-table-data">
                @forelse ($documents as $doc)
                    <tr class="border-b border-outline-variant table-row-hover h-[60px]"
                        wire:key="doc-{{ $doc->id }}">

                        {{-- Category Badge --}}
                        <td class="p-3">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $doc->category_color }}">
                                <span class="material-symbols-outlined text-xs">{{ $doc->category_icon }}</span>
                                {{ $doc->category_label }}
                            </span>
                        </td>

                        {{-- Title --}}
                        <td class="p-3">
                            <div class="flex items-center gap-3">
                                @if ($doc->cover_image_url)
                                    <img src="{{ $doc->cover_image_url }}" alt="" class="w-8 h-11 rounded object-cover border border-outline-variant shrink-0" />
                                @endif
                                <div class="flex flex-col">
                                    <a href="{{ route('documents.show', $doc->id) }}"
                                       class="font-bold text-on-surface hover:text-primary transition-colors">
                                        {{ $doc->title_lo }}
                                    </a>
                                    @if ($doc->title_en)
                                        <span class="text-on-surface-variant opacity-70 text-xs">{{ $doc->title_en }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Doc Number --}}
                        <td class="p-3 text-on-surface-variant font-mono text-xs">
                            {{ $doc->doc_number ?? '—' }}
                        </td>

                        {{-- Department --}}
                        <td class="p-3 text-on-surface-variant">
                            {{ $doc->department ? (app()->getLocale() === 'lo' ? $doc->department->name_lo : ($doc->department->name_en ?? $doc->department->name_lo)) : '—' }}
                        </td>

                        {{-- Date --}}
                        <td class="p-3 text-on-surface-variant">
                            {{ $doc->issued_date ? $doc->issued_date->format('d/m/Y') : '—' }}
                        </td>

                        {{-- File --}}
                        <td class="p-3">
                            @if ($doc->file_path)
                                <a href="{{ $doc->file_url }}"
                                   target="_blank"
                                   class="inline-flex items-center gap-1 text-primary hover:text-primary-container transition-colors text-xs font-bold">
                                    <span class="material-symbols-outlined text-base">{{ $doc->file_icon }}</span>
                                    {{ $doc->file_size_formatted }}
                                </a>
                            @else
                                <span class="text-on-surface-variant/50 text-xs">—</span>
                            @endif
                        </td>

                        {{-- Download Count --}}
                        <td class="p-3 text-center">
                            <span class="inline-flex items-center gap-1 text-xs font-bold {{ $doc->download_count > 0 ? 'text-indigo-700' : 'text-on-surface-variant/40' }}">
                                <span class="material-symbols-outlined text-sm">download</span>
                                {{ number_format($doc->download_count) }}
                            </span>
                        </td>

                        {{-- Active Toggle --}}
                        <td class="p-3">
                            <div class="flex justify-center">
                                <label class="toggle-switch">
                                    <input type="checkbox"
                                           {{ $doc->is_active ? 'checked' : '' }}
                                           wire:click="toggleActive({{ $doc->id }})" />
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                        </td>

                        {{-- Actions --}}
                        <td class="p-3 text-right">
                            <a href="{{ route('documents.show', $doc->id) }}"
                               class="p-1 hover:text-secondary transition-colors inline-block"
                               title="ເບິ່ງລາຍລະອຽດ">
                                <span class="material-symbols-outlined text-lg">visibility</span>
                            </a>
                            <a href="{{ route('documents.edit', $doc->id) }}"
                               class="p-1 hover:text-primary transition-colors inline-block"
                               title="ແກ້ໄຂ">
                                <span class="material-symbols-outlined text-lg">edit</span>
                            </a>
                            <button @click="deleteId = {{ $doc->id }}; deleteName = {{ json_encode($doc->title_lo) }}; showDeleteModal = true"
                                    type="button"
                                    class="p-1 hover:text-error transition-colors"
                                    title="ລຶບ">
                                <span class="material-symbols-outlined text-lg">delete</span>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="p-12 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-5xl mb-4 block opacity-30">folder_off</span>
                            <p class="text-lg">ບໍ່ພົບເອກະສານ / No documents found</p>
                            <a href="{{ route('documents.create') }}" class="text-primary hover:underline mt-2 inline-block">
                                ອັບໂຫລດເອກະສານທຳອິດ
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if ($documents->hasPages())
            <div class="p-4 flex justify-between items-center bg-surface-container-low border-t border-outline-variant">
                <p class="text-label-md text-on-surface-variant">
                    ສະແດງ {{ $documents->firstItem() }}–{{ $documents->lastItem() }} ຈາກ {{ number_format($documents->total()) }} ລາຍການ
                </p>
                {{ $documents->links() }}
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
                    ທ່ານແນ່ໃຈບໍ່ວ່າຕ້ອງການລຶບເອກະສານນີ້? ການດຳເນີນການນີ້ບໍ່ສາມາດກັບຄືນໄດ້.
                    <br>
                    <span class="text-xs opacity-75">Are you sure you want to delete this document? This action cannot be undone.</span>
                </p>
                <div class="bg-surface-container-low p-3 rounded-lg border border-outline-variant/50">
                    <p class="text-label-md text-on-surface-variant">ຊື່ເອກະສານ / Document Title:</p>
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
                        @click="$wire.deleteDocument(deleteId); showDeleteModal = false"
                        class="px-4 py-2.5 rounded-lg bg-error hover:bg-error/90 text-white font-bold text-label-md transition-all shadow-md btn-press">
                    ລຶບຂໍ້ມູນ / Confirm Delete
                </button>
            </div>
        </div>
    </div>
</div>
