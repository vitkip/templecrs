<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Supported locales.
     */
    protected array $supportedLocales = ['lo', 'en'];

    /**
     * Handle an incoming request.
     * Priority: URL param → Session → User preference → Config default
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->get('lang')
            ?? Session::get('locale')
            ?? config('app.locale', 'lo');

        if (!in_array($locale, $this->supportedLocales)) {
            $locale = 'lo';
        }

        App::setLocale($locale);
        Session::put('locale', $locale);

        return $next($request);
    }
}
