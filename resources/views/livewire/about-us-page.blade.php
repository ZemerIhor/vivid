
<div class="max-w-full ">
    <div class="top-section relative mx-auto">

        <header class="font-semibold">
            <div class="flex relative flex-col items-center pb-20 w-full min-h-[780px] max-md:px-5 max-md:max-w-full">
                <!-- Фоновое изображение -->
                @if (isset($settings->hero_background_image) && Storage::disk('public')->exists($settings->hero_background_image))
                    <img src="{{ Storage::url($settings->hero_background_image) }}"
                         alt="{{ is_array($settings->hero_background_image_alt) ? ($settings->hero_background_image_alt[app()->getLocale()] ?? $settings->hero_background_image_alt['en'] ?? __('messages.about_us.hero_background_image_alt')) : ($settings->hero_background_image_alt ?? __('messages.about_us.hero_background_image_alt')) }}"
                         class="object-cover absolute inset-0 size-full " />
                @else
                    <img src="{{ asset('images/fallback-hero.jpg') }}"
                         alt="{{ __('messages.about_us.hero_background_image_alt') }}"
                         class="object-cover absolute inset-0 size-full" />
                @endif

                <div class="container md:px-12 px-4 mx-auto ">
                    <!-- Breadcrumbs -->
                    <nav aria-label="{{ __('messages.about_us.breadcrumbs_aria_label') }}"
                         class="flex relative flex-wrap gap-2 items-center self-stretch w-full text-xs min-h-[34px] max-md:max-w-full">
                        <div class="flex gap-2 items-center self-stretch py-2 my-auto whitespace-nowrap text-neutral-400">
                            <a href="/" class="self-stretch my-auto hover:text-white focus:text-white transition-colors">{{ __('messages.about_us.home') }}</a>
                        </div>
                        <div class="flex gap-2 items-center self-stretch py-2 my-auto text-zinc-800">
                            <div class="flex flex-col justify-center self-stretch my-auto w-1.5 whitespace-nowrap" aria-hidden="true">
                                <span>/</span>
                            </div>
                            <span class="self-stretch my-auto">{{ __('messages.about_us.title') }}</span>
                        </div>
                    </nav>

                    <!-- Hero Content -->
                    <div class="relative mx-auto mt-40 max-w-full font-bold text-white w-[568px] max-md:mt-10">
                        <div class="flex flex-col w-full max-md:max-w-full">
                            <!-- Логотип -->
                            @if (isset($settings->hero_logo) && Storage::disk('public')->exists($settings->hero_logo))
                                <img src="{{ Storage::url($settings->hero_logo) }}"
                                     alt="{{ is_array($settings->hero_logo_alt) ? ($settings->hero_logo_alt[app()->getLocale()] ?? $settings->hero_logo_alt['en'] ?? __('messages.about_us.hero_logo_alt')) : ($settings->hero_logo_alt ?? __('messages.about_us.hero_logo_alt')) }}"
                                     class="object-contain self-center" style="max-width:200px" />
                            @else
                                <img src="{{ asset('images/fallback-logo.png') }}"
                                     alt="{{ __('messages.about_us.hero_logo_alt') }}"
                                     class="object-contain self-center" style="max-width:200px" />
                            @endif

                            <div class="flex flex-col items-center mt-8 w-full max-md:max-w-full">
                                <!-- Заголовок -->
                                @if (isset($settings->hero_title) && (is_array($settings->hero_title) ? isset($settings->hero_title[app()->getLocale()]) : is_string($settings->hero_title)))
                                    <h1 class="text-8xl tracking-tighter leading-none text-center max-md:max-w-full max-md:text-4xl">
                                        {{ is_array($settings->hero_title) ? $settings->hero_title[app()->getLocale()] : $settings->hero_title }}
                                    </h1>
                                @else
                                    <h1 class="text-8xl tracking-tighter leading-none text-center max-md:max-w-full max-md:text-4xl">
                                        {{ __('messages.about_us.hero_title') }}
                                    </h1>
                                @endif

                                <!-- Подзаголовок -->
                                @if (isset($settings->hero_subtitle) && (is_array($settings->hero_subtitle) ? isset($settings->hero_subtitle[app()->getLocale()]) : is_string($settings->hero_subtitle)))
                                    <p class="self-stretch mt-2 text-2xl leading-7 text-center text-white max-md:max-w-full">
                                        {!! str_replace(
                                            is_array($settings->hero_subtitle_highlight) && isset($settings->hero_subtitle_highlight[app()->getLocale()]) ? $settings->hero_subtitle_highlight[app()->getLocale()] : '',
                                            '<span class="text-green-600">' . (is_array($settings->hero_subtitle_highlight) && isset($settings->hero_subtitle_highlight[app()->getLocale()]) ? $settings->hero_subtitle_highlight[app()->getLocale()] : '') . '</span>',
                                            is_array($settings->hero_subtitle) ? $settings->hero_subtitle[app()->getLocale()] : $settings->hero_subtitle
                                        ) !!}
                                    </p>
                                @else
                                    <p class="self-stretch mt-2 text-2xl leading-7 text-center text-white max-md:max-w-full">
                                        {{ __('messages.about_us.hero_subtitle') }}
                                    </p>
                                @endif

                                <!-- Слоган -->
                                @if (isset($settings->hero_slogan) && (is_array($settings->hero_slogan) ? isset($settings->hero_slogan[app()->getLocale()]) : is_string($settings->hero_slogan)))
                                    <p class="mt-2 text-base font-semibold leading-none">
                                        {{ is_array($settings->hero_slogan) ? $settings->hero_slogan[app()->getLocale()] : $settings->hero_slogan }}
                                    </p>
                                @else
                                    <p class="mt-2 text-base font-semibold leading-none">
                                        {{ __('messages.about_us.hero_slogan') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Описание -->
                    @if (isset($settings->hero_description) && (is_array($settings->hero_description) ? isset($settings->hero_description[app()->getLocale()]) : is_string($settings->hero_description)))
                        <p class="relative mx-auto mt-20 text-base leading-6 text-center text-white w-[780px] max-md:mt-10 max-md:max-w-full">
                            {{ is_array($settings->hero_description) ? $settings->hero_description[app()->getLocale()] : $settings->hero_description }}
                        </p>
                    @else
                        <p class="relative mx-auto mt-20 text-base leading-6 text-center text-white w-[780px] max-md:mt-10 max-md:max-w-full">
                            {{ __('messages.about_us.hero_description') }}
                        </p>
                    @endif
                </div>
            </div>
        </header>

        <!-- Advantages Section -->
        <div class="mx-auto md:px-12 px-4 container relative about-us-advantages py-12">

            <section aria-labelledby="advantages-title " class=" ">
                <div
                    class=" ">

                    <h2 id="advantages-title" class="sr-only">{{ __('messages.about_us.advantages_title') }}</h2>
                    @php
                        $advantages = isset($settings->advantages) && is_array($settings->advantages) ? ($settings->advantages[app()->getLocale()] ?? []) : [];
                        $advantage_images = $settings->advantage_images ?? [];
                    @endphp
                    @if (!empty($advantages))
                        <div class="about-us-advantages-grid  wrap-advantage">

                            @foreach($advantages as $index => $advantage)
                                <article>
                                    <div class="self-center text-4xl leading-none text-green-600">
                                        {{ $advantage['value'] ?? '0' }}
                                    </div>
                                    <div class="flex overflow-hidden flex-col items-center mt-3 w-full text-white">
                                        @if (isset($advantage['title']) && is_string($advantage['title']))
                                            <h3 class="text-base font-bold leading-tight text-white">
                                                {{ $advantage['title'] }}
                                            </h3>
                                        @endif
                                        @if (isset($advantage['description']) && is_string($advantage['description']))
                                            <p class="mt-2 text-xs font-semibold text-white">
                                                {{ $advantage['description'] }}
                                            </p>
                                        @endif
                                    </div>
                                </article>
                                @if (isset($advantage_images[$index]) && isset($advantage_images[$index]['image']) && Storage::disk('public')->exists($advantage_images[$index]['image']))
                                    <div class="img overflow-hidden">
                                        <img src="{{ Storage::url($advantage_images[$index]['image']) }}"
                                             alt="{{ is_array($advantage_images[$index]['alt']) ? ($advantage_images[$index]['alt'][app()->getLocale()] ?? $advantage_images[$index]['alt']['en'] ?? '') : ($advantage_images[$index]['alt'] ?? '') }}"
                                             class="object-cover h-full w-full" />
                                    </div>
                                @elseif (isset($advantage_images[$index]))
                                    <div class="overflow-hidden">
                                        <img src="{{ asset('images/fallback-advantage.jpg') }}"
                                             alt="{{ is_array($advantage_images[$index]['alt']) ? ($advantage_images[$index]['alt'][app()->getLocale()] ?? $advantage_images[$index]['alt']['en'] ?? '') : ($advantage_images[$index]['alt'] ?? '') }}"
                                             class="object-cover h-full w-full" />
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <p class="text-neutral-400 text-center">{{ __('messages.about_us.no_advantages') }}</p>
                    @endif
                </div>
            </section>
        </div>

<div class="relative container m-auto md:px-12 px-4 ">

        <img src="{{ asset('images/1.png') }}" alt="" class="object-contain self-stretch my-auto  max-md:max-w-full absolute left-0 bottom-0" />
        <img src="{{ asset('images/2.png') }}" alt="" class="object-contain self-stretch my-auto  max-md:max-w-full absolute right-0 bottom-0" />

        <section class="flex container mx-auto overflow-hidden relative flex-col items-center py-20 w-full max-w-screen-xl max-md:px-5 max-md:max-w-full">


                <!-- Content -->
                <div class="z-0 max-w-full text-center text-zinc-100">
                    @if (isset($settings->about_title) && (is_array($settings->about_title) ? isset($settings->about_title[app()->getLocale()]) : is_string($settings->about_title)))
                        <h2 class="text-2xl font-bold leading-tight text-zinc-100 max-md:max-w-full">
                            {{ is_array($settings->about_title) ? $settings->about_title[app()->getLocale()] : $settings->about_title }}
                        </h2>
                    @else
                        <h2 class="text-2xl font-bold leading-tight text-zinc-100 max-md:max-w-full">
                            {{ __('messages.about_us.about_title') }}
                        </h2>
                    @endif

                    @php
                        $about_descriptions = isset($settings->about_description) && is_array($settings->about_description) ? ($settings->about_description[app()->getLocale()] ?? []) : [];
                    @endphp
                    @if (!empty($about_descriptions))
                        <div class="mt-4 text-base font-semibold leading-6 text-zinc-100 max-md:max-w-full">
                            @foreach ($about_descriptions as $description)
                                @if (isset($description['text']) && is_string($description['text']))
                                    <p style="color: rgba(255, 255, 255, 1)">
                                        {{ $description['text'] }}
                                    </p>
                                    <br />
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="mt-4 text-base font-semibold leading-6 text-zinc-100 max-md:max-w-full">
                            <p style="color: rgba(255, 255, 255, 1)">
                                {{ __('messages.about_us.about_description_fallback') }}
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Call to Action Buttons -->
                <div class="flex z-0 flex-wrap gap-4 justify-center items-center mt-16 max-w-full text-base font-bold leading-snug w-[671px] max-md:mt-10">
                    <a href="{{ route('catalog.view', ['locale' => app()->getLocale()]) }}" class="flex gap-2 justify-center items-center self-stretch px-6 py-2.5 my-auto text-green-600 rounded-2xl border-2 border-green-600 border-solid min-h-11 max-md:px-5 hover:bg-green-600 hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 focus:ring-offset-zinc-800">
                        <span class="self-stretch my-auto">{{ __('messages.about_us.catalog') }}</span>
                        <svg width="19" height="14" viewBox="0 0 19 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M10.9697 0.46967C11.2626 0.176777 11.7374 0.176777 12.0303 0.46967L18.0303 6.46967C18.3232 6.76256 18.3232 7.23744 18.0303 7.53033L12.0303 13.5303C11.7374 13.8232 11.2626 13.8232 10.9697 13.5303C10.6768 13.2374 10.6768 12.7626 10.9697 12.4697L15.6893 7.75H1.5C1.08579 7.75 0.75 7.41421 0.75 7C0.75 6.58579 1.08579 6.25 1.5 6.25H15.6893L10.9697 1.53033C10.6768 1.23744 10.6768 0.762563 10.9697 0.46967Z" fill="#228F5D"/>
                        </svg>
                    </a>
                    <button class="flex gap-2 justify-center items-center self-stretch px-6 py-2.5 my-auto text-white bg-green-600 rounded-2xl min-h-11 max-md:px-5 hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 focus:ring-offset-zinc-800">
                        <span class="self-stretch my-auto">{{ __('messages.about_us.buy_now') }}</span>
                        <svg width="19" height="14" viewBox="0 0 19 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M10.9697 0.46967C11.2626 0.176777 11.7374 0.176777 12.0303 0.46967L18.0303 6.46967C18.3232 6.76256 18.3232 7.23744 18.0303 7.53033L12.0303 13.5303C11.7374 13.8232 11.2626 13.8232 10.9697 13.5303C10.6768 13.2374 10.6768 12.7626 10.9697 12.4697L15.6893 7.75H1.5C1.08579 7.75 0.75 7.41421 0.75 7C0.75 6.58579 1.08579 6.25 1.5 6.25H15.6893L10.9697 1.53033C10.6768 1.23744 10.6768 0.762563 10.9697 0.46967Z" fill="white"/>
                        </svg>
                    </button>
                </div>
            </section>

        </div>
    </div>


        <!-- Gallery Section -->
        <section class="m-auto relative overflow-hidden about-us-gallery" aria-labelledby="gallery-title">
            <div class="py-12 md:px-12 px-4 container m-auto">
                @if (isset($settings->gallery_title) && (is_array($settings->gallery_title) ? isset($settings->gallery_title[app()->getLocale()]) : is_string($settings->gallery_title)))
                    <h2 id="gallery-title" class="text-4xl font-bold text-zinc-800">
                        {{ is_array($settings->gallery_title) ? $settings->gallery_title[app()->getLocale()] : $settings->gallery_title }}
                    </h2>
                @else
                    <h2 id="gallery-title" class="text-4xl font-bold text-zinc-800">
                        {{ __('messages.about_us.gallery_title') }}
                    </h2>
                @endif

                @php
                    $gallery_images = $settings->gallery_images ?? [];
                @endphp
                @if (!empty($gallery_images))
                    <div class="mt-5 py-6 w-full h-[400px]" id="about-us_gallery-swiper">
                        <x-flexible-slider :aria-label="__('messages.about_us.gallery_aria_label')" :config="[
                            'loop' => true,
                            'autoplay' => ['delay' => 3000],
                            'spaceBetween' => 10,
                        ]">
                            @foreach ($gallery_images as $image)
                                <div class="swiper-slide gallery-slide flex overflow-hidden flex-col items-center rounded-3xl aspect-square">
                                    @if (isset($image['image']) && Storage::disk('public')->exists($image['image']))
                                        <img src="{{ Storage::url($image['image']) }}"
                                             alt="{{ is_array($image['alt']) ? ($image['alt'][app()->getLocale()] ?? $image['alt']['en'] ?? 'Gallery Image') : ($image['alt'] ?? 'Gallery Image') }}"
                                             class="object-cover w-[280px] h-[280px]" />
                                    @else
                                        <img src="{{ asset('images/fallback-gallery.jpg') }}"
                                             alt="{{ is_array($image['alt']) ? ($image['alt'][app()->getLocale()] ?? $image['alt']['en'] ?? 'Gallery Image') : ($image['alt'] ?? 'Gallery Image') }}"
                                             class="object-cover w-[280px] h-[280px]" />
                                    @endif
                                </div>
                            @endforeach
                        </x-flexible-slider>
                    </div>
                @else
                    <p class="text-neutral-400 text-center">{{ __('messages.about_us.no_gallery_images') }}</p>
                @endif
            </div>

            <div class="absolute z-[-1] left-1/2 transform -translate-x-1/2 bottom-0">
                <svg class="bg-shape" viewBox="0 0 1236 556" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g filter="url(#filter0_dd_125_12566)">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M704.402 534.649C704.402 602.398 678.025 664.179 635.28 712.46C578.026 777.873 509.176 815.049 417.804 815.049C254.275 815.049 158.981 683.444 131.922 547.557C113.49 461.898 126.039 444.766 122.118 394.927C120.941 385.583 120.941 381.689 113.49 379.353C112.314 379.353 112.314 378.574 112.314 378.963C112.314 379.353 111.137 378.963 111.137 378.963C107.608 378.963 111.137 377.795 107.608 379.353C96.6275 384.025 80.549 422.961 77.0197 431.917C48.7843 509.4 31.9216 582.6 28 653.074V710.31C35.451 823.615 75.8432 931.463 155.059 1039.33L195.451 1087.99C211.529 1105.51 229.568 1119.53 241.726 1131.21C254.275 1143.67 267.608 1153.4 280.941 1163.53C362.902 1224.27 494.667 1278 598.588 1278H641.333C744.863 1278 840.157 1236.73 915.843 1189.23C923.294 1184.94 930.745 1180.66 937.804 1175.21C945.255 1170.15 952.706 1165.47 958.589 1160.02C975.844 1146.4 1002.51 1125.37 1017.41 1110.19L1053.88 1072.41C1060.16 1065.02 1063.69 1059.57 1069.96 1052.56C1158.98 942.367 1208 808.041 1208 660.472C1208 593.112 1186.04 498.887 1157.8 439.314C1133.49 388.308 1115.06 357.938 1079.76 313.551L1073.23 304.195C986.376 217.568 917.02 162.867 900.157 150.019C890.353 143.399 884.079 138.338 874.275 130.94L848.784 109.525C840.157 102.128 834.275 96.6756 824.471 89.2784L754.666 21.9186C714.667 -17.4064 700.941 -34.1498 674.275 -79.7052C640.157 -135.774 635.059 -193.009 635.059 -260.758C635.059 -298.526 655.844 -371.726 622.902 -338.631C604.47 -320.331 564.47 -247.52 551.921 -222.6C533.883 -187.558 538.589 -202.744 529.96 -168.48C526.431 -157.967 524.078 -149.79 522.901 -139.666L514.275 -72.3067C513.098 -59.4586 513.098 -47.7775 511.922 -36.4861L516.628 32.8202C532.706 162.479 618 300.036 673.099 403.493C672.658 402.393 704.402 472.425 704.402 534.649Z"
                              fill="#F3F3F3" />
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M291.906 92.0079C291.906 158.589 304.063 207.649 324.848 256.709C349.161 318.617 380.926 355.606 429.945 394.931C440.925 403.108 445.631 408.949 455.436 418.294C460.533 423.355 462.886 424.134 467.985 428.807C539.749 493.829 595.827 593.117 536.22 685.785C515.435 718.102 489.946 732.509 486.024 745.358C489.946 750.809 491.122 751.587 498.18 750.029C503.279 748.473 511.906 744.968 515.435 743.022C554.651 725.89 567.985 718.881 599.749 686.953C619.357 665.539 642.495 625.044 649.553 597.399C673.083 508.236 633.867 403.887 581.318 333.802C560.534 307.325 528.769 261.77 518.965 230.232C504.455 185.455 533.867 193.632 465.239 176.5C406.808 161.704 368.769 134.059 335.828 84.9992C310.337 48.0104 307.984 18.419 300.534 9.46381C295.435 31.6568 291.906 64.7527 291.906 92.0079Z"
                              fill="#F3F3F3" />
                    </g>
                    <defs>
                        <filter id="filter0_dd_125_12566" x="0" y="-375" width="1236" height="1681"
                                filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                            <feFlood flood-opacity="0" result="BackgroundImageFix" />
                            <feColorMatrix in="SourceAlpha" type="matrix"
                                           values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha" />
                            <feMorphology radius="4" operator="erode" in="SourceAlpha"
                                          result="effect1_dropShadow_125_12566" />
                            <feOffset />
                            <feGaussianBlur stdDeviation="2" />
                            <feComposite in2="hardAlpha" operator="out" />
                            <feColorMatrix type="matrix"
                                           values="0 0 0 0 0.0470588 0 0 0 0 0.0470588 0 0 0 0 0.0509804 0 0 0 0.05 0" />
                            <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_125_12566" />
                            <feColorMatrix in="SourceAlpha" type="matrix"
                                           values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha" />
                            <feMorphology radius="4" operator="erode" in="SourceAlpha"
                                          result="effect2_dropShadow_125_12566" />
                            <feOffset />
                            <feGaussianBlur stdDeviation="16" />
                            <feComposite in2="hardAlpha" operator="out" />
                            <feColorMatrix type="matrix"
                                           values="0 0 0 0 0.0470588 0 0 0 0 0.0470588 0 0 0 0 0.0509804 0 0 0 0.15 0" />
                            <feBlend mode="normal" in2="effect1_dropShadow_125_12566"
                                     result="effect2_dropShadow_125_12566" />
                            <feBlend mode="normal" in="SourceGraphic" in2="effect2_dropShadow_125_12566"
                                     result="shape" />
                        </filter>
                    </defs>
                </svg>
            </div>
        </section>

        <!-- Certificates Section -->
    <section class="container relative mx-auto about-us-certificate md:px-12 py-12 px-4" aria-labelledby="certificates-title">
        <div>
            @if (isset($settings->certificates_title) && (is_array($settings->certificates_title) ? isset($settings->certificates_title[app()->getLocale()]) : is_string($settings->certificates_title)))
                <h2 id="certificates-title" class="text-3xl font-bold text-white">
                    {{ is_array($settings->certificates_title) ? $settings->certificates_title[app()->getLocale()] : $settings->certificates_title }}
                </h2>
            @else
                <h2 id="certificates-title" class="text-3xl font-bold text-white">
                    {{ __('messages.about_us.certificates_title') }}
                </h2>
            @endif

            @php
                $certificates_images = $settings->certificates_images ?? [];
            @endphp
            @if (!empty($certificates_images))
                <div class="mt-5 w-full h-[400px]">
                    <x-flexible-slider :aria-label="__('messages.about_us.certificates_aria_label')" :config="[
                    'loop' => false,
                    'autoplay' => ['delay' => 3000],
                    'spaceBetween' => 10,
                ]">
                        @foreach ($certificates_images as $image)
                            <div class="swiper-slide certificate-slide flex flex-col items-center rounded-3xl aspect-[0.71] bg-white shadow-md">
                                @if (isset($image['image']) && Storage::disk('public')->exists($image['image']))
                                    <img src="{{ Storage::url($image['image']) }}"
                                         alt="{{ is_array($image['alt']) ? ($image['alt'][app()->getLocale()] ?? $image['alt']['en'] ?? 'Certificate Image') : ($image['alt'] ?? 'Certificate Image') }}"
                                         class="object-contain w-full h-full rounded-3xl cursor-pointer"
                                         data-fullscreen-src="{{ Storage::url($image['image']) }}"
                                         onclick="openModal(this)" />
                                @else
                                    <img src="{{ asset('images/fallback-certificate.jpg') }}"
                                         alt="{{ is_array($image['alt']) ? ($image['alt'][app()->getLocale()] ?? $image['alt']['en'] ?? 'Certificate Image') : ($image['alt'] ?? 'Certificate Image') }}"
                                         class="object-contain w-full h-full rounded-3xl cursor-pointer"
                                         data-fullscreen-src="{{ asset('images/fallback-certificate.jpg') }}"
                                         onclick="openModal(this)" />
                                @endif
                            </div>
                        @endforeach
                    </x-flexible-slider>
                </div>

                <!-- Fullscreen Modal -->
                <div id="fullscreen-modal" class="fixed inset-0 bg-black bg-opacity-80 hidden flex items-center justify-center z-50 p-4">
                    <div class="relative max-w-3xl max-h-[90vh] bg-white rounded-2xl shadow-2xl p-6">
                        <button id="close-modal" class="absolute top-2 right-2 text-gray-600 hover:text-gray-800 text-3xl z-50">&times;</button>
                        <img id="modal-image" src="" alt="Fullscreen Certificate" class="object-contain max-w-full max-h-[80vh] mx-auto rounded-xl" />
                    </div>
                </div>
            @else
                <p class="text-neutral-400 text-center">{{ __('messages.about_us.no_certificates') }}</p>
            @endif
        </div>

        @push('scripts')
            <script>
                function openModal(element) {
                    const modal = document.getElementById('fullscreen-modal');
                    const modalImage = document.getElementById('modal-image');
                    modalImage.src = element.getAttribute('data-fullscreen-src');
                    modalImage.alt = element.alt;
                    modal.classList.remove('hidden');
                }

                document.addEventListener('DOMContentLoaded', function () {
                    const modal = document.getElementById('fullscreen-modal');
                    const closeModal = document.getElementById('close-modal');

                    // Close modal on close button click
                    closeModal.addEventListener('click', function () {
                        modal.classList.add('hidden');
                    });

                    // Close modal on click outside
                    modal.addEventListener('click', function (e) {
                        if (e.target === modal) {
                            modal.classList.add('hidden');
                        }
                    });
                });
            </script>
        @endpush
    </section>


        <livewire:components.blog-section />
    <style>
        @media (max-width: 767px) {
            .wrap-advantage {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }
        }
    </style>
    </div>



