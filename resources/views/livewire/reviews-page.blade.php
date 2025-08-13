
<main class=" container mx-auto box-border px-[16px] sm:px-[28px] md:px-[50px] py-0 mx-auto my-0 mb-[100px] w-full ">
     <!-- Breadcrumbs -->
            <livewire:components.breadcrumbs
                :currentPage="__('messages.reviews.title')"
                :items="[]"
            />

            <h1 class="text-2xl font-bold leading-tight text-zinc-800 max-md:max-w-full my-4">
                {{ __('messages.reviews.title') }}
            </h1>
    <section class="relative flex flex-col justify-center items-center mx-auto my-0 w-full" aria-label="Customer Reviews">
        <div class="">
            <div class="flex flex-col gap-2 justify-center items-start max-w-[782px] ">
                @foreach ($reviews as $review)
                    <article class="flex relative flex-col items-start w-full bg-white rounded-3xl shadow-xl">
                        <div class="box-border flex relative flex-col items-start p-10 w-full max-md:p-8 max-sm:p-5">
                            <header class="flex relative flex-col gap-4 items-start pb-5 w-full">
                                <div class="flex relative gap-5 items-center w-full max-md:p-8 max-sm:p-5">
                                    <div class="flex relative flex-col gap-1 items-start flex-[1_0_0]">
                                        <div class="flex relative gap-2.5 items-start w-full">
                                            <h3 class="relative text-xl font-bold leading-6 flex-[1_0_0] text-zinc-800 max-md:text-lg max-sm:text-base">
                                                {{ $review->name }}
                                            </h3>
                                        </div>
                                        <div class="flex relative gap-1.5 items-start w-full">
                                            <div class="flex relative gap-4 items-start">
                                                <time class="relative text-xs font-semibold leading-5 text-zinc-600 max-md:text-sm max-sm:text-sm" datetime="{{ $review->published_at->format('Y-m-d') }}">
                                                    {{ $review->published_at->locale(app()->getLocale())->translatedFormat('d F Y') }}
                                                </time>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex relative gap-1 items-start pt-1.5 h-14" role="img" aria-label="{{ $review->rating }} out of 5 stars">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                <path d="M8.00985 2.04749L9.03651 4.10083C9.17651 4.38666 9.54985 4.66083 9.86485 4.71333L11.7257 5.02249C12.9157 5.22083 13.1957 6.08416 12.3382 6.93583L10.8915 8.38249C10.6465 8.62749 10.5123 9.09999 10.5882 9.43833L11.0023 11.2292C11.329 12.6467 10.5765 13.195 9.32235 12.4542L7.57818 11.4217C7.26318 11.235 6.74401 11.235 6.42318 11.4217L4.67901 12.4542C3.43068 13.195 2.67235 12.6408 2.99901 11.2292L3.41318 9.43833C3.48901 9.09999 3.35485 8.62749 3.10985 8.38249L1.66318 6.93583C0.811513 6.08416 1.08568 5.22083 2.27568 5.02249L4.13651 4.71333C4.44568 4.66083 4.81901 4.38666 4.95901 4.10083L5.98568 2.04749C6.54568 0.933327 7.45568 0.933327 8.00985 2.04749Z" fill="{{ $i <= $review->rating ? '#FACC15' : '#DBDBDB' }}"></path>
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                            </header>
                            <div class="flex relative gap-2.5 items-start w-full">
                                <div class="flex relative flex-col gap-2.5 items-start flex-[1_0_0]">
                                    <p class="relative w-full text-base font-semibold leading-5 text-zinc-800 max-md:text-sm max-sm:text-sm">
                                        {{ $review->comment }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

        </div>

        <!-- Кнопка для отправки отзыва -->
        <a href="{{ route('submit-review') }}" class="mt-4 inline-flex  gap-2 justify-center items-center px-6 py-2.5 h-11 rounded-2xl border-2 border-green-600 border-solid " aria-label="Leave a review">
            <span class="relative text-base font-bold leading-6 text-green-600">Залишити відгук</span>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M13.4697 5.46967C13.7626 5.17678 14.2374 5.17678 14.5303 5.46967L20.5303 11.4697C20.8232 11.7626 20.8232 12.2374 20.5303 12.5303L14.5303 18.5303C14.2374 18.8232 13.7626 18.8232 13.4697 18.5303C13.1768 18.2374 13.1768 17.7626 13.4697 17.4697L18.1893 12.75H4C3.58579 12.75 3.25 12.4142 3.25 12C3.25 11.5858 3.58579 11.25 4 11.25H18.1893L13.4697 6.53033C13.1768 6.23744 13.1768 5.76256 13.4697 5.46967Z" fill="#228F5D"></path>
            </svg>
        </a>
    </section>
</main>
