<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.error_404.title') }} - {{ __('messages.error_404.subtitle') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .sign-icon {
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
        }

        button:hover .arrow-icon path {
            fill: black;
        }

        button:hover span {
            color: black;
        }
    </style>
</head>
<body class="antialiased">
    <!-- Logo Header -->
    <header class="absolute top-0 left-0 w-full z-50 p-6">
        <div class="container mx-auto">
            <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="inline-block">
                <x-brand.logo class="h-12 w-auto" />
            </a>
        </div>
    </header>

    <!-- 404 Content -->
    <main class="overflow-hidden relative w-full h-screen">
        <img
            src="https://api.builder.io/api/v1/image/assets/TEMP/288b6d5e1e331a4ad65315191ecd17ef31ea3559?width=2560"
            alt=""
            class="object-cover absolute top-0 left-0 size-full"
        />

        <section class="flex absolute top-2/4 left-2/4 flex-col gap-3.5 items-center -translate-x-2/4 -translate-y-2/4 h-[489px] w-[314px]">
            <div class="relative h-[363px] w-[314px]">
                <div class="relative">
                    <svg
                        width="314"
                        height="347"
                        viewBox="0 0 314 347"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                        class="sign-icon"
                        role="img"
                        aria-label="Road sign"
                    >
                        <path
                            d="M162.414 340.907V238.094C162.414 235.107 160.974 227.271 157.985 227.271C154.997 227.271 151.586 229.695 151.586 232.682V340.907C151.586 343.894 154.986 346.318 157.985 346.318C160.985 346.318 162.414 343.894 162.414 340.907ZM163.399 16.9046V5.41122C163.399 3.97607 162.829 2.59971 161.813 1.58491C160.798 0.570109 159.421 0 157.985 0C156.549 0 155.172 0.570109 154.157 1.58491C153.142 2.59971 152.572 3.97607 152.572 5.41122V16.9046C152.572 18.3398 153.142 19.7162 154.157 20.731C155.172 21.7458 156.549 22.3159 157.985 22.3159C159.421 22.3159 160.798 21.7458 161.813 20.731C162.829 19.7162 163.399 18.3398 163.399 16.9046ZM42.1193 37.5647L3.49731 67.8459C2.40151 68.8533 1.52535 70.0759 0.923603 71.4371C0.321853 72.7983 0.00743618 74.2689 0 75.7571C0 78.7332 1.27766 81.6228 3.83297 83.9605L41.7837 113.668C45.1077 116.731 51.0304 119.047 55.5672 119.047H205.291C211.495 119.047 216.552 114.036 216.552 107.867V43.6469C216.552 37.4781 211.495 32.4673 205.291 32.4673H55.5672C51.0304 32.4673 45.1077 34.7833 42.1193 37.5647ZM205.724 107.867C205.724 108.03 205.529 108.224 205.291 108.224H55.5672C53.7265 108.224 50.4674 106.958 48.7999 105.432L10.5136 76.0709L49.1248 45.7897C50.4782 44.556 53.7265 43.2898 55.5672 43.2898L205.724 43.6469V107.867ZM272.216 135.248C268.892 132.185 262.97 129.869 258.433 129.869H108.709C102.505 129.869 97.4483 134.88 97.4483 141.049V205.28C97.4483 211.438 102.505 216.449 108.709 216.449H258.433C262.97 216.449 268.892 214.133 271.892 211.351L310.503 181.07C311.598 180.063 312.475 178.84 313.076 177.479C313.678 176.118 313.993 174.647 314 173.159C313.978 171.599 313.624 170.061 312.964 168.646C312.303 167.232 311.35 165.974 310.167 164.956L272.216 135.248ZM108.276 140.692H258.433C260.274 140.692 263.533 141.958 265.2 143.484L303.486 172.845L264.875 203.126C263.012 204.561 260.776 205.429 258.433 205.626H108.276V140.692Z"
                            fill="white"
                        />
                    </svg>
                </div>

                <h1 class="absolute h-10 text-4xl font-bold leading-10 text-white left-[90px] top-[65px] w-[62px]">
                    {{ __('messages.error_404.title') }}
                </h1>

                <h2 class="absolute text-2xl font-bold leading-7 text-white h-[29px] left-[138px] top-[166px] w-[119px]">
                    {{ __('messages.error_404.subtitle') }}
                </h2>
            </div>

            <div class="flex relative flex-col gap-6 items-center w-[289px]">
                <div class="flex relative flex-col gap-4 items-center self-stretch">
                    <p class="relative self-stretch text-base font-semibold leading-5 text-center text-white">
                        {{ __('messages.error_404.message') }}
                    </p>
                </div>

                <button class="flex relative gap-2 justify-center items-center px-6 py-2.5 rounded-2xl border-2 border-white border-solid cursor-pointer min-h-11 hover:bg-white hover:text-black transition-colors duration-200"
                        onclick="window.location.href='{{ route('home', ['locale' => app()->getLocale()]) }}'">
                    <span class="relative text-base font-bold leading-6 text-white">
                        {{ __('messages.error_404.home_button') }}
                    </span>
                    <svg
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                        class="arrow-icon"
                        aria-hidden="true"
                    >
                        <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M13.4697 5.46967C13.7626 5.17678 14.2374 5.17678 14.5303 5.46967L20.5303 11.4697C20.8232 11.7626 20.8232 12.2374 20.5303 12.5303L14.5303 18.5303C14.2374 18.8232 13.7626 18.8232 13.4697 18.5303C13.1768 18.2374 13.1768 17.7626 13.4697 17.4697L18.1893 12.75H4C3.58579 12.75 3.25 12.4142 3.25 12C3.25 11.5858 3.58579 11.25 4 11.25H18.1893L13.4697 6.53033C13.1768 6.23744 13.1768 5.76256 13.4697 5.46967Z"
                            fill="currentColor"
                        />
                    </svg>
                </button>
            </div>
        </section>
    </main>
</body>
</html>
