<?php

namespace App\Livewire;

use App\Models\BlogPost;
use App\Settings\HomeSettings;
use Illuminate\View\View;
use Livewire\Component;
use Lunar\Models\Collection;
use App\Models\Product;
use Lunar\Models\Url;

class Home extends Component
{
    /**
     * Return the sale collection.
     */
    public function getSaleCollectionProperty(): Collection | null
    {
        return Url::whereElementType((new Collection)->getMorphClass())->whereSlug('sale')->first()?->element ?? null;
    }

    /**
     * Return all images in sale collection.
     */
    public function getSaleCollectionImagesProperty()
    {
        if (! $this->getSaleCollectionProperty()) {
            return null;
        }

        $collectionProducts = $this->getSaleCollectionProperty()
            ->products()->inRandomOrder()->limit(4)->get();

        $saleImages = $collectionProducts->map(function ($product) {
            return $product->thumbnail;
        });

        return $saleImages->chunk(2);
    }

    /**
     * Return a random collection.
     */
    public function getRandomCollectionProperty(): ?Collection
    {
        $collections = Url::whereElementType((new Collection)->getMorphClass());

        if ($this->getSaleCollectionProperty()) {
            $collections = $collections->where('element_id', '!=', $this->getSaleCollectionProperty()?->id);
        }

        return $collections->inRandomOrder()->first()?->element;
    }

    /**
     * Return up to 6 published blog posts.
     */
    public function getBlogPostsProperty()
    {
        return BlogPost::query()
            ->where('published', true)
            ->whereNotNull('published_at')
            ->orderBy('published_at', 'desc')
            ->take(6)
            ->get();
    }

    public function render(HomeSettings $settings): View
    {
        $products = Product::with(['thumbnail', 'defaultUrl'])->get();

        // Добавляем отладку для проверки slug и attribute_data
        \Log::info('Home::render products', [
            'locale' => app()->getLocale(),
            'products' => $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'slug' => $product->slug,
                    'defaultUrl' => $product->defaultUrl?->toArray(),
                    'attribute_data' => $product->attribute_data,
                ];
            })->toArray(),
        ]);

        return view('livewire.home', [
            'allProducts' => $products,
            'settings' => $settings,
            'blogPosts' => $this->blogPosts,
        ]);
    }
}
