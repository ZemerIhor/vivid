<?php

namespace App\Repositories;

use App\Models\BlogPost;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface BlogPostRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get published blog posts
     */
    public function getPublished(): Collection;

    /**
     * Get published blog posts paginated
     */
    public function getPublishedPaginated(int $perPage = 10): LengthAwarePaginator;

    /**
     * Find by slug
     */
    public function findBySlug(string $slug): ?BlogPost;

    /**
     * Get recent posts
     */
    public function getRecent(int $limit = 5): Collection;

    /**
     * Search posts by title or content
     */
    public function search(string $query, int $perPage = 10): LengthAwarePaginator;

    /**
     * Publish post
     */
    public function publish(int $id): bool;

    /**
     * Unpublish post
     */
    public function unpublish(int $id): bool;
}
