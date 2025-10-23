@props(['product', 'odd' => false])

@php
    // Get current locale
    $locale = app()->getLocale();

    // Get the language ID for the current locale
    $language = \Lunar\Models\Language::where('code', $locale)->first();
    $languageId = $language ? $language->id : 1;

    // Get the slug for the current locale
    $slug = $product->urls()->where('language_id', $languageId)->first()?->slug;

    // Fallback to default URL slug, product slug, or generate a default slug
    if (!$slug) {
        $slug = $product->defaultUrl?->slug ?? $product->slug ?? \Illuminate\Support\Str::slug($product->translateAttribute('name') ?? 'product-' . $product->id);
    }

    // Check if the slug is valid
    $hasValidSlug = is_string($slug) && trim($slug) !== '';

    // Generate product URL
    $productUrl = $hasValidSlug
        ? route('product.view', ['locale' => $locale, 'slug' => $slug], false)
        : route('home', ['locale' => $locale], false);

    // Extract translations
    $nameValue = $product->translateAttribute('name') ?? 'Product';
    $descriptionValue = $product->translateAttribute('description') ?? '';

    // Debug logging
    \Log::info('ProductCard Debug', [
        'product_id' => $product->id,
        'locale' => $locale,
        'fallback_locale' => config('app.fallback_locale'),
        'name' => $product->attribute_data['name'] ?? null,
        'nameValue' => $nameValue,
        'descriptionValue' => $descriptionValue,
        'attribute_data' => $product->attribute_data->toArray(),
        'slug' => $slug,
        'localizedUrl_slug' => $product->urls()->where('language_id', $languageId)->first()?->slug,
        'defaultUrl_slug' => $product->defaultUrl?->slug,
        'urls' => $product->urls->toArray(),
        'productUrl' => $productUrl,
    ]);
@endphp

<article class="overflow-hidden product-card flex-1 shrink self-stretch my-auto rounded-3xl basis-0 bg-neutral-200 h-full" role="listitem">
    <div class="flex flex-row justify-between group h-full">
        <div class="flex flex-col md:{{ $odd ? 'flex-row-reverse' : 'flex-row' }} w-full h-full">
            <div class="flex relative flex-col flex-1">
                <div class="flex overflow-hidden flex-col max-w-full w-full">
                    @if ($product->thumbnail)
                        <a href="{{ $productUrl }}">
                            <img src="{{ $product->thumbnail->getUrl() }}"
                                 alt="{{ $nameValue }}"
                                 class="object-cover w-full aspect-[1.77] transition-transform duration-300 group-hover:scale-105"/>
                        </a>
                    @else
                        <img src="https://cdn.builder.io/api/v1/image/assets/bdb2240bae064d82b869b3fcebf2733a/d7f2f96fb365d97b578a2cfa0ccb76eaba272ebd?placeholderIfAbsent=true"
                             alt="{{ __('messages.catalog.placeholder_image_alt') }}"
                             class="object-contain w-full aspect-[1.77]"/>
                    @endif
                </div>
            </div>

            <div class="flex-1 flex flex-col justify-between">
                <div class="w-full text-zinc-800 p-4">
                    <h2 class="text-base font-bold leading-5 text-zinc-800">{{ $nameValue }}</h2>
                    <p class="mt-3 text-xs font-semibold leading-5 text-zinc-800">{!! $descriptionValue !!}</p>
                </div>
                <div class="w-full p-4 flex justify-center">
                    <button
                        wire:click="$dispatch('openContactForm')"
                        class="px-4 py-2 text-sm font-bold text-green-600 rounded-2xl border-2 border-green-600 hover:bg-green-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-green-600 w-full max-w-xs"
                        aria-label="{{ __('messages.feedback_form.submit_button') }}"
                    >
                        {{ __('messages.feedback_form.submit_button') }}
                    </button>
                </div>
            </div>
        </div>

        <div class="gap-4 justify-between items-end mt-4 px-4 pb-4 w-full hidden">
            <span class="text-base font-bold leading-tight text-zinc-800">
                <x-product-price :product="$product" />
            </span>

            <livewire:components.add-to-cart :purchasable="$product->variants->first()" :key="'add-to-cart-' . $product->id" />
        </div>

        @if (!$hasValidSlug)
            <div class="p-4 text-red-600 text-sm">
                {{ __('messages.catalog.warning_no_slug') }}: {{ $product->id }} ({{ __('messages.catalog.locale') }}: {{ app()->getLocale() }})
                @dump($product->urls->toArray())
                @dump($product->localizedUrl?->toArray())
                @dump($product->defaultUrl?->toArray())
                @dump($product->slug)
            </div>
    @endif
</article>
