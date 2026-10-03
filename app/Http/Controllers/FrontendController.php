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
        $personnel = Cache::remember('frontend_personnel_all', 3600, fn() =>
            Personnel::active()->ordered()->with('department')->get()
        );
        $departments = Cache::remember('frontend_personnel_depts', 3600, fn() =>
            Department::active()->ordered()
                ->whereHas('personnel', fn($q) => $q->where('is_active', true))
                ->get()
        );

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

        $otherPersonnel = Cache::remember("frontend_personnel_related_{$id}", 3600, fn() =>
            Personnel::active()->ordered()->with('department')
                ->where('id', '!=', $id)
                ->limit(4)->get()
        );

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
        $documents = Cache::remember('frontend_documents_all', 1800, fn() =>
            Document::active()->ordered()->with('department')->get()
        );
        $departments = Cache::remember('frontend_documents_depts', 1800, fn() =>
            Department::active()->ordered()
                ->whereHas('documents', fn($q) => $q->where('is_active', true))
                ->get()
        );

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

    public function structure(): View
    {
        $settings = Cache::remember(FrontendCacheService::KEY_SETTINGS, 86400, fn() => [
            'org_name_lo'  => Setting::get('org_name_lo', 'ອົງການພຣະພຸດທະສາສະໜາ'),
            'org_name_en'  => Setting::get('org_name_en', 'Buddhist Organization'),
            'org_logo_url' => Setting::get('org_logo_url'),
        ]);

        $departments = Cache::remember('frontend_structure_depts', 1800, fn() =>
            Department::active()->ordered()
                ->with([
                    'head',
                    'personnel' => fn($q) => $q->active()->orderBy('sort_order')->orderBy('name_lo')
                ])
                ->get()
        );

        $provincialStats = Cache::remember('frontend_structure_provincial_stats', 1800, fn() =>
            Personnel::active()
                ->where('affiliation_level', 'provincial')
                ->whereNotNull('affiliation_province')
                ->where('affiliation_province', '!=', '')
                ->selectRaw('affiliation_province, count(*) as count')
                ->groupBy('affiliation_province')
                ->orderBy('affiliation_province')
                ->get()
        );

        $centralCount = Cache::remember('frontend_structure_central_count', 1800, fn() =>
            Personnel::active()->where('affiliation_level', 'central')->count()
        );

        $provincialCount = Cache::remember('frontend_structure_provincial_count', 1800, fn() =>
            Personnel::active()->where('affiliation_level', 'provincial')->count()
        );

        $totalPersonnel = Cache::remember('frontend_structure_total_personnel', 1800, fn() =>
            Personnel::active()->count()
        );

        $leadershipDept = $departments->firstWhere('id', 9);
        $adminDept      = $departments->firstWhere('id', 8);
        $techDept       = $departments->firstWhere('id', 10);
        $operatingDepts = $departments->whereIn('id', [11, 17, 13, 23]);
        $otherDepts     = $departments->whereNotIn('id', [9, 8, 10, 11, 17, 13, 23]);

        return view('frontend.structure', [
            'orgName'         => $settings['org_name_lo'],
            'orgNameEn'       => $settings['org_name_en'],
            'orgLogo'         => $settings['org_logo_url'],
            'departments'     => $departments,
            'leadershipDept'  => $leadershipDept,
            'adminDept'       => $adminDept,
            'techDept'        => $techDept,
            'operatingDepts'  => $operatingDepts,
            'otherDepts'      => $otherDepts,
            'provincialStats' => $provincialStats,
            'centralCount'    => $centralCount,
            'provincialCount' => $provincialCount,
            'totalPersonnel'  => $totalPersonnel,
        ]);
    }

    public function duties(): View
    {
        $settings = Cache::remember(FrontendCacheService::KEY_SETTINGS, 86400, fn() => [
            'org_name_lo'  => Setting::get('org_name_lo', 'ອົງການພຣະພຸດທະສາສະໜາ'),
            'org_name_en'  => Setting::get('org_name_en', 'Buddhist Organization'),
            'org_logo_url' => Setting::get('org_logo_url'),
        ]);

        return view('frontend.duties', [
            'orgName'   => $settings['org_name_lo'],
            'orgNameEn' => $settings['org_name_en'],
            'orgLogo'   => $settings['org_logo_url'],
        ]);
    }

    public function guide(): View
    {
        $settings = Cache::remember(FrontendCacheService::KEY_SETTINGS, 86400, fn() => [
            'org_name_lo'  => Setting::get('org_name_lo', 'ອົງການພຣະພຸດທະສາສະໜາ'),
            'org_name_en'  => Setting::get('org_name_en', 'Buddhist Organization'),
            'org_logo_url' => Setting::get('org_logo_url'),
        ]);

        return view('frontend.guide', [
            'orgName'   => $settings['org_name_lo'],
            'orgNameEn' => $settings['org_name_en'],
            'orgLogo'   => $settings['org_logo_url'],
        ]);
    }

    public function history(): View
    {
        $settings = Cache::remember(FrontendCacheService::KEY_SETTINGS, 86400, fn() => [
            'org_name_lo'  => Setting::get('org_name_lo', 'ອົງການພຣະພຸດທະສາສະໜາ'),
            'org_name_en'  => Setting::get('org_name_en', 'Buddhist Organization'),
            'org_logo_url' => Setting::get('org_logo_url'),
        ]);

        return view('frontend.history', [
            'orgName'   => $settings['org_name_lo'],
            'orgNameEn' => $settings['org_name_en'],
            'orgLogo'   => $settings['org_logo_url'],
        ]);
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
