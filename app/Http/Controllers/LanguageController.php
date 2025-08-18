<?php

namespace App\Http\Controllers;

use App\Services\LanguageService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL as FacadeURL;

class LanguageController extends Controller
{
    /**
     * Switch application language
     */
    public function switch(string $locale)
    {
        $languageService = app(LanguageService::class);
        
        try {
            $redirectTo = request('redirect_to', FacadeURL::full());
            $path = $languageService->switchLanguage($locale, $redirectTo);
            $path = $languageService->addQueryParameters($path, $redirectTo);
            
            return redirect($path ?: "/{$locale}");
        } catch (\InvalidArgumentException $e) {
            \Log::warning('Invalid locale attempted in language switch', [
                'locale' => $locale,
                'user_ip' => request()->ip(),
            ]);
            return Redirect::back()->with('error', 'Invalid locale');
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Database error during language switch', [
                'locale' => $locale,
                'error' => $e->getMessage(),
            ]);
            return Redirect::back()->with('error', 'Language switch temporarily unavailable');
        } catch (\Exception $e) {
            \Log::error('Unexpected error during language switch', [
                'locale' => $locale,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            
            return Redirect::back()->with('error', 'Language switch failed');
        }
    }
}
