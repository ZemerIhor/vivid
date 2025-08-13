<?php

namespace App\Livewire;

use App\Models\Review;
use Livewire\Component;
use Livewire\WithPagination;

class ReviewsPage extends Component
{
    use WithPagination;

    public function render()
    {
        // Получаем только опубликованные отзывы, сортируем по дате публикации
        $reviews = Review::query()
            ->where('published', true)
            ->whereNotNull('published_at')
            ->orderBy('published_at', 'desc')
            ->paginate(6); // 6 отзывов на страницу

        return view('livewire.reviews-page', [
            'reviews' => $reviews,
        ]);
    }
}
