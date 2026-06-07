<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\News;
use App\Models\Personnel;
use App\Models\Setting;
use App\Models\HeroSlide;
use App\Services\FrontendCacheService;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class FrontendController extends Controller
{
    public function index(): View
    {
        $slides = Cache::remember(FrontendCacheService::KEY_SLIDES, 1800, fn() =>
            HeroSlide::active()->ordered()->get()
        );

        $news = Cache::remember(FrontendCacheService::KEY_NEWS_LATEST, 600, fn() =>
            News::published()->ordered()->limit(6)->get()
        );

        $featuredNews = Cache::remember(FrontendCacheService::KEY_NEWS_FEATURED, 600, fn() =>
            News::published()->featured()->orderByDesc('published_at')->limit(3)->get()
        );

        $personnel = Cache::remember(FrontendCacheService::KEY_PERSONNEL, 3600, fn() =>
            Personnel::active()->ordered()->with('department')->limit(8)->get()
        );

        $documents = Cache::remember(FrontendCacheService::KEY_DOCUMENTS, 1800, fn() =>
            Document::active()->ordered()->with('department')->limit(8)->get()
        );

        $settings = Cache::remember(FrontendCacheService::KEY_SETTINGS, 86400, fn() => [
            'org_name_lo'  => Setting::get('org_name_lo', 'ອົງການພຣະພຸດທະສາສະໜາ'),
            'org_name_en'  => Setting::get('org_name_en', 'Buddhist Organization'),
            'org_logo_url' => Setting::get('org_logo_url'),
        ]);

        $orgName   = $settings['org_name_lo'];
        $orgNameEn = $settings['org_name_en'];
        $orgLogo   = $settings['org_logo_url'];

        return view('frontend.index', compact(
            'slides',
            'news',
            'featuredNews',
            'personnel',
            'documents',
            'orgName',
            'orgNameEn',
            'orgLogo',
        ));
    }
}
