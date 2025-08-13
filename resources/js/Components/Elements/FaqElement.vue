<template>
    <section class="flex flex-col py-10" aria-label="Frequently Asked Questions">
        <div class="main-container">
            <h1 class="text-2xl font-bold leading-tight text-zinc-800 max-md:max-w-full">
                {{ content.title || translations.faq.defaultTitle }}
            </h1>

            <div class="flex flex-wrap gap-5 justify-center mt-5 w-full max-sm:max-w-full">
                <img
                    :src="content.image || 'https://example.com/faq-image.png'"
                    alt="FAQ illustration"
                    class="rounded-3xl max-w-xs"
                />

                <div class="flex-1 shrink self-start basis-0 min-w-60 max-md:max-w-full">
                    <article
                        v-for="(faq, index) in content.faqs"
                        :key="index"
                        class="flex flex-wrap items-start px-4 py-2 w-full rounded-3xl bg-neutral-200 max-md:max-w-full mb-1"
                    >
                        <div class="flex gap-2.5 items-start self-stretch py-2 h-full w-[70px]">
                            <img
                                src="https://cdn.builder.io/api/v1/image/assets/...question-icon.png"
                                alt="Question icon"
                                class="object-contain rounded-xl aspect-[1.11] w-[70px]"
                            />
                        </div>

                        <div class="flex-1 shrink pt-4 pb-2 pl-4 basis-0 min-w-60 text-zinc-800 max-md:max-w-full">
                            <h2
                                class="text-xl font-bold cursor-pointer"
                                :data-toggle="'faq-' + index"
                            >
                                {{ faq.question }}
                            </h2>

                            <div class="flex w-full bg-zinc-300 min-h-px"></div>

                            <p
                                class="faq-answer max-h-0 overflow-hidden transition-all duration-300 ease-in-out text-base font-semibold"
                                :id="'faq-' + index"
                            >
                                {{ faq.answer }}
                            </p>
                        </div>

                        <button
                            class="flex shrink-0 gap-2.5 w-14 h-14 items-center justify-center"
                            :aria-expanded="false"
                            :data-toggle="'faq-' + index"
                        >
                            <svg class="w-6 h-6 text-zinc-800 toggle-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </button>
                    </article>
                </div>
            </div>

            <a
                href="#"
                class="flex w-fit ml-auto gap-2 justify-center items-center self-end px-6 py-2.5 mt-5 text-base font-bold text-green-600 border-2 border-solid border-green-600 rounded-2xl hover:bg-green-50"
            >
                {{ translations.faq.buttonMore }}
                <img
                    src="https://cdn.builder.io/api/v1/image/assets/...arrow-right.png"
                    alt=""
                    class="w-6 aspect-square"
                />
            </a>
        </div>
    </section>
</template>

<script setup>
import { defineProps } from 'vue'

const props = defineProps({
    content: {
        type: Object,
        required: true
    },
    translations: {
        type: Object,
        required: true
    }
})
</script>
