<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Lunar\Models\Brand;
use Lunar\Models\Currency;
use Lunar\Models\Product;
use Lunar\Models\Url;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Illuminate\View\View;

class CatalogPage extends Component
{
    use WithPagination;

    public $perPage = 12;
    public $brands = [];
    public $priceMax = null;
    public $sort = 'name_asc';
    public $view = 'grid';
    public $locale;
    public $currency;
    public $peatTypes = [];
    public $productWeights = [];

    public function mount(): void
    {
        $this->locale = app()->getLocale();
        $this->currency = Currency::where('code', 'UAH')->first() ?? Currency::first();

        $this->priceMax = Request::query('price_max') ? (float) Request::query('price_max') * 100 : null;
        $this->brands = Request::query('brands', []);
        $this->sort = Request::query('sort', 'name_asc');
        $this->view = Request::query('view', 'grid');
        $this->peatTypes = Request::query('peat_types', []);
        $this->productWeights = Request::query('product_weights', []);

        Log::info('Catalog Page Mounted', [
            'locale' => $this->locale,
            'currency' => $this->currency ? $this->currency->code : 'not found',
            'minPrice' => $this->priceRange['min'],
            'maxPrice' => $this->priceRange['max'],
            'query_params' => [
                'price_max' => $this->priceMax,
                'brands' => $this->brands,
                'sort' => $this->sort,
                'view' => $this->view,
            ],
        ]);
    }

    public function applyFilters()
    {
        if ($this->priceMax !== null) {
            $this->priceMax = max(0, (float) $this->priceMax);
            if ($this->priceMax > $this->priceRange['max'] * 100) {
                $this->priceMax = $this->priceRange['max'] * 100;
            }
        }

        $this->resetPage();
        $this->updateUrl();

        Log::info('Filters Applied', [
            'brands' => $this->brands,
            'priceMax' => $this->priceMax,
            'sort' => $this->sort,
            'view' => $this->view,
        ]);
    }

    public function removeBrand($id)
    {
        $this->brands = array_diff($this->brands, [$id]);
        $this->updateUrl();
    }

    public function clearPrice()
    {
        $this->priceMax = null;
        $this->updateUrl();
    }

    public function clearAllFilters()
    {
        $this->brands = [];
        $this->priceMax = null;
        $this->peatTypes = [];
        $this->productWeights = [];
        $this->updateUrl();
    }
    
    public function removePeatType($id)
    {
        $this->peatTypes = array_diff($this->peatTypes, [$id]);
        $this->updateUrl();
    }
    
    public function removeProductWeight($id)
    {
        $this->productWeights = array_diff($this->productWeights, [$id]);
        $this->updateUrl();
    }

    public function setView($view)
    {
        $this->view = $view;
        $this->updateUrl();
        Log::info('View Changed', ['view' => $this->view]);
    }

    public function setSort($sort)
    {
        $this->sort = $sort;
        $this->updateUrl();
        Log::info('Sort Changed', ['sort' => $this->sort]);
    }

    protected function updateUrl()
    {
        $query = array_filter([
            'price_max' => $this->priceMax ? $this->priceMax / 100 : null,
            'brands' => !empty($this->brands) ? $this->brands : null,
            'peat_types' => !empty($this->peatTypes) ? $this->peatTypes : null,
            'product_weights' => !empty($this->productWeights) ? $this->productWeights : null,
            'sort' => $this->sort !== 'name_asc' ? $this->sort : null,
            'view' => $this->view !== 'grid' ? $this->view : null,
        ]);

        $url = route('catalog.view', ['locale' => $this->locale], false);
        if (!empty($query)) {
            $url .= '?' . http_build_query($query);
        }

        $this->redirect($url, navigate: true);
    }

    public function getProductsProperty()
    {
        $productsQuery = Product::where('status', 'published')
            ->with(['variants', 'thumbnail', 'brand', 'variants.prices', 'urls' => function ($query) {
                $query->where('language_id', \Lunar\Models\Language::where('code', $this->locale)->first()->id ?? 1);
            }]);

        if (!empty($this->brands)) {
            $productsQuery->whereIn('brand_id', $this->brands);
        }

        if (!empty($this->peatTypes)) {
            $productsQuery->whereIn('peat_type_id', $this->peatTypes);
        }

        if (!empty($this->productWeights)) {
            $productsQuery->whereIn('product_weight_id', $this->productWeights);
        }

        if ($this->priceMax !== null) {
            $productsQuery->whereHas('variants', function ($query) {
                $query->whereHas('prices', function ($priceQuery) {
                    $priceQuery->where('currency_id', $this->currency->id)
                        ->where('price', '<=', (float) $this->priceMax);
                });
            });
            Log::info('Price Filter Applied', ['priceMax' => $this->priceMax]);
        }

        switch ($this->sort) {
            case 'name_asc':
                $productsQuery->orderByRaw("JSON_UNQUOTE(JSON_EXTRACT(attribute_data, '$.name.value.{$this->locale}')) ASC");
                break;
            case 'name_desc':
                $productsQuery->orderByRaw("JSON_UNQUOTE(JSON_EXTRACT(attribute_data, '$.name.value.{$this->locale}')) DESC");
                break;
            case 'price_asc':
                $productsQuery->select('lunar_products.*')
                    ->leftJoin('lunar_product_variants', 'lunar_products.id', '=', 'lunar_product_variants.product_id')
                    ->leftJoin('lunar_prices', function ($join) {
                        $join->on('lunar_product_variants.id', '=', 'lunar_prices.priceable_id')
                            ->where('lunar_prices.priceable_type', 'Lunar\Models\ProductVariant')
                            ->where('lunar_prices.currency_id', '=', $this->currency->id);
                    })
                    ->groupBy('lunar_products.id')
                    ->orderByRaw('COALESCE(MIN(lunar_prices.price), 999999999) ASC');
                break;
            case 'price_desc':
                $productsQuery->select('lunar_products.*')
                    ->leftJoin('lunar_product_variants', 'lunar_products.id', '=', 'lunar_product_variants.product_id')
                    ->leftJoin('lunar_prices', function ($join) {
                        $join->on('lunar_product_variants.id', '=', 'lunar_prices.priceable_id')
                            ->where('lunar_prices.priceable_type', 'Lunar\Models\ProductVariant')
                            ->where('lunar_prices.currency_id', '=', $this->currency->id);
                    })
                    ->groupBy('lunar_products.id')
                    ->orderByRaw('COALESCE(MAX(lunar_prices.price), 0) DESC');
                break;
        }

        $products = $productsQuery->distinct()->paginate($this->perPage);

        Log::info('Products Retrieved', [
            'total' => $products->total(),
            'current_page' => $products->currentPage(),
            'items' => array_map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->translateAttribute('name'),
                    'slug' => $product->urls->first() ? $product->urls->first()->slug : 'product-' . $product->id,
                    'prices' => $product->variants->map(function ($variant) {
                        return $variant->prices->map(function ($price) {
                            return [
                                'currency_id' => $price->currency_id,
                                'price' => $price->price,
                            ];
                        })->toArray();
                    })->toArray(),
                ];
            }, $products->items()),
        ]);

        return $products;
    }

    public function getAvailableBrandsProperty()
    {
        return Brand::whereHas('products')->get();
    }

    public function getAvailablePeatTypesProperty()
    {
        return \App\Models\PeatType::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function getAvailableProductWeightsProperty()
    {
        return \App\Models\ProductWeight::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function getPriceRangeProperty()
    {
        return Cache::remember('price_range_' . $this->currency->id, now()->addHours(1), function () {
            $minPrice = Product::where('status', 'published')
                ->join('lunar_product_variants', 'lunar_products.id', '=', 'lunar_product_variants.product_id')
                ->join('lunar_prices', function ($join) {
                    $join->on('lunar_product_variants.id', '=', 'lunar_prices.priceable_id')
                        ->where('lunar_prices.priceable_type', 'Lunar\Models\ProductVariant')
                        ->where('lunar_prices.currency_id', '=', $this->currency->id);
                })
                ->min('lunar_prices.price') ?? 4200;

            $maxPrice = Product::where('status', 'published')
                ->join('lunar_product_variants', 'lunar_products.id', '=', 'lunar_product_variants.product_id')
                ->join('lunar_prices', function ($join) {
                    $join->on('lunar_product_variants.id', '=', 'lunar_prices.priceable_id')
                        ->where('lunar_prices.priceable_type', 'Lunar\Models\ProductVariant')
                        ->where('lunar_prices.currency_id', '=', $this->currency->id);
                })
                ->max('lunar_prices.price') ?? 15000;

            Log::info('Price Range Calculated', [
                'minPrice' => $minPrice,
                'maxPrice' => $maxPrice,
            ]);

            return [
                'min' => $minPrice / 100,
                'max' => $maxPrice / 100,
            ];
        });
    }

    public function render(): View
    {
        try {
            $products = $this->products;
            Log::info('Catalog Page Rendering', [
                'products_count' => $products->total(),
                'filters' => [
                    'brands' => $this->brands,
                    'priceMax' => $this->priceMax,
                    'sort' => $this->sort,
                    'view' => $this->view,
                ],
            ]);

            return view('livewire.catalog-page', [
                'products' => $products,
                'availableBrands' => $this->availableBrands,
                'minPrice' => $this->priceRange['min'],
                'maxPrice' => $this->priceRange['max'],
                'locale' => $this->locale,
                'currency' => $this->currency,
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading Catalog Page', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'filters' => [
                    'brands' => $this->brands,
                    'priceMax' => $this->priceMax,
                    'sort' => $this->sort,
                    'view' => $this->view,
                ],
            ]);

            $products = Product::where('status', 'published')->paginate($this->perPage);

            return view('livewire.catalog-page', [
                'products' => $products,
                'availableBrands' => Brand::whereHas('products')->get(),
                'minPrice' => 42,
                'maxPrice' => 150,
                'locale' => $this->locale,
                'currency' => $this->currency,
                'error' => __('messages.catalog.error') . ': ' . $e->getMessage(),
            ])->with('error', __('messages.catalog.error') . ': ' . $e->getMessage());
        }
    }
}
