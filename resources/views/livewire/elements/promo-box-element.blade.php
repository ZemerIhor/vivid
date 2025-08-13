<section class="flex flex-col py-10 bg-gray-50">
    <div class="main-container max-w-6xl mx-auto px-4">
        <h1 class="text-2xl font-bold leading-tight text-zinc-800 mb-4">
            {{ $content['title'] ?? 'Заголовок' }}
        </h1>
        <p class="mb-6 text-lg text-gray-700">
            {{ $content['subtitle'] ?? 'Описание промо блока' }}
        </p>
        <a href="{{ $content['button_url'] ?? '#' }}"
           class="inline-flex items-center justify-center px-6 py-3 text-base font-semibold text-white bg-green-600 hover:bg-green-700 rounded-md transition duration-200">
            {{ $content['button_text'] ?? 'Подробнее' }}
        </a>
    </div>
</section>
