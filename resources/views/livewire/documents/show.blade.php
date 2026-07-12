<div x-data="{ showDeleteModal: false }">
    {{-- Page Header --}}
    <div class="flex justify-between items-start mb-8 animate-fade-in">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-label-md border {{ $document->category_color }}">
                    <span class="material-symbols-outlined text-sm">{{ $document->category_icon }}</span>
                    {{ $document->category_label }}
                </span>
                @if (!$document->is_active)
                    <span class="px-2 py-0.5 bg-outline-variant/30 text-on-surface-variant text-[10px] font-bold rounded-full uppercase">
                        ບໍ່ໃຊ້ງານ
                    </span>
                @endif
            </div>
            <h2 class="text-headline-lg text-on-surface mb-1">{{ $document->title_lo }}</h2>
            @if ($document->title_en)
                <p class="text-body-lg text-on-surface-variant">{{ $document->title_en }}</p>
            @endif
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('documents.index') }}"
               class="px-4 py-2 border border-outline-variant rounded-lg text-body-md font-bold text-on-surface-variant hover:bg-surface-container transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                ກັບຄືນ
            </a>
            <a href="{{ route('documents.edit', $document->id) }}"
               class="px-4 py-2 bg-primary/10 text-primary rounded-lg font-bold flex items-center gap-2 hover:bg-primary/20 transition-all">
                <span class="material-symbols-outlined text-sm">edit</span>
                ແກ້ໄຂ
            </a>
            <button @click="showDeleteModal = true"
                    type="button"
                    class="px-4 py-2 bg-error/10 text-error rounded-lg font-bold flex items-center gap-2 hover:bg-error/20 transition-all">
                <span class="material-symbols-outlined text-sm">delete</span>
                ລຶບ
            </button>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6">

        {{-- Main Info --}}
        <div class="col-span-12 lg:col-span-8 space-y-6">

            {{-- Description --}}
            @if ($document->description_lo || $document->description_en)
                <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm animate-fade-in">
                    <h3 class="text-headline-sm text-on-surface mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">notes</span>
                        ລາຍລະອຽດ / Description
                    </h3>

                    @if ($document->description_lo)
                        <div class="mb-4">
                            <p class="text-[10px] font-bold text-on-surface-variant uppercase mb-1">ລາວ</p>
                            <p class="text-body-md text-on-surface leading-relaxed whitespace-pre-line">{{ $document->description_lo }}</p>
                        </div>
                    @endif

                    @if ($document->description_en)
                        <div>
                            <p class="text-[10px] font-bold text-on-surface-variant uppercase mb-1">English</p>
                            <p class="text-body-md text-on-surface leading-relaxed whitespace-pre-line">{{ $document->description_en }}</p>
                        </div>
                    @endif
                </div>
            @endif

            {{-- File Download Card --}}
            @if ($document->file_path)
                <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm animate-fade-in">
                    <h3 class="text-headline-sm text-on-surface mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">attach_file</span>
                        ໄຟລ໌ / File
                    </h3>

                    <div class="flex items-center gap-4 p-4 bg-surface-container-low rounded-xl border border-outline-variant">
                        <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-primary text-3xl">{{ $document->file_icon }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-on-surface text-body-md truncate">{{ $document->file_name }}</p>
                            <p class="text-xs text-on-surface-variant mt-0.5">
                                {{ $document->file_size_formatted }}
                                @if ($document->file_type)
                                    · {{ strtoupper(last(explode('/', $document->file_type))) }}
                                @endif
                            </p>
                        </div>
                        <a href="{{ $document->file_url }}"
                           target="_blank"
                           class="shrink-0 bg-primary text-white px-5 py-2.5 rounded-lg font-bold flex items-center gap-2 hover:bg-primary-container transition-all shadow-sm btn-press">
                            <span class="material-symbols-outlined text-base">download</span>
                            ດາວໂຫລດ
                        </a>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm animate-fade-in">
                    <div class="flex items-center gap-3 text-on-surface-variant/50">
                        <span class="material-symbols-outlined text-3xl">folder_off</span>
                        <p class="text-body-md">ບໍ່ມີໄຟລ໌ແນບ / No file attached</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar Info --}}
        <div class="col-span-12 lg:col-span-4 space-y-6">

            {{-- Cover Page --}}
            @if ($document->cover_image_url)
                <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm animate-fade-in">
                    <h3 class="text-label-md text-on-surface-variant uppercase tracking-wider mb-4">ໜ້າປົກ / Cover Page</h3>
                    <img src="{{ $document->cover_image_url }}"
                         alt="{{ $document->title_lo }}"
                         loading="lazy"
                         class="w-full rounded-lg border border-outline-variant shadow-sm object-cover aspect-[3/4]" />
                </div>
            @endif

            {{-- Document Details --}}
            <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm animate-fade-in">
                <h3 class="text-label-md text-on-surface-variant uppercase tracking-wider mb-4">ຂໍ້ມູນເອກະສານ</h3>

                <dl class="space-y-3">
                    @if ($document->doc_number)
                        <div>
                            <dt class="text-[10px] font-bold text-on-surface-variant uppercase">ເລກທີ / Reference</dt>
                            <dd class="text-body-md font-mono font-bold text-primary mt-0.5">{{ $document->doc_number }}</dd>
                        </div>
                    @endif

                    <div>
                        <dt class="text-[10px] font-bold text-on-surface-variant uppercase">ໝວດ / Category</dt>
                        <dd class="mt-0.5">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $document->category_color }}">
                                <span class="material-symbols-outlined text-xs">{{ $document->category_icon }}</span>
                                {{ $document->category_label }}
                            </span>
                        </dd>
                    </div>

                    @if ($document->department)
                        <div>
                            <dt class="text-[10px] font-bold text-on-surface-variant uppercase">ພາກສ່ວນ / Department</dt>
                            <dd class="text-body-md text-on-surface mt-0.5">
                                {{ app()->getLocale() === 'lo' ? $document->department->name_lo : ($document->department->name_en ?? $document->department->name_lo) }}
                            </dd>
                        </div>
                    @endif

                    @if ($document->issued_date)
                        <div>
                            <dt class="text-[10px] font-bold text-on-surface-variant uppercase">ວັນທີ່ອອກ / Issued Date</dt>
                            <dd class="text-body-md text-on-surface mt-0.5">{{ $document->issued_date->format('d/m/Y') }}</dd>
                        </div>
                    @endif

                    <div class="pt-3 border-t border-outline-variant">
                        <dt class="text-[10px] font-bold text-on-surface-variant uppercase">ອັບໂຫລດໂດຍ</dt>
                        <dd class="text-body-md text-on-surface mt-0.5">
                            {{ $document->uploader?->name ?? '—' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-[10px] font-bold text-on-surface-variant uppercase">ວັນທີ່ເພີ່ມ</dt>
                        <dd class="text-body-md text-on-surface mt-0.5">{{ $document->created_at->format('d/m/Y H:i') }}</dd>
                    </div>

                    @if ($document->updated_at != $document->created_at)
                        <div>
                            <dt class="text-[10px] font-bold text-on-surface-variant uppercase">ແກ້ໄຂລ່າສຸດ</dt>
                            <dd class="text-body-md text-on-surface mt-0.5">{{ $document->updated_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    @endif

                    <div class="pt-3 border-t border-outline-variant flex items-center justify-between">
                        <dt class="text-[10px] font-bold text-on-surface-variant uppercase">ສະຖານະ</dt>
                        <dd>
                            @if ($document->is_active)
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-green-700 bg-green-50 border border-green-200 px-2 py-0.5 rounded-full">
                                    <span class="material-symbols-outlined text-xs">check_circle</span>
                                    ໃຊ້ງານ
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-gray-500 bg-gray-100 border border-gray-200 px-2 py-0.5 rounded-full">
                                    <span class="material-symbols-outlined text-xs">cancel</span>
                                    ບໍ່ໃຊ້ງານ
                                </span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm animate-fade-in">
                <h3 class="text-label-md text-on-surface-variant uppercase tracking-wider mb-4">ດຳເນີນການ</h3>
                <div class="space-y-2">
                    <a href="{{ route('documents.edit', $document->id) }}"
                       class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg border border-outline-variant hover:bg-surface-container transition-colors text-body-md font-bold text-on-surface">
                        <span class="material-symbols-outlined text-primary text-lg">edit</span>
                        ແກ້ໄຂເອກະສານ
                    </a>
                    @if ($document->file_url)
                        <a href="{{ $document->file_url }}" target="_blank"
                           class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg border border-outline-variant hover:bg-surface-container transition-colors text-body-md font-bold text-on-surface">
                            <span class="material-symbols-outlined text-secondary text-lg">open_in_new</span>
                            ເປີດໄຟລ໌ໃໝ່
                        </a>
                    @endif
                    <a href="{{ route('documents.create') }}"
                       class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg border border-outline-variant hover:bg-surface-container transition-colors text-body-md font-bold text-on-surface">
                        <span class="material-symbols-outlined text-tertiary text-lg">upload_file</span>
                        ເພີ່ມເອກະສານໃໝ່
                    </a>
                </div>
            </div>
        </div>
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
                    <p class="text-label-md text-on-surface-variant">ເອກະສານ / Document:</p>
                    <p class="text-body-md font-bold text-primary text-left">{{ $document->title_lo }}</p>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button"
                        @click="showDeleteModal = false"
                        class="px-4 py-2.5 rounded-lg border border-outline-variant text-label-md font-bold text-on-surface-variant hover:bg-surface-container transition-all">
                    ຍົກເລີກ / Cancel
                </button>
                <button type="button"
                        @click="$wire.delete(); showDeleteModal = false"
                        class="px-4 py-2.5 rounded-lg bg-error hover:bg-error/90 text-white font-bold text-label-md transition-all shadow-md btn-press">
                    ລຶບຂໍ້ມູນ / Confirm Delete
                </button>
            </div>
        </div>
    </div>
</div>
