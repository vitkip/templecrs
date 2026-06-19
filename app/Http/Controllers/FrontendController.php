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

    public function personnelShow(int $id): View
    {
        $person = Personnel::active()->with('department')->findOrFail($id);

        $otherPersonnel = Personnel::active()->ordered()->with('department')
            ->where('id', '!=', $id)
            ->limit(4)->get();

        $settings = Cache::remember(FrontendCacheService::KEY_SETTINGS, 86400, fn() => [
            'org_name_lo'  => Setting::get('org_name_lo', 'ອົງການພຣະພຸດທະສາສະໜາ'),
            'org_name_en'  => Setting::get('org_name_en', 'Buddhist Organization'),
            'org_logo_url' => Setting::get('org_logo_url'),
        ]);

        $orgName   = $settings['org_name_lo'];
        $orgNameEn = $settings['org_name_en'];
        $orgLogo   = $settings['org_logo_url'];

        return view('frontend.personnel_show', compact(
            'person',
            'otherPersonnel',
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

    public function about(): View
    {
        $settings = Cache::remember(FrontendCacheService::KEY_SETTINGS, 86400, fn() => [
            'org_name_lo'  => Setting::get('org_name_lo', 'ອົງການພຣະພຸດທະສາສະໜາ'),
            'org_name_en'  => Setting::get('org_name_en', 'Buddhist Organization'),
            'org_logo_url' => Setting::get('org_logo_url'),
        ]);

        $orgName   = $settings['org_name_lo'];
        $orgNameEn = $settings['org_name_en'];
        $orgLogo   = $settings['org_logo_url'];

        $statsPersonnelCount = Cache::remember('stats_personnel_count', 3600, fn() => Personnel::active()->count());
        $statsDocumentsCount = Cache::remember('stats_documents_count', 1800, fn() => Document::active()->count());
        $statsNewsCount      = Cache::remember('stats_news_count', 600, fn() => News::published()->count());

        $donationAccounts = Cache::remember('donation_accounts', 3600, fn() => [
            ['key' => 'kip',  'flag' => '🇱🇦', 'label_lo' => 'ກີບ',  'label_en' => 'Lao Kip (LAK)',      'bank_name' => Setting::get('donate_kip_bank_name',  ''), 'account_name' => Setting::get('donate_kip_account_name',  ''), 'account_no' => Setting::get('donate_kip_account_no',  ''), 'qr_url' => Setting::get('donate_kip_qr_url')],
            ['key' => 'baht', 'flag' => '🇹🇭', 'label_lo' => 'ບາດ',  'label_en' => 'Thai Baht (THB)',    'bank_name' => Setting::get('donate_baht_bank_name', ''), 'account_name' => Setting::get('donate_baht_account_name', ''), 'account_no' => Setting::get('donate_baht_account_no', ''), 'qr_url' => Setting::get('donate_baht_qr_url')],
            ['key' => 'usd',  'flag' => '🇺🇸', 'label_lo' => 'ໂດລາ', 'label_en' => 'US Dollar (USD)',    'bank_name' => Setting::get('donate_usd_bank_name',  ''), 'account_name' => Setting::get('donate_usd_account_name',  ''), 'account_no' => Setting::get('donate_usd_account_no',  ''), 'qr_url' => Setting::get('donate_usd_qr_url')],
            ['key' => 'cny',  'flag' => '🇨🇳', 'label_lo' => 'ຢວນ',  'label_en' => 'Chinese Yuan (CNY)', 'bank_name' => Setting::get('donate_cny_bank_name',  ''), 'account_name' => Setting::get('donate_cny_account_name',  ''), 'account_no' => Setting::get('donate_cny_account_no',  ''), 'qr_url' => Setting::get('donate_cny_qr_url')],
        ]);

        return view('frontend.about', compact(
            'orgName',
            'orgNameEn',
            'orgLogo',
            'statsPersonnelCount',
            'statsDocumentsCount',
            'statsNewsCount',
            'donationAccounts',
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
