<div class="max-w-2xl mx-auto p-6">
    @if (session('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="submit" class="space-y-6">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Имя</label>
            <input wire:model="name" type="text" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring focus:ring-green-600 focus:ring-opacity-50">
            @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="rating" class="block text-sm font-medium text-gray-700">Рейтинг</label>
            <select wire:model="rating" id="rating" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring focus:ring-green-600 focus:ring-opacity-50">
                <option value="">Выберите рейтинг</option>
                @for ($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
            @error('rating') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="comment" class="block text-sm font-medium text-gray-700">Отзыв</label>
            <textarea wire:model="comment" id="comment" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-600 focus:ring focus:ring-green-600 focus:ring-opacity-50"></textarea>
            @error('comment') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-green-600 text-white font-bold rounded-2xl hover:bg-green-700">
            Отправить отзыв
        </button>
    </form>
</div>
