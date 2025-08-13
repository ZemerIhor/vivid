<style>
    .faq-container {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
        align-items: start;
    }

    @media (min-width: 768px) {
        .faq-container {
            grid-template-columns: 1fr 2fr;
        }
        .faq-container:nth-child(odd) {
            grid-template-columns: 2fr 1fr;
        }
        .faq-container:nth-child(odd) .faq-items{
order: -1;
        }
    }


    .faq-image {
        width: 100%;
        max-width: 24rem;
        object-fit: cover;
        height: 100%;
        border-radius: 1.5rem;
        margin: 0 auto;
        max-height: 548px;
    }

    .faq-items {
        display: grid;
        gap: 0.5rem;
    }

    .faq-card {
        display: grid;
        grid-template-columns: auto 1fr auto;
        align-items: start;
        padding: 0.5rem 1rem;
        background-color: #e5e5e5;
        border-radius: 1.5rem;
        transition: all 0.3s ease-in-out;
    }

    .faq-thumbnail {
        width: 100%;
        max-width: 4.5rem; /* ~72px */
        height: auto;
        border-radius: 0.75rem;
        object-fit: cover;
    }

    .faq-content {
        padding: 1rem 0.5rem 1rem 1rem;
    }

    .faq-content h3 {
        font-size: 1.25rem; /* 20px */
        font-weight: 700;
        line-height: 1.5rem;
        color: #27272a;
    }

    .faq-answer {
        transition: all 0.3s ease-in-out;
        max-height: 100%;
    }

    .faq-answer p {
        font-size: 1rem; /* 16px */
        font-weight: 700;
        line-height: 1.25rem;
        color: #27272a;
    }

    .faq-toggle {
        padding: 0.5rem;
    }

    .faq-arrow {
        width: 3.5rem; /* 56px */
        height: 3.5rem;
    }

    @media (max-width: 767px) {
        .faq-image {
            max-width: 100%;
        }

        .faq-card {
            grid-template-columns: auto 1fr auto;
        }

        .faq-thumbnail {
            max-width: 3.75rem; /* ~60px */
        }

        .faq-content h3 {
            font-size: 1rem; /* 16px */
        }

        .faq-answer p {
            font-size: 0.875rem; /* 14px */
        }

        .faq-arrow {
            width: 3rem; /* 48px */
            height: 3rem;
        }
    }

    @media (max-width: 640px) {
        .faq-content h3 {
            font-size: 0.875rem; /* 14px */
        }

        .faq-answer p {
            font-size: 0.75rem; /* 12px */
        }

        .faq-thumbnail {
            max-width: 3rem; /* ~48px */
        }
    }
</style>


<div class="container mx-auto px-4 md:px-12">

    <section class="" aria-label="Frequently Asked Questions">
        <div class="">
            <!-- Breadcrumbs -->
            <livewire:components.breadcrumbs
                :currentPage="__('messages.faq.title')"
                :items="[]"
            />

            <h1 class="text-2xl font-bold leading-tight text-zinc-800 max-md:max-w-full mt-4">
                {{ __('messages.faq.title') }}
            </h1>
            <div class="mt-5 w-full">
                @if (!empty($settings->faq_blocks[app()->getLocale()]))
                    @foreach ($settings->faq_blocks[app()->getLocale()] as $block_index => $block)
                        <div class="faq-container">
                            <img
                                src="{{ Storage::url($block['main_image']) }}"
                                alt="{{ $block['main_image_alt'] ?? '' }}"
                                class="faq-image"
                            />
                            <div class="faq-items">
                                @foreach ($block['items'] as $index => $faq)
                                    <article
                                        class="faq-card"
                                        role="button"
                                        tabindex="0"
                                        aria-expanded="{{ $index === 0 && $block_index === 0 ? 'true' : 'false' }}"
                                        aria-controls="faq-answer-{{ $block_index }}-{{ $index }}"
                                    >
                                        <div class="py-2">
                                            <img
                                                src="{{ Storage::url($faq['thumbnail']) }}"
                                                alt="{{ $faq['thumbnail_alt'] ?? '' }}"
                                                class="faq-thumbnail"
                                            />
                                        </div>
                                        <div class="faq-content">
                                            <h3>{{ $faq['question'] ?? '' }}</h3>
                                            <div
                                                id="faq-answer-{{ $block_index }}-{{ $index }}"
                                                class="faq-answer {{ $index === 0 && $block_index === 0 ? '' : 'hidden' }}"
                                            >
                                                <div class="h-px bg-zinc-300 mt-2 mb-2" role="separator"></div>
                                                <p>{{ $faq['answer'] ?? '' }}</p>
                                            </div>
                                        </div>
                                        <button
                                            class="faq-toggle"
                                            aria-label="{{ $index === 0 && $block_index === 0 ? __('Сгорнуть ответ') : __('Развернуть ответ') }}"
                                        >
                                            <svg
                                                class="faq-arrow {{ $index === 0 && $block_index === 0 ? 'faq-arrow-up' : 'faq-arrow-down' }}"
                                                width="56"
                                                height="56"
                                                viewBox="0 0 56 56"
                                                fill="none"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <path
                                                    d="{{ $index === 0 && $block_index === 0 ? 'M37.8039 33.6143L37.8039 18.1966L18.1971 37.8033L26.8675 29.133C30.8868 25.1137 37.7607 27.9304 37.8039 33.6143ZM37.8039 18.1966L27.5254 18.1966L22.3862 18.1966C28.0731 18.2172 30.9068 25.0936 26.8856 29.1149L18.1971 37.8033L37.8039 18.1966ZM16.2466 18.1964C16.2466 17.6442 16.6942 17.1965 17.2465 17.1965L37.8036 17.1965C38.3557 17.1968 38.8035 17.6443 38.8035 18.1964V38.7536C38.8035 39.3057 38.3557 39.7533 37.8036 39.7535C37.2514 39.7535 36.8038 39.3059 36.8038 38.7536L36.8031 33.614C36.7609 28.8976 31.1441 26.5249 27.735 29.6848L27.5741 29.8402L18.9037 38.5106C18.5133 38.901 17.8801 38.901 17.4895 38.5106C17.1235 38.1445 17.1003 37.5651 17.4205 37.1723L17.4895 37.0964L26.1778 28.4081C29.5703 25.0155 27.1797 19.2144 22.382 19.197L22.3813 19.1963L17.2465 19.1963C16.6942 19.1963 16.2466 18.7487 16.2466 18.1964ZM31.169 26.2453C33.2744 26.1951 35.3483 27.0531 36.8024 28.5779L36.8038 20.6105L31.169 26.2453ZM27.4429 19.1963C28.9628 20.6471 29.8188 22.7157 29.77 24.8159L35.3895 19.1963H27.4429Z' : 'M18.1597 22.4465L18.2551 37.8639L37.7402 18.1362L29.1236 26.8601C25.1293 30.9041 18.2381 28.13 18.1597 22.4465ZM18.2551 37.8639L28.5334 37.8003L33.6725 37.7685C27.9856 37.7831 25.1093 30.9243 29.1057 26.8782L37.7402 18.1362L18.2551 37.8639ZM39.8114 37.7307C39.8145 38.2484 39.4238 38.6763 38.9199 38.7306L38.8177 38.7367L18.2609 38.8639C17.7089 38.8671 17.2583 38.4223 17.2549 37.8702L17.1277 17.3134C17.1243 16.7613 17.5693 16.311 18.1214 16.3073C18.6736 16.3039 19.124 16.7488 19.1274 17.301L19.1599 22.4406C19.2312 27.1566 24.8626 29.4946 28.2521 26.3135L28.4121 26.1572L37.0286 17.4334C37.4167 17.0405 38.0499 17.0366 38.4428 17.4246C38.8111 17.7884 38.8379 18.3677 38.5201 18.7624L38.4515 18.8388L29.8172 27.5807C26.4457 30.9941 28.8722 36.7803 33.6699 36.768L33.6706 36.7687L38.8053 36.737L38.9083 36.7412C39.4126 36.7895 39.8082 37.2131 39.8114 37.7307ZM24.8395 29.7743C22.7344 29.8375 20.6552 28.9923 19.1918 27.4765L19.2397 35.4438L24.8395 29.7743ZM28.6091 36.8C27.0803 35.3587 26.2114 33.2954 26.2473 31.195L20.6626 36.8492L28.6091 36.8Z' }}"
                                                    fill="#228F5D"
                                                />
                                            </svg>
                                        </button>
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @else
                    <p>{{ __('messages.faq.no_items') }}</p>
                @endif
            </div>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggles = document.querySelectorAll('.faq-toggle');
        toggles.forEach(toggle => {
            toggle.addEventListener('click', () => {
                const article = toggle.closest('.faq-card');
                const answer = article.querySelector('.faq-answer');
                const isExpanded = article.getAttribute('aria-expanded') === 'true';
                const svg = toggle.querySelector('.faq-arrow');

                if (isExpanded) {
                    answer.classList.add('hidden');
                    article.setAttribute('aria-expanded', 'false');
                    toggle.setAttribute('aria-label', '{{ __('Развернуть ответ') }}');
                    svg.classList.remove('faq-arrow-up');
                    svg.classList.add('faq-arrow-down');
                    svg.querySelector('path').setAttribute('d', 'M18.1597 22.4465L18.2551 37.8639L37.7402 18.1362L29.1236 26.8601C25.1293 30.9041 18.2381 28.13 18.1597 22.4465ZM18.2551 37.8639L28.5334 37.8003L33.6725 37.7685C27.9856 37.7831 25.1093 30.9243 29.1057 26.8782L37.7402 18.1362L18.2551 37.8639ZM39.8114 37.7307C39.8145 38.2484 39.4238 38.6763 38.9199 38.7306L38.8177 38.7367L18.2609 38.8639C17.7089 38.8671 17.2583 38.4223 17.2549 37.8702L17.1277 17.3134C17.1243 16.7613 17.5693 16.311 18.1214 16.3073C18.6736 16.3039 19.124 16.7488 19.1274 17.301L19.1599 22.4406C19.2312 27.1566 24.8626 29.4946 28.2521 26.3135L28.4121 26.1572L37.0286 17.4334C37.4167 17.0405 38.0499 17.0366 38.4428 17.4246C38.8111 17.7884 38.8379 18.3677 38.5201 18.7624L38.4515 18.8388L29.8172 27.5807C26.4457 30.9941 28.8722 36.7803 33.6699 36.768L33.6706 36.7687L38.8053 36.737L38.9083 36.7412C39.4126 36.7895 39.8082 37.2131 39.8114 37.7307ZM24.8395 29.7743C22.7344 29.8375 20.6552 28.9923 19.1918 27.4765L19.2397 35.4438L24.8395 29.7743ZM28.6091 36.8C27.0803 35.3587 26.2114 33.2954 26.2473 31.195L20.6626 36.8492L28.6091 36.8Z');
                } else {
                    answer.classList.remove('hidden');
                    article.setAttribute('aria-expanded', 'true');
                    toggle.setAttribute('aria-label', '{{ __('Сгорнуть ответ') }}');
                    svg.classList.remove('faq-arrow-down');
                    svg.classList.add('faq-arrow-up');
                    svg.querySelector('path').setAttribute('d', 'M37.8039 33.6143L37.8039 18.1966L18.1971 37.8033L26.8675 29.133C30.8868 25.1137 37.7607 27.9304 37.8039 33.6143ZM37.8039 18.1966L27.5254 18.1966L22.3862 18.1966C28.0731 18.2172 30.9068 25.0936 26.8856 29.1149L18.1971 37.8033L37.8039 18.1966ZM16.2466 18.1964C16.2466 17.6442 16.6942 17.1965 17.2465 17.1965L37.8036 17.1965C38.3557 17.1968 38.8035 17.6443 38.8035 18.1964V38.7536C38.8035 39.3057 38.3557 39.7533 37.8036 39.7535C37.2514 39.7535 36.8038 39.3059 36.8038 38.7536L36.8031 33.614C36.7609 28.8976 31.1441 26.5249 27.735 29.6848L27.5741 29.8402L18.9037 38.5106C18.5133 38.901 17.8801 38.901 17.4895 38.5106C17.1235 38.1445 17.1003 37.5651 17.4205 37.1723L17.4895 37.0964L26.1778 28.4081C29.5703 25.0155 27.1797 19.2144 22.382 19.197L22.3813 19.1963L17.2465 19.1963C16.6942 19.1963 16.2466 18.7487 16.2466 18.1964ZM31.169 26.2453C33.2744 26.1951 35.3483 27.0531 36.8024 28.5779L36.8038 20.6105L31.169 26.2453ZM27.4429 19.1963C28.9628 20.6471 29.8188 22.7157 29.77 24.8159L35.3895 19.1963H27.4429Z');
                }
            });
        });
    });
</script>
