```blade
<form wire:submit.prevent="checkout" class="bg-white p-8 max-md:p-5">
    <header class="flex gap-4 items-start w-full text-base font-semibold leading-none max-md:max-w-full">
        <div class="flex flex-col justify-center items-center text-center text-white whitespace-nowrap rounded-2xl bg-zinc-800 h-[22px] w-[22px]" aria-label="{{ __('messages.checkout.step_3') }}">
            <span class="text-white">3</span>
        </div>
        <h1 id="form-heading" class="flex-1 shrink basis-0 text-zinc-800">
            {{ __('messages.checkout.payment') }}
        </h1>
    </header>

    <div class="mt-10 w-full text-base font-semibold leading-none whitespace-nowrap text-neutral-400 max-md:max-w-full space-y-4">
        <!-- Выбор способа оплаты -->
        <fieldset class="flex flex-col gap-4 items-start">
            <legend class="sr-only">Оберіть спосіб оплати</legend>

            <!-- Оплата картой -->
            <div class="flex relative gap-2 items-center">
                <div class="relative">
                    <input type="radio"
                           id="card-payment"
                           name="payment-method"
                           value="card"
                           wire:model.live="paymentType"
                           class="w-6 h-6 cursor-pointer radio-button"
                           aria-describedby="card-payment-desc">
                    <label for="card-payment"
                           class="absolute top-0 left-0 flex justify-center items-center w-6 h-6 rounded-full border-[1.5px] cursor-pointer {{ $paymentType === 'card' ? 'radio-selected' : 'radio-unselected' }}"
                           tabindex="0"
                           role="radio"
                           aria-checked="{{ $paymentType === 'card' ? 'true' : 'false' }}">
                        <span class="sr-only">Сплатити карткою Visa/Mastercard</span>
                    </label>
                </div>
                <label for="card-payment"
                       id="card-payment-desc"
                       class="relative text-base font-bold leading-5 text-zinc-800 cursor-pointer ml-8">
                    Сплатити карткою Visa/Mastercard
                </label>
            </div>

            <!-- Наложенный платеж -->
            <div class="flex relative gap-2 items-center">
                <div class="relative">
                    <input type="radio"
                           id="cash-payment"
                           name="payment-method"
                           value="cash-on-delivery"
                           wire:model.live="paymentType"
                           class="w-6 h-6 cursor-pointer radio-button"
                           aria-describedby="cash-payment-desc">
                    <label for="cash-payment"
                           class="absolute top-0 left-0 flex justify-center items-center w-6 h-6 rounded-full border-[1.5px] cursor-pointer {{ $paymentType === 'cash-on-delivery' ? 'radio-selected' : 'radio-unselected' }}"
                           tabindex="0"
                           role="radio"
                           aria-checked="{{ $paymentType === 'cash-on-delivery' ? 'true' : 'false' }}">
                        <span class="sr-only">Накладений платіж</span>
                    </label>
                </div>
                <label for="cash-payment"
                       id="cash-payment-desc"
                       class="relative text-base font-bold leading-5 text-zinc-800 cursor-pointer ml-8">
                    Накладений платіж
                </label>
            </div>
        </fieldset>
        @error('paymentType')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror

        <!-- Поле для комментария -->
        <div class="flex flex-col items-end px-4 py-3 rounded-2xl border border-solid border-neutral-400 mt-4">
            <textarea id="comment"
                      name="comment"
                      wire:model.live="comment"
                      placeholder="Коментар"
                      class="relative self-stretch text-base font-bold leading-5 h-[90px] text-neutral-400 bg-transparent border-none outline-none resize-none comment-textarea"
                      aria-label="Коментар до замовлення"></textarea>
            <button type="button"
                    class="flex-shrink-0 p-1 rounded hover:bg-gray-100 focus:outline-2 focus:outline-gray-400"
                    aria-label="Очистити коментар"
                    wire:click="$set('comment', '')">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M8.21967 19.8869L19.8869 8.21967C20.1798 7.92678 20.6547 7.92678 20.9476 8.21967C21.2405 8.51256 21.2405 8.98744 20.9476 9.28033L9.28033 20.9476C8.98744 21.2405 8.51256 21.2405 8.21967 20.9476C7.92678 20.6547 7.92678 20.1798 8.21967 19.8869Z" fill="#8C8C8C"></path>
                    <path d="M13.4477 19.4583L19.1215 13.7845C19.4144 13.4916 19.8892 13.4916 20.1821 13.7845C20.475 14.0774 20.475 14.5523 20.1821 14.8452L14.5084 20.5189C14.2155 20.8118 13.7406 20.8118 13.4477 20.5189C13.1548 20.226 13.1548 19.7512 13.4477 19.4583Z" fill="#8C8C8C"></path>
                </svg>
            </button>
        </div>
        @error('comment')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror

        <!-- Условный контент -->
        @if ($paymentType === 'card')
            <div class="mt-4">
                <livewire:stripe.payment :cart="$cart" :returnUrl="route('checkout.view')" />
            </div>
        @elseif ($paymentType === 'cash-on-delivery')
            <div class="mt-4 p-4 text-sm text-center text-blue-700 rounded-2xl bg-blue-50">
                {{ __('messages.checkout.cash_on_delivery_info') }}
            </div>
        @endif
    </div>

    <!-- Кнопки навигации -->
    <div class="mt-8 flex gap-4 items-center w-full text-base font-bold leading-snug whitespace-nowrap max-md:max-w-full max-md:flex-col max-md:gap-4">
        <button
            type="button"
            wire:click="goBackStep"
            class="flex gap-2 justify-center items-center self-stretch px-6 py-2.5 my-auto text-green-600 rounded-2xl border-2 border-green-600 border-solid min-h-11 btn-secondary max-md:px-5 hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2"
            aria-label="{{ __('messages.checkout.back') }}"
        >
            <span class="self-stretch my-auto text-green-600">{{ __('messages.checkout.back') }}</span>
        </button>

        <button
            type="submit"
            class="flex gap-2 justify-center items-center self-stretch px-6 py-2.5 my-auto text-white bg-green-600 rounded-2xl min-h-11 btn-primary max-md:px-5 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2"
            aria-label="{{ __('messages.checkout.confirm_order') }}"
            wire:loading.attr="disabled"
            @if(!$paymentType) disabled @endif
        >
            <span wire:loading.remove>{{ __('messages.checkout.confirm_order') }}</span>
            <span wire:loading>
                <svg class="w-5 h-5 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </span>
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
        <div class="flex gap-4 items-start mt-4 max-w-full min-h-[22px] w-[440px]">
            <div class="flex flex-col justify-center items-center text-center text-white rounded-2xl bg-neutral-400 h-[22px] w-[22px]" aria-label="{{ __('messages.checkout.step_2') }}">
                <span class="text-white">2</span>
            </div>
            <span class="flex-1 shrink basis-0 text-neutral-400">{{ __('messages.checkout.delivery') }}</span>
        </div>
    </nav>
</form>

<style>
    /* Стили для радиокнопок */
    .radio-selected {
        border-color: #228F5D;
    }
    .radio-selected::after {
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
    .radio-unselected {
        border-color: #9CA3AF;
    }

    /* Стили фокуса для доступности */
    .radio-button:focus {
        outline: 2px solid #228F5D;
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

    /* Эффекты наведения */
    .btn-primary:hover {
        background-color: #1e7a4f;
    }

    .btn-secondary:hover {
        background-color: #f0fdf4;
    }

    /* Убираем стандартный вид радиокнопок */
    input[type="radio"] {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        margin: 0;
    }
</style>
