<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Lunar\Models\Url;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Find product by slug
     */
    public function findBySlug(string $slug): ?Product;

    /**
     * Get products with full relations
     */
    public function getAllWithRelations(): Collection;

    /**
     * Get similar products for a given product
     */
    public function getSimilarProducts(Product $product, int $limit = 4): Collection;

    /**
     * Get featured products
     */
    public function getFeatured(int $limit = 8): Collection;

    /**
     * Search products
     */
    public function search(string $query, int $perPage = 12): LengthAwarePaginator;

    /**
     * Get products by collection
     */
    public function getByCollection(int $collectionId, int $perPage = 12): LengthAwarePaginator;
}
