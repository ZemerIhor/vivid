<style>
    .contacts-container {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    @media (min-width: 768px) {
        .contacts-container {
            grid-template-columns: 1fr 1fr;
        }
    }

    .contact-section {
        background-color: #ffffff;
        border-radius: 1.5rem;
        padding: 1.5rem;
    }

    .contact-section-alt {
        background-color: #e5e5e5;
    }

    .contact-title {
        font-size: 1.25rem;
        font-weight: 700;
        line-height: 1.5rem;
        color: #27272a;
    }

    .contact-text {
        font-size: 1rem;
        font-weight: 600;
        line-height: 1.5rem;
        color: #27272a;
    }

    .map-container {
        width: 100%;
        height: 222px;
        border-radius: 1.5rem;
        overflow: hidden;
    }

    .form-button {
        padding: 0.625rem 1.5rem;
        border-radius: 1rem;
        font-weight: 700;
        transition: all 0.3s ease-in-out;
    }

    .form-button-secondary {
        border: 2px solid #15803d;
        color: #15803d;
        background: transparent;
    }

    .form-button-secondary:hover,
    .form-button-secondary:focus {
        background-color: rgba(21, 128, 61, 0.1);
        outline: none;
        box-shadow: 0 0 0 2px rgba(21, 128, 61, 0.2);
    }

    @media (max-width: 767px) {
        .contacts-container {
            grid-template-columns: 1fr;
        }

        .contact-title {
            font-size: 1rem;
        }

        .contact-text {
            font-size: 0.875rem;
        }
    }

    @media (max-width: 640px) {
        .contact-title {
            font-size: 0.875rem;
        }

        .contact-text {
            font-size: 0.75rem;
        }
    }
</style>

<!-- Подключение Leaflet CSS и JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<div class="mx-auto px-12 container py-4">
    <!-- Breadcrumbs -->
    <livewire:components.breadcrumbs
        :currentPage="__('messages.contacts.title')"
        :items="[]"
    />

    <!-- Page Title -->
    <header class="mt-5">
        <h1 class="text-2xl font-bold leading-tight text-zinc-800">
            {{ __('messages.contacts.title') }}
        </h1>
    </header>

    <!-- Contact Information Section -->
    <section class="mt-5 text-zinc-800" aria-labelledby="contact-info">
        <h2 id="contact-info" class="sr-only">{{ __('messages.contacts.info') }}</h2>

        <!-- Main Contact Info -->
        <article class="contacts-container contact-section">
            <section>
                <h3 class="contact-title">
                    {{ __('messages.contacts.address') }}
                </h3>
                <address class="contact-text mt-4 not-italic">
                    @php
                        $address = data_get($settings, 'main_address.' . app()->getLocale(), data_get($settings, 'main_address.en', ''));
                    @endphp
                    {!! nl2br(e($address)) !!}<br>
                    E-Mail: <a href="mailto:{{ $settings->main_email }}" class="text-green-600 hover:text-green-700 focus:text-green-700 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 rounded">
                        {{ $settings->main_email }}
                    </a>
                </address>
            </section>

            <section>
                <h3 class="contact-title">
                    {{ __('messages.contacts.sales') }}
                </h3>
                <div class="contact-text mt-4">
                    @foreach ($settings->sales_phones as $phone)
                        @php
                            $phoneNumber = is_array($phone) ? ($phone['phone'] ?? '') : $phone;
                        @endphp
                        <a href="tel:{{ $phoneNumber }}" class="block text-green-600 hover:text-green-700 focus:text-green-700 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 rounded">
                            {{ $phoneNumber }}
                        </a>
                    @endforeach
                    E-Mail: <a href="mailto:{{ $settings->sales_email }}" class="text-green-600 hover:text-green-700 focus:text-green-700 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 rounded">
                        {{ $settings->sales_email }}
                    </a>
                </div>
            </section>
        </article>

        <!-- Export and Additional Contacts -->
        <article class="contacts-container contact-section contact-section-alt mt-2">
            <section>
                <h3 class="contact-title">
                    {{ __('messages.contacts.export') }}
                </h3>
                <div class="contact-text mt-4">
                    @php
                        $contact = data_get($settings, 'export_contact.' . app()->getLocale(), data_get($settings, 'export_contact.en', ''));
                    @endphp
                    <a href="tel:{{ $settings->export_phone }}" class="text-green-600 hover:text-green-700 focus:text-green-700 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 rounded">
                        {{ $settings->export_phone }}
                    </a> {{ e($contact) }}<br>
                    E-Mail: <a href="mailto:{{ $settings->export_email }}" class="text-green-600 hover:text-green-700 focus:text-green-700 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 rounded">
                        {{ $settings->export_email }}
                    </a>
                </div>
            </section>

            <section>
                <h3 class="contact-title">
                    {{ __('messages.contacts.additional_emails') }}
                </h3>
                <div class="contact-text mt-4">
                    @foreach ($settings->additional_emails as $key => $email)
                        {{ __('messages.contacts.' . $key, [], app()->getLocale()) ?: $key }}: <a href="mailto:{{ $email }}" class="text-green-600 hover:text-green-700 focus:text-green-700 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 rounded">
                            {{ $email }}
                        </a><br>
                    @endforeach
                </div>
            </section>
        </article>

        <!-- Map and Route Section -->
        <section class="mt-2" aria-labelledby="map-section">
            <h3 id="map-section" class="sr-only">{{ __('messages.contacts.map') }}</h3>
            @if ($settings->map_latitude && $settings->map_longitude)
                <div id="map" class="map-container z-0"></div>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        // Проверка, что Leaflet загружен
                        if (typeof L === 'undefined') {
                            console.error('Leaflet is not loaded. Please check CDN links.');
                            return;
                        }

                        // Проверка валидности координат
                        const latitude = parseFloat('{{ $settings->map_latitude }}');
                        const longitude = parseFloat('{{ $settings->map_longitude }}');
                        if (isNaN(latitude) || isNaN(longitude)) {
                            console.error('Invalid coordinates:', { latitude, longitude });
                            return;
                        }

                        // Инициализация карты
                        const map = L.map('map').setView([latitude, longitude], 15);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                        }).addTo(map);
                        L.marker([latitude, longitude]).addTo(map)
                            .bindPopup('{{ addslashes(data_get($settings, "main_address." . app()->getLocale(), data_get($settings, "main_address.en", ""))) }}')
                            .openPopup();
                    });
                </script>
            @else
                <p class="text-neutral-400">{{ __('messages.contacts.no_map_coordinates') }}</p>
            @endif
            <button
                type="button"
                class="form-button form-button-secondary mt-4 ml-auto flex items-center gap-2"
                onclick="openGoogleMaps()"
                @if (!$settings->map_latitude || !$settings->map_longitude) disabled @endif
            >
                <span>{{ __('messages.contacts.route') }}</span>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12.3101 9.03904C12.603 8.74614 13.0778 8.74614 13.3707 9.03904L15.5514 11.2197C15.8443 11.5126 15.8443 11.9874 15.5514 12.2803L13.3707 14.461C13.0778 14.7539 12.603 14.7539 12.3101 14.461C12.0172 14.1681 12.0172 13.6932 12.3101 13.4003L13.2104 12.5H9.22913V13.9306C9.22913 14.3448 8.89334 14.6806 8.47913 14.6806C8.06491 14.6806 7.72913 14.3448 7.72913 13.9306V11.75C7.72913 11.3358 8.06491 11 8.47913 11H13.2104L12.3101 10.0997C12.0172 9.8068 12.0172 9.33193 12.3101 9.03904Z" fill="#228F5D"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M10.8444 1.14344C11.433 0.952188 12.067 0.952188 12.6556 1.14344C13.0362 1.2671 13.3551 1.48414 13.6687 1.75037C13.9704 2.00642 14.3124 2.34846 14.7256 2.7617L20.7382 8.77433C21.1515 9.18759 21.4936 9.52964 21.7496 9.83126C22.0159 10.1449 22.2329 10.4638 22.3566 10.8444C22.5478 11.433 22.5478 12.067 22.3566 12.6556C22.2329 13.0362 22.0159 13.3551 21.7496 13.6687C21.4936 13.9704 21.1515 14.3124 20.7383 14.7256L14.7257 20.7382C14.3124 21.1515 13.9704 21.4936 13.6687 21.7496C13.3551 22.0159 13.0362 22.2329 12.6556 22.3566C12.067 22.5478 11.433 22.5478 10.8444 22.3566C10.4638 22.2329 10.1449 22.0159 9.83126 21.7496C9.52964 21.4936 9.18759 21.1515 8.77434 20.7383L2.76174 14.7257C2.34848 14.3124 2.00643 13.9704 1.75037 13.6687C1.48414 13.3551 1.2671 13.0362 1.14344 12.6556C0.952188 12.067 0.952188 11.433 1.14344 10.8444C1.2671 10.4638 1.48414 10.1449 1.75037 9.83126C2.00642 9.52964 2.34847 9.1876 2.76172 8.77437L8.77437 2.76172C9.18761 2.34847 9.52964 2.00642 9.83126 1.75037C10.1449 1.48414 10.4638 1.2671 10.8444 1.14344ZM12.1921 2.57002C11.9048 2.47666 11.5952 2.47666 11.3079 2.57002C11.1906 2.60813 11.0447 2.68788 10.802 2.89388C10.5527 3.10556 10.2538 3.40365 9.81323 3.84419L3.84419 9.81322C3.40365 10.2538 3.10556 10.5527 2.89388 10.802C2.68788 11.0447 2.60813 11.1906 2.57002 11.3079C2.47666 11.5952 2.47666 11.9048 2.57002 12.1921C2.60813 12.3094 2.68788 12.4553 2.89388 12.698C3.10556 12.9473 3.40365 13.2462 3.84419 13.6868L9.81322 19.6558C10.2538 20.0963 10.5527 20.3944 10.802 20.6061C11.0447 20.8121 11.1906 20.8919 11.3079 20.93C11.5952 21.0233 11.9048 21.0233 12.1921 20.93C12.3094 20.8919 12.4553 20.8121 12.698 20.6061C12.9473 20.3944 13.2462 20.0963 13.6868 19.6558L19.6558 13.6868C20.0963 13.2462 20.3944 12.9473 20.6061 12.698C20.8121 12.4553 20.8919 12.3094 20.93 12.1921C21.0233 11.9048 21.0233 11.5952 20.93 11.3079C20.8919 11.1906 20.8121 11.0447 20.6061 10.802C20.3944 10.5527 20.0963 10.2538 19.6558 9.81323L13.6868 3.84419C13.2462 3.40365 12.9473 3.10556 12.698 2.89388C12.4553 2.68788 12.3094 2.60813 12.1921 2.57002Z" fill="#228F5D"/>
                </svg>
            </button>
            <script>
                function openGoogleMaps() {
                    const latitude = parseFloat('{{ $settings->map_latitude }}');
                    const longitude = parseFloat('{{ $settings->map_longitude }}');
                    if (isNaN(latitude) || isNaN(longitude)) {
                        console.error('Invalid coordinates for Google Maps:', { latitude, longitude });
                        alert('{{ __('messages.contacts.invalid_coordinates') }}');
                        return;
                    }
                    const url = `https://www.google.com/maps?q=${latitude},${longitude}`;
                    window.open(url, '_blank');
                }
            </script>
        </section>

        <!-- Contact Form Section -->
        <section class="py-20" aria-labelledby="contact-form">
            <livewire:components.feedback-form-block />
        </section>
    </section>

</div>

