<?php

namespace App\Livewire;

use App\Repositories\ProductRepositoryInterface;
use App\Traits\FetchesUrls;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use Lunar\Models\Cart;
use Lunar\Models\Product;
use Lunar\Models\ProductVariant;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductPage extends Component
{
    use FetchesUrls;

    public array $selectedOptionValues = [];
    public int $quantity = 1;
    public $slug;
    protected ?Product $product = null;
    private ProductRepositoryInterface $productRepository;

    public function boot(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function mount($slug): void
    {
        $this->slug = $slug;
        $this->loadProduct();
    }

    public function hydrate()
    {
        $this->loadProduct();
    }

    protected function loadProduct(): void
    {
        if (!$this->product || !$this->product->exists) {
            $this->product = $this->productRepository->findBySlug($this->slug);
            
            if (!$this->product) {
                abort(404, 'Product not found');
            }

            // Initialize selected option values only on first load
            if (empty($this->selectedOptionValues) && $this->productOptions->count() > 0) {
                $this->selectedOptionValues = $this->productOptions->mapWithKeys(function ($data) {
                    return [$data['option']->id => $data['values']->first()->id];
                })->toArray();
            }
        }
    }
    public function getVariantProperty(): ProductVariant
    {
        return $this->product->variants->first(function ($variant) {
            return !$variant->values->pluck('id')
                ->diff(
                    collect($this->selectedOptionValues)->values()
                )->count();
        });
    }

    public function getProductOptionValuesProperty(): Collection
    {
        return $this->product->variants->pluck('values')->flatten();
    }

    public function getProductOptionsProperty(): Collection
    {
        return $this->productOptionValues->unique('id')->groupBy('product_option_id')
            ->map(function ($values) {
                return [
                    'option' => $values->first()->option,
                    'values' => $values,
                ];
            })->values();
    }

    public function getProductProperty(): Product
    {
        return $this->product;
    }

    public function getImagesProperty(): Collection
    {
        return $this->product->media->sortBy('order_column');
    }

    public function getImageProperty(): ?Media
    {
        if (count($this->variant->images)) {
            return $this->variant->images->first();
        }

        if ($primary = $this->images->first(fn ($media) => $media->getCustomProperty('primary'))) {
            return $primary;
        }

        return $this->images->first();
    }
    public function getAttributesProperty(): array
    {
        $locale = app()->getLocale();
        $attributes = [];

        // Список атрибутов, которые нужно пропустить
        $excludedAttributes = ['name', 'description'];

        // Перебираем все ключи из attribute_data, исключая name и description
        foreach ($this->product->attribute_data->keys()->filter(fn($handle) => !in_array($handle, $excludedAttributes)) as $handle) {
            // Получаем значение атрибута для текущей локали
            $value = $this->product->translateAttribute($handle, $locale) ?: 'N/A';

            // Динамически формируем читаемое имя атрибута
            $name = ucwords(str_replace(['_', '-'], ' ', $handle));

            // Если есть модель Attribute с переводами имен, используем её
            if ($attributeModel = \Lunar\Models\Attribute::where('handle', $handle)->first()) {
                $name = $attributeModel->name[$locale] ?? $attributeModel->name['en'] ?? $name;
            }

            $attributes[$handle] = [
                'name' => $name,
                'value' => strip_tags($value), // Убираем HTML-теги
            ];
        }
        return $attributes;
    }

    public function getSimilarProductsProperty(): Collection
    {
        return $this->productRepository->getSimilarProducts($this->product, 4);
    }

    public function incrementQuantity(): void
    {
        $this->quantity++;
    }

    public function decrementQuantity(): void
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function buyNow(): \Illuminate\Http\RedirectResponse
    {
        Cart::add($this->variant, $this->quantity);
        return redirect()->route('checkout');
    }

    public function render(): View
    {
        return view('livewire.product-page', [
            'product' => $this->product,
            'variant' => $this->variant,
            'images' => $this->images,
            'image' => $this->image,
            'attributes' => $this->attributes,
            'similarProducts' => $this->similarProducts,
        ]);
    }
}
