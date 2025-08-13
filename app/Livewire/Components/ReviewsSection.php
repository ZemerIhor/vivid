<?php

namespace App\Livewire\Components;

use App\Models\Review;
use Livewire\Component;

class ReviewsSection extends Component
{
    public function render()
    {
        $reviews = Review::query()
            ->where('published', true)
            ->whereNotNull('published_at')
            ->orderBy('published_at', 'desc')
            ->take(6) // Fetch enough for sliding
            ->get()
            ->map(function ($review) {
                return [
                    'name' => $review->name,
                    'date' => $review->published_at->locale(app()->getLocale())->translatedFormat('d F Y'),
                    'rating' => $review->rating,
                    'text' => $review->comment,
                ];
            })
            ->toArray();

        return view('livewire.components.reviews-section', [
            'reviews' => $reviews,
        ]);
    }
}
