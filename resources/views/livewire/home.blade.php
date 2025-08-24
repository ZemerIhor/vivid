<div>
    <x-welcome-banner :settings="$settings"/>
    <div class="container mx-auto">

        <div class=" px-2 mx-auto pt-40">

            <section
                class="container mx-auto flex relative flex-col w-full gap-0.5 items-start self-stretch pb-0 max-md:pt-8 max-md:pb-0 max-sm:pt-5 max-sm:pb-0"
                aria-label="Company Advantages">
                <div class="advantages-pc">



                @if (!empty($settings->advantages_cards[app()->getLocale()]))
                        @foreach ($settings->advantages_cards[app()->getLocale()] as $index => $card)
                            <article style="max-height: 250px" class="flex flex-col gap-3 items-center p-6 rounded-3xl bg-zinc-800">
                                <div class="flex flex-col gap-2 w-full text-center text-white">
                                    @if (!empty($card['icon']))
                                        <img src="{{ Storage::url($card['icon']) }}"
                                            alt="{{ isset($card['title']) ? $card['title'] : 'Advantage icon' }}"
                                            class="w-12 h-12 mx-auto mb-2" />
                                    @endif
                                    <h2 class="text-base font-bold leading-5 max-sm:text-sm">
                                        {{ isset($card['title']) ? $card['title'] : '' }}
                                    </h2>
                                    <p class="text-xs font-semibold leading-5 max-sm:text-xs">
                                        {{ isset($card['description']) ? $card['description'] : '' }}
                                    </p>
                                </div>
                            </article>

                            @if (!empty($settings->{'advantages_image_' . ($index + 1)}))
                                <img style="max-height: 250px" src="{{ Storage::url($settings->{'advantages_image_' . ($index + 1)}) }}"
                                    alt="Advantage image" class="object-cover w-full h-full rounded-3xl" />
                            @endif
                        @endforeach
                    @else
                        <p>{{ __('messages.advantages.no_cards') }}</p>
                    @endif
                </div>

                <div class="advantages-mobile">



                @if (!empty($settings->advantages_cards[app()->getLocale()]))
                        <!-- Первый article -->
                        <article style="max-height: 160px" class="flex flex-col gap-3 items-center p-6 rounded-3xl bg-zinc-800 max-sm:h-[187px]">
                            <div class="flex flex-col gap-2 w-full text-center text-white">
                                @if (!empty($settings->advantages_cards[app()->getLocale()][0]['icon']))
                                    <img src="{{ Storage::url($settings->advantages_cards[app()->getLocale()][0]['icon']) }}"
                                        alt="{{ isset($settings->advantages_cards[app()->getLocale()][0]['title']) ? $settings->advantages_cards[app()->getLocale()][0]['title'] : 'Advantage icon' }}"
                                        class="w-12 h-12 mx-auto mb-2 max-sm:h-[124px] max-sm:object-cover" />
                                @endif
                                <h2 class="text-base font-bold leading-5 max-sm:text-sm">
                                    {{ isset($settings->advantages_cards[app()->getLocale()][0]['title']) ? $settings->advantages_cards[app()->getLocale()][0]['title'] : '' }}
                                </h2>
                                <p class="text-xs font-semibold leading-5 max-sm:text-xs">
                                    {{ isset($settings->advantages_cards[app()->getLocale()][0]['description']) ? $settings->advantages_cards[app()->getLocale()][0]['description'] : '' }}
                                </p>
                            </div>
                        </article>

                        <!-- Первое фото -->
                        @if (!empty($settings->{'advantages_image_1'}))
                            <img style="max-height: 120px" src="{{ Storage::url($settings->{'advantages_image_1'}) }}"
                                alt="Advantage image" class="object-cover w-full h-full rounded-3xl max-sm:h-[124px]" />
                        @endif

                        <!-- Три article -->
                        @for ($i = 1; $i <= 3; $i++)
                            @if (isset($settings->advantages_cards[app()->getLocale()][$i]))
                                <article style="max-height: 250px" class="flex flex-col gap-3 items-center p-6 rounded-3xl bg-zinc-800 max-sm:h-[187px]">
                                    <div class="flex flex-col gap-2 w-full text-center text-white">
                                        @if (!empty($settings->advantages_cards[app()->getLocale()][$i]['icon']))
                                            <img src="{{ Storage::url($settings->advantages_cards[app()->getLocale()][$i]['icon']) }}"
                                                alt="{{ isset($settings->advantages_cards[app()->getLocale()][$i]['title']) ? $settings->advantages_cards[app()->getLocale()][$i]['title'] : 'Advantage icon' }}"
                                                class="w-12 h-12 mx-auto mb-2 max-sm:h-[124px] max-sm:object-cover" />
                                        @endif
                                        <h2 class="text-base font-bold leading-5 max-sm:text-sm">
                                            {{ isset($settings->advantages_cards[app()->getLocale()][$i]['title']) ? $settings->advantages_cards[app()->getLocale()][$i]['title'] : '' }}
                                        </h2>
                                        <p class="text-xs font-semibold leading-5 max-sm:text-xs">
                                            {{ isset($settings->advantages_cards[app()->getLocale()][$i]['description']) ? $settings->advantages_cards[app()->getLocale()][$i]['description'] : '' }}
                                        </p>
                                    </div>
                                </article>
                            @endif
                        @endfor

                        <!-- Второе фото -->
                        @if (!empty($settings->{'advantages_image_2'}))
                            <img style="max-height: 250px" src="{{ Storage::url($settings->{'advantages_image_2'}) }}"
                                alt="Advantage image" class="object-cover w-full h-full rounded-3xl max-sm:h-[124px]" />
                        @endif

                        <!-- Оставшиеся article -->
                        @for ($i = 4; $i < count($settings->advantages_cards[app()->getLocale()]); $i++)
                            @if (isset($settings->advantages_cards[app()->getLocale()][$i]))
                                <article style="max-height: 250px" class="flex flex-col gap-3 items-center p-6 rounded-3xl bg-zinc-800 max-sm:h-[187px]">
                                    <div class="flex flex-col gap-2 w-full text-center text-white">
                                        @if (!empty($settings->advantages_cards[app()->getLocale()][$i]['icon']))
                                            <img src="{{ Storage::url($settings->advantages_cards[app()->getLocale()][$i]['icon']) }}"
                                                alt="{{ isset($settings->advantages_cards[app()->getLocale()][$i]['title']) ? $settings->advantages_cards[app()->getLocale()][$i]['title'] : 'Advantage icon' }}"
                                                class="w-12 h-12 mx-auto mb-2 max-sm:h-[124px] max-sm:object-cover" />
                                        @endif
                                        <h2 class="text-base font-bold leading-5 max-sm:text-sm">
                                            {{ isset($settings->advantages_cards[app()->getLocale()][$i]['title']) ? $settings->advantages_cards[app()->getLocale()][$i]['title'] : '' }}
                                        </h2>
                                        <p class="text-xs font-semibold leading-5 max-sm:text-xs">
                                            {{ isset($settings->advantages_cards[app()->getLocale()][$i]['description']) ? $settings->advantages_cards[app()->getLocale()][$i]['description'] : '' }}
                                        </p>
                                    </div>
                                </article>
                            @endif
                        @endfor

                        <!-- Третье фото -->
                        @if (!empty($settings->{'advantages_image_3'}))
                            <img style="max-height: 250px" src="{{ Storage::url($settings->{'advantages_image_3'}) }}"
                                alt="Advantage image" class="object-cover w-full h-full rounded-3xl max-sm:h-[124px]" />
                        @endif
                    @else
                        <p>{{ __('messages.advantages.no_cards') }}</p>
                    @endif
                </div>
            </section>
        </div>
        <style>
            /* По умолчанию показываем мобилку */
            .advantages-mobile {
                display: flex;
                flex-wrap: wrap;

            }
            .advantages-mobile img , .advantages-mobile article {
                width: 49%;
            }
            .advantages-pc {
                display: none;
            }

            /* На ПК (от 1024px и шире) показываем десктопную */
            @media (min-width: 1024px) {
                .advantages-mobile {
                    display: none;
                }
                .advantages-pc {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
                    gap: 0.25rem; /* заменяет gap-1 */
                    width: 100%;
                }
            }

        </style>
        <div class="container mx-auto px-2 py-4 pt-40 products" id="catalog">

            <section class="flex flex-col self-stretch" aria-label="Каталог">
                <div class="">
                    <!-- <h2 class="text-2xl pb-5 font-bold leading-tight text-black max-md:max-w-full">
                    {{ __('messages.products.title') }}
                    </h2> -->
                    <div
                        class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 xl:grid-cols-1  overflow-hidden gap-2 sm:h-auto"
                        role="list">

                        @if (!empty($allProducts))
                            @foreach ($allProducts as $product)
                                <x-product-card :product="$product" :odd="$loop->odd"/>
                                <div class="flex w-full justify-center hidden">
                                    <button wire:click="$dispatch('openContactForm')"
                                            class="px-4 py-2 text-sm font-bold text-green-600 rounded-2xl border-2 border-green-600 hover:bg-green-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-green-600 w-full max-w-xs"
                                            aria-label="{{ __('messages.feedback_form.submit_button') }}">
                                        {{ __('messages.feedback_form.submit_button') }}
                                    </button>
                                </div>

                            @endforeach
                        @else
                            <p>{{ __('messages.products.no_items') }}</p>
                        @endif
                    </div>
                </div>
            </section>
        </div>

        <div class="container mx-auto px-2 pt-40">
            <section class="flex overflow-hidden flex-col font-bold" aria-labelledby="advantages-title">
                <div class="flex flex-col items-center">
                    <h1 id="advantages-title" class="text-4xl leading-none text-center text-zinc-800 max-md:max-w-full">
                        {{ isset($settings->comparison_title[app()->getLocale()]) ? $settings->comparison_title[app()->getLocale()] : __('messages.advantages.title') }}
                    </h1>
                    @if (!empty($settings->main_comparison_image) && is_string($settings->main_comparison_image))
                        <div class="relative w-full">
                            <img src="{{ Storage::url($settings->main_comparison_image) }}"
                                 alt="{{ isset($settings->main_comparison_alt[app()->getLocale()]) ? $settings->main_comparison_alt[app()->getLocale()] : 'Comparison of peat briquettes' }}"
                                 class="object-fill w-full min-h-60 mt-6 aspect-[4.13] rounded-[32px] max-md:max-w-full"/>
                            <span
                                class="w-full flex absolute top-0 z-10 flex-col justify-center items-center self-center px-4 py-12 leading-none text-center whitespace-nowrap max-md:top-10"
                                aria-label="Quantity of peat briquettes for comparison">
                                <span
                                    class="text-8xl tracking-tighter text-white max-md:text-4xl">{{ isset($settings->central_text_value[app()->getLocale()]) ? $settings->central_text_value[app()->getLocale()] : '1t' }}</span>
                                <span
                                    class="text-4xl text-white">{{ isset($settings->central_text_unit[app()->getLocale()]) ? $settings->central_text_unit[app()->getLocale()] : 'briquettes' }}</span>
                            </span>
                        </div>
                    @endif
                </div>
                <!-- Part 2: Comparison Items and Central Text -->
                <div class="flex relative flex-col self-center mt-2 w-full text-white max-md:max-w-full">
                    <div class="flex z-0 gap-2 justify-between items-center w-full min-h-60 max-md:gap-6 main-advantages-container max-md:flex-col">
                        @if (!empty($settings->comparison_items[app()->getLocale()]))
                            @foreach ($settings->comparison_items[app()->getLocale()] as $item)
                                <div class="flex relative flex-col grow items-start self-stretch overflow-hidden
                                             my-auto min-h-60 rounded-[32px] max-md:w-full">
                                    @if (!empty($item['image']) && is_string($item['image']))
                                        <img src="{{ Storage::url($item['image']) }}"
                                             alt="{{ isset($item['alt']) ? $item['alt'] : '' }}"
                                             class="advantages__img object-cover absolute inset-0 size-full"/>
                                    @endif
                                    <div
                                        class="flex relative gap-2 items-end p-4 max-md:flex-col items-center w-full mt-auto">
                                        <p class="text-4xl leading-none">{{ isset($item['value']) ? $item['value'] : '' }}</p>
                                        <p class="text-2xl leading-tight">{!! isset($item['unit']) ? $item['unit'] : '' !!}</p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-center w-full">{{ __('messages.comparison.no_items') }}</p>
                        @endif
                    </div>
                </div>
            </section>
        </div>
        <style>
            @media (max-width: 768px) {
                .advantages__img {
                    max-height: 124px;
                }

                .main-advantages-container {
                    display: flex !important;
                    flex-wrap: wrap;
                }

                .main-advantages-container > div {
                    width: 49%;
                    margin-bottom: 10px;
                }
            }
        </style>

        <livewire:components.reviews-section/>


        <section class="home-about-section relative container  mx-auto px-2 py-20" id="about-us" role="main"
                 aria-labelledby="about-heading">
            <style>
                .home-about-section:before {
                    content: "";
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100vw;
                    margin-left: calc(-50vw + 50%);
                    background: url('{{ asset('images/earth.png') }}') no-repeat center center/cover;
                    z-index: -1;
                    height: 100%;
                }

                .home-about-section:after {
                    content: "";
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100vw;
                    margin-left: calc(-50vw + 50%);
                    background: #333;
                    z-index: -2;
                    height: 100%;
                }
            </style>

            <div class="home-about-us-grid ">
                <!-- Part 1: Main Content -->

                <header class="grid-about w-full max-md:w-full max-md:max-w-none">
                    <h1 id="about-heading"
                        class="text-4xl leading-none text-white max-md:text-3xl max-md:w-full">
                        {{ isset($settings->about_title[app()->getLocale()]) ? $settings->about_title[app()->getLocale()] : __('messages.about.title') }}
                    </h1>
                    <div class="mt-5 text-2xl text-white max-md:text-base max-md:w-full leading-3" style="">
                        {!! isset($settings->about_description[app()->getLocale()])
                            ? str($settings->about_description[app()->getLocale()])->sanitizeHtml()
                            : '' !!}
                    </div>

                </header>
                <nav
                    class="grid-buttons flex gap-4 items-center mt-40 text-base leading-snug whitespace-nowrap max-md:mt-10 max-md:flex-wrap max-md:justify-start">
                    @if (!empty($settings->about_more_link[app()->getLocale()]))
                        <a href="{{route('about-us')}}"
                           class="flex gap-2 justify-center items-center self-stretch px-6 py-2.5 my-auto text-green-600 rounded-2xl border-2 border-solid border-[color:var(--Primaries-700,#228F5D)] min-h-11 hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 focus:ring-offset-zinc-800"
                           type="button" aria-label="{{ __('messages.about.more_button_aria_label') }}">
                                    <span
                                        class="self-stretch my-auto text-green-600">{{ __('messages.about.more_button') }}</span>
                        </a>
                    @endif
                    @if (!empty($settings->about_certificates_link[app()->getLocale()]))
                        <a href="{{route('about-us')}}"
                           class="gap-2 self-stretch px-6 py-2.5 my-auto text-white bg-green-600 rounded-2xl min-h-11 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 focus:ring-offset-zinc-800"
                           type="button" aria-label="{{ __('messages.about.certificates_button_aria_label') }}">
                            {{ __('messages.about.certificates_button') }}
                        </a>
                    @endif
                </nav>

                <!-- Part 2: Statistics and Image -->

                <article class="grid-article min-w-[15rem] max-w-sm max-md:max-w-sm">
                    <header>
                        <h2 class="text-4xl font-bold leading-none text-green-600 max-md:text-3xl">
                            {{ isset($settings->about_statistic_title[app()->getLocale()]) ? $settings->about_statistic_title[app()->getLocale()] : '' }}
                        </h2>
                    </header>
                    <div class="mt-3 text-xs font-semibold leading-5 text-white max-md:text-sm">
                        {!! isset($settings->about_statistic_description[app()->getLocale()]) ? $settings->about_statistic_description[app()->getLocale()] : '' !!}
                    </div>
                </article>

                @if (!empty($settings->about_location_image))
                    <figure
                        class="grid-img overflow-hidden h-full text-xs font-semibold text-right text-white rounded-3xl shadow-[var(--sds-size-depth-0)_var(--sds-size-depth-400)_var(--sds-size-depth-800)_var(--sds-size-depth-negative-200)_var(--sds-color-black-400)] max-md:w-full max-md:rounded-lg pb-4"
                        style="min-width: 15rem;">
                        <div class="flex relative flex-col h-full">
                            <img src="{{ Storage::url($settings->about_location_image) }}"
                                 alt="{{ isset($settings->about_location_caption[app()->getLocale()]) ? $settings->about_location_caption[app()->getLocale()] : '' }}"
                                 class="object-cover max-md:rounded-lg h-full"/>
                            <p class="relative z-10">
                                {{ isset($settings->about_location_caption[app()->getLocale()]) ? $settings->about_location_caption[app()->getLocale()] : '' }}
                            </p>
                        </div>
                    </figure>
                @else
                    <p>{{ __('messages.about.no_image') }}</p>
                @endif

            </div>

        </section>


        <section class="container mx-auto flex flex-col px-2 py-20 bg-zinc-100 max-md:px-5" id="faq" role="main"
                 aria-labelledby="faq-title">
            <header>
                <h1 id="faq-title" class="text-2xl font-bold leading-tight text-zinc-800 max-md:max-w-full">
                    {{ is_string($settings->faq_title[app()->getLocale()] ?? null) ? $settings->faq_title[app()->getLocale()] : __('messages.faq.title') }}
                </h1>
            </header>

            <div class="flex flex-wrap gap-5 justify-center mt-5 w-full max-md:max-w-full">
                @if (!empty($settings->faq_main_image))
                    <img src="{{ Storage::url($settings->faq_main_image) }}"
                         alt="{{ is_string($settings->faq_main_image_alt[app()->getLocale()] ?? '') ? $settings->faq_main_image_alt[app()->getLocale()] : '' }}"
                         class="rounded-3xl min-w-60 w-[380px]"/>
                @else
                    <p>{{ __('messages.faq.no_image') }}</p>
                @endif

                <div class="flex-1 shrink self-start basis-0 min-w-60 max-md:max-w-full">
                    @foreach ($settings->faq_items[app()->getLocale()] ?? [] as $index => $item)
                        <article
                            class="flex flex-wrap items-start px-4 py-2 mt-1 w-full rounded-3xl bg-neutral-200 max-md:max-w-full">
                            <div class="flex gap-2.5 items-start self-stretch py-2 h-full w-[70px]">
                                @if (!empty($item['icon']))
                                    <img src="{{ Storage::url($item['icon']) }}"
                                         alt="{{ __('messages.faq.icon_alt', ['question' => $item['question']]) }}"
                                         class="object-contain rounded-xl faq-thumbnail"/>
                                @else
                                    <p>{{ __('messages.faq.no_icon') }}</p>
                                @endif
                            </div>
                            <div class="flex-1 shrink pt-4 pb-2 pl-4 basis-0 min-w-60 text-zinc-800 max-md:max-w-full">
                                <!-- Скрытый чекбокс для переключения -->
                                <input type="checkbox" id="faq-toggle-{{ $index }}" class="hidden faq-toggle-checkbox">
                                <label for="faq-toggle-{{ $index }}"
                                       class="faq-toggle flex gap-2.5 justify-center items-center pb-2 w-full text-xl font-bold leading-6 max-md:max-w-full text-left cursor-pointer">
                                    <h2 class="flex-1 shrink self-stretch my-auto basis-0 text-zinc-800 max-md:max-w-full">
                                        {{ $item['question'] }}
                                    </h2>
                                    <div class="flex shrink-0 gap-2.5 w-14 h-14 items-center justify-center">
                                        <svg class="arrow-open w-6 h-6 text-zinc-600" fill="none" stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                        <svg class="arrow-close w-6 h-6 text-zinc-600 hidden" fill="none"
                                             stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    </div>
                                </label>
                                <div class="flex w-full bg-zinc-300 min-h-px max-md:max-w-full" role="separator"></div>
                                <div id="answer-{{ $index }}" class="faq-answer max-h-0 overflow-hidden">
                                    <div
                                        class="flex gap-2.5 items-center py-2 w-full text-base font-semibold leading-none rounded-2xl max-md:max-w-full">
                                        <p
                                            class="flex-1 shrink self-stretch my-auto basis-0 text-zinc-800 max-md:max-w-full">
                                            {{ $item['answer'] }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            <footer class="container mx-auto flex justify-end mt-5">
                <a
                    href="#"
                    {{--                    href="{{ route('faq', ['locale' => app()->getLocale()]) }}"--}}
                    class="flex mt-4 gap-2 justify-center items-center self-center px-6 py-2.5 text-base font-bold leading-snug text-green-600 whitespace-nowrap rounded-2xl border-2 border-green-600 border-solid min-h-11 max-md:px-5 w-fit mx-auto hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 transition-colors"
                    aria-label="{{ __('messages.faq.show_more') }}">
                    <span class="self-stretch my-auto text-green-600">
                        {{ __('messages.faq.show_more') }}
                    </span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>

            </footer>
        </section>

        <style>
            /* Скрываем чекбокс */
            .faq-toggle-checkbox {
                display: none !important;
            }

            /* Стили для ответа */
            .faq-answer {
                max-height: 0 !important;
                transition: max-height 0.3s ease !important;
                overflow: hidden !important;
            }

            /* Показ ответа при активном чекбоксе */
            .faq-toggle-checkbox:checked ~ .faq-answer {
                max-height: 384px !important;
                /* Эквивалент max-h-96 (96 * 4 = 384px) */
            }

            /* Скрываем стрелку "открыть" и показываем "закрыть" при активном чекбоксе */
            .faq-toggle-checkbox:checked + .faq-toggle .arrow-open {
                display: none !important;
            }

            .faq-toggle-checkbox:checked + .faq-toggle .arrow-close {
                display: block !important;
            }

            /* Стили для кнопки (лейбла) */
            .faq-toggle {
                cursor: pointer !important;
                display: flex !important;
                align-items: center !important;
                justify-content: space-between !important;
                width: 100% !important;
            }

            /* Стили для стрелок */
            .arrow-open,
            .arrow-close {
                width: 24px !important;
                height: 24px !important;
            }

            .arrow-close {
                display: none !important;
            }

            /* Плавный переход для стрелок */
            .arrow-open,
            .arrow-close {
                transition: opacity 0.3s ease !important;
            }
        </style>

        <section class="flex flex-col justify-center self-stretch px-2 py-20 text-base bg-zinc-100 max-md:px-5"
                 aria-labelledby="tenders-heading">
            <h2 id="tenders-heading" class="text-2xl font-bold leading-tight text-zinc-800 max-md:max-w-full">
                {{ __('messages.tenders.title') }}
            </h2>

            <div class="flex flex-wrap gap-2 mt-5 w-full font-semibold leading-6 text-white max-md:max-w-full"
                 role="list" aria-label="Список тендерів">
                @foreach ($settings->tender_items[app()->getLocale()] ?? [] as $item)
                    <article
                        class="flex overflow-hidden relative grow shrink self-start p-4 rounded-3xl min-h-[210px] min-w-60 w-[310px]"
                        role="listitem" style="background-color: {{ $item['background_color'] ?? '#34C759' }};">
                        <svg width="384" height="211" viewBox="0 0 384 211" fill="none"
                             xmlns="http://www.w3.org/2000/svg"
                             class="object-contain absolute bottom-0 right-1 z-0 self-start aspect-[1.68] h-[485px] min-w-60 w-[352px]">
                            <g filter="url(#filter0_dd_2231_3718)">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                      d="M229.774 160.816C229.774 181.037 221.906 199.476 209.155 213.886C192.075 233.409 171.537 244.505 144.28 244.505C95.499 244.505 67.0722 205.226 59.0003 164.668C53.5022 139.103 57.2456 133.989 56.0758 119.114C55.7248 116.326 55.7248 115.163 53.5022 114.466C53.1512 114.466 53.1512 114.234 53.1512 114.35C53.1512 114.466 52.8003 114.35 52.8003 114.35C51.7474 114.35 52.8003 114.001 51.7474 114.466C48.4719 115.861 43.6756 127.482 42.6228 130.154C34.2001 153.28 29.1698 175.128 28 196.161V213.244C30.2227 247.061 42.2719 279.25 65.9022 311.443L77.9515 325.968C82.7478 331.197 88.1289 335.38 91.7555 338.866C95.499 342.584 99.4762 345.491 103.453 348.513C127.903 366.641 167.209 382.678 198.209 382.678H210.96C241.844 382.678 270.271 370.359 292.848 356.182C295.071 354.903 297.294 353.627 299.399 352C301.622 350.488 303.845 349.093 305.599 347.466C310.747 343.4 318.701 337.123 323.147 332.593L334.026 321.318C335.898 319.111 336.951 317.484 338.822 315.393C365.377 282.504 380 242.413 380 198.369C380 178.265 373.449 150.142 365.026 132.362C357.773 117.139 352.275 108.075 341.747 94.8267L339.797 92.0343C313.888 66.1796 293.199 49.8535 288.169 46.0188C285.244 44.043 283.373 42.5325 280.448 40.3243L272.844 33.9329C270.271 31.7251 268.516 30.0978 265.591 27.89L244.768 7.78574C232.836 -3.95126 228.742 -8.94852 220.787 -22.545C210.609 -39.2793 209.089 -56.3618 209.089 -76.5824C209.089 -87.8547 215.289 -109.702 205.462 -99.8243C199.964 -94.3625 188.032 -72.6313 184.288 -65.1938C178.907 -54.735 180.311 -59.2673 177.737 -49.0408C176.685 -45.9031 175.983 -43.4626 175.632 -40.4412L173.058 -20.3369C172.707 -16.5022 172.707 -13.0158 172.356 -9.6458L173.76 11.0395C178.556 49.7375 204 90.7932 220.436 121.671C220.305 121.343 229.774 142.245 229.774 160.816Z"
                                      fill="{{ $item['background_color'] ?? '#34C759' }}"/>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                      d="M106.724 28.7047C106.724 48.5767 110.351 63.2191 116.551 77.8616C123.804 96.3387 133.28 107.379 147.902 119.116C151.178 121.556 152.582 123.299 155.506 126.089C157.027 127.599 157.729 127.832 159.25 129.226C180.657 148.633 197.386 178.266 179.604 205.924C173.404 215.57 165.801 219.87 164.631 223.705C165.801 225.331 166.152 225.564 168.257 225.099C169.778 224.634 172.352 223.588 173.404 223.007C185.103 217.894 189.08 215.802 198.556 206.273C204.405 199.882 211.307 187.796 213.412 179.545C220.431 152.933 208.733 121.789 193.058 100.871C186.857 92.9686 177.382 79.3721 174.457 69.9592C170.129 56.595 178.903 59.0355 158.431 53.9223C141 49.5063 129.653 41.2553 119.827 26.6129C112.223 15.5732 111.521 6.74124 109.298 4.06847C107.777 10.6922 106.724 20.5701 106.724 28.7047Z"
                                      fill="{{ $item['background_color'] ?? '#34C759' }}"/>
                            </g>
                            <defs>
                                <filter id="filter0_dd_2231_3718" x="0" y="-130.322" width="408" height="541"
                                        filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                    <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                                    <feColorMatrix in="SourceAlpha" type="matrix"
                                                   values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
                                                   result="hardAlpha"/>
                                    <feMorphology radius="4" operator="erode" in="SourceAlpha"
                                                  result="effect1_dropShadow_2231_3718"/>
                                    <feOffset/>
                                    <feGaussianBlur stdDeviation="2"/>
                                    <feComposite in2="hardAlpha" operator="out"/>
                                    <feColorMatrix type="matrix"
                                                   values="0 0 0 0 0.0470588 0 0 0 0 0.0470588 0 0 0 0 0.0509804 0 0 0 0.05 0"/>
                                    <feBlend mode="normal" in2="BackgroundImageFix"
                                             result="effect1_dropShadow_2231_3718"/>
                                    <feColorMatrix in="SourceAlpha" type="matrix"
                                                   values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
                                                   result="hardAlpha"/>
                                    <feMorphology radius="4" operator="erode" in="SourceAlpha"
                                                  result="effect2_dropShadow_2231_3718"/>
                                    <feOffset/>
                                    <feGaussianBlur stdDeviation="16"/>
                                    <feComposite in2="hardAlpha" operator="out"/>
                                    <feColorMatrix type="matrix"
                                                   values="0 0 0 0 0.0470588 0 0 0 0 0.0470588 0 0 0 0 0.0509804 0 0 0 0.15 0"/>
                                    <feBlend mode="normal" in2="effect1_dropShadow_2231_3718"
                                             result="effect2_dropShadow_2231_3718"/>
                                    <feBlend mode="normal" in="SourceGraphic" in2="effect2_dropShadow_2231_3718"
                                             result="shape"/>
                                </filter>
                            </defs>
                        </svg>
                        <div class="z-0 flex-1 shrink p-4 basis-0 min-w-60">
                            @if (!empty($item['icon']))
                                <img src="{{ Storage::url($item['icon']) }}" alt="Іконка категорії {{ $item['title'] }}"
                                     class="object-contain w-10 aspect-square" onerror="this.style.display='none'"/>
                            @endif
                            <div class="flex items-end mt-5 w-full min-h-[88px]">
                                <h3
                                    class="flex-1 shrink basis-0 {{ str_starts_with($item['background_color'] ?? '#34C759', '#') && in_array(strtolower(substr($item['background_color'], 1)), ['34c759', '4ade80']) ? 'text-white' : 'text-green-600' }}">
                                    {{ $item['title'] }}
                                </h3>
                            </div>
                        </div>
                        <div class="flex z-0 shrink-0 gap-2.5 self-end w-14 h-14" aria-label="Кнопки навігації"></div>
                    </article>
                @endforeach
            </div>

            <footer class="flex flex-wrap gap-5 items-center mt-5 w-full max-md:max-w-full">
                <a href="tel:{{ $settings->tenders_phone[app()->getLocale()] ?? 'Відділ тендерів +38 099 900-14-30' }}"
                   class="flex flex-wrap flex-1 shrink gap-4 items-start self-stretch my-auto font-semibold leading-none basis-12 min-h-6 min-w-60 justify-end items-center text-zinc-800 max-md:max-w-full not-italic">

                    <svg class="object-contain shrink-0 w-6 aspect-square" width="22" height="24" viewBox="0 0 22 24"
                         fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M1 9.42773C1 5.04296 5.36522 0.677734 10.75 0.677734C16.1348 0.677734 20.5 5.04296 20.5 9.42773V12.2204C20.5782 12.1928 20.6623 12.1777 20.75 12.1777C21.1642 12.1777 21.5 12.5135 21.5 12.9277V14.9277C21.5 15.3419 21.1642 15.6777 20.75 15.6777C20.6594 15.6777 20.5725 15.6617 20.492 15.6322C20.4865 15.8326 20.492 15.8027 20.492 15.8027C20.492 17.9148 19.9443 20.179 18.6489 21.2547C17.6411 22.0917 16.2636 22.5 14.5758 22.6301C14.3239 23.1046 13.8247 23.4277 13.25 23.4277H11.25C10.4216 23.4277 9.75 22.7562 9.75 21.9277C9.75 21.0993 10.4216 20.4277 11.25 20.4277H13.25C13.7851 20.4277 14.2548 20.7079 14.5203 21.1296C16.0059 21.0081 17.0198 20.6579 17.6906 20.1008C18.1242 19.7407 18.458 19.256 18.675 18.595C18.6114 18.6175 18.5468 18.6385 18.4812 18.6584C18.0926 18.7761 17.6101 18.8713 17.0385 18.984L16.9642 18.9986C16.5739 19.0756 16.2195 19.1456 15.9245 19.1688C15.6071 19.1938 15.2493 19.1764 14.9065 18.9835C14.7036 18.8692 14.5262 18.7157 14.3834 18.533C14.1461 18.2295 14.0667 17.8829 14.0324 17.5639C13.9999 17.2631 14 16.8921 14 16.4757V11.2394C14 10.8855 14 10.5676 14.0254 10.3065C14.0525 10.0279 14.1147 9.72685 14.2981 9.44796C14.4491 9.21819 14.6516 9.02549 14.8911 8.88742C15.1862 8.71736 15.4938 8.6797 15.7715 8.67781C16.0282 8.67606 16.3363 8.70647 16.6723 8.73963L16.7423 8.74654C17.328 8.80427 17.8207 8.85284 18.22 8.93193C18.4937 8.98613 18.7531 9.0597 18.9962 9.17628C18.8633 5.73626 15.2222 2.17773 10.75 2.17773C6.27777 2.17773 2.63668 5.73626 2.50376 9.17628C2.74693 9.0597 3.00631 8.98613 3.27996 8.93193C3.67926 8.85284 4.17201 8.80427 4.75766 8.74654L4.82774 8.73963C5.16372 8.70647 5.4718 8.67606 5.72846 8.67781C6.00619 8.6797 6.31376 8.71736 6.60887 8.88742C6.84845 9.02549 7.05087 9.21819 7.20194 9.44796C7.38531 9.72685 7.4475 10.0279 7.47463 10.3065C7.50005 10.5676 7.50003 10.8855 7.5 11.2394V16.4757C7.50003 16.8921 7.50006 17.2631 7.46765 17.5639C7.43328 17.8829 7.35391 18.2295 7.11662 18.533C6.97384 18.7157 6.79645 18.8692 6.59347 18.9835C6.25067 19.1764 5.89291 19.1938 5.57548 19.1688C5.28055 19.1456 4.92614 19.0756 4.53587 18.9987L4.46157 18.984C3.89002 18.8713 3.40742 18.7761 3.01885 18.6584C2.60769 18.5337 2.23634 18.3659 1.9159 18.0706C1.70448 17.8758 1.52432 17.649 1.38113 17.399C1.16589 17.0231 1.07879 16.6218 1.03845 16.1864C1.02277 16.0172 1.01348 15.8326 1.00798 15.6322C0.92754 15.6617 0.840648 15.6777 0.75 15.6777C0.335786 15.6777 0 15.3419 0 14.9277V12.9277C0 12.5135 0.335786 12.1777 0.75 12.1777C0.837659 12.1777 0.921805 12.1928 1 12.2204V9.42773ZM2.5 14.621C2.5 15.2726 2.50075 15.7102 2.53205 16.048C2.5622 16.3734 2.61609 16.5371 2.68282 16.6536C2.75072 16.7722 2.83514 16.8779 2.93242 16.9676C3.02434 17.0523 3.16013 17.1338 3.45398 17.2229C3.76235 17.3163 4.17073 17.3978 4.78738 17.5194C5.22899 17.6064 5.49625 17.6579 5.69334 17.6735C5.79961 17.6818 5.84804 17.6758 5.86422 17.6725C5.88915 17.6574 5.91292 17.6369 5.93363 17.6108C5.9398 17.5986 5.96084 17.5465 5.97628 17.4032C5.99897 17.1926 6 16.9038 6 16.4381V11.2724C6 10.8751 5.99917 10.6314 5.98169 10.4519C5.96948 10.3265 5.9526 10.2811 5.94802 10.2712C5.92461 10.2358 5.8957 10.2088 5.86498 10.1901C5.85182 10.1865 5.81058 10.1784 5.71825 10.1778C5.55038 10.1766 5.32237 10.1981 4.94054 10.2358C4.30982 10.298 3.89042 10.3402 3.5714 10.4033C3.26569 10.4639 3.12392 10.5315 3.02863 10.6032C2.8888 10.7086 2.76933 10.8445 2.67927 11.0037C2.61481 11.1177 2.56135 11.2819 2.53152 11.6125C2.50071 11.9542 2.5 12.3976 2.5 13.0553V14.621ZM19 13.0553C19 12.3976 18.9993 11.9542 18.9685 11.6125C18.9386 11.2819 18.8852 11.1177 18.8207 11.0037C18.7307 10.8445 18.6112 10.7086 18.4714 10.6032C18.3761 10.5315 18.2343 10.4639 17.9286 10.4033C17.6096 10.3402 17.1902 10.298 16.5595 10.2358C16.1776 10.1981 15.9496 10.1766 15.7818 10.1778C15.6894 10.1784 15.6482 10.1865 15.635 10.1901C15.6043 10.2088 15.5754 10.2358 15.552 10.2712C15.5474 10.2811 15.5305 10.3265 15.5183 10.4519C15.5008 10.6314 15.5 10.8751 15.5 11.2724V16.4381C15.5 16.9038 15.501 17.1926 15.5237 17.4032C15.5392 17.5465 15.5602 17.5986 15.5664 17.6108C15.5871 17.6369 15.6109 17.6574 15.6358 17.6725C15.652 17.6758 15.7004 17.6818 15.8067 17.6735C16.0038 17.6579 16.271 17.6064 16.7126 17.5194C17.3293 17.3978 17.7376 17.3163 18.046 17.2229C18.3399 17.1338 18.4757 17.0523 18.5676 16.9676C18.6649 16.8779 18.7493 16.7722 18.8172 16.6536C18.8839 16.5371 18.9378 16.3734 18.968 16.048C18.9993 15.7102 19 15.2726 19 14.621V13.0553Z"
                              fill="#333333"/>
                    </svg>
                    <div class="text-zinc-800">
                        {{ $settings->tenders_phone[app()->getLocale()] ?? 'Відділ тендерів +38 099 900-14-30' }}
                    </div>
                </a>

                <button
                    class="flex gap-2 justify-center items-center self-stretch px-6 py-2.5 my-auto font-bold leading-snug text-green-600 whitespace-nowrap rounded-2xl border-2 border-green-600 border-solid min-h-11 max-md:px-5 hover:bg-green-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 transition-colors duration-200"
                    type="button" aria-label="Переглянути більше тендерів">
                    <span class="self-stretch my-auto text-current">
                        {{__('messages.tenders.more_button')}}
                    </span>
                    <svg width="18" height="15" viewBox="0 0 18 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M10.4697 1.1474C10.7626 0.854511 11.2374 0.854511 11.5303 1.1474L17.5303 7.1474C17.8232 7.4403 17.8232 7.91517 17.5303 8.20806L11.5303 14.2081C11.2374 14.501 10.7626 14.501 10.4697 14.2081C10.1768 13.9152 10.1768 13.4403 10.4697 13.1474L15.1893 8.42773H1C0.585786 8.42773 0.25 8.09195 0.25 7.67773C0.25 7.26352 0.585786 6.92773 1 6.92773H15.1893L10.4697 2.20806C10.1768 1.91517 10.1768 1.4403 10.4697 1.1474Z"
                              fill="#228F5D"/>
                    </svg>

                </button>
            </footer>
        </section>

        <section class="main-banner container mx-auto" role="banner" aria-label="{{ __('messages.hero.aria_label') }}">
            <style>
                .main-banner:before {
                    content: "";
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100vw;
                    margin-left: calc(-50vw + 50%);
                    background: url('{!! !empty($settings->banner_image) ? Storage::url($settings->banner_image) : '' !!}') no-repeat center center/cover;
                    z-index: -1;
                    height: 100%;
                }
            </style>
            <header class="main-banner__header">
                <div class="main-banner__content">
                    <h1 class="main-banner__title">
                        {{ isset($settings->banner_title[app()->getLocale()]) ? $settings->banner_title[app()->getLocale()] : __('messages.hero.title') }}
                    </h1>
                    <p class="main-banner__description">
                        {{ isset($settings->banner_description[app()->getLocale()]) ? $settings->banner_description[app()->getLocale()] : __('messages.hero.description') }}
                    </p>
                </div>
                <nav class="main-banner__nav" role="navigation" aria-label="{{ __('messages.hero.aria_label') }}">
                    <!-- <a href="{{ route('catalog.view', ['locale' => app()->getLocale()]) }}"
                        class="main-banner__link main-banner__link--catalog"
                        aria-label="{{ __('messages.hero.catalog_button') }}">
                        <span>{{ __('messages.hero.catalog_button') }}</span>
                        <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg"
                            class="main-banner__icon" aria-hidden="true">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M13.9697 6.1474C14.2626 5.85451 14.7374 5.85451 15.0303 6.1474L21.0303 12.1474C21.3232 12.4403 21.3232 12.9152 21.0303 13.2081L15.0303 19.2081C14.7374 19.501 14.2626 19.501 13.9697 19.2081C13.6768 18.9152 13.6768 18.4403 13.9697 18.1474L18.6893 13.4277H4.5C4.08579 13.4277 3.75 13.0919 3.75 12.6777C3.75 12.2635 4.08579 11.9277 4.5 11.9277H18.6893L13.9697 7.20806C13.6768 6.91517 13.6768 6.4403 13.9697 6.1474Z"
                                fill="#228F5D" />
                        </svg>
                    </a>
                    <a href="#" class="main-banner__link main-banner__link--buy"
                        aria-label="{{ __('messages.hero.buy_now_button') }}">
                        <span>{{ __('messages.hero.buy_now_button') }}</span>
                        <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg"
                            class="main-banner__icon" aria-hidden="true">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M13.9697 6.1474C14.2626 5.85451 14.7374 5.85451 15.0303 6.1474L21.0303 12.1474C21.3232 12.4403 21.3232 12.9152 21.0303 13.2081L15.0303 19.2081C14.7374 19.501 14.2626 19.501 13.9697 19.2081C13.6768 18.9152 13.6768 18.4403 13.9697 18.1474L18.6893 13.4277H4.5C4.08579 13.4277 3.75 13.0919 3.75 12.6777C3.75 12.2635 4.08579 11.9277 4.5 11.9277H18.6893L13.9697 7.20806C13.6768 6.91517 13.6768 6.4403 13.9697 6.1474Z"
                                fill="white" />
                        </svg>
                    </a> -->
                    <button
                        wire:click="$dispatch('openContactForm')"
                        class="px-6 py-3 bg-green-600 text-white rounded-2xl hover:bg-green-700 transition"
                        aria-label="{{ __('messages.feedback_form.submit_button') }}"
                    >
                        {{ __('messages.feedback_form.submit_button') }}
                    </button>
                </nav>
            </header>
        </section>

        <livewire:components.blog-section/>
        <div class="px-2 py-4 container mx-auto" id="contacts">
            <livewire:components.feedback-form-block/>
        </div>
    </div>
</div>
