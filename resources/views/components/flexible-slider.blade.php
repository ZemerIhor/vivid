@php
    $uniqueId = 'swiper_' . uniqid();
    $swiperClass = $uniqueId . '_instance';
    $prevClass = $uniqueId . '_prev';
    $nextClass = $uniqueId . '_next';
    $paginationClass = $uniqueId . '_pagination';

    $defaultConfig = [
        'loop' => true,
        'autoplay' => [
            'delay' => 5000,
        ],
        'spaceBetween' => 20,
        'slidesPerView' => 'auto',
        'navigation' => [
            'nextEl' => '.' . $nextClass,
            'prevEl' => '.' . $prevClass,
        ],
        'pagination' => [
            'el' => '.' . $paginationClass,
            'clickable' => true,
            'bulletClass' => 'bg-gray-300 w-3 h-3 rounded-full',
            'bulletActiveClass' => 'bg-green-600',
        ],
    ];

    $config = array_replace_recursive($defaultConfig, $attributes->get('config') ?? []);
@endphp

<div class="relative w-full overflow-hidden py-6">
    <div class="swiper relative {{ $swiperClass }}" data-swiper-config='@json($config)'>
        <div class="swiper-wrapper pb-[50px] flex gap-5 items-center w-full" role="region" aria-label="{{ $ariaLabel ?? 'Галерея зображень' }}">
            {{ $slot }}
        </div>

        <div class="swiper-button-prev {{ $prevClass }} absolute bottom-[-10px] right-[50px] z-10  rounded-full transition-transform transform-gpu origin-center hover:scale-[1.03] ">
           <svg width="44" height="44" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M12.1636 16.4466L12.259 31.8641L31.7441 12.1364L23.1275 20.8602C19.1332 24.9043 12.242 22.1302 12.1636 16.4466L-nan -nanL12.1636 16.4466Z" fill="#228F5D"/>
<path d="M12.259 31.8641L22.5373 31.8005L27.6764 31.7687L-nan -nanL27.6764 31.7687C21.9895 31.7833 19.1133 24.9245 23.1096 20.8784L31.7441 12.1364L12.259 31.8641Z" fill="#228F5D"/>
<path d="M33.8153 31.7307C33.8184 32.2485 33.4277 32.6764 32.9238 32.7306L32.8216 32.7368L12.2648 32.8639C11.7128 32.8672 11.2623 32.4223 11.2588 31.8703L11.1316 11.3135C11.1282 10.7614 11.5732 10.311 12.1253 10.3074C12.6775 10.304 13.1279 10.7489 13.1313 11.3011L13.1638 16.4406C13.2351 21.1566 18.8665 23.4946 22.256 20.3136L22.416 20.1572L31.0325 11.4334C31.4206 11.0405 32.0538 11.0367 32.4467 11.4247C32.815 11.7884 32.8418 12.3677 32.524 12.7625L32.4555 12.8389L23.8211 21.5807C20.4496 24.9942 22.8761 30.7804 27.6738 30.7681L32.8093 30.737L32.9122 30.7412C33.4165 30.7895 33.8121 31.2131 33.8153 31.7307ZM18.8434 23.7743C16.7383 23.8376 14.6591 22.9924 13.1957 21.4766L13.2436 29.4438L18.8434 23.7743ZM22.613 30.8001C21.0842 29.3587 20.2154 27.2955 20.2512 25.195L14.6665 30.8493L22.613 30.8001Z" fill="#228F5D"/>
</svg>




        </div>
        <div class="swiper-button-next {{ $nextClass }} absolute bottom-[-10px] right-0 top-auto left-auto z-10   rounded-full  transition-transform transform-gpu origin-center hover:scale-[1.03] ">
            <svg width="37" height="36" viewBox="0 0 37 36" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M28.3039 23.6143L28.3039 8.19658L8.69714 27.8033L17.3675 19.133C21.3868 15.1137 28.2607 17.9304 28.3039 23.6143Z" fill="#228F5D"/>
<path d="M28.3039 8.19658L18.0254 8.19658L12.8862 8.19658C18.5731 8.21717 21.4068 15.0936 17.3856 19.1149L8.69714 27.8033L28.3039 8.19658Z" fill="#228F5D"/>
<path d="M6.74805 8.19643C6.74812 7.64421 7.1957 7.19653 7.74794 7.19653L28.3051 7.19653C28.8572 7.19674 29.305 7.64434 29.305 8.19643V28.7536C29.305 29.3057 28.8572 29.7533 28.3051 29.7535C27.7529 29.7535 27.3053 29.3059 27.3052 28.7536L27.3046 23.614C27.2624 18.8976 21.6456 16.5249 18.2365 19.6848L18.0756 19.8402L9.40522 28.5106C9.01474 28.901 8.38154 28.901 7.99101 28.5106C7.62496 28.1445 7.60179 27.5651 7.92195 27.1723L7.99101 27.0963L16.6793 18.408C20.0718 15.0155 17.6812 9.2144 12.8834 9.19701L7.74794 9.19632C7.19565 9.19632 6.74805 8.74871 6.74805 8.19643ZM21.6705 16.2453C23.7759 16.195 25.8498 17.0531 27.3039 18.5779L27.3052 10.6105L21.6705 16.2453ZM17.9444 9.19632C19.4642 10.6471 20.3203 12.7157 20.2715 14.8159L25.891 9.19632H17.9444Z" fill="#228F5D"/>
</svg>


        </div>

        <div class="swiper-pagination {{ $paginationClass }} absolute bottom-4 justify-center left-1/2 -translate-x-1/2 flex space-x-2"></div>
    </div>
    <style>
        .swiper-button-next:after, .swiper-button-prev:after {
           content: "";
            display: none;
        }
    </style>
    <!-- @pushOnce('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.swiper').forEach(function (el) {
                    const config = JSON.parse(el.dataset.swiperConfig || '{}');
                    const swiper = new Swiper(el, config);

                    setTimeout(() => {
                        swiper.update();
                    }, 100);
                });
            });
        </script>
    @endpushOnce -->
</div>
