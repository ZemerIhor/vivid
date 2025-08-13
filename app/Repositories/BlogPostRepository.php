<?php

namespace App\Repositories;

use App\Models\BlogPost;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class BlogPostRepository extends BaseRepository implements BlogPostRepositoryInterface
{
    public function __construct(BlogPost $model)
    {
        parent::__construct($model);
    }

    /**
     * Get published blog posts
     */
    public function getPublished(): Collection
    {
        return Cache::remember('blog_posts.published', 3600, function () {
            return $this->model->where('published', true)
                ->whereNotNull('published_at')
                ->orderBy('published_at', 'desc')
                ->get();
        });
    }

    /**
     * Get published blog posts paginated
     */
    public function getPublishedPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->where('published', true)
            ->whereNotNull('published_at')
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Find by slug
     */
    public function findBySlug(string $slug): ?BlogPost
    {
        return Cache::remember("blog_posts.slug.{$slug}", 3600, function () use ($slug) {
            return $this->model->where('slug', $slug)
                ->where('published', true)
                ->first();
        });
    }

    /**
     * Get recent posts
     */
    public function getRecent(int $limit = 5): Collection
    {
        return Cache::remember("blog_posts.recent.{$limit}", 1800, function () use ($limit) {
            return $this->model->where('published', true)
                ->whereNotNull('published_at')
                ->orderBy('published_at', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Search posts by title or content
     */
    public function search(string $query, int $perPage = 10): LengthAwarePaginator
    {
        $locale = app()->getLocale();
        
        return $this->model->where('published', true)
            ->where(function ($q) use ($query, $locale) {
                $q->whereJsonContains("title->{$locale}", $query)
                  ->orWhereJsonContains("content->{$locale}", $query)
                  ->orWhereJsonContains("excerpt->{$locale}", $query);
            })
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Publish post
     */
    public function publish(int $id): bool
    {
        $post = $this->findOrFail($id);
        $result = $post->update([
            'published' => true,
            'published_at' => now(),
        ]);

        $this->clearCache();
        return $result;
    }

    /**
     * Unpublish post
     */
    public function unpublish(int $id): bool
    {
        $post = $this->findOrFail($id);
        $result = $post->update([
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
        Cache::forget('blog_posts.published');
        
        // Clear recent posts cache for different limits
        for ($i = 1; $i <= 20; $i++) {
            Cache::forget("blog_posts.recent.{$i}");
        }
        
        // Clear specific slug caches (this is simplified, in production you might want a more sophisticated approach)
        $posts = $this->model->pluck('slug');
        foreach ($posts as $slug) {
            Cache::forget("blog_posts.slug.{$slug}");
        }
    }

    /**
     * Override create to clear cache
     */
    public function create(array $data): \Illuminate\Database\Eloquent\Model
    {
        $post = parent::create($data);
        $this->clearCache();
        return $post;
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
