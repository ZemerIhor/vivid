<?php

namespace App\Observers;

use App\Helpers\CacheHelper;
use App\Helpers\LogHelper;
use App\Models\Product;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        $this->clearCache();
        
        LogHelper::modelChange(
            Product::class,
            'created',
            $product->id,
            $product->getAttributes()
        );
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        $this->clearCache($product);
        
        LogHelper::modelChange(
            Product::class,
            'updated',
            $product->id,
            $product->getDirty()
        );
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        $this->clearCache($product);
        
        LogHelper::modelChange(
            Product::class,
            'deleted',
            $product->id
        );
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        $this->clearCache($product);
        
        LogHelper::modelChange(
            Product::class,
            'force_deleted',
            $product->id
        );
    }

    /**
     * Clear all product-related caches
     */
    private function clearCache(?Product $product = null): void
    {
        $keys = [
            'products.all_with_relations',
        ];

        foreach ($keys as $key) {
            CacheHelper::forget($key);
        }

        // Clear featured products cache for different limits
        for ($i = 1; $i <= 20; $i++) {
            CacheHelper::forget("products.featured.{$i}");
        }

        // Clear specific product cache if we have the product
        if ($product) {
            CacheHelper::forget("product.{$product->id}");
            
            // Clear product slug cache
            if ($product->slug) {
                CacheHelper::forget("product.slug.{$product->slug}");
            }
            
            // Clear similar products cache
            CacheHelper::forgetByPattern("product.{$product->id}.similar.*");
        }

        // Clear home page caches
        CacheHelper::forget('home.sale_collection');
        CacheHelper::forget('home.sale_collection_images');
        CacheHelper::forget('home.random_collection');

        // Clear collection and search caches
        CacheHelper::forgetByPattern('products.collection.*');
        CacheHelper::forgetByPattern('products.search.*');
        
        LogHelper::cache('cleared', 'products.*');
    }
}
