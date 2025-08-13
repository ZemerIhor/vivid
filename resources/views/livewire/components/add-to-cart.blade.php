<div>
    <button
        wire:click.prevent="addToCart"
        class="hidden md:flex gap-2 self-stretch px-6 py-2.5 my-auto text-white bg-green-600 rounded-2xl min-h-11 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 focus:ring-offset-zinc-800"
        aria-label="{{ __('messages.cart.add_to_cart_aria') }}"
        type="button"
    >
        {{ __('messages.cart.add_to_cart') }}
    </button>

    <button
        wire:click.prevent="addToCart"
        class="flex md:hidden items-center justify-center w-11 h-11 bg-green-600 rounded-full hover:bg-green-700"
        aria-label="{{ __('messages.cart.add_to_cart_aria') }}"
        type="button"
    >
        {{-- Вставляємо SVG іконку --}}
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
             xmlns="http://www.w3.org/2000/svg">
            <path d="M2 2H3.74C4.82 2 5.67 2.93 5.58 4L4.75 13.96C4.61 15.59 5.9 16.99 7.54 16.99H18.19C19.63 16.99 20.89 15.81 21 14.38L21.54 6.88C21.66 5.22 20.4 3.87 18.73 3.87H5.82"
                  stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M16.25 22C16.94 22 17.5 21.44 17.5 20.75C17.5 20.06 16.94 19.5 16.25 19.5C15.56 19.5 15 20.06 15 20.75C15 21.44 15.56 22 16.25 22Z"
                  stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M8.25 22C8.94 22 9.5 21.44 9.5 20.75C9.5 20.06 8.94 19.5 8.25 19.5C7.56 19.5 7 20.06 7 20.75C7 21.44 7.56 22 8.25 22Z"
                  stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M9 8H21" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </button>

    @if ($errors->has('quantity'))
        <div class="p-2 mt-4 text-xs font-medium text-center text-red-700 rounded bg-red-50" role="alert">
            @foreach ($errors->get('quantity') as $error)
                {{ $error }}
            @endforeach
        </div>
    @endif

    <div
        x-data="{ open: false }"
        x-on:add-to-cart.window="open = true; setTimeout(() => open = false, 2000)"
        class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg"
        x-show="open"
        x-transition
        style="display: none;"
    >
        {{ __('messages.cart.added_to_cart') }}
    </div>
</div>

<!-- <div>
    {{-- Кнопка (показується тільки на екранах md і більше) --}}
    <button
        wire:click.prevent="addToCart"
        class="hidden md:flex gap-2 self-stretch px-6 py-2.5 my-auto text-white bg-green-600 rounded-2xl min-h-11 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 focus:ring-offset-zinc-800"
        aria-label="{{ __('messages.cart.add_to_cart_aria') }}"
        type="button"
    >
        {{ __('messages.cart.add_to_cart') }}
    </button>

    {{-- Іконка кошика (показується тільки на мобілці) --}}
    <button
        wire:click.prevent="addToCart"
        class="flex md:hidden items-center justify-center w-11 h-11 bg-green-600 rounded-full hover:bg-green-700"
        aria-label="{{ __('messages.cart.add_to_cart_aria') }}"
        type="button"
    >
        {{-- Вставляємо SVG іконку --}}
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
             xmlns="http://www.w3.org/2000/svg">
            <path d="M2 2H3.74C4.82 2 5.67 2.93 5.58 4L4.75 13.96C4.61 15.59 5.9 16.99 7.54 16.99H18.19C19.63 16.99 20.89 15.81 21 14.38L21.54 6.88C21.66 5.22 20.4 3.87 18.73 3.87H5.82"
                  stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M16.25 22C16.94 22 17.5 21.44 17.5 20.75C17.5 20.06 16.94 19.5 16.25 19.5C15.56 19.5 15 20.06 15 20.75C15 21.44 15.56 22 16.25 22Z"
                  stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M8.25 22C8.94 22 9.5 21.44 9.5 20.75C9.5 20.06 8.94 19.5 8.25 19.5C7.56 19.5 7 20.06 7 20.75C7 21.44 7.56 22 8.25 22Z"
                  stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M9 8H21" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </button>

    {{-- Повідомлення про додавання --}}
    <div
        x-data="{ open: false }"
        x-on:add-to-cart.window="open = true; setTimeout(() => open = false, 2000)"
        class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg"
        x-show="open"
        x-transition
        style="display: none;"
    >
        {{ __('messages.cart.added_to_cart') }}
    </div>
</div> -->
