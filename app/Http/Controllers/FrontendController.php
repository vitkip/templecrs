<?php

namespace App\Http\Controllers;

use App\Models\Department;
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

    public function newsIndex(): View
    {
        $news = Cache::remember('frontend_news_all', 600, fn() =>
            News::published()->ordered()->get()
        );

        $settings = Cache::remember(FrontendCacheService::KEY_SETTINGS, 86400, fn() => [
            'org_name_lo'  => Setting::get('org_name_lo', 'ອົງການພຣະພຸດທະສາສະໜາ'),
            'org_name_en'  => Setting::get('org_name_en', 'Buddhist Organization'),
            'org_logo_url' => Setting::get('org_logo_url'),
        ]);

        $orgName   = $settings['org_name_lo'];
        $orgNameEn = $settings['org_name_en'];
        $orgLogo   = $settings['org_logo_url'];

        return view('frontend.news', compact('news', 'orgName', 'orgNameEn', 'orgLogo'));
    }

    public function personnelIndex(): View
    {
        $personnel = Personnel::active()->ordered()->with('department')->get();
        $departments = Department::active()->ordered()
            ->whereHas('personnel', fn($q) => $q->where('is_active', true))
            ->get();

        $settings = Cache::remember(FrontendCacheService::KEY_SETTINGS, 86400, fn() => [
            'org_name_lo'  => Setting::get('org_name_lo', 'ອົງການພຣະພຸດທະສາສະໜາ'),
            'org_name_en'  => Setting::get('org_name_en', 'Buddhist Organization'),
            'org_logo_url' => Setting::get('org_logo_url'),
        ]);

        $orgName   = $settings['org_name_lo'];
        $orgNameEn = $settings['org_name_en'];
        $orgLogo   = $settings['org_logo_url'];

        return view('frontend.personnel', compact(
            'personnel',
            'departments',
            'orgName',
            'orgNameEn',
            'orgLogo',
        ));
    }

    public function documentsIndex(): View
    {
        $documents = Document::active()->ordered()->with('department')->get();
        $departments = Department::active()->ordered()
            ->whereHas('documents', fn($q) => $q->where('is_active', true))
            ->get();

        $totalDownloads = $documents->sum('download_count');

        $settings = Cache::remember(FrontendCacheService::KEY_SETTINGS, 86400, fn() => [
            'org_name_lo'  => Setting::get('org_name_lo', 'ອົງການພຣະພຸດທະສາສະໜາ'),
            'org_name_en'  => Setting::get('org_name_en', 'Buddhist Organization'),
            'org_logo_url' => Setting::get('org_logo_url'),
        ]);

        $orgName   = $settings['org_name_lo'];
        $orgNameEn = $settings['org_name_en'];
        $orgLogo   = $settings['org_logo_url'];

        return view('frontend.documents', compact(
            'documents',
            'departments',
            'totalDownloads',
            'orgName',
            'orgNameEn',
            'orgLogo',
        ));
    }

    public function show(int $id): View
    {
        $newsItem = News::published()->findOrFail($id);

        // Fetch other latest news for related section, excluding current news
        $relatedNews = Cache::remember("frontend_news_related_{$id}", 600, fn() =>
            News::published()->where('id', '!=', $id)->ordered()->limit(3)->get()
        );

        $settings = Cache::remember(FrontendCacheService::KEY_SETTINGS, 86400, fn() => [
            'org_name_lo'  => Setting::get('org_name_lo', 'ອົງການພຣະພຸດທະສາສະໜາ'),
            'org_name_en'  => Setting::get('org_name_en', 'Buddhist Organization'),
            'org_logo_url' => Setting::get('org_logo_url'),
        ]);

        $orgName   = $settings['org_name_lo'];
        $orgNameEn = $settings['org_name_en'];
        $orgLogo   = $settings['org_logo_url'];

        return view('frontend.news_show', compact(
            'newsItem',
            'relatedNews',
            'orgName',
            'orgNameEn',
            'orgLogo',
        ));
    }
}
