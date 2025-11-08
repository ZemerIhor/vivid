<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @php
        $settings = app(\App\Settings\GlobalSettings::class);
        $locale = app()->getLocale();
        $currentRoute = request()->route() ? request()->route()->getName() : 'home';

        // По умолчанию используем site_name и meta_description из GlobalSettings
        $pageTitle = $settings->site_name[$locale] ?? __('messages.settings.default_site_name', [], $locale);
        $pageDescription = $settings->meta_description[$locale] ?? __('messages.settings.default_meta_description', [], $locale);

        // Определяем заголовок и мета-описание в зависимости от маршрута
        switch ($currentRoute) {
            // Статические страницы (из GlobalSettings)
            case 'home':
                $pageTitle = $settings->home_title[$locale] ?? __('messages.home.title', [], $locale);
                $pageDescription = $settings->home_meta_description[$locale] ?? __('messages.home.meta_description', [], $locale);
                break;
            case 'about-us':
                $pageTitle = $settings->about_us_title[$locale] ?? __('messages.about-us.title', [], $locale);
                $pageDescription = $settings->about_us_meta_description[$locale] ?? __('messages.about-us.meta_description', [], $locale);
                break;
            case 'contacts':
                $pageTitle = $settings->contacts_title[$locale] ?? __('messages.contacts.title', [], $locale);
                $pageDescription = $settings->contacts_meta_description[$locale] ?? __('messages.contacts.meta_description', [], $locale);
                break;
            case 'faq':
                $pageTitle = $settings->faq_title[$locale] ?? __('messages.faq.title', [], $locale);
                $pageDescription = $settings->faq_meta_description[$locale] ?? __('messages.faq.meta_description', [], $locale);
                break;
            case 'reviews':
                $pageTitle = $settings->reviews_title[$locale] ?? __('messages.reviews.title', [], $locale);
                $pageDescription = $settings->reviews_meta_description[$locale] ?? __('messages.reviews.meta_description', [], $locale);
                break;
            case 'submit-review':
                $pageTitle = $settings->submit_review_title[$locale] ?? __('messages.submit-review.title', [], $locale);
                $pageDescription = $settings->submit_review_meta_description[$locale] ?? __('messages.submit-review.meta_description', [], $locale);
                break;
            case 'blog.index':
                $pageTitle = $settings->blog_title[$locale] ?? __('messages.blog.title', [], $locale);
                $pageDescription = $settings->blog_meta_description[$locale] ?? __('messages.blog.meta_description', [], $locale);
                break;
            case 'checkout.view':
                $pageTitle = $settings->checkout_title[$locale] ?? __('messages.checkout.title', [], $locale);
                $pageDescription = $settings->checkout_meta_description[$locale] ?? __('messages.checkout.meta_description', [], $locale);
                break;
            case 'checkout-success.view':
                $pageTitle = $settings->checkout_success_title[$locale] ?? __('messages.checkout-success.title', [], $locale);
                $pageDescription = $settings->checkout_success_meta_description[$locale] ?? __('messages.checkout-success.meta_description', [], $locale);
                break;

            // Продуктовые и системные страницы (из lang и моделей Lunar)
            case 'catalog.view':
                $pageTitle = __('messages.catalog.title', [], $locale);
                $pageDescription = __('messages.catalog.meta_description', [], $locale);
                break;
            case 'product.view':
                $language = \Lunar\Models\Language::where('code', $locale)->first();
                $url = \Lunar\Models\Url::where('slug', request()->route()->parameter('slug'))
                    ->where('element_type', 'Lunar\Models\Product')
                    ->where('language_id', $language ? $language->id : 1)
                    ->first();


                if (!$url) {
                    $url = \Lunar\Models\Url::where('slug', request()->route()->parameter('slug'))
                        ->where('element_type', 'Lunar\Models\Product')
                        ->where('default', 1)
                        ->first();
                }

                $product = $url ? \Lunar\Models\Product::where('id', $url->element_id)
                    ->where('status', 'published')
                    ->first() : null;
                // Логирование для отладки
                \Illuminate\Support\Facades\Log::info('Product View Meta', [
                    'locale' => $locale,
                    'slug' => request()->route()->parameter('slug'),
                    'url_found' => $url ? $url->toArray() : null,
                    'product_found' => $product ? $product->id : null,
                    'product_name' => $product ? $product->translateAttribute('name') : null,
                    'product_description' => $product ? $product->translateAttribute('description') : null,
                ]);

                $pageTitle = $product && $product->translateAttribute('name')
                    ? $product->translateAttribute('name')
                    : __('messages.product.default_title', [], $locale);
                $pageDescription = $product && $product->translateAttribute('description')
                    ? html_entity_decode(strip_tags($product->translateAttribute('description')))
                    : __('messages.product.default_meta_description', [], $locale);
                break;
            case 'collection.view':
                $language = \Lunar\Models\Language::where('code', $locale)->first();
                $url = \Lunar\Models\Url::where('slug', request()->route()->parameter('slug'))
                    ->where('element_type', 'Lunar\Models\Collection')
                    ->where('language_id', $language ? $language->id : 1)
                    ->first();
                if (!$url) {
                    $url = \Lunar\Models\Url::where('slug', request()->route()->parameter('slug'))
                        ->where('element_type', 'Lunar\Models\Collection')
                        ->where('default', 1)
                        ->first();
                }
                $collection = $url ? \Lunar\Models\Collection::where('id', $url->element_id)->first() : null;
                $pageTitle = $collection ? ($collection->translateAttribute('name') ?? __('messages.collection.title', [], $locale)) : __('messages.collection.title', [], $locale);
                $pageDescription = $collection ? (html_entity_decode(strip_tags($collection->translateAttribute('description'))) ?? __('messages.collection.meta_description', [], $locale)) : __('messages.collection.meta_description', [], $locale);
                break;
            case 'search.view':
            case 'products.index':
                $pageTitle = __('messages.search.title', [], $locale);
                $pageDescription = __('messages.search.meta_description', [], $locale);
                break;
            case 'blog.post':
                $post = \App\Models\BlogPost::where('slug', request()->route()->parameter('slug'))->first();
                $pageTitle = $post ? ($post->getTranslation('title', $locale) ?? __('messages.blog.post_default_title', [], $locale)) : __('messages.blog.post_default_title', [], $locale);
                $pageDescription = $post ? (html_entity_decode(strip_tags($post->getTranslation('excerpt', $locale))) ?? __('messages.blog.post_default_meta_description', [], $locale)) : __('messages.blog.post_default_meta_description', [], $locale);
                break;
            case 'privacy-policy':
                $pageTitle = __('messages.privacy-policy.title', [], $locale);
                $pageDescription = __('messages.privacy-policy.meta_description', [], $locale);
                break;
            case 'terms':
                $pageTitle = __('messages.terms.title', [], $locale);
                $pageDescription = __('messages.terms.meta_description', [], $locale);
                break;
        }
    @endphp

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">

    @if ($settings->favicon)
        <link rel="icon" href="{{ Storage::url($settings->favicon) }}">
    @else
        <link rel="icon" href="{{ asset('favicon.svg') }}">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="flex flex-col min-h-screen antialiased text-gray-900 relative">
@livewire('components.navigation')

<div >
    {{ $slot }}
</div>

@livewireScripts
@stack('scripts')


<x-footer/>
<button
    id="scrollToTopBtn"
    type="button"
    aria-label="Scroll to top of page"
    class="flex fixed bottom-4 right-4 z-50 gap-2.5 justify-center items-center self-start px-3 w-12 h-12 bg-green-600 rounded-[32px] hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:ring-offset-zinc-800 transition-colors duration-200"
    onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
>
    <div class="flex self-stretch my-auto w-6 min-h-6" aria-hidden="true">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
             xmlns="http://www.w3.org/2000/svg" class="text-white">
            <path d="M7 14L12 9L17 14" stroke="currentColor" stroke-width="2"
                  stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </div>
</button>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const scrollBtn = document.getElementById('scrollToTopBtn');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                scrollBtn.classList.remove('hidden');
                scrollBtn.classList.add('flex');
            } else {
                scrollBtn.classList.remove('flex');
                scrollBtn.classList.add('hidden');
            }
        });
    });
</script>
</body>
</html>
