<?php

namespace App\Services;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Lunar\Models\Url as LunarUrl;
use Lunar\Models\Product;
use Lunar\Models\Language;

class LanguageService
{
    private const SUPPORTED_LOCALES = ['en', 'pl'];

    /**
     * Switch application language
     */
    public function switchLanguage(string $locale, ?string $redirectTo = null): string
    {
        if (!$this->isValidLocale($locale)) {
            Log::warning('Invalid locale attempted: ' . $locale);
            throw new \InvalidArgumentException('Invalid locale');
        }

        $this->setLocale($locale);
        $redirectTo = $redirectTo ?: request()->fullUrl();

        $path = $this->processRedirectPath($redirectTo, $locale);
        
        // Логируем только в debug режиме
        if (config('app.debug')) {
            Log::debug('Language switch requested', [
                'locale' => $locale,
                'redirect_to' => $path,
                'current_url' => $redirectTo,
            ]);
        }

        return $path;
    }

    /**
     * Check if locale is valid
     */
    public function isValidLocale(string $locale): bool
    {
        return in_array($locale, self::SUPPORTED_LOCALES);
    }

    /**
     * Detect locale from URL or session
     */
    public function detectLocale(?string $urlLocale = null): string
    {
        // Сначала проверяем URL
        if ($urlLocale && $this->isValidLocale($urlLocale)) {
            return $urlLocale;
        }

        // Затем сессию
        $sessionLocale = Session::get('locale');
        if ($sessionLocale && $this->isValidLocale($sessionLocale)) {
            return $sessionLocale;
        }

        // Затем заголовки браузера
        $browserLocale = $this->getBrowserLocale();
        if ($browserLocale && $this->isValidLocale($browserLocale)) {
            return $browserLocale;
        }

        // Дефолтная локаль
        return config('app.locale', 'en');
    }

    /**
     * Set application locale
     */
    public function setLocale(string $locale): bool
    {
        if (!$this->isValidLocale($locale)) {
            return false;
        }

        Session::put('locale', $locale);
        App::setLocale($locale);
        
        return true;
    }

    /**
     * Get locale from browser headers
     */
    private function getBrowserLocale(): ?string
    {
        $acceptLanguage = request()->header('Accept-Language');
        
        if (!$acceptLanguage) {
            return null;
        }

        // Парсим Accept-Language заголовок
        $languages = [];
        foreach (explode(',', $acceptLanguage) as $lang) {
            $parts = explode(';', trim($lang));
            $locale = trim($parts[0]);
            $quality = 1.0;
            
            if (isset($parts[1]) && str_starts_with(trim($parts[1]), 'q=')) {
                $quality = (float) substr(trim($parts[1]), 2);
            }
            
            $languages[$locale] = $quality;
        }

        // Сортируем по качеству
        arsort($languages);

        // Ищем поддерживаемую локаль
        foreach (array_keys($languages) as $locale) {
            // Проверяем полную локаль (например, en-US)
            if ($this->isValidLocale($locale)) {
                return $locale;
            }
            
            // Проверяем основную часть (например, en из en-US)
            $mainLocale = explode('-', $locale)[0];
            if ($this->isValidLocale($mainLocale)) {
                return $mainLocale;
            }
        }

        return null;
    }

    /**
     * Process redirect path for locale switching
     */
    private function processRedirectPath(string $redirectTo, string $locale): string
    {
        $baseUrl = config('app.url');
        $path = str_replace($baseUrl, '', $redirectTo);
        $path = $this->removeLocalePrefix($path);

        if ($this->isProductPage($path)) {
            return $this->handleProductPageLocale($path, $locale);
        }

        return $this->handleRegularPageLocale($path, $locale);
    }

    /**
     * Remove locale prefix from path
     */
    private function removeLocalePrefix(string $path): string
    {
        return preg_replace('#^/(en|pl)/#', '/', $path);
    }

    /**
     * Check if current path is a product page
     */
    private function isProductPage(string $path): bool
    {
        return preg_match('#^/products/([^/]+)#', $path);
    }

    /**
     * Handle product page locale switching
     */
    private function handleProductPageLocale(string $path, string $locale): string
    {
        preg_match('#^/products/([^/]+)#', $path, $matches);
        $currentSlug = $matches[1];

        $url = $this->findProductUrl($currentSlug);
        
        if (!$url) {
            Log::warning('Product not found for slug: ' . $currentSlug);
            return "/{$locale}";
        }

        $newUrl = $this->getProductUrlForLocale($url->element, $locale);
        
        if ($newUrl) {
            return "/products/{$newUrl->slug}";
        }

        // Fallback to product's default slug
        return "/products/{$url->element->slug}";
    }

    /**
     * Handle regular page locale switching
     */
    private function handleRegularPageLocale(string $path, string $locale): string
    {
        $newPath = "/{$locale}{$path}";
        
        if ($this->routeExists($newPath)) {
            return $newPath;
        }

        Log::warning('Route not found for path: ' . $newPath);
        return "/{$locale}";
    }

    /**
     * Find product URL by slug
     */
    private function findProductUrl(string $slug): ?LunarUrl
    {
        $url = LunarUrl::where('slug', $slug)
            ->where('element_type', Product::class)
            ->first();

        if (!$url) {
            // Check alternative slugs
            $url = LunarUrl::whereIn('slug', [$slug, $slug . 'vfv', str_replace('vfv', '', $slug)])
                ->where('element_type', Product::class)
                ->first();
        }

        return $url;
    }

    /**
     * Get product URL for specific locale
     */
    private function getProductUrlForLocale(Product $product, string $locale): ?LunarUrl
    {
        $languageId = Language::where('code', $locale)->first()?->id ?? 1;
        
        return $product->urls()
            ->where('language_id', $languageId)
            ->first();
    }

    /**
     * Check if route exists
     */
    private function routeExists(string $path): bool
    {
        try {
            Route::getRoutes()->match(
                \Illuminate\Http\Request::create($path, 'GET')
            );
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Add query parameters to path
     */
    public function addQueryParameters(string $path, string $originalUrl): string
    {
        $query = parse_url($originalUrl, PHP_URL_QUERY);
        
        if ($query) {
            $path .= '?' . $query;
        }

        return $path;
    }

    /**
     * Get supported locales
     */
    public function getSupportedLocales(): array
    {
        return self::SUPPORTED_LOCALES;
    }

    /**
     * Get current locale
     */
    public function getCurrentLocale(): string
    {
        return App::getLocale();
    }

    /**
     * Get locale from request
     */
    public function getLocaleFromRequest(): string
    {
        $locale = request()->segment(1);
        
        if ($this->isValidLocale($locale)) {
            return $locale;
        }

        return Session::get('locale', config('app.locale', 'en'));
    }
}
