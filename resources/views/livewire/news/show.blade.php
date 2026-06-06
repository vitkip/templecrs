<div x-data="{ showDeleteModal: false }">
    {{-- Page Header --}}
    <div class="flex justify-between items-start mb-8 animate-fade-in">
        <div>
            <div class="flex items-center gap-3 mb-2">
                @if ($news->is_featured)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold text-amber-700 bg-amber-50 border border-amber-200">
                        <span class="material-symbols-outlined text-xs filled">star</span>
                        ແນະນຳ
                    </span>
                @endif
                @if (!$news->is_active)
                    <span class="px-2 py-0.5 bg-outline-variant/30 text-on-surface-variant text-[10px] font-bold rounded-full uppercase">
                        ບໍ່ໃຊ້ງານ
                    </span>
                @endif
            </div>
            <h2 class="text-headline-lg text-on-surface mb-1">{{ $news->title_lo }}</h2>
            @if ($news->title_en)
                <p class="text-body-lg text-on-surface-variant">{{ $news->title_en }}</p>
            @endif
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('news.index') }}"
               class="px-4 py-2 border border-outline-variant rounded-lg text-body-md font-bold text-on-surface-variant hover:bg-surface-container transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                ກັບຄືນ
            </a>
            <a href="{{ route('news.edit', $news->id) }}"
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

        {{-- Main Content --}}
        <div class="col-span-12 lg:col-span-8 space-y-6">

            {{-- Cover Image --}}
            @if ($news->cover_image_url)
                <div class="bg-white rounded-xl border border-outline-variant overflow-hidden shadow-sm animate-fade-in">
                    <img src="{{ $news->cover_image_url }}" alt="{{ $news->title_lo }}" class="w-full h-64 object-cover" />
                </div>
            @endif

            {{-- Excerpt --}}
            @if ($news->excerpt_lo || $news->excerpt_en)
                <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm animate-fade-in">
                    <h3 class="text-headline-sm text-on-surface mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">short_text</span>
                        ສະຫຼຸບຫຍໍ້ / Excerpt
                    </h3>
                    @if ($news->excerpt_lo)
                        <div class="mb-3">
                            <p class="text-[10px] font-bold text-on-surface-variant uppercase mb-1">ລາວ</p>
                            <p class="text-body-md text-on-surface leading-relaxed">{{ $news->excerpt_lo }}</p>
                        </div>
                    @endif
                    @if ($news->excerpt_en)
                        <div>
                            <p class="text-[10px] font-bold text-on-surface-variant uppercase mb-1">English</p>
                            <p class="text-body-md text-on-surface leading-relaxed">{{ $news->excerpt_en }}</p>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Content --}}
            @if ($news->content_lo || $news->content_en)
                <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm animate-fade-in">
                    <h3 class="text-headline-sm text-on-surface mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">notes</span>
                        ເນື້ອໃນ / Content
                    </h3>
                    @if ($news->content_lo)
                        <div class="mb-4">
                            <p class="text-[10px] font-bold text-on-surface-variant uppercase mb-1">ລາວ</p>
                            <div class="text-body-md text-on-surface leading-relaxed whitespace-pre-line">{{ $news->content_lo }}</div>
                        </div>
                    @endif
                    @if ($news->content_en)
                        <div>
                            <p class="text-[10px] font-bold text-on-surface-variant uppercase mb-1">English</p>
                            <div class="text-body-md text-on-surface leading-relaxed whitespace-pre-line">{{ $news->content_en }}</div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        {{-- Sidebar Info --}}
        <div class="col-span-12 lg:col-span-4 space-y-6">

            {{-- Article Details --}}
            <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm animate-fade-in">
                <h3 class="text-label-md text-on-surface-variant uppercase tracking-wider mb-4">ຂໍ້ມູນຂ່າວ</h3>

                <dl class="space-y-3">
                    @if ($news->published_at)
                        <div>
                            <dt class="text-[10px] font-bold text-on-surface-variant uppercase">ວັນທີ່ເຜີຍແຜ່</dt>
                            <dd class="text-body-md text-on-surface mt-0.5">{{ $news->published_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    @endif

                    <div>
                        <dt class="text-[10px] font-bold text-on-surface-variant uppercase">ຜູ້ຂຽນ / Author</dt>
                        <dd class="text-body-md text-on-surface mt-0.5">{{ $news->author?->name ?? '—' }}</dd>
                    </div>

                    <div class="pt-3 border-t border-outline-variant">
                        <dt class="text-[10px] font-bold text-on-surface-variant uppercase">ວັນທີ່ເພີ່ມ</dt>
                        <dd class="text-body-md text-on-surface mt-0.5">{{ $news->created_at->format('d/m/Y H:i') }}</dd>
                    </div>

                    @if ($news->updated_at != $news->created_at)
                        <div>
                            <dt class="text-[10px] font-bold text-on-surface-variant uppercase">ແກ້ໄຂລ່າສຸດ</dt>
                            <dd class="text-body-md text-on-surface mt-0.5">{{ $news->updated_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    @endif

                    <div class="pt-3 border-t border-outline-variant flex items-center justify-between">
                        <dt class="text-[10px] font-bold text-on-surface-variant uppercase">ສະຖານະ</dt>
                        <dd>
                            @if ($news->is_active)
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
                    <a href="{{ route('news.edit', $news->id) }}"
                       class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg border border-outline-variant hover:bg-surface-container transition-colors text-body-md font-bold text-on-surface">
                        <span class="material-symbols-outlined text-primary text-lg">edit</span>
                        ແກ້ໄຂຂ່າວ
                    </a>
                    <a href="{{ route('news.create') }}"
                       class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg border border-outline-variant hover:bg-surface-container transition-colors text-body-md font-bold text-on-surface">
                        <span class="material-symbols-outlined text-tertiary text-lg">add_circle</span>
                        ເພີ່ມຂ່າວໃໝ່
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
                    ທ່ານແນ່ໃຈບໍ່ວ່າຕ້ອງການລຶບຂ່າວນີ້? ການດຳເນີນການນີ້ບໍ່ສາມາດກັບຄືນໄດ້.
                    <br>
                    <span class="text-xs opacity-75">Are you sure you want to delete this news article? This action cannot be undone.</span>
                </p>
                <div class="bg-surface-container-low p-3 rounded-lg border border-outline-variant/50">
                    <p class="text-label-md text-on-surface-variant">ຫົວຂໍ້ຂ່າວ / News Title:</p>
                    <p class="text-body-md font-bold text-primary text-left">{{ $news->title_lo }}</p>
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
