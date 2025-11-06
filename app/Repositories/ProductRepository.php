<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Lunar\Models\Url;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    /**
     * Find product by slug
     */
    public function findBySlug(string $slug): ?Product
    {
        $locale = app()->getLocale();
        $language = \Lunar\Models\Language::where('code', $locale)->first();
        $languageId = $language ? $language->id : 1;
        
        return Cache::remember("product.slug.{$slug}.{$locale}", 3600, function () use ($slug, $languageId) {
            // Try to find URL for current language
            // Note: Lunar stores element_type as 'product', not full class name
            $url = Url::where('slug', $slug)
                ->where('element_type', 'product')
                ->where('language_id', $languageId)
                ->with([
                    'element.media',
                    'element.variants.basePrices.currency',
                    'element.variants.basePrices.priceable',
                    'element.variants.values.option',
                    'element.collections',
                ])
                ->first();

            // Fallback: check any language
            if (!$url) {
                $url = Url::where('slug', $slug)
                    ->where('element_type', 'product')
                    ->with([
                        'element.media',
                        'element.variants.basePrices.currency',
                        'element.variants.basePrices.priceable',
                        'element.variants.values.option',
                        'element.collections',
                    ])
                    ->first();
            }

            // Fallback: check alternative slugs
            if (!$url) {
                $url = Url::whereIn('slug', [$slug, $slug . 'vfv', str_replace('vfv', '', $slug)])
                    ->where('element_type', 'product')
                    ->with([
                        'element.media',
                        'element.variants.basePrices.currency',
                        'element.variants.basePrices.priceable',
                        'element.variants.values.option',
                        'element.collections',
                    ])
                    ->first();
            }

            return $url?->element;
        });
    }

    /**
     * Get products with full relations
     */
    public function getAllWithRelations(): Collection
    {
        return Cache::remember('products.all_with_relations', 1800, function () {
            return $this->model->with([
                'thumbnail',
                'defaultUrl',
                'variants.basePrices.currency',
                'variants.basePrices.priceable',
                'collections',
            ])->get();
        });
    }

    /**
     * Get similar products for a given product
     */
    public function getSimilarProducts(Product $product, int $limit = 4): Collection
    {
        return Cache::remember("product.{$product->id}.similar.{$limit}", 1800, function () use ($product, $limit) {
            $collections = $product->collections->pluck('id');

            if ($collections->isEmpty()) {
                // If no collections, return random products
                return $this->model->where('id', '!=', $product->id)
                    ->with([
                        'media',
                        'variants.basePrices.currency',
                        'variants.basePrices.priceable',
                        'thumbnail',
                        'defaultUrl',
                    ])
                    ->inRandomOrder()
                    ->limit($limit)
                    ->get();
            }

            // Get products from same collections
            return $this->model->whereHas('collections', function ($query) use ($collections) {
                    $query->whereIn('collection_id', $collections);
                })
                ->where('id', '!=', $product->id)
                ->with([
                    'media',
                    'variants.basePrices.currency',
                    'variants.basePrices.priceable',
                    'thumbnail',
                    'defaultUrl',
                ])
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get featured products
     */
    public function getFeatured(int $limit = 8): Collection
    {
        return Cache::remember("products.featured.{$limit}", 1800, function () use ($limit) {
            return $this->model->with([
                'thumbnail',
                'defaultUrl',
                'variants.basePrices.currency',
                'variants.basePrices.priceable',
            ])
            ->inRandomOrder() // Можно заменить на whereHas('collections', ...) для featured collection
            ->limit($limit)
            ->get();
        });
    }

    /**
     * Search products
     */
    public function search(string $query, int $perPage = 12): LengthAwarePaginator
    {
        $locale = app()->getLocale();
        
        return $this->model->where(function ($q) use ($query, $locale) {
                $q->whereJsonContains("attribute_data->name->{$locale}", $query)
                  ->orWhereJsonContains("attribute_data->description->{$locale}", $query);
            })
            ->with([
                'thumbnail',
                'defaultUrl',
                'variants.basePrices.currency',
                'variants.basePrices.priceable',
            ])
            ->paginate($perPage);
    }

    /**
     * Get products by collection
     */
    public function getByCollection(int $collectionId, int $perPage = 12): LengthAwarePaginator
    {
        return $this->model->whereHas('collections', function ($query) use ($collectionId) {
                $query->where('collection_id', $collectionId);
            })
            ->with([
                'thumbnail',
                'defaultUrl',
                'variants.basePrices.currency',
                'variants.basePrices.priceable',
            ])
            ->paginate($perPage);
    }

    /**
     * Clear cached data
     */
    private function clearCache(): void
    {
        Cache::forget('products.all_with_relations');
        
        // Clear featured products cache for different limits
        for ($i = 1; $i <= 20; $i++) {
            Cache::forget("products.featured.{$i}");
        }
        
        // Clear individual product caches (simplified approach)
        // In production, you might want to use cache tags
    }

    /**
     * Override create to clear cache
     */
    public function create(array $data): \Illuminate\Database\Eloquent\Model
    {
        $product = parent::create($data);
        $this->clearCache();
        return $product;
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
