<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class Localization
{
    public function handle($request, Closure $next)
    {
        $locale = $request->segment(1);

        if (in_array($locale, ['en', 'uk'])) {
            // Локаль указана в URL (например, /en/...)
            App::setLocale($locale);
            Session::put('locale', $locale);
        } else {
            // Нет локали в URL (например, /products/{slug}), используем сессию или фоллбек
            $locale = Session::get('locale', config('app.locale', 'en'));
            App::setLocale($locale);
        }

        \Log::info('Localization Middleware', [
            'locale' => $locale,
            'session_locale' => Session::get('locale'),
            'app_locale' => App::getLocale(),
            'path' => $request->path(),
        ]);

        return $next($request);
    }
}
