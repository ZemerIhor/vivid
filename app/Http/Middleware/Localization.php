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
        // Пропускаем системные маршруты, которые не должны обрабатываться локализацией
        $excludedPaths = [
            'lunar/*',
            'livewire/*',
            'api/*',
            '_debugbar/*',
            'storage/*',
            'stripe/*',
            'passkeys/*',
        ];
        
        foreach ($excludedPaths as $pattern) {
            if ($request->is($pattern)) {
                return $next($request);
            }
        }

        $languageService = app(LanguageService::class);
        
        $urlLocale = $request->segment(1);
        $detectedLocale = $languageService->detectLocale($urlLocale);
        
        // Если в URL нет локали или она не валидна, добавляем префикс с текущей локалью
        if (!$languageService->isValidLocale($urlLocale) && $request->path() !== '/') {
            $currentPath = $request->path();
            // Проверяем, не является ли это уже локализованным путем
            if (!preg_match('#^(en|pl)/#', $currentPath)) {
                return redirect("/{$detectedLocale}/{$currentPath}");
            }
        }
        
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
