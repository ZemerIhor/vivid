@props([
    'currentPage' => '',
    'items' => [], // Optional array of additional breadcrumb items: [['label' => 'Label', 'url' => '/url'], ...]
])

<nav class="flex flex-wrap gap-2 items-center w-full text-xs font-semibold whitespace-nowrap min-h-[34px] max-md:max-w-full" aria-label="{{ __('breadcrumbs.aria_label') }}">
    <!-- Home Link -->
    <div class="flex gap-2 items-center self-stretch py-2 my-auto text-neutral-400">
        <a href="/" class="self-stretch my-auto text-neutral-400 hover:text-zinc-800 focus:text-zinc-800 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 rounded">
            {{ __('messages.breadcrumbs.home') }}
        </a>
    </div>

    <!-- Additional Items (if provided) -->
    @foreach ($items as $item)
        <div class="flex gap-2 items-center self-stretch py-2 my-auto text-zinc-800">
            <div class="flex flex-col justify-center self-stretch my-auto w-1.5" aria-hidden="true">
                <div class="text-zinc-800">/</div>
            </div>
            <a href="{{ $item['url'] }}" class="self-stretch my-auto text-zinc-800 hover:text-zinc-800 focus:text-zinc-800 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 rounded">
                {{ $item['label'] }}
            </a>
        </div>
    @endforeach

    <!-- Current Page -->
    @if ($currentPage)
        <div class="flex gap-2 items-center self-stretch py-2 my-auto text-zinc-800">
            <div class="flex flex-col justify-center self-stretch my-auto w-1.5" aria-hidden="true">
                <div class="text-zinc-800">/</div>
            </div>
            <span class="self-stretch my-auto text-zinc-800" aria-current="page">
                {{ $currentPage }}
            </span>
        </div>
    @endif
</nav>
