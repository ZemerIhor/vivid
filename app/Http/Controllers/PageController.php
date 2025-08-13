<?php

namespace App\Http\Controllers;

use App\Livewire\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show($slug, Request $request)
    {
        \Log::info('PageController::show called', [
            'slug' => $slug,
            'locale' => app()->getLocale(),
            'path' => $request->path(),
            'url' => $request->fullUrl(),
        ]);

        // Поиск страницы по slug
        $page = Page::where('slug', $slug)->first();

        if (!$page) {
            \Log::warning('Page not found for slug: ' . $slug);
            abort(404, 'Page not found');
        }

        return view('page.show', compact('page'));
    }
}
