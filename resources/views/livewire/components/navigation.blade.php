@php
    $footer = app(\App\Settings\FooterSettings::class);
    $currentLocale = app()->getLocale(); // Get current locale (e.g., 'en' or 'pl')
@endphp

<div x-data="{ isScrolled: false, mobileMenu: false, languageMenu: false }" @scroll.window="isScrolled = (window.scrollY > 0)">
    <header id="header" class="shadow-xl flex items-center bg-white top-0 left-0 right-0 z-50 transition-all duration-300"
            :class="{ 'is-fixed': isScrolled }"
            role="banner">
        <div class="nav-header flex relative px-2 justify-between items-center w-full h-auto container mx-auto">
            <!-- Logo -->
            <a href="{{ url('/') }}" class="flex items-center"
               aria-label="{{ __('messages.banner.catalog_button_aria_label') }}" wire:navigate>
                <div>
                    <x-brand.logo class="w-auto h-8 text-indigo-600" />
                </div>
            </a>

            @php
                use Datlechin\FilamentMenuBuilder\Models\Menu;
                $headerLocation = app()->getLocale() === 'en' ? 'header_en' : 'header_pl';
                $headerMenu = Menu::location($headerLocation);
                \Log::info('Header Menu Debug', [
                    'location' => $headerLocation,
                    'menu' => $headerMenu ? $headerMenu->toArray() : null,
                    'locale' => app()->getLocale(),
                ]);
            @endphp

            <style>
                /* Base styles for the menu container */
                .desktop-menu {
                    align-items: center;
                    display: flex;
                }

                /* Show desktop menu on screens 900px and above */
                @media (max-width: 900px) {
                    .desktop-menu {
                        display: none;
                    }
                }

                /* Show mobile menu toggle on screens below 900px */
                .mobile-menu-toggle {
                    display: none;
                }
                @media (max-width: 900px) {
                    .mobile-menu-toggle {
                        display: block;
                    }
                }

                /* Menu list styles */
                .nav-header ul {
                    display: flex;
                    justify-content: space-between;
                    gap: 15px;
                    margin: 0;
                    padding: 0;
                    list-style: none;
                }

                /* Mobile menu styles */
                @media (max-width: 900px) {
                    .nav-header ul.mobile-menu-items {
                        flex-direction: column;
                        gap: 10px;
                        text-align: center;
                        width: 100%;
                    }

                    .nav-header ul {
                        flex-direction: column;
                    }
                }

                .nav-header ul li a {
                    color: #333333;
                    text-decoration: none;
                    font-size: 14px;
                    font-weight: 600;
                    padding: 8px 12px;
                    transition: color 0.3s;
                }

                .nav-header ul li a:hover {
                    color: #16a34a;
                }

                /* Header styles */
                header {
                    position: relative;
                    transform: translateY(0);
                    opacity: 1;
                }

                header.is-fixed {
                    position: fixed;
                    transform: translateY(0);
                    opacity: 1;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                }

                header:not(.is-fixed) {
                    transition: transform 0.3s ease, opacity 0.3s ease;
                }

                .header-placeholder {
                    transition: height 0.3s ease;
                }

                /* Contact button styles */
                .contact-button {
                    display: none;
                    padding: 8px 12px;
                    font-size: 14px;
                    font-weight: bold;
                    color: #16a34a;
                    border: 2px solid #16a34a;
                    border-radius: 16px;
                    background: none;
                    cursor: pointer;
                    transition: background-color 0.3s, color 0.3s;
                }

                .contact-button:hover {
                    background-color: #16a34a;
                    color: white;
                }

                @media (min-width: 640px) {
                    .contact-button {
                        display: block;
                    }
                }

                /* Social links and language container */
                .nav-right {
                    display: flex;
                    gap: 12px;
                    align-items: center;
                }

                @media (max-width: 640px) {
                    .nav-right {
                        gap: 8px;
                    }
                }
            </style>


            <!-- Desktop Menu -->
            <div class="desktop-menu ">

                @if ($headerMenu)
                    <ul class="mobile-menu-items">
                        @foreach ($headerMenu->menuItems as $item)
                            <li>
                                @if (str_contains($item->url, '#'))
                                    <a href="{{ $item->url }}" @if(str_starts_with($item->url, '#')) x-on:click.prevent="window.scrollTo({ top: document.querySelector('{{ $item->url }}').offsetTop, behavior: 'smooth' }); console.log('Scrolling to', '{{ $item->url }}')" @endif>{{ $item->title }}</a>
                                @else
                                    <a href="{{ $item->url }}" wire:navigate>{{ $item->title }}</a>
                                @endif
                                @if ($item->children)
                                    <ul>
                                        @foreach ($item->children as $child)
                                            @if (str_contains($child->url, '#'))
                                                <li><a href="{{ $child->url }}" @if(str_starts_with($child->url, '#')) x-on:click.prevent="window.scrollTo({ top: document.querySelector('{{ $child->url }}').offsetTop, behavior: 'smooth' }); console.log('Scrolling to', '{{ $child->url }}')" @endif>{{ $child->title }}</a></li>
                                            @else
                                                <li><a href="{{ $child->url }}" wire:navigate>{{ $child->title }}</a></li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif

            </div>

            <div class="nav-right">
                <a class="mr-2 text-base font-semibold leading-5 text-zinc-800" href="tel:+48791229325">+48791229325</a>
                <button
                    wire:click="$dispatch('openContactForm')"
                    class="contact-button"
                    aria-label="{{ __('messages.feedback_form.submit_button') }}"
                >
                    {{ __('messages.feedback_form.submit_button') }}
                </button>

                @php
                    $currentUrl = request()->path();
                    $segments = explode('/', $currentUrl);

                    // Удалим текущую локаль, если она есть
                    if (in_array($segments[0], ['en', 'pl'])) {
                        array_shift($segments);
                    }

                    $pathWithoutLocale = implode('/', $segments);

                    $enUrl = url('/en/' . $pathWithoutLocale);
                    $ukUrl = url('/pl/' . $pathWithoutLocale);

                    \Log::info('Header Language Switch', [
                        'current_locale' => app()->getLocale(),
                        'current_url' => request()->fullUrl(),
                        'en_url' => $enUrl,
                        'pl_url' => $ukUrl,
                    ]);
                @endphp

                @php
                    $headerSettings = app(\App\Settings\HeaderSettings::class);
                    $locale = app()->getLocale();
                @endphp

                    <!-- Social Links, Cart, and Language -->
                <div class="flex gap-2 items-center">
                    <a href="{{ isset($headerSettings->instagram_url[$locale]) ? $headerSettings->instagram_url[$locale] : 'https://instagram.com' }}"
                       class="social-link"
                       aria-label="{{ __('messages.social.instagram') }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="social-icon hover:stroke-green-600">
                            <path d="M8.65 21H14.35C19.1 21 21 19.1 21 14.35V8.65C21 3.9 19.1 2 14.35 2H8.65C3.9 2 2 3.9 2 8.65V14.35C2 19.1 3.9 21 8.65 21Z" stroke="#333333" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M11.5002 15.3C13.5989 15.3 15.3002 13.5987 15.3002 11.5C15.3002 9.40134 13.5989 7.70003 11.5002 7.70003C9.40151 7.70003 7.7002 9.40134 7.7002 11.5C7.7002 13.5987 9.40151 15.3 11.5002 15.3Z" stroke="#333333" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M16.8543 6.75005H16.8653" stroke="#333333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </a>
                    <a href="{{ isset($headerSettings->facebook_url[$locale]) ? $headerSettings->facebook_url[$locale] : 'https://facebook.com' }}"
                       class="social-link"
                       aria-label="{{ __('messages.social.facebook') }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="social-icon hover:stroke-green-600">
                            <path d="M11.8 21H12.2C12.9499 21 13.3249 21 13.5878 20.809C13.6727 20.7473 13.7473 20.6727 13.809 20.5878C14 20.3249 14 19.9499 14 19.2V13.5H15.25C15.9522 13.5 16.3033 13.5 16.5556 13.3315C16.6648 13.2585 16.7585 13.1648 16.8315 13.0556C17 12.8033 17 12.4522 17 11.75C17 11.0478 17 10.6967 16.8315 10.4444C16.7585 10.3352 16.6648 10.2415 16.5556 10.1685C16.3033 10 15.9522 10 15.25 10H14V8C14 7.53406 14 7.30109 14.0761 7.11732C14.1776 6.87229 14.3723 6.67761 14.6173 6.57612C14.8011 6.5 15.0341 6.5 15.5 6.5C15.9659 6.5 16.1989 6.5 16.3827 6.42388C16.6277 6.32239 16.8224 6.12771 16.9239 5.88268C17 5.69891 17 5.46594 17 5V4.55556C17 4.03739 17 3.77831 16.9063 3.57738C16.8069 3.36431 16.6357 3.19305 16.4226 3.09369C16.2217 3 15.9626 3 15.4444 3C13.6309 3 12.7241 3 12.0208 3.32792C11.2751 3.67568 10.6757 4.27507 10.3279 5.02084C10 5.72407 10 6.63086 10 8.44445V10H8.75C8.04777 10 7.69665 10 7.44443 10.1685C7.33524 10.2415 7.24149 10.3352 7.16853 10.4444C7 10.6967 7 11.0478 7 11.75C7 12.4522 7 12.8033 7.16853 13.0556C7.24149 13.1648 7.33524 13.2585 7.44443 13.3315C7.69665 13.5 8.04777 13.5 8.75 13.5H10V19.2C10 19.9499 10 20.3249 10.191 20.5878C10.2527 20.6727 10.3273 20.7473 10.4122 20.809C10.6751 21 11.0501 21 11.8 21Z" stroke="#333333" stroke-width="1.5" stroke-linejoin="round"></path>
                        </svg>
                    </a>
                    <a href="{{ isset($headerSettings->telegram_url[$locale]) ? $headerSettings->telegram_url[$locale] : 'https://telegram.com' }}"
                       class="social-link"
                       aria-label="{{ __('messages.social.telegram') }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="social-icon hover:stroke-green-600">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M14.3263 5.725C12.3559 6.5341 8.41795 8.20876 2.51239 10.749C1.55341 11.1255 1.05106 11.4938 1.00533 11.8539C0.928043 12.4626 1.70008 12.7022 2.7514 13.0286C2.8944 13.073 3.04257 13.119 3.19447 13.1678C4.2288 13.4997 5.62016 13.888 6.34347 13.9035C6.99958 13.9174 7.73187 13.6504 8.54035 13.1023C14.0581 9.42509 16.9064 7.56645 17.0852 7.52638C17.2114 7.49812 17.3862 7.46257 17.5046 7.56651C17.6231 7.67045 17.6115 7.8673 17.5989 7.92011C17.5224 8.242 14.4919 11.0236 12.9236 12.4631C12.4347 12.9118 12.0879 13.2301 12.017 13.3028C11.8582 13.4656 11.6963 13.6197 11.5408 13.7677C10.5799 14.6823 9.85925 15.3681 11.5807 16.488C12.4079 17.0262 13.0699 17.4713 13.7302 17.9153C14.4515 18.4002 15.1708 18.8838 16.1015 19.4861C16.3387 19.6396 16.5651 19.799 16.7857 19.9542C17.625 20.545 18.3791 21.0757 19.3107 20.9911C19.852 20.9419 20.4111 20.4394 20.6951 18.9406C21.3662 15.3986 22.6854 7.72419 22.9902 4.56174C23.0169 4.28467 22.9833 3.93008 22.9563 3.77442C22.9294 3.61876 22.873 3.39697 22.668 3.23279C22.4253 3.03836 22.0506 2.99736 21.883 3.00013C21.1211 3.01352 19.9521 3.41482 14.3263 5.725Z" stroke="#333333" stroke-width="1.5" stroke-linejoin="round"></path>
                        </svg>
                    </a>
                    <div class="hidden">
                        @livewire('components.cart')
                    </div>


                    <!-- Language Dropdown (Desktop) -->
                    <div class="relative" x-data="{ desktopLanguageMenu: false }">
                        <button
                            x-on:click="desktopLanguageMenu = !desktopLanguageMenu"
                            class="flex items-center gap-1 text-sm font-semibold text-zinc-800 hover:text-green-600 focus:outline-none focus:ring-2 focus:ring-green-600"
                            aria-label="{{ __('messages.language.current') }}"
                            :aria-expanded="desktopLanguageMenu"
                        >
                            <span class="uppercase">{{ app()->getLocale() }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div
                            x-show="desktopLanguageMenu"
                            x-transition
                            x-cloak
                            class="absolute right-0 mt-2 w-32 bg-white shadow-lg rounded-md z-50"
                            x-on:click.away="desktopLanguageMenu = false"
                        >
                            <a
                                href="{{ $enUrl }}"
                                class="block px-4 py-2 text-sm text-zinc-800 hover:bg-green-600 hover:text-white"
                                wire:navigate
                            >
                                {{ __('messages.language.english') }}
                            </a>
                            <a
                                href="{{ $ukUrl }}"
                                class="block px-4 py-2 text-sm text-zinc-800 hover:bg-green-600 hover:text-white"
                                wire:navigate
                            >
                                {{ __('messages.language.poland') }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Mobile Menu Toggle -->
                <button
                    x-on:click="mobileMenu = !mobileMenu; console.log('Mobile menu toggled:', mobileMenu)"
                    class="mobile-menu-toggle text-2xl cursor-pointer text-zinc-800 focus:outline-none focus:ring-2 focus:ring-green-600"
                    aria-label="{{ __('messages.nav.toggle_mobile_menu') }}"
                    :aria-expanded="mobileMenu"
                >
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 6H21" stroke="#333333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M3 12H21" stroke="#333333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M3 18H21" stroke="#333333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>

                <!-- Mobile Menu -->
                <div
                    x-cloak
                    x-transition
                    x-show="mobileMenu"
                    id="mobile-menu"
                    class="bg-white shadow-lg absolute top-14 left-0 w-full z-50"
                    x-on:click.away="mobileMenu = false"
                >
                    <nav class="flex flex-col items-center gap-4 px-2 py-6 text-base font-semibold text-zinc-800" role="navigation" aria-label="{{ __('messages.nav.mobile_navigation') }}">
                        <!-- Menu Items -->
                        @if ($headerMenu)
                            <ul class="mobile-menu-items">
                                @foreach ($headerMenu->menuItems as $item)
                                    <li>
                                        @if (str_contains($item->url, '#'))
                                            <a href="{{ $item->url }}" class="w-full text-center py-2 hover:text-green-600" @if(str_starts_with($item->url, '#')) x-on:click.prevent="window.scrollTo({ top: document.querySelector('{{ $item->url }}').offsetTop, behavior: 'smooth' }); console.log('Scrolling to', '{{ $item->url }}')" @endif>
                                                {{ $item->title }}
                                            </a>
                                        @else
                                            <a href="{{ $item->url }}" class="w-full text-center py-2 hover:text-green-600" wire:navigate>
                                                {{ $item->title }}
                                            </a>
                                        @endif
                                        @if ($item->children)
                                            <ul class="mobile-menu-items">
                                                @foreach ($item->children as $child)
                                                    @if (str_contains($child->url, '#'))
                                                        <li>
                                                            <a href="{{ $child->url }}" class="w-full text-center py-2 text-sm hover:text-green-600" @if(str_starts_with($child->url, '#')) x-on:click.prevent="window.scrollTo({ top: document.querySelector('{{ $child->url }}').offsetTop, behavior: 'smooth' }); console.log('Scrolling to', '{{ $child->url }}')" @endif>
                                                                {{ $child->title }}
                                                            </a>
                                                        </li>
                                                    @else
                                                        <li>
                                                            <a href="{{ $child->url }}" class="w-full text-center py-2 text-sm hover:text-green-600" wire:navigate>
                                                                {{ $child->title }}
                                                            </a>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif






                        <!-- Contact Button -->
                        <button
                            wire:click="$dispatch('openContactForm')"
                            class="px-4 py-2 text-sm font-bold text-green-600 rounded-2xl border-2 border-green-600 hover:bg-green-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-green-600 w-full max-w-xs"
                            aria-label="{{ __('messages.feedback_form.submit_button') }}"
                        >
                            {{ __('messages.feedback_form.submit_button') }}
                        </button>


                    </nav>
                </div>

            </div>
        </div>
    </header>



    <div class="header-placeholder"
         x-show="isScrolled"
         style="height: 56px;"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="height 0"
         x-transition:enter-end="height 56px"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="height 56px"
         x-transition:leave-end="height 0">
    </div>
    @livewire('components.contact-form')
</div>
