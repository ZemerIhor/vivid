<main class="flex container m-auto relative flex-col gap-14 items-start self-stretch px-12 py-4 max-sm:gap-8 max-sm:px-4 product-page">
    <!-- Product Header Section -->
    <header class="flex relative flex-col gap-4 items-start self-stretch">
        <div class="flex relative flex-col gap-1 items-start">
            <h1 class="relative text-2xl font-bold leading-7 text-zinc-800 w-[1179px] max-md:w-full max-sm:text-xl">
                {{ $this->product->translateAttribute('name') }}
            </h1>
            <p class="relative text-xs font-semibold leading-5 text-neutral-400 w-[1179px] max-md:w-full">
                {{ __('messages.product.sku_label') }}: {{ $this->variant->sku }}
            </p>
        </div>

        <div class="flex relative gap-28 items-start self-stretch max-md:flex-col max-md:gap-8">
            <section class="flex relative flex-col items-start w-[487px] max-md:w-full"
                     aria-label="{{ __('messages.product.image_gallery') }}">
                <div class="swiper product-gallery w-full rounded-3xl" wire:ignore>
                    <div class="swiper-wrapper">
                        @foreach ($this->images as $media)
                            <div class="swiper-slide flex justify-center items-center">
                                <img src="{{ $media->getUrl() }}"
                                     alt="{{ $this->product->translateAttribute('name') }} - {{ __('messages.product.image') }} {{ $loop->iteration }}"
                                     class="object-cover h-[368px] w-[645px] max-md:w-full"/>
                            </div>
                        @endforeach
                        @if (!$this->images->count())
                            <div class="swiper-slide flex justify-center items-center">
                                <img src="https://via.placeholder.com/645x368"
                                     alt="{{ __('messages.product.placeholder_image') }}"
                                     class="object-contain h-[368px] w-[645px] max-md:w-full"/>
                            </div>
                        @endif
                    </div>

                </div>

                <div class="swiper-pagination" style="position: absolute; bottom: -50px !important; left: 0; width: 136px;"></div>
                <div class="swiper-button-prev" style="left: 413px; bottom: -50px !important; width: 26px; height: 56px;">
                    <svg width="41" height="42" viewBox="0 0 41 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11.0777 15.4464L11.173 30.8638L30.6581 11.1361L22.0416 19.86C18.0472 23.904 11.1561 21.13 11.0777 15.4464L-nan -nanL11.0777 15.4464Z" fill="#228F5D"/>
                        <path d="M11.173 30.8638L21.4513 30.8002L26.5905 30.7684L-nan -nanL26.5905 30.7684C20.9036 30.783 18.0273 23.9242 22.0236 19.8781L30.6581 11.1361L11.173 30.8638Z" fill="#228F5D"/>
                        <path d="M10.9082 9.99996C11.4605 9.99654 11.9106 10.4419 11.9141 10.9941L11.9473 16.1328C12.0186 20.8488 17.6495 23.1868 21.0391 20.0058L21.1992 19.8496L29.8154 11.1259C30.2034 10.7331 30.8365 10.7294 31.2295 11.1171C31.5977 11.4809 31.6252 12.0603 31.3076 12.455L31.2383 12.5312L22.6045 21.2734C19.233 24.6868 21.6593 30.4732 26.457 30.4609L31.5928 30.4296L31.6953 30.4336C32.1997 30.4819 32.5954 30.9062 32.5986 31.4238C32.6015 31.9413 32.2108 32.3685 31.707 32.4228L31.6045 32.4296L11.0479 32.5566C10.4959 32.5597 10.0455 32.1145 10.042 31.5625L9.91506 11.0058C9.91184 10.4539 10.3564 10.0037 10.9082 9.99996ZM13.4502 30.542L21.3965 30.4931C19.8677 29.0517 18.9983 26.9881 19.0342 24.8877L13.4502 30.542ZM12.0264 29.1367L17.627 23.4668C15.5219 23.53 13.442 22.6847 11.9785 21.1689L12.0264 29.1367Z" fill="#228F5D"/>
                    </svg>
                </div>
                <div class="swiper-button-next" style="left: 449px; bottom: -50px !important; width: 26px; height: 56px;">
                    <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M27.634 23.6144L27.634 8.19664L8.02722 27.8034L16.6976 19.133C20.7169 15.1137 27.5907 17.9304 27.634 23.6144Z" fill="#228F5D"/>
                        <path d="M27.634 8.19664L17.3555 8.19664L12.2163 8.19664C17.9031 8.21723 20.7369 15.0937 16.7156 19.115L8.02722 27.8034L27.634 8.19664Z" fill="#228F5D"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M27.875 7C28.4271 7.00021 28.8749 7.44791 28.875 8V28.5566C28.8749 29.1087 28.4271 29.5564 27.875 29.5566C27.323 29.5564 26.876 29.1087 26.876 28.5566L26.875 23.417C26.8326 18.7009 21.2157 16.3284 17.8066 19.4883L17.6455 19.6436L8.97559 28.3135C8.5851 28.704 7.95206 28.7039 7.56152 28.3135C7.19558 27.9474 7.17206 27.3683 7.49219 26.9756L7.56152 26.8994L16.25 18.2109C19.6418 14.8184 17.2514 9.01767 12.4541 9H7.31836C6.76615 9 6.31848 8.55218 6.31836 8C6.31844 7.44778 6.76612 7 7.31836 7H27.875ZM21.2412 16.0488C23.3464 15.9987 25.4201 16.8563 26.874 18.3809L26.876 10.4141L21.2412 16.0488ZM17.5146 9C19.0344 10.4508 19.8906 12.5191 19.8418 14.6191L25.4609 9H17.5146Z" fill="#228F5D"/>
                    </svg>
                </div>
            </section>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    let swiperInstance = null;

                    function destroySwiper() {
                        if (swiperInstance) {
                            console.log('Уничтожение Swiper');
                            swiperInstance.destroy(true, true);
                            swiperInstance = null;
                        }
                    }

                    function waitForElement(selector, callback, interval = 100, timeout = 3000) {
                        const startTime = Date.now();
                        const check = () => {
                            const el = document.querySelector(selector);
                            if (el) {
                                callback(el);
                            } else if (Date.now() - startTime < timeout) {
                                setTimeout(check, interval);
                            } else {
                                console.warn(`Элемент ${selector} не найден за ${timeout} мс`);
                            }
                        };
                        check();
                    }

                    function initSwiper() {
                        console.log('Попытка инициализировать Swiper...');
                        if (!window.Swiper) {
                            console.error('Swiper не загружен');
                            return;
                        }

                        const swiperContainer = document.querySelector('.product-gallery');
                        if (!swiperContainer) {
                            console.error('Контейнер .product-gallery не найден');
                            return;
                        }

                        const slides = swiperContainer.querySelectorAll('.swiper-slide');
                        destroySwiper();

                        swiperInstance = new Swiper(swiperContainer, {
                            slidesPerView: 1,
                            spaceBetween: 0,
                            loop: slides.length > 1,
                            touchRatio: 1,
                            grabCursor: true,
                            pagination: {
                                el: '.swiper-pagination',
                                clickable: true,
                                bulletClass: 'swiper-pagination-bullet',
                                bulletActiveClass: 'swiper-pagination-bullet-active',
                                bulletElement: 'span',
                                type: 'bullets',
                            },
                            navigation: {
                                nextEl: '.swiper-button-next',
                                prevEl: '.swiper-button-prev',
                                disabledClass: 'swiper-button-disabled',
                            },
                            speed: 600,
                            watchSlidesProgress: true,
                            on: {
                                init: function () {
                                    console.log('Swiper инициализирован, слайдов:', slides.length);
                                },
                                slideChange: function () {
                                    console.log('Слайд изменен, индекс:', this.activeIndex);
                                }
                            },
                        });

                        swiperInstance.on('reachBeginning reachEnd', function () {
                            console.log('Достигнут край слайдера');
                        });
                    }

                    // Инициализация при первом рендере
                    waitForElement('.swiper-pagination', () => initSwiper());

                    // Livewire события
                    document.addEventListener('livewire:update', () => {
                        console.log('Livewire:update – перезапуск Swiper');
                        waitForElement('.swiper-pagination', () => initSwiper());
                    });

                    document.addEventListener('livewire:navigated', () => {
                        console.log('Livewire:navigated – перезапуск Swiper');
                        waitForElement('.swiper-pagination', () => initSwiper());
                    });
                });
            </script>


            <section class="flex relative flex-col gap-6 items-end flex-[1_0_0]">
                <p class="relative self-stretch text-base font-semibold leading-5 text-black max-sm:text-sm">
                    {!! nl2br(e(html_entity_decode(strip_tags($this->product->translateAttribute('description'))))) !!}
                </p>

                @if ($this->product->shortPoints->count() > 0)
                <table class="product-properties flex relative flex-col items-start self-stretch" role="table" aria-label="{{ __('messages.product.short_specs') }}">
                    <tbody class="w-full">
                    @foreach ($this->product->shortPoints as $point)
                        <tr class="flex relative items-center self-stretch {{ $loop->even ? 'bg-white' : '' }} rounded-lg">
                            <td class="flex relative gap-2.5 items-center px-4 py-2 flex-[1_0_0]">
                                <span class="relative text-base font-semibold leading-5 flex-[1_0_0] text-zinc-600 max-sm:text-sm">
                                    {{ $point->name[app()->getLocale()] ?? $point->name['en'] ?? '' }}
                                </span>
                            </td>
                            <td class="flex relative gap-2.5 justify-end items-center px-4 py-2 flex-[1_0_0]">
                                <span class="relative text-base font-semibold leading-5 text-right flex-[1_0_0] text-zinc-800 max-sm:text-sm">
                                    {{ $point->value[app()->getLocale()] ?? $point->value['en'] ?? '' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @endif

                <!-- Price and Actions Section -->
                <div class="flex relative justify-between items-center self-stretch h-11">
                    <div class="flex relative gap-1 items-center flex-[1_0_0]">
                        <span class="relative text-2xl font-bold leading-7 text-zinc-800 max-sm:text-xl">
                            <x-product-price :variant="$this->variant" />
                        </span>
                    </div>
                    <div class="flex relative gap-4 items-center max-sm:flex-col max-sm:gap-3">
                        <!-- Quantity Selection -->
                        <div class="flex relative gap-2 items-center px-2 py-0 h-11 rounded-2xl bg-neutral-200"
                             role="group"
                             aria-label="{{ __('messages.product.quantity_selection') }}">
                            <button wire:click="decrementQuantity"
                                    class="flex relative gap-2.5 items-center"
                                    aria-label="{{ __('messages.product.decrement_quantity') }}">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="minus-icon">
                                    <path d="M17.2174 12.5C17.6496 12.5 18 12.1642 18 11.75C18 11.3358 17.6496 11 17.2174 11H6.78261C6.35039 11 6 11.3358 6 11.75C6 11.5858 6.35039 12.5 6.78261 12.5H17.2174Z" fill="#333333"/>
                                </svg>
                            </button>

                            <div class="flex relative gap-2.5 justify-center items-center">
                                <span class="relative text-base font-semibold leading-5 text-zinc-800 max-sm:text-sm">
                                    {{ $this->quantity }}
                                </span>
                            </div>
                            <button wire:click="incrementQuantity"
                                    class="flex relative gap-2.5 items-center"
                                    aria-label="{{ __('messages.product.increment_quantity') }}">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="plus-icon">
                                    <path d="M12.75 7C12.75 6.58579 12.4142 6.25 12 6.25C11.5858 6.25 11.25 6.58579 11.25 7L11.25 11.25H7C6.58579 11.25 6.25 11.5858 6.25 12C6.25 12.4142 6.58579 12.75 7 12.75H11.25V17C11.25 17.4142 11.5858 17.75 12 17.75C12.4142 17.75 12.75 17.4142 12.75 17L12.75 12.75H17C17.4142 12.75 17.75 12.4142 17.75 12C17.75 11.5858 17.4142 11.25 17 11.25H12.75V7Z" fill="#333333"/>
                                </svg>
                            </button>
                        </div>
                        <livewire:components.add-to-cart :purchasable="$this->variant" :quantity="$this->quantity" :wire:key="$this->variant->id" />
                    </div>
                </div>
            </section>
        </div>
    </header>

    <!-- Description Section -->
    <section class="flex relative flex-col gap-4 items-start self-stretch">
        <h2 class="relative self-stretch text-xl font-bold leading-6 text-black max-sm:text-lg">
            {{ __('messages.product.description') }}
        </h2>
        <p class="relative self-stretch text-base font-semibold leading-5 text-black max-sm:text-sm">
            {!! nl2br(e(html_entity_decode(strip_tags($this->product->translateAttribute('description'))))) !!}
        </p>
    </section>

    <!-- Characteristics Section -->
    @if ($this->product->characteristics->count() > 0)
    <section class="characteristics-section flex relative flex-col gap-4 items-start self-stretch">
        <h2 class="relative self-stretch text-xl font-bold leading-6 text-black max-sm:text-lg">
            {{ __('messages.product.characteristics') }}
        </h2>
        <table class="characteristics flex relative flex-col items-start self-stretch overflow-auto" style="overflow: auto" role="table" aria-label="{{ __('messages.product.characteristics_table') }}">
            <!-- Table Header -->
            <thead class="flex relative items-center self-stretch rounded-lg bg-zinc-800">
            <tr class="flex relative w-full items-center self-stretch rounded-lg">
                <th class="flex relative gap-2.5 items-center px-4 py-2 flex-[1_0_0]">
                        <span class="relative text-base font-semibold leading-5 text-white flex-[1_0_0] max-sm:text-sm">
                            {{ __('messages.product.indicator_name') }}
                        </span>
                </th>
                <th class="flex relative gap-2.5 justify-end items-center px-4 py-2 flex-[1_0_0]">
                        <span class="relative text-base font-semibold leading-5 text-right text-white flex-[1_0_0] max-sm:text-sm">
                            {{ __('messages.product.standard_norm') }}
                        </span>
                </th>
                <th class="flex relative gap-2.5 justify-end items-center px-4 py-2 flex-[1_0_0]">
                        <span class="relative text-base font-semibold leading-5 text-right text-white flex-[1_0_0] max-sm:text-sm">
                            {{ __('messages.product.actual_values') }}
                        </span>
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach ($this->product->characteristics as $characteristic)
                <tr class="flex relative w-full items-center self-stretch border-b border-gray-200">
                    <td class="flex relative gap-2.5 items-center px-4 py-2 flex-[1_0_0]">
                            <span class="relative text-base font-medium leading-5 text-black max-sm:text-sm">
                                {{ $characteristic->name[app()->getLocale()] ?? $characteristic->name['en'] ?? '' }}
                            </span>
                    </td>
                    <td class="flex relative gap-2.5 justify-end items-center px-4 py-2 flex-[1_0_0]">
                            <span class="relative text-base font-medium leading-5 text-right text-black max-sm:text-sm">
                                {{ $characteristic->standard[app()->getLocale()] ?? $characteristic->standard['en'] ?? '' }}
                            </span>
                    </td>
                    <td class="flex relative gap-2.5 justify-end items-center px-4 py-2 flex-[1_0_0]">
                            <span class="relative text-base font-medium leading-5 text-right text-black max-sm:text-sm">
                                {{ $characteristic->actual[app()->getLocale()] ?? $characteristic->actual['en'] ?? '' }}
                            </span>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </section>
    @endif
</main>
