<?php

namespace App\Http\Middleware;

use App\Services\LanguageService;
use Closure;
use Illuminate\Http\Request;

class Localization
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $languageService = app(LanguageService::class);
        
        $urlLocale = $request->segment(1);
        $detectedLocale = $languageService->detectLocale($urlLocale);
        
        // Устанавливаем локаль через сервис
        $languageService->setLocale($detectedLocale);

        // Логируем только в debug режиме для избежания спама в production
        if (config('app.debug')) {
            \Log::debug('Localization Middleware', [
                'url_locale' => $urlLocale,
                'detected_locale' => $detectedLocale,
                'final_locale' => $languageService->getCurrentLocale(),
                'path' => $request->path(),
            ]);
        }

        return $next($request);
    }
}
