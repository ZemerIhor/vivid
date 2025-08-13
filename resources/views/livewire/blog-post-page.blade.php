<div class=" mx-auto md:px-12 px-4 py-6">
    <article class="max-w-3xl mx-auto bg-white rounded-2xl shadow-sm p-6 flex flex-col gap-6">
        <!-- Header -->
        <header class="flex flex-col gap-2">
            <h1 class="text-3xl font-bold text-zinc-800">
                {{ $post->getTranslation('title', app()->getLocale()) }}
            </h1>
            <div class="flex items-center gap-2 text-sm text-neutral-400">
                @if ($post->published_at)
                    <time datetime="{{ $post->published_at->format('Y-m-d') }}">
                        {{ $post->published_at->format('d.m.Y') }}
                    </time>
                    <div class="h-1 w-1 bg-neutral-400 rounded-full"></div>
                @endif
                <span>{{ __('messages.breadcrumbs.blog') }}</span>
            </div>
            <hr class="border-zinc-200">
        </header>

        @if ($post->banner)
            <div class="overflow-hidden rounded-2xl">
                <img
                    src="{{ Storage::url($post->banner) }}"
                    alt="{{ $post->getTranslation('title', app()->getLocale()) }}"
                    class="w-full h-48 object-cover transition-transform hover:scale-105 duration-300"
                />
            </div>
        @endif

        <section class="prose prose-neutral max-w-none">
            {!! $post->getTranslation('content', app()->getLocale()) !!}
        </section>
    </article>
</div>
