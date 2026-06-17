<?php

namespace App\Services;

use App\Models\News;
use App\Services\FrontendCacheService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class NewsService
{
    public function create(array $data, ?UploadedFile $coverImage = null): News
    {
        if ($coverImage) {
            $data['cover_image'] = $coverImage->store('news/covers', 'public');
        }

        $data['author_id'] = auth()->id();

        $news = News::create($data);
        $this->clearFrontendCache($news->id);
        return $news;
    }

    public function update(int $id, array $data, ?UploadedFile $coverImage = null): News
    {
        $news = News::findOrFail($id);

        if ($coverImage) {
            if ($news->cover_image) {
                Storage::disk('public')->delete($news->cover_image);
            }
            $data['cover_image'] = $coverImage->store('news/covers', 'public');
        }

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
