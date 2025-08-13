<?php

namespace App\Repositories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class ReviewRepository extends BaseRepository implements ReviewRepositoryInterface
{
    public function __construct(Review $model)
    {
        parent::__construct($model);
    }

    /**
     * Get published reviews
     */
    public function getPublished(): Collection
    {
        return Cache::remember('reviews.published', 3600, function () {
            return $this->model->where('published', true)
                ->whereNotNull('published_at')
                ->orderBy('published_at', 'desc')
                ->get();
        });
    }

    /**
     * Get published reviews paginated
     */
    public function getPublishedPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->where('published', true)
            ->whereNotNull('published_at')
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get reviews by rating
     */
    public function getByRating(int $rating): Collection
    {
        return $this->model->where('published', true)
            ->where('rating', $rating)
            ->orderBy('published_at', 'desc')
            ->get();
    }

    /**
     * Get average rating
     */
    public function getAverageRating(): float
    {
        return Cache::remember('reviews.average_rating', 3600, function () {
            return (float) $this->model->where('published', true)->avg('rating') ?: 0;
        });
    }

    /**
     * Get rating statistics
     */
    public function getRatingStats(): array
    {
        return Cache::remember('reviews.rating_stats', 3600, function () {
            $stats = [];
            for ($i = 1; $i <= 5; $i++) {
                $stats[$i] = $this->model->where('published', true)
                    ->where('rating', $i)
                    ->count();
            }
            
            $total = array_sum($stats);
            $average = $total > 0 ? $this->getAverageRating() : 0;
            
            return [
                'ratings' => $stats,
                'total_reviews' => $total,
                'average_rating' => round($average, 1),
            ];
        });
    }

    /**
     * Publish review
     */
    public function publish(int $id): bool
    {
        $review = $this->findOrFail($id);
        $result = $review->update([
            'published' => true,
            'published_at' => now(),
        ]);

        $this->clearCache();
        return $result;
    }

    /**
     * Unpublish review
     */
    public function unpublish(int $id): bool
    {
        $review = $this->findOrFail($id);
        $result = $review->update([
            'published' => false,
            'published_at' => null,
        ]);

        $this->clearCache();
        return $result;
    }

    /**
     * Clear cached data
     */
    private function clearCache(): void
    {
        Cache::forget('reviews.published');
        Cache::forget('reviews.average_rating');
        Cache::forget('reviews.rating_stats');
    }

    /**
     * Override create to clear cache
     */
    public function create(array $data): \Illuminate\Database\Eloquent\Model
    {
        $review = parent::create($data);
        $this->clearCache();
        return $review;
    }

    /**
     * Override update to clear cache
     */
    public function update(int $id, array $data): bool
    {
        $result = parent::update($id, $data);
        $this->clearCache();
        return $result;
    }

    /**
     * Override delete to clear cache
     */
    public function delete(int $id): bool
    {
        $result = parent::delete($id);
        $this->clearCache();
        return $result;
    }
}
