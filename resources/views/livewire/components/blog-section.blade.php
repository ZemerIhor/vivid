<section class="flex mx-auto flex-col self-stretch px-2 py-12 container relative" role="main" aria-labelledby="blog-heading" id="blog">
    <h1 id="blog-heading" class="text-2xl font-bold leading-tight text-zinc-800 max-md:max-w-full">
        {{ __('messages.blog.title') }}
    </h1>

    <div class="flex flex-wrap gap-2 items-center mt-5 w-full min-h-[307px] max-md:max-w-full" role="region" aria-label="{{ __('blog.articles') }}">
        @forelse ($posts->take(4) as $post)
            @php
                $locale = app()->getLocale();
                $slug = $post->slug; // Используем непереводимый slug
                $hasValidSlug = is_string($slug) && trim($slug) !== '';

                $routeParams = ['slug' => $slug];
                if ($locale !== config('app.fallback_locale')) {
                    $routeParams['locale'] = $locale;
                }

                // Если slug валиден, формируем URL для поста, иначе для главной страницы
                $postUrl = $hasValidSlug ? route('blog.post', $routeParams, false) : route('home', $locale !== config('app.fallback_locale') ? ['locale' => $locale] : [], false);
            @endphp

            <a
{{--                href="{{'$postUrl }}" --}}
    href="#"
               wire:navigate class="block overflow-hidden relative flex-1 shrink self-stretch my-auto rounded-3xl basis-0 bg-neutral-200 min-w-60 hover:shadow-lg transition-shadow h-full" aria-label="{{ __('blog.read_article', ['title' => $post->getTranslation('title', $locale)]) }}">
                <article class="overflow-hidden" role="article">
                    <div class="overflow-hidden z-0 w-full">
                        <img
                            src="{{ $post->banner ? Storage::url($post->banner) : 'https://via.placeholder.com/300x158' }}"
                            alt="{{ $post->getTranslation('title', $locale) }}"
                            class="object-contain w-full"
                            loading="lazy"
                        />
                    </div>
                    <div class="z-0 p-4 w-full font-semibold">
                        <h2 class="text-base leading-6 text-ellipsis text-zinc-800">
                            {{ Str::limit($post->getTranslation('title', $locale), 50) }}
                        </h2>
                        <p class="text-sm text-zinc-600 mt-2">
                            {{ Str::limit($post->getTranslation('excerpt', $locale), 100) }}
                        </p>
                        <time class="mt-4 text-xs text-neutral-400" datetime="{{ $post->published_at->format('Y-m-d') }}">
                            {{ $post->published_at->format('d.m.Y') }}
                        </time>
                    </div>
                </article>
            </a>
        @empty
            <p class="text-sm text-neutral-400 w-full text-center">{{ __('messages.blog.no_posts') }}</p>
        @endforelse
    </div>

    @if ($posts->count() > 0)
        <a
{{--            href="{{ route('blog.index', $locale !== config('app.fallback_locale') ? ['locale' => $locale] : []) }}"--}}
    href="#"
            class="flex gap-2 justify-center items-center self-center px-6 py-2.5 mt-5 text-base font-bold leading-snug text-green-600 whitespace-nowrap rounded-2xl border-2 border-green-600 border-solid min-h-11 max-md:px-5 hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 transition-colors"
            aria-label="{{ __('messages.blog.read_more') }}"
        >
            <span class="self-stretch my-auto text-green-600">
                {{ __('messages.blog.read_more') }}
            </span>
            <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M13.9697 5.46967C14.2626 5.17678 14.7374 5.17678 15.0303 5.46967L21.0303 11.4697C21.3232 11.7626 21.3232 12.2374 21.0303 12.5303L15.0303 18.5303C14.7374 18.8232 14.2626 18.8232 13.9697 18.5303C13.6768 18.2374 13.6768 17.7626 13.9697 17.4697L18.6893 12.75H4.5C4.08579 12.75 3.75 12.4142 3.75 12C3.75 11.5858 4.08579 11.25 4.5 11.25H18.6893L13.9697 6.53033C13.6768 6.23744 13.6768 5.76256 13.9697 5.46967Z" fill="#228F5D"/>
            </svg>
        </a>
    @endif
</section>
