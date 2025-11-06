<div class="sm:relative" x-data="{ linesVisible: @entangle('linesVisible').live }">
    <!-- Cart Button -->
    <button class="relative grid w-16 h-16 transition border-l border-gray-100 lg:border-l-transparent hover:opacity-75"
            x-on:click="linesVisible = !linesVisible"
            aria-label="{{ __('messages.cart.cart') }}">
        <span class="place-self-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
        </span>
        @if (count($lines) > 0)
            <span class="absolute top-2 right-2 flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-green-600 rounded-full">
                {{ count($lines) }}
            </span>
        @endif
    </button>

    <!-- Cart Content -->
    <main class="absolute right-0 top-auto z-50 w-screen max-w-sm mx-auto mt-4 bg-white border border-gray-100 shadow-xl sm:left-auto rounded-xl max-md:px-5"
          x-show="linesVisible"
          x-on:click.away="linesVisible = false"
          x-transition
          x-cloak
          role="main"
          aria-labelledby="basket-title">
        <!-- Close Button -->
        <button class="absolute top-0 right-0 z-10 p-8 max-md:p-5 hover:bg-gray-50 rounded-full transition-colors"
                type="button"
                aria-label="{{ __('messages.cart.close') }}"
                x-on:click="linesVisible = false">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Cart Items Section -->
        <section class="z-0 w-full p-8 max-md:max-w-full" aria-labelledby="basket-title">
            <h1 id="basket-title" class="text-2xl font-bold leading-tight text-black max-md:max-w-full">
                {{ __('messages.cart.cart') }}
            </h1>

            @if ($this->cart && $lines && count($lines))
                @foreach ($lines as $index => $line)
                    <article class="flex flex-wrap gap-6 items-start mt-4 w-full max-md:max-w-full"
                             role="group"
                             aria-labelledby="item-{{ $line['id'] }}-title"
                             wire:key="line_{{ $line['id'] }}">
                        <!-- Product Image -->
                        <img src="{{ $line['thumbnail'] && (is_string($line['thumbnail']) || method_exists($line['thumbnail'], 'getUrl')) ? (is_string($line['thumbnail']) ? $line['thumbnail'] : $line['thumbnail']->getUrl()) : asset('images/fallback-product.jpg') }}"
                             alt="{{ $line['description'] ?? 'Product Image' }}"
                             class="object-contain shrink-0 w-32 rounded-2xl aspect-square">

                        <!-- Product Details -->
                        <div class="flex-1 shrink basis-0 min-h-[129px] min-w-60 max-md:max-w-full">
                            <h2 id="item-{{ $line['id'] }}-title" class="text-base font-semibold leading-6 text-zinc-800 max-md:max-w-full">
                                {{ $line['description'] ?? '' }}
                            </h2>

                            <div class="flex flex-wrap gap-5 items-center mt-3 w-full max-md:max-w-full">
                                <p class="flex-1 shrink self-stretch my-auto text-xs font-semibold basis-0 text-neutral-400 max-md:max-w-full">
                                    {{ __('messages.cart.identifier') }}: {{ $line['identifier'] ?? '' }}
                                </p>
                                <div class="flex gap-1 items-center self-stretch my-auto text-base font-bold leading-tight text-right text-zinc-800"
                                     role="text"
                                     aria-label="{{ __('messages.cart.price') }} {{ $line['unit_price'] ?? '' }}">
                                    <span class="self-stretch my-auto text-zinc-800">{{ $line['unit_price'] ?? '' }}</span>
                                    <span class="self-stretch my-auto text-zinc-800">₴</span>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-10 justify-between items-end mt-3 w-full max-md:max-w-full">
                                <!-- Quantity Controls -->
                                <div class="flex gap-2 items-center px-2 rounded-2xl bg-neutral-200 min-h-11"
                                     role="group"
                                     aria-label="{{ __('messages.cart.quantity_controls') }}">
                                    <button class="flex gap-2.5 items-center self-stretch my-auto w-6 hover:bg-neutral-300 rounded-full p-1 transition-colors"
                                            wire:click="decrementQuantity({{ $index }})"
                                            aria-label="{{ __('messages.cart.decrement_quantity') }}"
                                            type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6" />
                                        </svg>
                                    </button>

                                    <div class="flex gap-2.5 justify-center items-center self-stretch my-auto text-base font-semibold leading-none whitespace-nowrap text-zinc-800"
                                         role="text"
                                         aria-label="{{ __('messages.cart.quantity') }} {{ $line['quantity'] ?? 0 }}">
                                        <span class="self-stretch my-auto text-zinc-800">{{ $line['quantity'] ?? 0 }}</span>
                                    </div>

                                    <button class="flex gap-2.5 items-center self-stretch my-auto w-6 hover:bg-neutral-300 rounded-full p-1 transition-colors"
                                            wire:click="incrementQuantity({{ $index }})"
                                            aria-label="{{ __('messages.cart.increment_quantity') }}"
                                            type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Delete Button -->
                                <button class="flex gap-2 items-center text-base font-semibold leading-none text-red-500 whitespace-nowrap rounded-lg hover:bg-red-50 px-2 py-1 transition-colors"
                                        wire:click="removeLine('{{ $line['id'] }}')"
                                        aria-label="{{ __('messages.cart.remove_item') }} {{ $line['description'] ?? '' }}"
                                        type="button">
                                    <span class="self-stretch my-auto text-red-500">{{ __('messages.cart.remove') }}</span>
                                </button>
                            </div>

                            <!-- Error Messages -->
                            @if ($errors->get('lines.' . $index . '.quantity'))
                                <div class="p-2 mt-2 text-xs font-medium text-center text-red-700 rounded bg-red-50"
                                     role="alert">
                                    @foreach ($errors->get('lines.' . $index . '.quantity') as $error)
                                        {{ $error }}
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </article>

                    <!-- Divider -->
                    @if (!$loop->last)
                        <hr class="flex mt-4 w-full bg-zinc-300 min-h-px max-md:max-w-full border-0" role="separator" />
                    @endif
                @endforeach
            @else
                <p class="py-4 text-sm font-medium text-center text-gray-500">
                    {{ __('messages.cart.empty') }}
                </p>
            @endif
        </section>

        <!-- Cart Footer -->
        @if ($this->cart && $lines && count($lines))
            <footer class="z-0 w-full p-8 text-base font-bold max-md:max-w-full" role="contentinfo">
                <hr class="flex w-full bg-zinc-300 min-h-px max-md:max-w-full border-0" role="separator" />

                <!-- Total Section -->
                <div class="flex flex-wrap gap-10 justify-between items-center mt-4 w-full leading-tight min-h-[19px] max-md:max-w-full">
                    <h3 class="self-stretch my-auto text-black">
                        {{ __('messages.cart.sub_total') }}
                    </h3>
                    <div class="flex gap-1 items-center self-stretch my-auto text-right text-zinc-800"
                         role="text"
                         aria-label="{{ __('messages.cart.sub_total') }} {{ $this->cart->subTotal->formatted() }}">
                        <span class="self-stretch my-auto text-zinc-800">{{ $this->cart->subTotal->formatted() }}</span>
                        <span class="self-stretch my-auto text-zinc-800">₴</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-4 items-center mt-4 w-full leading-snug max-md:max-w-full" role="group" aria-label="{{ __('messages.cart.actions') }}">
                    <a class="flex flex-1 shrink gap-2 justify-center items-center self-stretch px-5 my-auto text-green-600 rounded-2xl border-2 border-green-600 border-solid basis-0 min-h-11 min-w-60 hover:bg-green-50 transition-colors focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2"
                       href="{{ route('home', ['locale' => app()->getLocale()]) }}"
                       wire:navigate
                       aria-label="{{ __('messages.cart.continue_shopping') }}">
                        <span class="self-stretch my-auto text-green-600">{{ __('messages.cart.continue_shopping') }}</span>
                    </a>

                    <a class="flex flex-1 shrink gap-2 justify-center items-center self-stretch px-6 py-2.5 my-auto text-white bg-green-600 rounded-2xl basis-0 min-h-11 min-w-60 max-md:px-5 hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2"
                       href="{{ route('checkout.view', ['locale' => app()->getLocale()]) }}"
                       wire:navigate
                       aria-label="{{ __('messages.cart.checkout') }}">
                        <span class="self-stretch my-auto text-white">{{ __('messages.cart.checkout') }}</span>
                    </a>
                </div>
            </footer>
        @endif
    </main>
</div>
