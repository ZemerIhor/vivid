<div>
    <div class="container mx-auto px-[50px] py-12 sm:px-6 lg:px-8">
        <div class="flex flex-wrap gap-8">
            <!-- Form Section -->
            <section class="flex-1 shrink self-start px-8 py-8 bg-white rounded-3xl border border-gray-100 min-w-60 max-md:px-5 max-md:max-w-full" role="main" aria-labelledby="form-heading">
                @if($currentStep === $steps['personal_info'])
                    @include('partials.checkout.address')
                @endif
                @if($currentStep === $steps['delivery'])
                    @include('partials.checkout.shipping_option')
                @endif
            </section>
            <!-- Basket Section -->
            <aside class="p-8 bg-white rounded-3xl min-w-60 w-[487px] max-md:px-5 max-md:max-w-full" role="complementary" aria-labelledby="order-heading">
                <div class="w-full max-md:max-w-full">
                    <h2 id="order-heading" class="text-2xl font-bold leading-tight text-zinc-800 max-md:max-w-full">
                        {{ __('messages.cart.order_summary') }}
                    </h2>
                    <hr class="flex mt-4 w-full bg-zinc-300 min-h-px max-md:max-w-full" />
                    <!-- Cart Items -->
                    @forelse ($cart->lines as $line)
                        <article class="flex gap-6 items-start mt-4 w-full max-md:max-w-full" wire:key="cart_line_{{ $line->id }}">
                            <img
                                src="{{ $line->purchasable->getThumbnail() ? $line->purchasable->getThumbnail()->getUrl() : asset('images/fallback-product.jpg') }}"
                                alt="{{ $line->purchasable->getDescription() ?? 'Product Image' }}"
                                class="object-contain shrink-0 w-20 rounded-2xl aspect-square"
                            />
                            <div class="flex-1 shrink basis-0 min-w-60">
                                <h3 class="text-xs font-semibold leading-5 text-zinc-800">
                                    {{ $line->purchasable->getDescription() ?? '' }}
                                </h3>
                                <div class="flex gap-5 items-center mt-3 w-full">
                                    <p class="flex-1 shrink self-stretch my-auto text-xs font-semibold basis-0 text-neutral-400">
                                        {{ __('messages.cart.id') }}: {{ $line->purchasable->sku ?? 'N/A' }}
                                    </p>
                                    <div class="flex gap-1 items-center self-stretch my-auto text-base font-bold leading-tight text-right text-zinc-800">
                                        <span class="self-stretch my-auto text-zinc-800">{{ $line->subTotal instanceof \Lunar\DataTypes\Price ? $line->subTotal->formatted() : number_format($line->subTotal, 2) }}</span>
                                        <span class="self-stretch my-auto text-zinc-800">₴</span>
                                    </div>
                                </div>
                                <div class="flex justify-between items-end mt-3 w-full">
                                    <div class="flex gap-2 items-center px-2 rounded-2xl bg-neutral-200 min-h-11" role="group" aria-label="{{ __('messages.cart.quantity') }}">
                                        <button
                                            type="button"
                                            wire:click="updateLineQuantity('{{ $line->id }}', {{ $line->quantity - 1 }})"
                                            class="flex gap-2.5 items-center self-stretch my-auto w-6 hover:bg-neutral-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            aria-label="{{ __('messages.cart.decrease_quantity') }}"
                                            @if($line->quantity <= 1) disabled @endif
                                        >
                                            <svg class="w-6 h-6 text-zinc-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                            </svg>
                                        </button>
                                        <div class="flex gap-2.5 justify-center items-center self-stretch my-auto text-base font-semibold leading-none whitespace-nowrap text-zinc-800">
                                            <span class="self-stretch my-auto text-zinc-800">{{ $line->quantity }}</span>
                                        </div>
                                        <button
                                            type="button"
                                            wire:click="updateLineQuantity('{{ $line->id }}', {{ $line->quantity + 1 }})"
                                            class="flex gap-2.5 items-center self-stretch my-auto w-6 hover:bg-neutral-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            aria-label="{{ __('messages.cart.increase_quantity') }}"
                                        >
                                            <svg class="w-6 h-6 text-zinc-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </article>
                        @error('lines.' . $loop->index . '.quantity')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    @empty
                        <p class="text-sm text-gray-500 mt-4">{{ __('messages.cart.empty') }}</p>
                    @endforelse
                </div>
                <!-- Order Summary -->
                <footer class="mt-4 w-full text-base font-bold leading-tight text-neutral-400 max-md:max-w-full">
                    <hr class="flex w-full bg-zinc-300 min-h-px max-md:max-w-full" />
                    <div class="flex gap-10 justify-between items-center mt-4 w-full min-h-[19px] max-md:max-w-full">
                        <span class="self-stretch my-auto text-neutral-400">{{ __('messages.cart.sub_total') }}</span>
                        <div class="flex gap-1 items-center self-stretch my-auto text-right">
                            <span class="self-stretch my-auto text-neutral-400">{{ $cart->subTotal instanceof \Lunar\DataTypes\Price ? $cart->subTotal->formatted() : number_format($cart->subTotal, 2) }}</span>
                            <span class="self-stretch my-auto text-neutral-400">₴</span>
                        </div>
                    </div>
                    @if ($shippingOption && isset($shippingOption['formatted_price']))
                        <div class="flex gap-10 justify-between items-center mt-4 w-full min-h-[19px] max-md:max-w-full">
                            <span class="self-stretch my-auto text-neutral-400">{{ __('messages.cart.shipping') }}</span>
                            <div class="flex gap-1 items-center self-stretch my-auto text-right">
                                <span class="self-stretch my-auto text-neutral-400">{{ $shippingOption['formatted_price'] }}</span>
                                <span class="self-stretch my-auto text-neutral-400">₴</span>
                            </div>
                        </div>
                    @else
                        <div class="flex gap-10 justify-between items-center mt-4 w-full min-h-[19px] max-md:max-w-full">
                            <span class="self-stretch my-auto text-neutral-400">{{ __('messages.cart.shipping') }}</span>
                            <div class="flex gap-1 items-center self-stretch my-auto text-right">
                                <span class="self-stretch my-auto text-neutral-400">0.00</span>
                                <span class="self-stretch my-auto text-neutral-400">₴</span>
                            </div>
                        </div>
                    @endif
                    <hr class="flex mt-4 w-full bg-zinc-300 min-h-px max-md:max-w-full" />
                    <div class="flex gap-10 justify-between items-center mt-4 w-full min-h-[19px] text-zinc-800 max-md:max-w-full">
                        <span class="self-stretch my-auto text-zinc-800">{{ __('messages.cart.total') }}</span>
                        <div class="flex gap-1 items-center self-stretch my-auto text-right">
                            <span class="self-stretch my-auto text-zinc-800">{{ $cart->total instanceof \Lunar\DataTypes\Price ? $cart->total->formatted() : number_format($cart->total, 2) }}</span>
                            <span class="self-stretch my-auto text-zinc-800">₴</span>
                        </div>
                    </div>
                </footer>
            </aside>
        </div>
    </div>
</div>
