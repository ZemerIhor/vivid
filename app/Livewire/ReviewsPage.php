<?php

namespace App\Livewire;

use App\Repositories\ReviewRepositoryInterface;
use App\Services\ReviewService;
use Livewire\Component;
use Livewire\WithPagination;

class ReviewsPage extends Component
{
    use WithPagination;

    public $filterRating = null;
    public $perPage = 6;
    private ReviewRepositoryInterface $reviewRepository;
    private ReviewService $reviewService;

    public function boot(
        ReviewRepositoryInterface $reviewRepository,
        ReviewService $reviewService
    ) {
        $this->reviewRepository = $reviewRepository;
        $this->reviewService = $reviewService;
    }

    /**
     * Фильтрация по рейтингу
     */
    public function filterByRating($rating)
    {
        $this->filterRating = $rating === $this->filterRating ? null : $rating;
        $this->resetPage();
    }

    /**
     * Сброс фильтров
     */
    public function clearFilters()
    {
        $this->filterRating = null;
        $this->resetPage();
    }

    /**
     * Получить статистику рейтингов
     */
    public function getRatingStatsProperty()
    {
        return $this->reviewService->getRatingStatistics();
    }

    /**
     * Получить средний рейтинг
     */
    public function getAverageRatingProperty()
    {
        return $this->reviewService->getAverageRating();
    }

    public function render()
    {
        // Получаем отзывы с учетом фильтра
        if ($this->filterRating) {
            $reviews = $this->reviewRepository->getByRating($this->filterRating);
            // Поскольку getByRating возвращает Collection, нужно создать пагинацию вручную
            $currentPage = \Livewire\WithPagination::resolveCurrentPage();
            $perPage = $this->perPage;
            $currentPageItems = $reviews->slice(($currentPage - 1) * $perPage, $perPage)->values();
            
            $reviews = new \Illuminate\Pagination\LengthAwarePaginator(
                $currentPageItems,
                $reviews->count(),
                $perPage,
                $currentPage,
                [
                    'path' => request()->url(),
                    'pageName' => 'page',
                ]
            );
        } else {
            $reviews = $this->reviewRepository->getPublishedPaginated($this->perPage);
        }

        return view('livewire.reviews-page', [
            'reviews' => $reviews,
            'ratingStats' => $this->ratingStats,
            'averageRating' => $this->averageRating,
        ]);
    }
}
