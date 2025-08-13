<form wire:submit.prevent="saveAddress" class="bg-white p-8 max-md:p-5">
    <header class="flex gap-4 items-start w-full text-base font-semibold leading-none max-md:max-w-full">
        <div class="flex flex-col justify-center items-center text-center text-white whitespace-nowrap rounded-2xl bg-zinc-800 h-[22px] w-[22px]" aria-label="{{ __('messages.checkout.step_1') }}">
            <span class="text-white">1</span>
        </div>
        <h1 id="form-heading" class="flex-1 shrink basis-0 text-zinc-800">
            {{ __('messages.checkout.personal_info') }}
        </h1>
    </header>
    <div class="mt-10 w-full text-base font-semibold leading-none whitespace-nowrap text-neutral-400 max-md:max-w-full space-y-4">
        <div class="flex overflow-hidden gap-2 items-center px-4 py-3.5 w-full rounded-2xl border border-solid border-neutral-400 min-h-12 max-md:max-w-full">
            <input
                type="text"
                id="firstName"
                wire:model="shippingData.first_name"
                placeholder="{{ __('messages.checkout.first_name') }}"
                class="flex-1 shrink self-stretch my-auto basis-0 text-neutral-400 bg-transparent border-none outline-none"
                aria-label="{{ __('messages.checkout.first_name') }}"
                required
            />
            @error('shippingData.first_name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex overflow-hidden gap-2 items-center px-4 py-3.5 w-full rounded-2xl border border-solid border-neutral-400 min-h-12 max-md:max-w-full">
            <input
                type="text"
                id="lastName"
                wire:model="shippingData.last_name"
                placeholder="{{ __('messages.checkout.last_name') }}"
                class="flex-1 shrink self-stretch my-auto basis-0 text-neutral-400 bg-transparent border-none outline-none"
                aria-label="{{ __('messages.checkout.last_name') }}"
                required
            />
            @error('shippingData.last_name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex overflow-hidden gap-2 items-center px-4 py-3.5 w-full rounded-2xl border border-solid border-neutral-400 min-h-12 max-md:max-w-full">
            <input
                type="tel"
                id="phone"
                wire:model="shippingData.contact_phone"
                placeholder="{{ __('messages.checkout.phone') }}"
                class="flex-1 shrink self-stretch my-auto basis-0 text-neutral-400 bg-transparent border-none outline-none"
                aria-label="{{ __('messages.checkout.phone') }}"
                required
            />
            @error('shippingData.contact_phone')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex overflow-hidden gap-2 items-center px-4 py-3.5 w-full rounded-2xl border border-solid border-neutral-400 min-h-12 max-md:max-w-full">
            <input
                type="email"
                id="email"
                wire:model="shippingData.contact_email"
                placeholder="{{ __('messages.checkout.email') }}"
                class="flex-1 shrink self-stretch my-auto basis-0 text-neutral-400 bg-transparent border-none outline-none"
                aria-label="{{ __('messages.checkout.email') }}"
                required
            />
            @error('shippingData.contact_email')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex overflow-hidden gap-2 items-center px-4 py-3.5 w-full rounded-2xl border border-solid border-neutral-400 min-h-12 max-md:max-w-full">
            <input
                type="text"
                id="company"
                wire:model="shippingData.company"
                placeholder="{{ __('messages.checkout.company') }}"
                class="flex-1 shrink self-stretch my-auto basis-0 text-neutral-400 bg-transparent border-none outline-none"
                aria-label="{{ __('messages.checkout.company') }}"
            />
            @error('shippingData.company')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Чекбокс для политики конфиденциальности -->
        <div class="flex gap-2 items-center self-start text-xs">
            <input
                type="checkbox"
                id="privacy-policy"
                wire:model="privacy_policy"
                class="w-6 h-6 rounded border-solid border-[1.5px] border-neutral-400 cursor-pointer"
                required
                aria-describedby="privacy-policy-label"
            />
            <label for="privacy-policy" id="privacy-policy-label" class="flex gap-0.5 items-start self-stretch my-auto min-w-60 cursor-pointer">
                <span class="font-semibold text-zinc-800">{{ __('messages.checkout.agree_to') }}</span>
                <a href="{{ route('privacy-policy') }}" class="flex gap-2 justify-center items-center text-indigo-500 underline rounded-lg" aria-label="{{ __('messages.checkout.privacy_policy') }}">
            <span class="self-stretch my-auto text-indigo-500 underline decoration-auto decoration-solid underline-offset-auto">
                {{ __('messages.checkout.privacy_policy') }}
            </span>
                </a>
            </label>
            @error('privacy_policy')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Кнопки навигации -->
    <div class="flex gap-4 items-center mt-4 w-full text-base font-bold leading-snug whitespace-nowrap max-md:max-w-full">
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
            class="flex gap-2 justify-center items-center self-stretch px-6 py-2.5 my-auto text-white bg-green-600 rounded-2xl min-h-11 max-md:px-5 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2"
            aria-label="{{ __('messages.checkout.continue') }}"
            wire:loading.attr="disabled"
        >
            <span wire:loading.remove>{{ __('messages.checkout.continue') }}</span>
            <span wire:loading>{{ __('messages.checkout.saving') }}</span>
        </button>
    </div>

    <!-- Индикаторы шагов -->
    <nav class="mt-10 w-full text-base font-semibold leading-none whitespace-nowrap max-md:max-w-full" aria-label="{{ __('messages.checkout.steps') }}">
        <div class="flex gap-4 items-start max-w-full min-h-[22px] w-[440px]">
            <div class="flex flex-col justify-center items-center text-center text-white rounded-2xl bg-neutral-400 h-[22px] w-[22px]" aria-label="{{ __('messages.checkout.step_2') }}">
                <span class="text-white">2</span>
            </div>
            <span class="flex-1 shrink basis-0 text-neutral-400">{{ __('messages.checkout.delivery') }}</span>
        </div>
    </nav>
</form>
