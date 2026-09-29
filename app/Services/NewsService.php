<?php

namespace App\Services;

use App\Models\News;
use App\Services\FrontendCacheService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class NewsService
{
    public function create(array $data, ?UploadedFile $coverImage = null, array $galleryImages = []): News
    {
        if ($coverImage) {
            $data['cover_image'] = $coverImage->store('news/covers', 'public');
        }

        $galleryPaths = [];
        foreach ($galleryImages as $img) {
            if ($img instanceof UploadedFile) {
                $galleryPaths[] = $img->store('news/gallery', 'public');
            }
        }
        if (!empty($galleryPaths)) {
            $data['gallery_images'] = $galleryPaths;
        }

        $data['author_id'] = auth()->id();

        $news = News::create($data);
        $this->clearFrontendCache($news->id);
        return $news;
    }

    public function update(
        int $id,
        array $data,
        ?UploadedFile $coverImage = null,
        array $newGalleryImages = [],
        array $keptGalleryImages = [],
        array $removedGalleryImages = []
    ): News {
        $news = News::findOrFail($id);

        if ($coverImage) {
            if ($news->cover_image) {
                Storage::disk('public')->delete($news->cover_image);
            }
            $data['cover_image'] = $coverImage->store('news/covers', 'public');
        }

        // Delete files that user explicitly removed
        foreach ($removedGalleryImages as $oldPath) {
            Storage::disk('public')->delete($oldPath);
        }

        // Store new gallery images
        $newPaths = [];
        foreach ($newGalleryImages as $img) {
            if ($img instanceof UploadedFile) {
                $newPaths[] = $img->store('news/gallery', 'public');
            }
        }

        // Combine kept existing images with newly stored images (max 5)
        $data['gallery_images'] = array_values(array_slice(array_merge($keptGalleryImages, $newPaths), 0, 5));

        $news->update($data);
        $this->clearFrontendCache($id);
        return $news->fresh();
    }

    public function delete(int $id): void
    {
        $news = News::findOrFail($id);

        if ($news->cover_image) {
            Storage::disk('public')->delete($news->cover_image);
        }

        if (!empty($news->gallery_images) && is_array($news->gallery_images)) {
            foreach ($news->gallery_images as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        $news->delete();
        $this->clearFrontendCache($id);
    }

    public function toggleActive(int $id): void
    {
        $news = News::findOrFail($id);
        $news->update(['is_active' => !$news->is_active]);
        $this->clearFrontendCache($id);
    }

    public function toggleFeatured(int $id): void
    {
        $news = News::findOrFail($id);
        $news->update(['is_featured' => !$news->is_featured]);
        $this->clearFrontendCache($id);
    }

    private function clearFrontendCache(int $newsId): void
    {
        FrontendCacheService::clearNews();
        Cache::forget("frontend_news_related_{$newsId}");
    }

    public function getStatistics(): array
    {
        return [
            'total'      => News::count(),
            'active'     => News::active()->count(),
            'published'  => News::published()->count(),
            'featured'   => News::featured()->count(),
            'this_month' => News::whereMonth('created_at', now()->month)
                                ->whereYear('created_at', now()->year)
                                ->count(),
        ];
    }
}
