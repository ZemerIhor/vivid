@php
    $footer = app(\App\Settings\FooterSettings::class);
    $currentLocale = app()->getLocale(); // Get current locale (e.g., 'en' or 'pl')
@endphp

<footer class="self-stretch bg-zinc-800 mt-auto relative " role="contentinfo" id="footer"
    aria-label="Site footer">
    <div class="container mx-auto px-4">
        <section class="header-section">
            <div class="logo"><x-brand.logo /></div>


            @if (!empty($footer->social_links))
                <nav aria-label="{{ __('messages.footer.social_aria_label') }}" class="social-links">
                    @foreach ($footer->social_links as $link)
                        @if (!empty($link['url']) && !empty($link['icon']))
                            <a href="{{ $link['url'] }}" aria-label="{{ __('messages.footer.follow_on') }} {{ $link['icon'] }}"
                                target="_blank" rel="noopener noreferrer">
                                <img src="{{ asset('images/icons/' . $link['icon'] . '.svg') }}" alt="{{ $link['icon'] }} icon"
                                    class="social-icon" role="img" />
                            </a>
                        @endif
                    @endforeach
                </nav>
            @endif
        </section>

        <!-- Navigation sections and contacts -->
        <section class="nav-contacts flex flex-wrap justify-between">
            @php
                use Datlechin\FilamentMenuBuilder\Models\Menu;
                $footerLocation = app()->getLocale() === 'en' ? 'header_en' : 'header_uk';
                $footerMenu = Menu::location($footerLocation);
            @endphp

            @if ($footerMenu)
                <nav class="menu hidden" aria-label="{{ __('messages.footer.main_navigation') }}">
                    <ul>
                        @foreach ($footerMenu->menuItems as $item)

                            <li>
                                <a href="{{ $item->url }}">{{ $item->title }}</a>
                                @if ($item->children)
                                    <ul>
                                        @foreach ($item->children as $child)
                                            <li><a href="{{ $child->url }}">{{ $child->title }}</a></li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </nav>
            @endif

            <!-- Контактная информация -->
            @if (isset($footer) && (!empty($footer->phone) || !empty($footer->email) || !empty($footer->address)))
                <section class="contacts " aria-label="{{ __('messages.footer.contact_aria_label') }}">
                    <address>
                        <div class="contacts-list">
                            @if (!empty($footer->phone))
                                <div class="contact-item">
                                    <a href="tel:{{ $footer->phone }}" class="contact-link"
                                        aria-label="{{ __('messages.footer.phone_aria_label') }}">
                                        {{ $footer->phone }}
                                    </a>
                                </div>
                            @endif
                            @if (!empty($footer->email))
                                <div class="contact-item">
                                    <a href="mailto:{{ is_array($footer->email) ? ($footer->email[app()->getLocale()] ?? $footer->email['en'] ?? '') : $footer->email }}"
                                        class="contact-link" aria-label="{{ __('messages.footer.email_aria_label') }}">
                                        {{ is_array($footer->email) ? ($footer->email[app()->getLocale()] ?? $footer->email['en'] ?? '') : $footer->email }}
                                    </a>
                                </div>
                            @endif
                            @if (!empty($footer->address))
                                <div class="contact-item">
                                    <p class="footer-contact-text">
                                        {{ is_array($footer->address) ? ($footer->address[app()->getLocale()] ?? $footer->address['en'] ?? '') : $footer->address }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </address>
                </section>
            @endif
        </section>

        <!-- Copyright and scroll to top -->
        <section class="footer-bottom">
            <p class="copyright">
                {{ is_array($footer->copyright_text) ? ($footer->copyright_text[app()->getLocale()] ?? $footer->copyright_text['en'] ?? '© All rights reserved') : ($footer->copyright_text ?? '© All rights reserved') }}
            </p>
        </section>

        <style>
            .main-container {
                max-width: 1280px;
                margin: 0 auto;
                padding: 0 8px;
            }

            .header-section {
                display: flex;
                justify-content: space-between;
                align-items: flex-end;
                padding: 44px 0 24px;
                width: 100%;
            }

            .logo {
                width: auto;
                height: 32px;
            }

            .social-links {
                display: flex;
                gap: 20px;
                align-items: flex-start;
            }

            .social-icon {
                width: 24px;
                height: 24px;
                object-fit: contain;
            }

            .nav-contacts ul {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                gap: 10px;
                align-items: flex-start;
                width: 100%;
            }

            @media (max-width: 768px) {
                .nav-contacts ul {
                    grid-template-columns: 1fr;
                }
            }

            .menu {
                display: flex;
                flex-direction: column;
                gap: 20px;
                align-items: flex-start;
                flex-grow: 1;
            }



            @media (max-width: 640px) {
                .menu {
                    gap: 16px;
                    width: 100%;
                }
            }


            @media (max-width: 640px) {
                .menu ul {
                    gap: 16px;
                }
            }

            .menu li {
                align-items: center;
                width: 100%;
                border-radius: 8px;
            }

            .menu a {
                font-size: 16px;
                font-weight: 700;
                color: #FFFFFF;
                text-decoration: none;
            }

            .menu a:hover {
                color: #D1D5DB;
            }

            .menu a:focus {
                outline: none;
                box-shadow: 0 0 0 2px #FFFFFF, 0 0 0 4px #18181B;
            }

            @media (max-width: 640px) {
                .menu a {
                    font-size: 14px;
                }
            }

            .contacts {
                min-width: 220px;
                max-width: 357px;
            }

            .contacts address {
                font-style: normal;
            }

            .contacts-list {
                display: flex;
                flex-direction: column;
                gap: 20px;
            }

            @media (max-width: 640px) {
                .contacts-list {
                    gap: 16px;
                }
            }

            .contact-item {
                display: flex;
                gap: 8px;
                align-items: center;
                width: 100%;
                border-radius: 8px;
            }

            .contact-link {
                font-size: 16px;
                font-weight: 700;
                color: #FFFFFF;
                text-decoration: none;
            }

            .contact-link:hover {
                color: #D1D5DB;
            }

            .contact-link:focus {
                outline: none;
                box-shadow: 0 0 0 2px #FFFFFF, 0 0 0 4px #18181B;
            }

            @media (max-width: 640px) {
                .contact-link {
                    font-size: 14px;
                }
            }

            .footer-contact-text {
                font-size: 16px;
                font-weight: 700;
                color: #FFFFFF;
                line-height: 24px;
                flex: 1 0 0;
            }

            @media (max-width: 640px) {
                .footer-contact-text {
                    font-size: 14px;
                }
            }

            .footer-bottom {
                display: flex;
                justify-content: center;
                align-items: flex-start;
                gap: 10px;
                padding: 24px 0;
                width: 100%;
                position: relative;
            }

            .copyright {
                font-size: 12px;
                font-weight: 600;
                color: #D1D5DB;
                margin: auto 0;
            }

            .scroll-to-top {
                position: absolute;
                top: 0;
                right: 16px;
                display: flex;
                justify-content: center;
                align-items: center;
                width: 48px;
                height: 48px;
                background: #16A34A;
                border-radius: 32px;
                border: none;
                cursor: pointer;
                transition: background 0.2s;
            }

            .scroll-to-top:hover {
                background: #15803D;
            }

            .scroll-to-top:focus {
                outline: none;
                box-shadow: 0 0 0 2px #22C55E, 0 0 0 4px #18181B;
            }

            .scroll-icon {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 24px;
                height: 24px;
            }

            .icon {
                color: #FFFFFF;
            }
        </style>
    </div>

</footer>
