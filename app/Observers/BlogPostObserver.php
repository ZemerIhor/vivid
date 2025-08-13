<?php

namespace App\Observers;

use App\Helpers\CacheHelper;
use App\Helpers\LogHelper;
use App\Models\BlogPost;

class BlogPostObserver
{
    /**
     * Handle the BlogPost "created" event.
     */
    public function created(BlogPost $blogPost): void
    {
        $this->clearCache();
        
        LogHelper::modelChange(
            BlogPost::class,
            'created',
            $blogPost->id,
            $blogPost->getAttributes()
        );
    }

    /**
     * Handle the BlogPost "updated" event.
     */
    public function updated(BlogPost $blogPost): void
    {
        $this->clearCache($blogPost);
        
        LogHelper::modelChange(
            BlogPost::class,
            'updated',
            $blogPost->id,
            $blogPost->getDirty()
        );
    }

    /**
     * Handle the BlogPost "deleted" event.
     */
    public function deleted(BlogPost $blogPost): void
    {
        $this->clearCache($blogPost);
        
        LogHelper::modelChange(
            BlogPost::class,
            'deleted',
            $blogPost->id
        );
    }

    /**
     * Handle the BlogPost "force deleted" event.
     */
    public function forceDeleted(BlogPost $blogPost): void
    {
        $this->clearCache($blogPost);
        
        LogHelper::modelChange(
            BlogPost::class,
            'force_deleted',
            $blogPost->id
        );
    }

    /**
     * Clear all blog post-related caches
     */
    private function clearCache(?BlogPost $blogPost = null): void
    {
        $keys = [
            'blog_posts.published',
        ];

        foreach ($keys as $key) {
            CacheHelper::forget($key);
        }

        // Clear recent posts cache for different limits
        for ($i = 1; $i <= 20; $i++) {
            CacheHelper::forget("blog_posts.recent.{$i}");
        }

        // Clear specific slug cache if we have the post
        if ($blogPost && $blogPost->slug) {
            CacheHelper::forget("blog_posts.slug.{$blogPost->slug}");
        }

        // Clear home page blog posts cache
        CacheHelper::forget('home.blog_posts');

        // Clear paginated caches
        CacheHelper::forgetByPattern('blog_posts.paginated.*');
        CacheHelper::forgetByPattern('blog_posts.search.*');
        
        LogHelper::cache('cleared', 'blog_posts.*');
    }
}
