<div class="mx-auto container relative reviews" id="reviews">
    <section class="flex flex-col px-4 py-20" aria-label="{{ __('messages.reviews.section_aria_label') }}" role="main" aria-labelledby="reviews-heading">
        <h2 id="reviews-heading" class="mb-4 w-full text-2xl font-bold leading-7 text-left text-zinc-800 max-md:text-2xl max-md:text-center max-sm:text-xl">
            {{ __('messages.reviews.title') }}
        </h2>
        <div class="swiper reviews-swiper pt-5 pb-5 w-full max-sm:max-w-full" role="region" aria-label="{{ __('messages.reviews.articles') }}">
            <div class="swiper-wrapper">
                @forelse ($reviews as $review)
                    <div class="swiper-slide">
                        <article class="flex flex-col gap-3 p-6 rounded-3xl bg-neutral-200 max-md:max-w-full h-full review-card">
                            <div class="flex flex-col gap-2 w-full">
                                <h3 class="gap-2.5 w-full text-xl font-bold leading-6 flex-[1_0_0] text-zinc-800 max-md:text-lg max-sm:text-base">
                                    {{ $review['name'] ?? '' }}
                                </h3>
                                <p class="text-sm font-semibold leading-5 text-zinc-800">
                                    {{ $review['date'] ?? '' }}
                                </p>
                                <div class="flex gap-1" role="img" aria-label="{{ __('messages.reviews.rating_aria_label', ['rating' => $review['rating'] ?? 0]) }}">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= ($review['rating'] ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.97a1 1 0 00.95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.384 2.46a1 1 0 00-.364 1.118l1.286 3.97c.3.921-.755 1.688-1.54 1.118l-3.384-2.46a1 1 0 00-1.176 0l-3.384 2.46c-.784.57-1.838-.197-1.54-1.118l1.286-3.97a1 1 0 00-.364-1.118L2.46 8.397c-.783-.57-.38-1.81.588-1.81h4.178a1 1 0 00.95-.69l1.286-3.97z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <p class="text-base font-semibold leading-6 text-zinc-800">
                                    {{ isset($review['text']) ? Str::limit($review['text'], 200) : '' }}
                                </p>
                            </div>
                        </article>
                    </div>
                @empty
                    <p class="text-sm text-neutral-400 w-full text-center">{{ __('messages.reviews.no_items') }}</p>
                @endforelse
            </div>
        </div>
        @if (!empty($reviews))
            <a
                href="#"
{{--                href="{{ route('reviews', ['locale' => app()->getLocale()]) }}"--}}
               class="flex mt-4 gap-2 justify-center items-center self-center px-6 py-2.5 text-base font-bold leading-snug text-green-600 whitespace-nowrap rounded-2xl border-2 border-green-600 border-solid min-h-11 max-md:px-5 w-fit mx-auto hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 transition-colors"
               aria-label="{{ __('messages.reviews.more_button_aria_label') }}"
               wire:navigate>
    <span class="self-stretch my-auto text-green-600">
        {{ __('messages.reviews.more_button') }}
    </span>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>

        @endif
    </section>
</div>
