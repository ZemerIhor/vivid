<div class="container mx-auto px-4 py-8 max-w-4xl">
    <!-- Breadcrumbs -->
    <nav class="flex mb-6" aria-label="{{ __('messages.navigation.breadcrumb') }}">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center text-sm font-medium text-zinc-800 hover:text-green-600"
                   wire:navigate>
                    {{ __('messages.navigation.home') }}
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="{{ route('reviews') }}" 
                       class="ml-1 text-sm font-medium text-zinc-800 hover:text-green-600"
                       wire:navigate>
                        {{ __('messages.reviews.title') }}
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-500">{{ __('messages.submit_review.breadcrumb') }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <header class="mb-8">
        <h1 class="text-4xl font-bold text-zinc-800 mb-3 max-md:text-3xl max-sm:text-2xl">
            {{ __('messages.submit_review.title') }}
        </h1>
        <p class="text-lg text-neutral-400 max-md:text-base">
            {{ __('messages.submit_review.description') }}
        </p>
    </header>

    <!-- Success Message -->
    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-600 text-green-800 p-4 mb-6 rounded-lg" role="alert">
            <div class="flex items-start">
                <svg class="w-5 h-5 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Error Message -->
    @if (session('error'))
        <div class="bg-red-50 border-l-4 border-red-600 text-red-800 p-4 mb-6 rounded-lg" role="alert">
            <div class="flex items-start">
                <svg class="w-5 h-5 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Review Form -->
    <div class="bg-white rounded-3xl shadow-md p-8 max-md:p-6 max-sm:p-4">
        <h2 class="text-2xl font-bold text-zinc-800 mb-6 max-md:text-xl max-sm:text-lg">
            {{ __('messages.submit_review.form_title') }}
        </h2>

        <form wire:submit.prevent="submit" class="space-y-6">
            <!-- Name Field -->
            <div>
                <label for="name" class="block text-base font-semibold text-zinc-800 mb-2">
                    {{ __('messages.submit_review.name_label') }} <span class="text-red-600">*</span>
                </label>
                <input 
                    wire:model="name" 
                    type="text" 
                    id="name" 
                    placeholder="{{ __('messages.submit_review.name_placeholder') }}"
                    class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-green-600 focus:ring-0 transition-colors"
                    @if($isSubmitting) disabled @endif
                >
                @error('name') 
                    <p class="text-red-600 text-sm mt-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Rating Field -->
            <div>
                <label for="rating" class="block text-base font-semibold text-zinc-800 mb-2">
                    {{ __('messages.submit_review.rating_label') }} <span class="text-red-600">*</span>
                </label>
                <div class="flex items-center gap-2">
                    @for ($i = 1; $i <= 5; $i++)
                        <button
                            type="button"
                            wire:click="$set('rating', {{ $i }})"
                            class="focus:outline-none transition-transform hover:scale-110"
                            @if($isSubmitting) disabled @endif
                        >
                            <svg class="w-10 h-10 {{ $rating >= $i ? 'text-yellow-400' : 'text-gray-300' }}" 
                                 fill="currentColor" 
                                 viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.97a1 1 0 00.95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.384 2.46a1 1 0 00-.364 1.118l1.286 3.97c.3.921-.755 1.688-1.54 1.118l-3.384-2.46a1 1 0 00-1.176 0l-3.384 2.46c-.784.57-1.838-.197-1.54-1.118l1.286-3.97a1 1 0 00-.364-1.118L2.46 8.397c-.783-.57-.38-1.81.588-1.81h4.178a1 1 0 00.95-.69l1.286-3.97z"/>
                            </svg>
                        </button>
                    @endfor
                    @if($rating)
                        <span class="ml-2 text-sm text-gray-600">
                            ({{ __('messages.submit_review.rating_stars', ['count' => $rating]) }})
                        </span>
                    @endif
                </div>
                @error('rating') 
                    <p class="text-red-600 text-sm mt-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Comment Field -->
            <div>
                <label for="comment" class="block text-base font-semibold text-zinc-800 mb-2">
                    {{ __('messages.submit_review.comment_label') }} <span class="text-red-600">*</span>
                </label>
                <textarea 
                    wire:model="comment" 
                    id="comment" 
                    rows="6" 
                    placeholder="{{ __('messages.submit_review.comment_placeholder') }}"
                    class="w-full px-4 py-3 rounded-2xl border-2 border-gray-200 focus:border-green-600 focus:ring-0 transition-colors resize-none"
                    @if($isSubmitting) disabled @endif
                ></textarea>
                <div class="flex justify-between mt-2">
                    <div>
                        @error('comment') 
                            <p class="text-red-600 text-sm flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    @if($comment)
                        <span class="text-sm text-gray-500">{{ strlen($comment) }} / 1000</span>
                    @endif
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end pt-4">
                <button 
                    type="submit" 
                    class="inline-flex items-center gap-2 px-8 py-3 bg-green-600 text-white font-bold text-base rounded-2xl hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-600 focus:ring-opacity-50 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                    @if($isSubmitting) disabled @endif
                >
                    @if($isSubmitting)
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ __('messages.submit_review.submit_loading') }}
                    @else
                        {{ __('messages.submit_review.submit_button') }}
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    @endif
                </button>
            </div>
        </form>
    </div>
</div>
