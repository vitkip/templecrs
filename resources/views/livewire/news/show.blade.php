@php
    $allPhotos = [];
    if ($news->cover_image_url) {
        $allPhotos[] = [
            'url'      => $news->cover_image_url,
            'caption'  => 'ຮູບປົກ: ' . $news->title_lo,
            'is_cover' => true,
        ];
    }
    if (!empty($news->gallery_images)) {
        foreach ($news->gallery_image_urls as $idx => $gUrl) {
            $allPhotos[] = [
                'url'      => $gUrl,
                'caption'  => 'ຮູບພາບປະກອບ ' . ($idx + 1) . ' — ' . $news->title_lo,
                'is_cover' => false,
            ];
        }
    }
@endphp

<div x-data="{
    showDeleteModal: false,
    openLightbox: false,
    currentLightboxIndex: 0,
    photos: @js($allPhotos),
    touchStartX: 0,
    touchEndX: 0,
    openGallery(idx) {
        if (!this.photos || this.photos.length === 0) return;
        this.currentLightboxIndex = (idx >= 0 && idx < this.photos.length) ? idx : 0;
        this.openLightbox = true;
        document.body.style.overflow = 'hidden';
    },
    closeGallery() {
        this.openLightbox = false;
        document.body.style.overflow = '';
    },
    nextPhoto() {
        if (this.photos && this.photos.length > 1) {
            this.currentLightboxIndex = (this.currentLightboxIndex + 1) % this.photos.length;
        }
    },
    prevPhoto() {
        if (this.photos && this.photos.length > 1) {
            this.currentLightboxIndex = (this.currentLightboxIndex - 1 + this.photos.length) % this.photos.length;
        }
    },
    goTo(idx) {
        if (idx >= 0 && idx < this.photos.length) {
            this.currentLightboxIndex = idx;
        }
    },
    handleKeyDown(e) {
        if (!this.openLightbox) return;
        if (e.key === 'Escape') this.closeGallery();
        if (e.key === 'ArrowRight' || e.key === 'ArrowDown') this.nextPhoto();
        if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') this.prevPhoto();
    },
    handleTouchStart(e) {
        if (e.changedTouches && e.changedTouches.length > 0) {
            this.touchStartX = e.changedTouches[0].screenX;
        }
    },
    handleTouchEnd(e) {
        if (e.changedTouches && e.changedTouches.length > 0) {
            this.touchEndX = e.changedTouches[0].screenX;
            const diff = this.touchEndX - this.touchStartX;
            if (Math.abs(diff) > 40) {
                if (diff < 0) this.nextPhoto();
                else this.prevPhoto();
            }
        }
    }
}"
@keydown.window="handleKeyDown($event)">
    {{-- Page Header --}}
    <div class="flex justify-between items-start mb-8 animate-fade-in">
        <div>
            <div class="flex items-center gap-2 flex-wrap mb-2">
                @if ($news->category)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold text-primary bg-primary/10 border border-primary/20">
                        <span class="material-symbols-outlined text-xs">{{ $news->category->icon }}</span>
                        {{ $news->category->name_lo }}
                    </span>
                @endif
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
                <div class="group relative bg-white rounded-xl border border-outline-variant overflow-hidden shadow-sm animate-fade-in cursor-pointer"
                     @click="openGallery(0)"
                     title="ຄລິກເພື່ອເບິ່ງຮູບຂະໜາດໃຫຍ່">
                    <img src="{{ $news->cover_image_url }}" alt="{{ $news->title_lo }}" loading="lazy" class="w-full h-64 sm:h-80 object-cover group-hover:scale-102 transition-transform duration-500" />
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                        <span class="w-10 h-10 rounded-full bg-black/60 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity backdrop-blur-xs shadow-md">
                            <span class="material-symbols-outlined">zoom_in</span>
                        </span>
                    </div>
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

            {{-- Gallery Images --}}
            @if (!empty($news->gallery_images) && count($news->gallery_images) > 0)
                <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm animate-fade-in">
                    <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
                        <h3 class="text-headline-sm text-on-surface flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">photo_library</span>
                            ຮູບພາບປະກອບ / Gallery
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-primary/10 text-primary">
                                {{ count($news->gallery_images) }} ຮູບ
                            </span>
                        </h3>
                        <button type="button"
                                @click="openGallery({{ $news->cover_image_url ? 1 : 0 }})"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-primary bg-primary/10 hover:bg-primary/15 border border-primary/20 transition-all cursor-pointer">
                            <span class="material-symbols-outlined text-sm">slideshow</span>
                            <span>ເບິ່ງສະໄລ້ຮູບພາບ</span>
                        </button>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach ($news->gallery_image_urls as $idx => $gUrl)
                            @php $photoIdx = $news->cover_image_url ? $idx + 1 : $idx; @endphp
                            <div @click="openGallery({{ $photoIdx }})"
                                 class="group relative aspect-[4/3] rounded-xl overflow-hidden bg-surface-container border border-outline-variant cursor-pointer hover:border-primary/50 transition-all duration-200 shadow-xs hover:shadow-md"
                                 title="ຄລິກເພື່ອເບິ່ງຮູບໃຫຍ່">
                                <img src="{{ $gUrl }}" alt="{{ $news->title_lo }} - {{ $idx + 1 }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end justify-between p-2.5">
                                    <span class="text-white text-xs font-medium">ຮູບທີ {{ $idx + 1 }}</span>
                                    <span class="w-7 h-7 rounded-full bg-white/20 text-white flex items-center justify-center backdrop-blur-xs">
                                        <span class="material-symbols-outlined text-sm">zoom_in</span>
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar Info --}}
        <div class="col-span-12 lg:col-span-4 space-y-6">

            {{-- Article Details --}}
            <div class="bg-white rounded-xl border border-outline-variant p-6 shadow-sm animate-fade-in">
                <h3 class="text-label-md text-on-surface-variant uppercase tracking-wider mb-4">ຂໍ້ມູນຂ່າວ</h3>

                <dl class="space-y-3">
                    @if ($news->category)
                        <div>
                            <dt class="text-[10px] font-bold text-on-surface-variant uppercase">ໝວດຂ່າວ / Category</dt>
                            <dd class="mt-0.5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-body-sm font-semibold text-primary bg-primary/10 border border-primary/15">
                                    <span class="material-symbols-outlined text-sm">{{ $news->category->icon }}</span>
                                    {{ $news->category->name_lo }}
                                    @if ($news->category->name_en)
                                        <span class="text-on-surface-variant/60">/ {{ $news->category->name_en }}</span>
                                    @endif
                                </span>
                            </dd>
                        </div>
                    @endif

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

    {{-- Lightbox Modal (Full-screen Photo Slider) --}}
    <template x-teleport="body">
        <div x-show="openLightbox"
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[99999] flex flex-col bg-black/95 backdrop-blur-md select-none"
             role="dialog"
             aria-modal="true"
             aria-label="Photo Lightbox">

            {{-- Top Bar --}}
            <div class="flex items-center justify-between px-4 sm:px-6 py-3.5 bg-gradient-to-b from-black/80 to-transparent z-30 shrink-0">
                <div class="flex items-center gap-3 min-w-0">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold text-amber-300 bg-amber-500/20 border border-amber-500/30">
                        <span class="material-symbols-outlined text-sm">photo_library</span>
                        <span x-text="(currentLightboxIndex + 1) + ' / ' + photos.length"></span>
                    </span>
                    <p class="text-white/80 text-sm font-medium truncate max-w-xs sm:max-w-md md:max-w-xl"
                       x-text="photos[currentLightboxIndex]?.caption || ''">
                    </p>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <a :href="photos[currentLightboxIndex]?.url"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-all cursor-pointer"
                       title="ເປີດຮູບຕົ້ນສະບັບໃນແທັບໃໝ່">
                        <span class="material-symbols-outlined text-lg">open_in_new</span>
                    </a>
                    <button type="button"
                            @click="closeGallery()"
                            class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/25 active:scale-95 text-white flex items-center justify-center transition-all cursor-pointer group"
                            title="ປິດ (Esc)">
                        <span class="material-symbols-outlined text-xl group-hover:rotate-90 transition-transform duration-200">close</span>
                    </button>
                </div>
            </div>

            {{-- Main Slider Area --}}
            <div class="relative flex-1 flex items-center justify-center overflow-hidden px-2 sm:px-16"
                 @click.self="closeGallery()"
                 @touchstart="handleTouchStart($event)"
                 @touchend="handleTouchEnd($event)">

                {{-- Left navigation button --}}
                <button type="button"
                        x-show="photos.length > 1"
                        @click.stop="prevPhoto()"
                        class="absolute left-3 sm:left-6 z-20 w-11 h-11 sm:w-14 sm:h-14 rounded-full bg-black/40 hover:bg-black/70 active:scale-95 text-white backdrop-blur-md flex items-center justify-center border border-white/20 hover:border-amber-400/60 shadow-2xl transition-all duration-200 cursor-pointer"
                        title="ຮູບກ່ອນໜ້າ (←)">
                    <span class="material-symbols-outlined text-2xl sm:text-3xl">chevron_left</span>
                </button>

                {{-- Image Display with Smooth Transition --}}
                <div class="relative w-full h-full flex items-center justify-center p-2 sm:p-4 pointer-events-none">
                    <template x-for="(photo, idx) in photos" :key="idx">
                        <div x-show="currentLightboxIndex === idx"
                             x-transition:enter="transition ease-out duration-250"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute inset-0 flex items-center justify-center p-2 pointer-events-auto">
                            <img :src="photo.url"
                                 :alt="photo.caption"
                                 class="max-h-[72vh] sm:max-h-[76vh] max-w-full object-contain rounded-xl shadow-2xl select-none" />
                        </div>
                    </template>
                </div>

                {{-- Right navigation button --}}
                <button type="button"
                        x-show="photos.length > 1"
                        @click.stop="nextPhoto()"
                        class="absolute right-3 sm:right-6 z-20 w-11 h-11 sm:w-14 sm:h-14 rounded-full bg-black/40 hover:bg-black/70 active:scale-95 text-white backdrop-blur-md flex items-center justify-center border border-white/20 hover:border-amber-400/60 shadow-2xl transition-all duration-200 cursor-pointer"
                        title="ຮູບຖັດໄປ (→)">
                    <span class="material-symbols-outlined text-2xl sm:text-3xl">chevron_right</span>
                </button>
            </div>

            {{-- Bottom Thumbnails strip & info --}}
            <div class="p-3 bg-black/70 backdrop-blur-md border-t border-white/10 flex flex-col items-center gap-2 shrink-0 z-30">
                <div class="flex items-center justify-center gap-2 overflow-x-auto max-w-full py-1 px-4 no-scrollbar">
                    <template x-for="(p, pIdx) in photos" :key="pIdx">
                        <button type="button"
                                @click="goTo(pIdx)"
                                class="relative h-12 w-16 sm:h-14 sm:w-20 rounded-lg overflow-hidden border-2 transition-all shrink-0 cursor-pointer"
                                :class="currentLightboxIndex === pIdx
                                    ? 'border-amber-400 ring-2 ring-amber-400/50 scale-105 opacity-100'
                                    : 'border-white/20 opacity-40 hover:opacity-90'">
                            <img :src="p.url" :alt="'Thumbnail ' + (pIdx + 1)" class="w-full h-full object-cover">
                            <span x-show="p.is_cover"
                                  class="absolute top-0.5 left-0.5 px-1 py-0.2 rounded text-[8px] font-bold bg-amber-500 text-white">
                                ປົກ
                            </span>
                        </button>
                    </template>
                </div>

                <div class="hidden sm:flex items-center gap-4 text-[11px] text-white/50">
                    <span class="flex items-center gap-1">
                        <kbd class="px-1.5 py-0.5 rounded bg-white/10 text-white/80 font-mono text-[10px]">←</kbd>
                        <kbd class="px-1.5 py-0.5 rounded bg-white/10 text-white/80 font-mono text-[10px]">→</kbd>
                        <span>ເລື່ອນຮູບ</span>
                    </span>
                    <span>•</span>
                    <span class="flex items-center gap-1">
                        <kbd class="px-1.5 py-0.5 rounded bg-white/10 text-white/80 font-mono text-[10px]">Esc</kbd>
                        <span>ປິດ</span>
                    </span>
                    <span>•</span>
                    <span>ເລື່ອນປັດ (Swipe) ເທິງຈໍມືຖືໄດ້</span>
                </div>
            </div>
        </div>
    </template>
</div>
