<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Document;
use App\Models\News;
use App\Models\NewsCategory;
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

        $statsNewsCount      = Cache::remember('stats_news_count', 600, fn() => News::published()->count());
        $statsPersonnelCount = Cache::remember('stats_personnel_count', 3600, fn() => Personnel::active()->count());
        $statsDocumentsCount = Cache::remember('stats_documents_count', 1800, fn() => Document::active()->count());
        $statsMonksCount     = Cache::remember('stats_monks_count', 3600, fn() => Personnel::active()->where('gender', 'monk')->count());

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
            'statsNewsCount',
            'statsPersonnelCount',
            'statsDocumentsCount',
            'statsMonksCount',
        ));
    }

    public function newsIndex(): View
    {
        $news = Cache::remember('frontend_news_all', 600, fn() =>
            News::published()->with('category')->ordered()->get()
        );

        $categories = Cache::remember('frontend_news_categories', 1800, fn() =>
            NewsCategory::active()->ordered()->withCount([
                'news as news_count' => fn($q) => $q->published(),
            ])->get()
        );

        $settings = Cache::remember(FrontendCacheService::KEY_SETTINGS, 86400, fn() => [
            'org_name_lo'  => Setting::get('org_name_lo', 'ອົງການພຣະພຸດທະສາສະໜາ'),
            'org_name_en'  => Setting::get('org_name_en', 'Buddhist Organization'),
            'org_logo_url' => Setting::get('org_logo_url'),
        ]);

        $orgName   = $settings['org_name_lo'];
        $orgNameEn = $settings['org_name_en'];
        $orgLogo   = $settings['org_logo_url'];

        return view('frontend.news', compact('news', 'categories', 'orgName', 'orgNameEn', 'orgLogo'));
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
        $newsItem = News::published()->with('category')->findOrFail($id);

        $relatedNews = Cache::remember("frontend_news_related_{$id}", 600, function () use ($id, $newsItem) {
            if ($newsItem->news_category_id) {
                $result = News::published()->with('category')
                    ->where('id', '!=', $id)
                    ->where('news_category_id', $newsItem->news_category_id)
                    ->ordered()->limit(3)->get();
                if ($result->count() >= 2) return $result;
            }
            return News::published()->with('category')->where('id', '!=', $id)->ordered()->limit(3)->get();
        });

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
