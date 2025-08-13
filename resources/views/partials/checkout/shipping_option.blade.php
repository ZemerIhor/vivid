<form wire:submit.prevent="saveShippingOption" class="bg-white p-8 max-md:p-5">
    <header class="flex gap-4 items-start w-full text-base font-semibold leading-none max-md:max-w-full">
        <div class="flex flex-col justify-center items-center text-center text-white whitespace-nowrap rounded-2xl bg-zinc-800 h-[22px] w-[22px]" aria-label="{{ __('messages.checkout.step_2') }}">
            <span class="text-white">2</span>
        </div>
        <h1 id="form-heading" class="flex-1 shrink basis-0 text-zinc-800">
            {{ __('messages.checkout.delivery') }}
        </h1>
    </header>

    @if($shippingData)
        <div class="mt-10 w-full text-base font-semibold leading-none whitespace-nowrap text-neutral-400 max-md:max-w-full space-y-4">
            @foreach($shippingOptions as $option)
                <label class="flex items-center p-4 border rounded-2xl border-neutral-400 hover:bg-zinc-50 cursor-pointer">
                    <input
                        type="radio"
                        wire:model.live="chosenShipping"
                        value="{{ $option['identifier'] }}"
                        class="w-5 h-5 text-green-600 border-neutral-400 focus:ring-green-600"
                        aria-label="{{ $option['description'] }}"
                        required
                    />
                    <span class="ml-4">
                        <p class="text-sm font-medium text-zinc-800">{{ $option['description'] }} ({{ $option['formatted_price'] }})</p>
                    </span>
                </label>
                @error('chosenShipping')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            @endforeach

            <!-- Самовывоз -->
            @if($chosenShipping === 'pickup')
                <div class="mt-4">
                    <p class="text-sm text-neutral-400">{{ __('messages.checkout.pickup_info') }}</p>
                </div>
            @endif

            <!-- Нова Пошта -->
            @if($chosenShipping === 'nova-poshta')
                <div class="mt-4">
                    <label for="np-city-search" class="block text-sm font-medium text-zinc-800 mb-1">{{ __('messages.checkout.city') }}</label>
                    <div class="flex overflow-hidden gap-2 items-center px-4 py-3.5 w-full rounded-2xl border border-solid border-neutral-400 min-h-12 max-md:max-w-full">
                        <input
                            type="text"
                            id="np-city-search"
                            wire:model.live.debounce.500ms="citySearchTerm"
                            placeholder="{{ __('messages.checkout.enter_city') }}"
                            class="flex-1 shrink self-stretch my-auto basis-0 text-neutral-400 bg-transparent border-none outline-none"
                            aria-label="{{ __('messages.checkout.enter_city') }}"
                            required
                        />
                    </div>
                    @if(!empty($npCities) && $showCityDropdown)
                        <div class="mt-2 max-h-40 overflow-y-auto border border-neutral-400 rounded-2xl bg-white z-10">
                            @foreach($npCities as $city)
                                <div
                                    wire:click="selectCity('{{ $city['MainDescription'] }}')"
                                    class="px-4 py-2 hover:bg-zinc-50 cursor-pointer text-sm text-zinc-800"
                                >
                                    {{ $city['MainDescription'] }}
                                </div>
                            @endforeach
                        </div>
                    @endif
                    @error('shippingData.city') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="mt-4">
                    <label for="np-warehouse" class="block text-sm font-medium text-zinc-800 mb-1">{{ __('messages.checkout.warehouse') }}</label>
                    <div class="flex overflow-hidden gap-2 items-center px-4 py-3.5 w-full rounded-2xl border border-solid border-neutral-400 min-h-12 max-md:max-w-full">
                        <select
                            id="np-warehouse"
                            wire:model.live="shippingData.line_one"
                            class="flex-1 shrink self-stretch my-auto basis-0 text-neutral-400 bg-transparent border-none outline-none"
                            aria-label="{{ __('messages.checkout.select_warehouse') }}"
                            @if(empty($npWarehouses)) disabled @endif
                            required
                        >
                            <option value="">{{ __('messages.checkout.select_warehouse') }}</option>
                            @foreach($npWarehouses as $warehouse)
                                <option value="{{ $warehouse['Description'] }}">{{ $warehouse['Description'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if(empty($npWarehouses) && !empty($shippingData['city']))
                        <p class="mt-1 text-sm text-neutral-400">{{ __('messages.checkout.select_city_for_warehouses') }}</p>
                    @endif
                    @error('shippingData.line_one') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            @endif

            <!-- Курьер -->
            @if($chosenShipping === 'courier')
                <div class="mt-4">
                    <label for="courier-city" class="block text-sm font-medium text-zinc-800 mb-1">{{ __('messages.checkout.city') }}</label>
                    <div class="flex overflow-hidden gap-2 items-center px-4 py-3.5 w-full rounded-2xl border border-solid border-neutral-400 min-h-12 max-md:max-w-full">
                        <input
                            type="text"
                            id="courier-city"
                            wire:model.live.debounce.500ms="citySearchTerm"
                            placeholder="{{ __('messages.checkout.enter_city') }}"
                            class="flex-1 shrink self-stretch my-auto basis-0 text-neutral-400 bg-transparent border-none outline-none"
                            aria-label="{{ __('messages.checkout.enter_city') }}"
                            required
                        />
                    </div>
                    @if(!empty($npCities) && $showCityDropdown)
                        <div class="mt-2 max-h-40 overflow-y-auto border border-neutral-400 rounded-2xl bg-white z-10">
                            @foreach($npCities as $city)
                                <div
                                    wire:click="selectCity('{{ $city['MainDescription'] }}')"
                                    class="px-4 py-2 hover:bg-zinc-50 cursor-pointer text-sm text-zinc-800"
                                >
                                    {{ $city['MainDescription'] }}
                                </div>
                            @endforeach
                        </div>
                    @endif
                    @error('shippingData.city') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="mt-4">
                    <label for="courier-address" class="block text-sm font-medium text-zinc-800 mb-1">{{ __('messages.checkout.address') }}</label>
                    <div class="flex overflow-hidden gap-2 items-center px-4 py-3.5 w-full rounded-2xl border border-solid border-neutral-400 min-h-12 max-md:max-w-full">
                        <input
                            type="text"
                            id="courier-address"
                            wire:model.live="shippingData.line_one"
                            placeholder="{{ __('messages.checkout.enter_address') }}"
                            class="flex-1 shrink self-stretch my-auto basis-0 text-neutral-400 bg-transparent border-none outline-none"
                            aria-label="{{ __('messages.checkout.enter_address') }}"
                            required
                        />
                    </div>
                    @error('shippingData.line_one') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            @endif

            <!-- Поле для комментария -->
            <div class="mt-4">
                <label for="comment" class="block text-sm font-medium text-zinc-800 mb-1">{{ __('messages.checkout.comment') }}</label>
                <div class="flex flex-col items-end px-4 py-3 rounded-2xl border border-solid border-neutral-400">
                    <textarea
                        id="comment"
                        name="comment"
                        wire:model.live="comment"
                        placeholder="Коментар"
                        class="relative self-stretch text-base font-bold leading-5 h-[90px] text-neutral-400 bg-transparent border-none outline-none resize-none comment-textarea"
                        aria-label="Коментар до замовлення"
                    ></textarea>
                    <button
                        type="button"
                        class="flex-shrink-0 p-1 rounded hover:bg-gray-100 focus:outline-2 focus:outline-gray-400"
                        aria-label="Очистити коментар"
                        wire:click="$set('comment', '')"
                    >
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M8.21967 19.8869L19.8869 8.21967C20.1798 7.92678 20.6547 7.92678 20.9476 8.21967C21.2405 8.51256 21.2405 8.98744 20.9476 9.28033L9.28033 20.9476C8.98744 21.2405 8.51256 21.2405 8.21967 20.9476C7.92678 20.6547 7.92678 20.1798 8.21967 19.8869Z" fill="#8C8C8C"></path>
                            <path d="M13.4477 19.4583L19.1215 13.7845C19.4144 13.4916 19.8892 13.4916 20.1821 13.7845C20.475 14.0774 20.475 14.5523 20.1821 14.8452L14.5084 20.5189C14.2155 20.8118 13.7406 20.8118 13.4477 20.5189C13.1548 20.226 13.1548 19.7512 13.4477 19.4583Z" fill="#8C8C8C"></path>
                        </svg>
                    </button>
                </div>
                @error('comment')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    @else
        <p class="mt-10 text-sm text-red-600">Адрес доставки недоступен. Пожалуйста, попробуйте снова или обратитесь в поддержку.</p>
    @endif

    <!-- Кнопки навигации -->
    <div class="mt-8 flex gap-4 items-center w-full text-base font-bold leading-snug whitespace-nowrap max-md:max-w-full max-md:flex-col max-md:gap-4">
        <button
            type="button"
            wire:click="goBackStep"
            class="flex gap-2 justify-center items-center self-stretch px-6 py-2.5 my-auto text-green-600 rounded-2xl border-2 border-green-600 border-solid min-h-11 max-md:px-5 hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2"
            aria-label="{{ __('messages.checkout.back') }}"
        >
            <span class="self-stretch my-auto text-green-600">{{ __('messages.checkout.back') }}</span>
        </button>
        <button
            type="submit"
            wire:loading.attr="disabled"
            class="flex gap-2 justify-center items-center self-stretch px-6 py-2.5 my-auto text-white bg-green-600 rounded-2xl min-h-11 max-md:px-5 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
            aria-label="{{ __('messages.checkout.confirm_order') }}"
            @if(!$chosenShipping || ($chosenShipping !== 'pickup' && (empty($shippingData['city']) || empty($shippingData['line_one'])))) disabled @endif
        >
            <span wire:loading.remove>{{ __('messages.checkout.confirm_order') }}</span>
            <span wire:loading>{{ __('messages.checkout.saving') }}</span>
        </button>
    </div>

    <!-- Индикаторы шагов -->
    <nav class="mt-10 w-full text-base font-semibold leading-none whitespace-nowrap max-md:max-w-full" aria-label="{{ __('messages.checkout.steps') }}">
        <div class="flex gap-4 items-start max-w-full min-h-[22px] w-[440px]">
            <div class="flex flex-col justify-center items-center text-center text-white rounded-2xl bg-neutral-400 h-[22px] w-[22px]" aria-label="{{ __('messages.checkout.step_1') }}">
                <span class="text-white">1</span>
            </div>
            <span class="flex-1 shrink basis-0 text-neutral-400">{{ __('messages.checkout.personal_info') }}</span>
        </div>
    </nav>
</form>

<style>
    input[type="radio"] {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        margin: 0;
        position: relative;
    }

    input[type="radio"]:checked {
        background-color: #228F5D;
        border-color: #228F5D;
    }

    input[type="radio"]:checked::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 12px;
        height: 12px;
        background-color: #228F5D;
        border-radius: 50%;
    }

    input[type="radio"]:focus {
        outline: 2px solid #3b82f6;
        outline-offset: 2px;
    }

    select:focus, input:focus {
        outline: 2px solid #3b82f6;
        outline-offset: 2px;
    }

    .btn-primary:focus,
    .btn-secondary:focus {
        outline: 2px solid #228F5D;
        outline-offset: 2px;
    }

    .comment-textarea:focus {
        outline: 2px solid #228F5D;
        border-color: #228F5D;
    }

    .btn-primary:hover {
        background-color: #1e7a4f;
    }

    .btn-secondary:hover {
        background-color: #f0fdf4;
    }

    input::placeholder, select:invalid, textarea::placeholder {
        color: #a3a3a3;
    }
</style>
