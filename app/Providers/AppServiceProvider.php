<?php

namespace App\Providers;

use App\Models\Document;
use App\Models\HeroSlide;
use App\Models\News;
use App\Models\Personnel;
use App\Models\Setting;
use App\Observers\FrontendCacheObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        HeroSlide::observe(FrontendCacheObserver::class);
        News::observe(FrontendCacheObserver::class);
        Personnel::observe(FrontendCacheObserver::class);
        Document::observe(FrontendCacheObserver::class);
        Setting::observe(FrontendCacheObserver::class);
    }
}
