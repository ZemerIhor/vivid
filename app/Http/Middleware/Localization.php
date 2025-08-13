<?php

namespace App\Http\Middleware;

use App\Services\LanguageService;
use Closure;
use Illuminate\Http\Request;

class Localization
{
    public function __construct(
        private LanguageService $languageService
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $urlLocale = $request->segment(1);
        $detectedLocale = $this->languageService->detectLocale($urlLocale);
        
        // Устанавливаем локаль через сервис
        $this->languageService->setLocale($detectedLocale);

        \Log::info('Localization Middleware', [
            'url_locale' => $urlLocale,
            'detected_locale' => $detectedLocale,
            'final_locale' => $this->languageService->getCurrentLocale(),
            'path' => $request->path(),
        ]);

        return $next($request);
    }
}
