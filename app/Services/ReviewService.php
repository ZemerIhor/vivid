<?php

namespace App\Services;

use App\Models\Review;
use App\Repositories\ReviewRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class ReviewService
{
    public function __construct(
        private ReviewRepositoryInterface $reviewRepository
    ) {}

    /**
     * Create a new review
     */
    public function createReview(array $data): Review
    {
        $reviewData = [
            'name' => $data['name'],
            'rating' => $data['rating'],
            'comment' => $data['comment'],
            'published' => false, // По умолчанию не опубликован
            'published_at' => null,
        ];

        $review = $this->reviewRepository->create($reviewData);
        
        Log::info('New review created', [
            'review_id' => $review->id,
            'name' => $review->name,
            'rating' => $review->rating,
        ]);

        return $review;
    }

    /**
     * Get published reviews with pagination
     */
    public function getPublishedReviews(int $perPage = 10): LengthAwarePaginator
    {
        return $this->reviewRepository->getPublishedPaginated($perPage);
    }

    /**
     * Get recent published reviews
     */
    public function getRecentReviews(int $limit = 5): Collection
    {
        return $this->reviewRepository->getPublished()->take($limit);
    }

    /**
     * Get reviews by rating
     */
    public function getReviewsByRating(int $rating): Collection
    {
        if ($rating < 1 || $rating > 5) {
            throw new \InvalidArgumentException('Rating must be between 1 and 5');
        }

        return $this->reviewRepository->getByRating($rating);
    }

    /**
     * Get rating statistics
     */
    public function getRatingStatistics(): array
    {
        return $this->reviewRepository->getRatingStats();
    }

    /**
     * Get average rating
     */
    public function getAverageRating(): float
    {
        return $this->reviewRepository->getAverageRating();
    }

    /**
     * Publish a review
     */
    public function publishReview(int $reviewId): bool
    {
        $result = $this->reviewRepository->publish($reviewId);
        
        if ($result) {
            Log::info('Review published', ['review_id' => $reviewId]);
        }

        return $result;
    }

    /**
     * Unpublish a review
     */
    public function unpublishReview(int $reviewId): bool
    {
        $result = $this->reviewRepository->unpublish($reviewId);
        
        if ($result) {
            Log::info('Review unpublished', ['review_id' => $reviewId]);
        }

        return $result;
    }

    /**
     * Update review
     */
    public function updateReview(int $reviewId, array $data): bool
    {
        $allowedFields = ['name', 'rating', 'comment', 'published', 'published_at'];
        $updateData = array_intersect_key($data, array_flip($allowedFields));

        $result = $this->reviewRepository->update($reviewId, $updateData);
        
        if ($result) {
            Log::info('Review updated', ['review_id' => $reviewId, 'data' => $updateData]);
        }

        return $result;
    }

    /**
     * Delete review
     */
    public function deleteReview(int $reviewId): bool
    {
        $result = $this->reviewRepository->delete($reviewId);
        
        if ($result) {
            Log::info('Review deleted', ['review_id' => $reviewId]);
        }

        return $result;
    }

    /**
     * Get review by ID
     */
    public function getReview(int $reviewId): ?Review
    {
        return $this->reviewRepository->find($reviewId);
    }

    /**
     * Bulk publish reviews
     */
    public function bulkPublishReviews(array $reviewIds): int
    {
        $published = 0;
        
        foreach ($reviewIds as $reviewId) {
            if ($this->publishReview($reviewId)) {
                $published++;
            }
        }

        Log::info('Bulk review publish completed', [
            'total' => count($reviewIds),
            'published' => $published,
        ]);

        return $published;
    }

    /**
     * Bulk unpublish reviews
     */
    public function bulkUnpublishReviews(array $reviewIds): int
    {
        $unpublished = 0;
        
        foreach ($reviewIds as $reviewId) {
            if ($this->unpublishReview($reviewId)) {
                $unpublished++;
            }
        }

        Log::info('Bulk review unpublish completed', [
            'total' => count($reviewIds),
            'unpublished' => $unpublished,
        ]);

        return $unpublished;
    }

    /**
     * Check if review can be published
     */
    public function canPublishReview(Review $review): bool
    {
        // Добавьте здесь свою логику проверки
        // Например, проверка на модерацию, спам и т.д.
        
        return !empty(trim($review->name)) && 
               $review->rating >= 1 && 
               $review->rating <= 5 &&
               !empty($review->comment);
    }
}
