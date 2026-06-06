<div>
    {{-- Page Header --}}
    <div class="flex justify-between items-center mb-8 animate-fade-in">
        <div>
            <h2 class="text-headline-lg text-on-surface mb-1">
                {{ $editMode ? 'ແກ້ໄຂຂ່າວ' : 'ເພີ່ມຂ່າວໃໝ່' }}
            </h2>
            <p class="text-body-md text-on-surface-variant">
                {{ $editMode ? 'Edit News Article' : 'Create New Article' }}
            </p>
        </div>
        <a href="{{ route('news.index') }}"
           class="px-4 py-2 border border-outline-variant rounded-lg text-body-md font-bold text-on-surface-variant hover:bg-surface-container transition-all flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            ກັບຄືນ
        </a>
    </div>

    <form wire:submit="save" class="space-y-8">

        {{-- ═══ Section 1: Title ═══ --}}
        <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm animate-fade-in">
            <h3 class="text-headline-sm text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">title</span>
                ຫົວຂໍ້ຂ່າວ / Article Title
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bilingual-required">
                    <label class="form-label">
                        ຫົວຂໍ້ <span class="text-xs text-on-surface-variant">(ລາວ)</span>
                        <span class="text-error">*</span>
                    </label>
                    <input type="text" wire:model="title_lo" placeholder="ຫົວຂໍ້ຂ່າວ..." class="form-input" />
                    @error('title_lo') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Title <span class="text-xs text-on-surface-variant">(English)</span></label>
                    <input type="text" wire:model="title_en" placeholder="Article title..." class="form-input" />
                </div>
            </div>
        </div>

        {{-- ═══ Section 2: Excerpt ═══ --}}
        <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm animate-fade-in">
            <h3 class="text-headline-sm text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">short_text</span>
                ສະຫຼຸບຫຍໍ້ / Excerpt
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bilingual-required">
                    <label class="form-label">ສະຫຼຸບ <span class="text-xs text-on-surface-variant">(ລາວ)</span></label>
                    <textarea wire:model="excerpt_lo" rows="3"
                              placeholder="ສະຫຼຸບເນື້ອໃນຂ່າວສັ້ນໆ..."
                              class="form-input"></textarea>
                </div>
                <div>
                    <label class="form-label">Excerpt <span class="text-xs text-on-surface-variant">(English)</span></label>
                    <textarea wire:model="excerpt_en" rows="3"
                              placeholder="Brief summary..."
                              class="form-input"></textarea>
                </div>
            </div>
        </div>

        {{-- ═══ Section 3: Content ═══ --}}
        <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm animate-fade-in">
            <h3 class="text-headline-sm text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">notes</span>
                ເນື້ອໃນ / Content
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bilingual-required">
                    <label class="form-label">ເນື້ອໃນ <span class="text-xs text-on-surface-variant">(ລາວ)</span></label>
                    <textarea wire:model="content_lo" rows="8"
                              placeholder="ເນື້ອໃນລາຍລະອຽດຂ່າວ..."
                              class="form-input"></textarea>
                </div>
                <div>
                    <label class="form-label">Content <span class="text-xs text-on-surface-variant">(English)</span></label>
                    <textarea wire:model="content_en" rows="8"
                              placeholder="Full article content..."
                              class="form-input"></textarea>
                </div>
            </div>
        </div>

        {{-- ═══ Section 4: Cover Image ═══ --}}
        <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm animate-fade-in">
            <h3 class="text-headline-sm text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">image</span>
                ຮູບປົກ / Cover Image
            </h3>

            @if ($existing_cover_image && !$cover_image)
                <div class="mb-4 flex items-center gap-3 p-3 bg-surface-container-low rounded-lg border border-outline-variant">
                    <img src="{{ Storage::url($existing_cover_image) }}" alt="" class="w-20 h-14 rounded-lg object-cover" />
                    <div class="flex-1">
                        <p class="text-body-md font-bold text-on-surface">ຮູບປົກປັດຈຸບັນ</p>
                        <p class="text-xs text-on-surface-variant">ເລືອກຮູບໃໝ່ເພື່ອປ່ຽນ</p>
                    </div>
                </div>
            @endif

            @if ($cover_image)
                <div class="mb-4 flex items-center gap-3 p-3 bg-green-50 rounded-lg border border-green-200">
                    <img src="{{ $cover_image->temporaryUrl() }}" alt="" class="w-20 h-14 rounded-lg object-cover" />
                    <div>
                        <p class="text-body-md font-bold text-green-800">{{ $cover_image->getClientOriginalName() }}</p>
                        <p class="text-xs text-green-700">{{ number_format($cover_image->getSize() / 1024, 1) }} KB — ພ້ອມອັບໂຫລດ</p>
                    </div>
                </div>
            @endif

            <div x-data="{ dragging: false }"
                 @dragover.prevent="dragging = true"
                 @dragleave.prevent="dragging = false"
                 @drop.prevent="dragging = false; $refs.coverInput.files = $event.dataTransfer.files; $refs.coverInput.dispatchEvent(new Event('change'))"
                 :class="dragging ? 'border-primary bg-primary/5' : 'border-outline-variant bg-surface-container-lowest'"
                 class="border-2 border-dashed rounded-xl p-8 text-center transition-all cursor-pointer"
                 @click="$refs.coverInput.click()">
                <span class="material-symbols-outlined text-5xl text-on-surface-variant/40 mb-3 block">add_photo_alternate</span>
                <p class="text-body-md font-bold text-on-surface mb-1">ລາກຮູບມາວາງ ຫຼື ຄລິກເພື່ອເລືອກ</p>
                <p class="text-xs text-on-surface-variant">JPG, PNG, WebP · ສູງສຸດ 10MB</p>
                <input type="file"
                       x-ref="coverInput"
                       wire:model="cover_image"
                       accept="image/jpeg,image/png,image/webp"
                       class="hidden" />
            </div>
            <div wire:loading wire:target="cover_image" class="mt-2 text-sm text-primary flex items-center gap-2">
                <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                ກຳລັງອ່ານໄຟລ໌...
            </div>
            @error('cover_image') <p class="form-error mt-2">{{ $message }}</p> @enderror
        </div>

        {{-- ═══ Section 5: Publishing Settings ═══ --}}
        <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm animate-fade-in">
            <h3 class="text-headline-sm text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">tune</span>
                ການຕັ້ງຄ່າ / Settings
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="form-label">ວັນທີ່ເຜີຍແຜ່ / Publish Date</label>
                    <input type="datetime-local" wire:model="published_at" class="form-input" />
                    @error('published_at') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">ລຳດັບ / Sort Order</label>
                    <input type="number" wire:model="sort_order" min="0" class="form-input w-24" />
                </div>
                <div class="flex items-end gap-6 pb-1">
                    <div class="flex items-center gap-3">
                        <label class="toggle-switch">
                            <input type="checkbox" wire:model="is_active" />
                            <span class="toggle-slider"></span>
                        </label>
                        <span class="form-label">ເຜີຍແຜ່ / Published</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <label class="toggle-switch">
                            <input type="checkbox" wire:model="is_featured" />
                            <span class="toggle-slider"></span>
                        </label>
                        <span class="form-label">ແນະນຳ / Featured</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex justify-end gap-4 animate-fade-in">
            <a href="{{ route('news.index') }}"
               class="px-6 py-3 border border-outline-variant rounded-lg text-body-md font-bold text-on-surface-variant hover:bg-surface-container transition-all btn-press">
                ຍົກເລີກ
            </a>
            <button type="submit"
                    class="px-8 py-3 bg-primary text-white rounded-lg font-bold flex items-center gap-2 hover:bg-primary-container transition-all shadow-md btn-press"
                    wire:loading.attr="disabled" wire:loading.class="opacity-50">
                <span wire:loading.remove>
                    <span class="material-symbols-outlined text-sm">{{ $editMode ? 'save' : 'add' }}</span>
                    {{ $editMode ? 'ອັບເດດ' : 'ບັນທຶກ' }}
                </span>
                <span wire:loading class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    ກຳລັງບັນທຶກ...
                </span>
            </button>
        </div>
    </form>
</div>
