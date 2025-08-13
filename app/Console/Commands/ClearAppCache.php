<?php

namespace App\Console\Commands;

use App\Helpers\CacheHelper;
use Illuminate\Console\Command;

class ClearAppCache extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:clear-cache 
                           {type? : Cache type to clear (reviews, blog_posts, products, all)}
                           {--force : Force clear without confirmation}';

    /**
     * The console command description.
     */
    protected $description = 'Clear application-specific cache';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $type = $this->argument('type') ?? 'all';
        $force = $this->option('force');

        if (!$force && !$this->confirm("Are you sure you want to clear {$type} cache?")) {
            $this->info('Cache clear cancelled.');
            return Command::SUCCESS;
        }

        $this->info("Clearing {$type} cache...");

        $startTime = microtime(true);
        $clearedCount = 0;

        try {
            switch ($type) {
                case 'reviews':
                    $clearedCount = $this->clearReviewsCache();
                    break;
                    
                case 'blog_posts':
                    $clearedCount = $this->clearBlogPostsCache();
                    break;
                    
                case 'products':
                    $clearedCount = $this->clearProductsCache();
                    break;
                    
                case 'all':
                    $clearedCount = $this->clearAllCache();
                    break;
                    
                default:
                    $this->error("Unknown cache type: {$type}");
                    $this->info('Available types: reviews, blog_posts, products, all');
                    return Command::FAILURE;
            }

            $duration = round((microtime(true) - $startTime) * 1000, 2);
            
            $this->info("✅ Cache cleared successfully!");
            $this->info("📊 Cleared {$clearedCount} cache entries in {$duration}ms");
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error("❌ Failed to clear cache: " . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Clear reviews cache
     */
    private function clearReviewsCache(): int
    {
        $keys = [
            'reviews.published',
            'reviews.average_rating',
            'reviews.rating_stats',
        ];

        $cleared = 0;
        
        foreach ($keys as $key) {
            if (CacheHelper::forget($key)) {
                $cleared++;
            }
        }

        // Clear rating-specific caches
        for ($rating = 1; $rating <= 5; $rating++) {
            if (CacheHelper::forget("reviews.rating.{$rating}")) {
                $cleared++;
            }
        }

        // Clear paginated caches using pattern
        $cleared += CacheHelper::forgetByPattern('reviews.paginated.*');

        return $cleared;
    }

    /**
     * Clear blog posts cache
     */
    private function clearBlogPostsCache(): int
    {
        $keys = [
            'blog_posts.published',
        ];

        $cleared = 0;
        
        foreach ($keys as $key) {
            if (CacheHelper::forget($key)) {
                $cleared++;
            }
        }

        // Clear recent posts cache for different limits
        for ($i = 1; $i <= 20; $i++) {
            if (CacheHelper::forget("blog_posts.recent.{$i}")) {
                $cleared++;
            }
        }

        // Clear pattern-based caches
        $cleared += CacheHelper::forgetByPattern('blog_posts.slug.*');
        $cleared += CacheHelper::forgetByPattern('blog_posts.paginated.*');
        $cleared += CacheHelper::forgetByPattern('blog_posts.search.*');

        return $cleared;
    }

    /**
     * Clear products cache
     */
    private function clearProductsCache(): int
    {
        $keys = [
            'products.all_with_relations',
            'home.sale_collection',
            'home.sale_collection_images',
            'home.random_collection',
        ];

        $cleared = 0;
        
        foreach ($keys as $key) {
            if (CacheHelper::forget($key)) {
                $cleared++;
            }
        }

        // Clear featured products cache for different limits
        for ($i = 1; $i <= 20; $i++) {
            if (CacheHelper::forget("products.featured.{$i}")) {
                $cleared++;
            }
        }

        // Clear pattern-based caches
        $cleared += CacheHelper::forgetByPattern('product.*');
        $cleared += CacheHelper::forgetByPattern('products.collection.*');
        $cleared += CacheHelper::forgetByPattern('products.search.*');

        return $cleared;
    }

    /**
     * Clear all application cache
     */
    private function clearAllCache(): int
    {
        $cleared = 0;
        $cleared += $this->clearReviewsCache();
        $cleared += $this->clearBlogPostsCache();
        $cleared += $this->clearProductsCache();
        
        return $cleared;
    }
}
