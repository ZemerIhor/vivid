<div class="relative mt-5 w-full mx-auto overflow-hidden home-slider-main" aria-label="{{ __('messages.hero_slider.aria_label', [], app()->getLocale()) }}">
    <div class="relative mx-auto transition-opacity duration-300" style="width: 90%" id="slider-wrap">
        <div class="flex duration-300" id="hero-slider" style="gap: 20px;">
            @php
                $locale = app()->getLocale();
                $heroSlides = $settings->hero_slides[$locale] ?? [];
            @endphp

            @if (!empty($heroSlides) && is_array($heroSlides))
                @foreach ($heroSlides as $index => $slide)
                    <div class="w-full shrink-0 justify-center rounded-2xl items-center bg-black bg-cover bg-center" data-visible="{{ $index }}"
                         style="background-image: url('{{ isset($slide['background_image']) ? Storage::url($slide['background_image']) : '' }}'); height: 480px;">
                        <div class="text-center text-white p-8">
                            <div class="flex justify-center mb-4">
                                <svg width="30" height="41" viewBox="0 0 30 41" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M17.1926 21.8202C17.1926 23.4879 16.4119 25.2226 15.3614 26.4111C13.9543 28.0212 12.3255 29.1043 10.0799 29.1043C6.06099 29.1043 3.71902 25.8648 3.05401 22.5199C2.60103 20.4113 2.90944 19.9896 2.81306 18.7628C2.78415 18.5328 2.78415 18.437 2.60103 18.3794C2.57212 18.3794 2.57212 18.3603 2.57212 18.3699C2.57212 18.3794 2.5432 18.3699 2.5432 18.3699C2.45646 18.3699 2.5432 18.3411 2.45646 18.3794C2.18661 18.4945 1.79146 19.4529 1.70472 19.6733C1.0108 21.5806 0.596378 23.3825 0.5 25.1172V26.5261C0.683117 29.3151 1.67581 31.9699 3.62263 34.6249L4.61532 35.8229C5.01047 36.2542 5.4538 36.5991 5.75259 36.8867C6.06099 37.1933 6.38867 37.433 6.71634 37.6823C8.73064 39.1774 11.9689 40.5 14.5229 40.5H15.5734C18.1178 40.5 20.4598 39.4841 22.3199 38.3148C22.503 38.2093 22.6861 38.1041 22.8596 37.9699C23.0427 37.8452 23.2258 37.7301 23.3704 37.5959C23.7945 37.2606 24.4498 36.7429 24.816 36.3693L25.7124 35.4394C25.8666 35.2574 25.9533 35.1232 26.1075 34.9507C28.2953 32.2383 29.5 28.9318 29.5 25.2993C29.5 23.6412 28.9603 21.3218 28.2664 19.8554C27.6688 18.5999 27.2159 17.8523 26.3485 16.7597L23.5535 13.9323C23.2258 13.6352 22.3488 13.0506 21.9344 12.7343C21.6934 12.5714 21.5392 12.4468 21.2983 12.2647L20.6718 11.7375C20.4598 11.5554 20.3152 11.4212 20.0743 11.2392L18.3587 9.58107C17.3757 8.61307 17.0384 8.20093 16.383 7.07956C15.5445 5.69942 15.4193 4.29055 15.4193 2.62288C15.4193 1.6932 15.9301 -0.108649 15.1205 0.706017C14.6675 1.15648 13.6844 2.94874 13.376 3.56214C12.9327 4.42472 13.0484 4.05093 12.8363 4.89435C12.7496 5.15313 12.6917 5.35441 12.6628 5.60359L12.4508 7.26168C12.4219 7.57794 12.4219 7.86548 12.393 8.14342L12.5086 9.84942C12.9038 13.041 15.2072 16.1271 16.3541 18.9737C16.5276 19.4241 16.6818 19.8171 16.8649 20.2771C17.0095 20.6893 17.1926 21.2835 17.1926 21.8202Z" fill="white"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M6.98582 11.3063C6.98582 12.9453 7.28461 14.1529 7.79541 15.3605C8.39293 16.8844 9.17361 17.7949 10.3783 18.7629C10.6482 18.9642 10.7638 19.108 11.0048 19.338C11.1301 19.4626 11.1879 19.4818 11.3132 19.5968C13.0769 21.1973 14.4551 23.6413 12.9901 25.9224C12.4793 26.7179 11.8529 27.0725 11.7565 27.3888C11.8529 27.523 11.8818 27.5421 12.0553 27.5038C12.1806 27.4655 12.3926 27.3792 12.4793 27.3313C13.4431 26.9096 13.7708 26.7371 14.5515 25.9512C15.0334 25.424 15.602 24.4272 15.7754 23.7468C16.3537 21.552 15.3899 18.9834 14.0985 17.2582C13.5877 16.6065 12.807 15.4851 12.5661 14.7088C12.2095 13.6066 12.9323 13.8079 11.2457 13.3861C9.80969 13.0219 8.87482 12.3414 8.06526 11.1338C7.4388 10.2233 7.38097 9.49493 7.19787 9.27449C7.07256 9.82078 6.98582 10.6354 6.98582 11.3063Z" fill="white"/>
                                </svg>
                            </div>
                            <div class="flex flex-col gap-2 justify-center mb-5">
                                <h1 class="text-5xl font-bold">{{ $slide['heading'] ?? 'VIVID ENEGE' }}</h1>
                                <p class="mt-4 text-xl">{!! $slide['subheading'] ?? 'Українська компанія з видобування й переробки торфу' !!}</p>
                                <p class="mt-2 text-sm">{{ $slide['extra_text'] ?? 'Keep warm' }}</p>
                            </div>
                            <div class="flex justify-center mt-6 gap-4 max-md:flex-col">
                                <!-- <a href="{{ route('catalog.view', ['locale' => app()->getLocale()]) }}"
                                   class="px-6 py-3 border-2 border-white rounded-2xl text-white hover:bg-white hover:text-black transition"
                                   aria-label="{{ __('messages.hero.catalog_button') }}">
                                    {{ __('messages.hero.catalog_button') }}
                                </a>
                                <a href="#"
                                   class="px-6 py-3 bg-green-600 text-white rounded-2xl hover:bg-green-700 transition"
                                   aria-label="{{ __('messages.hero.buy_now_button') }}">
                                    {{ __('messages.hero.buy_now_button') }}
                                </a> -->
                                  <button
                            wire:click="$dispatch('openContactForm')"
                            class="px-6 py-3 bg-green-600 text-white rounded-2xl hover:bg-green-700 transition"
                            aria-label="{{ __('messages.feedback_form.submit_button') }}"
                        >
                            Замовити
                  </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="min-w-full flex items-center justify-center bg-gray-200 text-gray-500 p-10">
                    {{ __('messages.hero.no_slides') }}
                </div>
            @endif
        </div>

        <!-- Navigation Arrows -->
        @if (!empty($heroSlides) && is_array($heroSlides) && count($heroSlides) > 1)
            <button class="absolute top-1/2 left-4 transform -translate-y-1/2 rounded-full p-2 hero-prev">
                <svg width="15" height="46" viewBox="0 0 15 46" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12.999 43.9977L4.40193 28.9524C2.29391 25.2633 2.29391 20.7344 4.40193 17.0453L12.999 2" stroke="white" stroke-width="4" stroke-linecap="round"/>
                </svg>
            </button>
            <button class="absolute top-1/2 right-4 transform -translate-y-1/2 rounded-full p-2 hero-next">
                <svg width="15" height="46" viewBox="0 0 15 46" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2.00098 2.00229L10.5981 17.0476C12.7061 20.7367 12.7061 25.2656 10.5981 28.9547L2.00098 44" stroke="white" stroke-width="4" stroke-linecap="round"/>
                </svg>
            </button>
        @endif

        <!-- Indicators -->
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
            @if (!empty($heroSlides) && is_array($heroSlides))
                @foreach ($heroSlides as $index => $slide)
                    <span class="w-4 h-1 rounded-full cursor-pointer hero-indicator {{ $index === 0 ? 'bg-green-500' : 'bg-white/50' }}"
                          data-slide="{{ $index }}"></span>
                @endforeach
            @endif
        </div>
    </div>
</div>
