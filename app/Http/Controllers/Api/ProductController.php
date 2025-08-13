<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {}

    /**
     * Get all products with relations
     */
    public function index(Request $request): JsonResponse
    {
        $products = $this->productRepository->getAllWithRelations();

        // Apply simple filtering if needed
        if ($request->has('featured')) {
            $limit = min($request->get('limit', 8), 20);
            $products = $this->productRepository->getFeatured($limit);
        }

        return response()->json([
            'data' => $products->map(function ($product) {
                return $this->formatProduct($product);
            }),
            'meta' => [
                'total' => $products->count(),
            ],
        ]);
    }

    /**
     * Get a specific product by slug
     */
    public function show(string $slug): JsonResponse
    {
        $product = $this->productRepository->findBySlug($slug);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found',
            ], 404);
        }

        return response()->json([
            'data' => $this->formatProduct($product, true),
        ]);
    }

    /**
     * Search products
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q');
        $perPage = min($request->get('per_page', 12), 50);

        if (empty($query) || strlen($query) < 2) {
            return response()->json([
                'message' => 'Search query must be at least 2 characters long',
            ], 400);
        }

        $products = $this->productRepository->search($query, $perPage);

        return response()->json([
            'data' => $products->items(),
            'meta' => [
                'query' => $query,
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
        ]);
    }

    /**
     * Get featured products
     */
    public function featured(Request $request): JsonResponse
    {
        $limit = min($request->get('limit', 8), 20);
        $products = $this->productRepository->getFeatured($limit);

        return response()->json([
            'data' => $products->map(function ($product) {
                return $this->formatProduct($product);
            }),
            'meta' => [
                'limit' => $limit,
                'total' => $products->count(),
            ],
        ]);
    }

    /**
     * Get similar products
     */
    public function similar(string $slug, Request $request): JsonResponse
    {
        $product = $this->productRepository->findBySlug($slug);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found',
            ], 404);
        }

        $limit = min($request->get('limit', 4), 10);
        $similar = $this->productRepository->getSimilarProducts($product, $limit);

        return response()->json([
            'data' => $similar->map(function ($product) {
                return $this->formatProduct($product);
            }),
            'meta' => [
                'limit' => $limit,
                'total' => $similar->count(),
                'for_product' => $product->slug,
            ],
        ]);
    }

    /**
     * Get products by collection
     */
    public function byCollection(int $collectionId, Request $request): JsonResponse
    {
        $perPage = min($request->get('per_page', 12), 50);
        $products = $this->productRepository->getByCollection($collectionId, $perPage);

        return response()->json([
            'data' => $products->items(),
            'meta' => [
                'collection_id' => $collectionId,
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
        ]);
    }

    /**
     * Format product data for API response
     */
    private function formatProduct($product, bool $detailed = false): array
    {
        $locale = app()->getLocale();
        
        $data = [
            'id' => $product->id,
            'slug' => $product->slug,
            'name' => $product->translateAttribute('name', $locale),
            'description' => $product->translateAttribute('description', $locale),
            'thumbnail' => $product->thumbnail?->getUrl(),
            'price' => $this->formatPrice($product),
            'created_at' => $product->created_at->toISOString(),
            'updated_at' => $product->updated_at->toISOString(),
        ];

        if ($detailed) {
            $data['images'] = $product->media->map(function ($media) {
                return [
                    'id' => $media->id,
                    'url' => $media->getUrl(),
                    'alt' => $media->getCustomProperty('alt'),
                ];
            });

            $data['variants'] = $product->variants->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'sku' => $variant->sku,
                    'stock' => $variant->stock,
                    'price' => $this->formatVariantPrice($variant),
                ];
            });

            $data['attributes'] = $this->formatAttributes($product);
        }

        return $data;
    }

    /**
     * Format product price
     */
    private function formatPrice($product): ?array
    {
        $variant = $product->variants->first();
        
        if (!$variant) {
            return null;
        }

        return $this->formatVariantPrice($variant);
    }

    /**
     * Format variant price
     */
    private function formatVariantPrice($variant): ?array
    {
        $price = $variant->basePrices->first();
        
        if (!$price) {
            return null;
        }

        return [
            'amount' => $price->price->value,
            'currency' => $price->currency->code,
            'formatted' => $price->price->formatted,
        ];
    }

    /**
     * Format product attributes
     */
    private function formatAttributes($product): array
    {
        $locale = app()->getLocale();
        $attributes = [];

        foreach ($product->attribute_data->keys() as $handle) {
            $value = $product->translateAttribute($handle, $locale);
            
            if ($value && !in_array($handle, ['name', 'description'])) {
                $attributes[$handle] = [
                    'name' => ucwords(str_replace(['_', '-'], ' ', $handle)),
                    'value' => strip_tags($value),
                ];
            }
        }

        return $attributes;
    }
}
