<?php

namespace App\Observers;

use App\Helpers\CacheHelper;
use App\Helpers\LogHelper;
use App\Models\Review;

class ReviewObserver
{
    /**
     * Handle the Review "created" event.
     */
    public function created(Review $review): void
    {
        $this->clearCache();
        
        LogHelper::modelChange(
            Review::class,
            'created',
            $review->id,
            $review->getAttributes()
        );
    }

    /**
     * Handle the Review "updated" event.
     */
    public function updated(Review $review): void
    {
        $this->clearCache();
        
        LogHelper::modelChange(
            Review::class,
            'updated',
            $review->id,
            $review->getDirty()
        );
    }

    /**
     * Handle the Review "deleted" event.
     */
    public function deleted(Review $review): void
    {
        $this->clearCache();
        
        LogHelper::modelChange(
            Review::class,
            'deleted',
            $review->id
        );
    }

    /**
     * Handle the Review "force deleted" event.
     */
    public function forceDeleted(Review $review): void
    {
        $this->clearCache();
        
        LogHelper::modelChange(
            Review::class,
            'force_deleted',
            $review->id
        );
    }

    /**
     * Clear all review-related caches
     */
    private function clearCache(): void
    {
        $keys = [
            'reviews.published',
            'reviews.average_rating',
            'reviews.rating_stats',
        ];

        foreach ($keys as $key) {
            CacheHelper::forget($key);
        }

        // Clear rating-specific caches
        for ($rating = 1; $rating <= 5; $rating++) {
            CacheHelper::forget("reviews.rating.{$rating}");
        }

        // Clear paginated caches (simplified approach)
        CacheHelper::forgetByPattern('reviews.paginated.*');
        
        LogHelper::cache('cleared', 'reviews.*');
    }
}
