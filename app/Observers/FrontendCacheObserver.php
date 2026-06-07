<?php

namespace App\Observers;

use App\Models\Document;
use App\Models\HeroSlide;
use App\Models\News;
use App\Models\Personnel;
use App\Models\Setting;
use App\Services\FrontendCacheService;

class FrontendCacheObserver
{
    public function saved($model): void
    {
        $this->clearFor($model);
    }

    public function deleted($model): void
    {
        $this->clearFor($model);
    }

    public function restored($model): void
    {
        $this->clearFor($model);
    }

    private function clearFor($model): void
    {
        match (true) {
            $model instanceof HeroSlide => FrontendCacheService::clearSlides(),
            $model instanceof News      => FrontendCacheService::clearNews(),
            $model instanceof Personnel => FrontendCacheService::clearPersonnel(),
            $model instanceof Document  => FrontendCacheService::clearDocuments(),
            $model instanceof Setting   => FrontendCacheService::clearSettings(),
            default                     => null,
        };
    }
}
