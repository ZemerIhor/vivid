<?php

namespace App\Repositories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ReviewRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get published reviews
     */
    public function getPublished(): Collection;

    /**
     * Get published reviews paginated
     */
    public function getPublishedPaginated(int $perPage = 10): LengthAwarePaginator;

    /**
     * Get reviews by rating
     */
    public function getByRating(int $rating): Collection;

    /**
     * Get average rating
     */
    public function getAverageRating(): float;

    /**
     * Get rating statistics
     */
    public function getRatingStats(): array;

    /**
     * Publish review
     */
    public function publish(int $id): bool;

    /**
     * Unpublish review
     */
    public function unpublish(int $id): bool;
}
