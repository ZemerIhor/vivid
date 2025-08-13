<?php

namespace App\Livewire;
use Lunar\Models\Url;
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
    public function mount($slug): void
    {
        // Find the URL record for the given slug
        $url = Url::where('slug', $slug)
            ->where('element_type', (new Product)->getMorphClass())
            ->first();

        if (!$url) {
            // Check if the slug exists in any language
            $url = Url::whereIn('slug', [$slug, $slug . 'vfv']) // Handle both English and Ukrainian slugs
            ->where('element_type', (new Product)->getMorphClass())
                ->first();

            if (!$url) {
                abort(404);
            }
        }

        // Fetch product with relations
        $this->url = $this->fetchUrl(
            $url->slug,
            (new Product)->getMorphClass(),
            [
                'element.media',
                'element.variants.basePrices.currency',
                'element.variants.basePrices.priceable',
                'element.variants.values.option',
            ]
        );

        if (!$this->url) {
            abort(404);
        }

        $this->slug = $slug;

        // Initialize selected option values
        $this->selectedOptionValues = $this->productOptions->mapWithKeys(function ($data) {
            return [$data['option']->id => $data['values']->first()->id];
        })->toArray();
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
        return $this->url->element;
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
        if (!$this->product->category) {
            return collect([]);
        }

        return $this->product->category->products()
            ->where('id', '!=', $this->product->id)
            ->with([
                'media',
                'variants.basePrices.currency',
                'variants.basePrices.priceable',
            ])
            ->take(4)
            ->get();
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
