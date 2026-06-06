<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\News;
use App\Models\Personnel;
use App\Models\Setting;
use App\Models\HeroSlide;
use Illuminate\View\View;

class FrontendController extends Controller
{
    public function index(): View
    {
        // Active Hero Slides
        $slides = HeroSlide::active()
            ->ordered()
            ->get();

        // Latest news (published, active, ordered)
        $news = News::published()
            ->ordered()
            ->limit(6)
            ->get();

        // Featured news for hero carousel
        $featuredNews = News::published()
            ->featured()
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        // Personnel — active monks first, then laypersons
        $personnel = Personnel::active()
            ->ordered()
            ->with('department')
            ->limit(8)
            ->get();

        // Documents — active, recent
        $documents = Document::active()
            ->ordered()
            ->with('department')
            ->limit(8)
            ->get();

        // Organization settings
        $orgName   = Setting::get('org_name_lo', 'ອົງການພຣະພຸດທະສາສະໜາ');
        $orgNameEn = Setting::get('org_name_en', 'Buddhist Organization');
        $orgLogo   = Setting::get('org_logo_url');

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
