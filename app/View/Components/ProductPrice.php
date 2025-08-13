<?php

namespace App\View\Components;

use Exception;
use Illuminate\View\Component;
use Illuminate\View\View;
use Lunar\Facades\Pricing;
use Lunar\Models\Price;
use Lunar\Models\ProductVariant;

class ProductPrice extends Component
{
    public ?Price $price = null;
    public ?ProductVariant $variant = null;

    /**
     * Create a new component instance.
     */
    public function __construct($product = null, $variant = null)
    {
        // Check if a valid variant is available
        $targetVariant = $variant ?: ($product->variants->first() ?? null);

        if ($targetVariant) {
            try {
                $this->price = Pricing::for($targetVariant)->get()->matched;
                $this->variant = $targetVariant;
            } catch (\Lunar\Exceptions\MissingCurrencyPriceException) {
                // Handle the missing price gracefully (e.g., set price to null)
                $this->price = null;
            }
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.product-price');
    }
}
