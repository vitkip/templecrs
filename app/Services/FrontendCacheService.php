<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class FrontendCacheService
{
    const KEY_SLIDES          = 'frontend_slides';
    const KEY_NEWS_LATEST     = 'frontend_news_latest';
    const KEY_NEWS_FEATURED   = 'frontend_news_featured';
    const KEY_NEWS_ALL        = 'frontend_news_all';
    const KEY_NEWS_CATEGORIES = 'frontend_news_categories';
    const KEY_PERSONNEL       = 'frontend_personnel';
    const KEY_DOCUMENTS       = 'frontend_documents';
    const KEY_SETTINGS        = 'frontend_settings';

    public static function clearSlides(): void
    {
        Cache::forget(self::KEY_SLIDES);
    }

    public static function clearNews(): void
    {
        Cache::forget(self::KEY_NEWS_LATEST);
        Cache::forget(self::KEY_NEWS_FEATURED);
        Cache::forget(self::KEY_NEWS_ALL);
        Cache::forget(self::KEY_NEWS_CATEGORIES);
    }

    public static function clearPersonnel(): void
    {
        Cache::forget(self::KEY_PERSONNEL);
    }

    public static function clearDocuments(): void
    {
        Cache::forget(self::KEY_DOCUMENTS);
    }

    public static function clearSettings(): void
    {
        Cache::forget(self::KEY_SETTINGS);
    }

    public static function clearAll(): void
    {
        Cache::forget(self::KEY_SLIDES);
        Cache::forget(self::KEY_NEWS_LATEST);
        Cache::forget(self::KEY_NEWS_FEATURED);
        Cache::forget(self::KEY_NEWS_ALL);
        Cache::forget(self::KEY_NEWS_CATEGORIES);
        Cache::forget(self::KEY_PERSONNEL);
        Cache::forget(self::KEY_DOCUMENTS);
        Cache::forget(self::KEY_SETTINGS);
    }
}
