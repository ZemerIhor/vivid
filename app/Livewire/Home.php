<?php

namespace App\Livewire;

use App\Repositories\BlogPostRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;
use App\Settings\HomeSettings;
use Illuminate\View\View;
use Livewire\Component;
use Lunar\Models\Collection;
use Lunar\Models\Url;
use Illuminate\Support\Facades\Cache;

class Home extends Component
{
    private BlogPostRepositoryInterface $blogPostRepository;
    private ProductRepositoryInterface $productRepository;

    public function boot(
        BlogPostRepositoryInterface $blogPostRepository,
        ProductRepositoryInterface $productRepository
    ) {
        $this->blogPostRepository = $blogPostRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * Return the sale collection.
     */
    public function getSaleCollectionProperty(): Collection | null
    {
        return Cache::remember('home.sale_collection', 3600, function () {
            return Url::whereElementType((new Collection)->getMorphClass())
                ->whereSlug('sale')
                ->first()?->element;
        });
    }

    /**
     * Return all images in sale collection.
     */
    public function getSaleCollectionImagesProperty()
    {
        if (! $this->getSaleCollectionProperty()) {
            return null;
        }

        return Cache::remember('home.sale_collection_images', 1800, function () {
            $collectionProducts = $this->getSaleCollectionProperty()
                ->products()
                ->with('thumbnail')
                ->inRandomOrder()
                ->limit(4)
                ->get();

            $saleImages = $collectionProducts->map(function ($product) {
                return $product->thumbnail;
            });

            return $saleImages->chunk(2);
        });
    }

    /**
     * Return a random collection.
     */
    public function getRandomCollectionProperty(): ?Collection
    {
        return Cache::remember('home.random_collection', 1800, function () {
            $collections = Url::whereElementType((new Collection)->getMorphClass());

            if ($this->getSaleCollectionProperty()) {
                $collections = $collections->where('element_id', '!=', $this->getSaleCollectionProperty()?->id);
            }

            return $collections->inRandomOrder()->first()?->element;
        });
    }

    /**
     * Return up to 6 published blog posts.
     */
    public function getBlogPostsProperty()
    {
        return $this->blogPostRepository->getRecent(6);
    }

    public function render(HomeSettings $settings): View
    {
        $products = $this->productRepository->getAllWithRelations();

        return view('livewire.home', [
            'allProducts' => $products,
            'settings' => $settings,
            'blogPosts' => $this->blogPosts,
        ]);
    }
}
