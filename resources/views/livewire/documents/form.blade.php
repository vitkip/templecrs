<div>
    {{-- Page Header --}}
    <div class="flex justify-between items-center mb-8 animate-fade-in">
        <div>
            <h2 class="text-headline-lg text-on-surface mb-1">
                {{ $editMode ? 'ແກ້ໄຂເອກະສານ' : 'ອັບໂຫລດເອກະສານໃໝ່' }}
            </h2>
            <p class="text-body-md text-on-surface-variant">
                {{ $editMode ? 'Edit Document Record' : 'Upload New Document' }}
            </p>
        </div>
        <a href="{{ route('documents.index') }}"
           class="px-4 py-2 border border-outline-variant rounded-lg text-body-md font-bold text-on-surface-variant hover:bg-surface-container transition-all flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            ກັບຄືນ
        </a>
    </div>

    <form wire:submit="save" class="space-y-8">

        {{-- ═══ Section 1: Category ═══ --}}
        <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm animate-fade-in">
            <h3 class="text-headline-sm text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">category</span>
                ໝວດເອກະສານ / Document Category
            </h3>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                @foreach ($categories as $cat)
                    <label class="cursor-pointer">
                        <input type="radio" wire:model.live="category" value="{{ $cat->slug }}" class="hidden peer" />
                        <div class="peer-checked:border-primary peer-checked:bg-primary/5 border-2 border-outline-variant rounded-xl p-4 text-center transition-all hover:border-primary/40">
                            <span class="material-symbols-outlined text-2xl mb-1.5 block text-on-surface-variant peer-checked:text-primary">{{ $cat->icon }}</span>
                            <span class="font-bold block text-xs text-on-surface leading-tight">{{ $cat->name_lo }}</span>
                            <span class="text-[10px] text-on-surface-variant">{{ $cat->name_en }}</span>
                        </div>
                    </label>
                @endforeach
            </div>
            @error('category') <p class="form-error mt-2">{{ $message }}</p> @enderror
        </div>

        {{-- ═══ Section 2: Title & Document Number ═══ --}}
        <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm animate-fade-in">
            <h3 class="text-headline-sm text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">title</span>
                ຊື່ ແລະ ເລກທີເອກະສານ / Title & Reference
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="bilingual-required">
                    <label class="form-label">
                        ຊື່ເອກະສານ <span class="text-xs text-on-surface-variant">(ລາວ)</span>
                        <span class="text-error">*</span>
                    </label>
                    <input type="text" wire:model="title_lo" placeholder="ຊື່ເອກະສານ..." class="form-input" />
                    @error('title_lo') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Document Title <span class="text-xs text-on-surface-variant">(English)</span></label>
                    <input type="text" wire:model="title_en" placeholder="Document title..." class="form-input" />
                </div>
            </div>

            <div class="max-w-xs">
                <label class="form-label">ເລກທີ / Reference Number</label>
                <input type="text" wire:model="doc_number"
                       placeholder="ຕ.ວ. 001/ທ.ລ.ສ/2026"
                       class="form-input font-mono" />
                @error('doc_number') <p class="form-error">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- ═══ Section 3: Description ═══ --}}
        <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm animate-fade-in">
            <h3 class="text-headline-sm text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">notes</span>
                ເນື້ອໃນ / Description
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bilingual-required">
                    <label class="form-label">ລາຍລະອຽດ <span class="text-xs text-on-surface-variant">(ລາວ)</span></label>
                    <textarea wire:model="description_lo" rows="4"
                              placeholder="ອະທິບາຍເນື້ອໃນຂອງເອກະສານ..."
                              class="form-input"></textarea>
                </div>
                <div>
                    <label class="form-label">Description <span class="text-xs text-on-surface-variant">(English)</span></label>
                    <textarea wire:model="description_en" rows="4"
                              placeholder="Brief description of this document..."
                              class="form-input"></textarea>
                </div>
            </div>
        </div>

        {{-- ═══ Section 4: Department & Date ═══ --}}
        <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm animate-fade-in">
            <h3 class="text-headline-sm text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">corporate_fare</span>
                ພາກສ່ວນ ແລະ ວັນທີ / Department & Date
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">ພາກສ່ວນ / Department</label>
                    <select wire:model="department_id" class="form-input">
                        <option value="">— ເລືອກພາກສ່ວນ —</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept->id }}">
                                {{ $dept->name_lo }} {{ $dept->name_en ? '(' . $dept->name_en . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">ວັນທີ່ອອກ / Issued Date</label>
                    <input type="date" wire:model="issued_date" class="form-input" />
                    @error('issued_date') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- ═══ Section 5: File Upload ═══ --}}
        <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm animate-fade-in">
            <h3 class="text-headline-sm text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">attach_file</span>
                ໄຟລ໌ / File Attachment
            </h3>

            @if ($existing_file_name && !$file)
                <div class="mb-4 flex items-center gap-3 p-3 bg-surface-container-low rounded-lg border border-outline-variant">
                    <span class="material-symbols-outlined text-primary text-2xl">insert_drive_file</span>
                    <div class="flex-1">
                        <p class="text-body-md font-bold text-on-surface">{{ $existing_file_name }}</p>
                        <p class="text-xs text-on-surface-variant">ໄຟລ໌ປັດຈຸບັນ — ເລືອກໄຟລ໌ໃໝ່ເພື່ອປ່ຽນ</p>
                    </div>
                    <a href="{{ route('documents.download', $documentId) }}" target="_blank"
                       class="text-primary hover:text-primary-container flex items-center gap-1 text-label-md">
                        <span class="material-symbols-outlined text-base">download</span>
                        ດາວໂຫລດ
                    </a>
                </div>
            @endif

            @if ($file)
                <div class="mb-4 flex items-center gap-3 p-3 bg-green-50 rounded-lg border border-green-200">
                    <span class="material-symbols-outlined text-green-600 text-2xl">check_circle</span>
                    <div>
                        <p class="text-body-md font-bold text-green-800">{{ $file->getClientOriginalName() }}</p>
                        <p class="text-xs text-green-700">{{ number_format($file->getSize() / 1024, 1) }} KB — ພ້ອມອັບໂຫລດ</p>
                    </div>
                </div>
            @endif

            <div x-data="{ dragging: false }"
                 @dragover.prevent="dragging = true"
                 @dragleave.prevent="dragging = false"
                 @drop.prevent="dragging = false; $refs.fileInput.files = $event.dataTransfer.files; $refs.fileInput.dispatchEvent(new Event('change'))"
                 :class="dragging ? 'border-primary bg-primary/5' : 'border-outline-variant bg-surface-container-lowest'"
                 class="border-2 border-dashed rounded-xl p-8 text-center transition-all cursor-pointer"
                 @click="$refs.fileInput.click()">
                <span class="material-symbols-outlined text-5xl text-on-surface-variant/40 mb-3 block">cloud_upload</span>
                <p class="text-body-md font-bold text-on-surface mb-1">ລາກໄຟລ໌ມາວາງ ຫຼື ຄລິກເພື່ອເລືອກ</p>
                <p class="text-xs text-on-surface-variant">PDF, Word, Excel, JPG, PNG · ສູງສຸດ 500MB</p>
                <input type="file"
                       x-ref="fileInput"
                       wire:model="file"
                       accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.webp"
                       class="hidden" />
            </div>
            <div wire:loading wire:target="file" class="mt-2 text-sm text-primary flex items-center gap-2">
                <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                ກຳລັງອ່ານໄຟລ໌...
            </div>
            @error('file') <p class="form-error mt-2">{{ $message }}</p> @enderror
        </div>

        {{-- ═══ Section 6: Settings ═══ --}}
        <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm animate-fade-in">
            <h3 class="text-headline-sm text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">tune</span>
                ການຕັ້ງຄ່າ / Settings
            </h3>

            <div class="flex items-center gap-8">
                <div>
                    <label class="form-label">ລຳດັບ / Sort Order</label>
                    <input type="number" wire:model="sort_order" min="0" class="form-input w-24" />
                </div>
                <div class="flex items-center gap-3">
                    <label class="toggle-switch">
                        <input type="checkbox" wire:model="is_active" />
                        <span class="toggle-slider"></span>
                    </label>
                    <span class="form-label">ເຜີຍແຜ່ / Published</span>
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex justify-end gap-4 animate-fade-in">
            <a href="{{ route('documents.index') }}"
               class="px-6 py-3 border border-outline-variant rounded-lg text-body-md font-bold text-on-surface-variant hover:bg-surface-container transition-all btn-press">
                ຍົກເລີກ
            </a>
            <button type="submit"
                    class="px-8 py-3 bg-primary text-white rounded-lg font-bold flex items-center gap-2 hover:bg-primary-container transition-all shadow-md btn-press"
                    wire:loading.attr="disabled" wire:loading.class="opacity-50">
                <span wire:loading.remove>
                    <span class="material-symbols-outlined text-sm">{{ $editMode ? 'save' : 'upload' }}</span>
                    {{ $editMode ? 'ອັບເດດ' : 'ອັບໂຫລດ' }}
                </span>
                <span wire:loading class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    ກຳລັງບັນທຶກ...
                </span>
            </button>
        </div>
    </form>
</div>
