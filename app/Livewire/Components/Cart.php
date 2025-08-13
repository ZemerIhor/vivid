<?php

namespace App\Livewire\Components;

use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use Lunar\Facades\CartSession;

class Cart extends Component
{
    /**
     * The editable cart lines.
     */
    public array $lines;

    public bool $linesVisible = false;

    protected $listeners = [
        'add-to-cart' => 'handleAddToCart',
    ];

    public function rules(): array
    {
        return [
            'lines.*.quantity' => 'required|numeric|min:1|max:10000',
        ];
    }

    public function mount(): void
    {
        $this->mapLines();
    }

    /**
     * Get the current cart instance.
     */
    public function getCartProperty()
    {
        return CartSession::current();
    }

    /**
     * Return the cart lines from the cart.
     */
    public function getCartLinesProperty(): Collection
    {
        return $this->cart->lines ?? collect();
    }

    /**
     * Update the cart lines.
     */
    public function updateLines(): void
    {
        $this->validate();

        CartSession::updateLines(
            collect($this->lines)
        );
        $this->mapLines();
        $this->dispatch('cartUpdated');
    }

    /**
     * Increment the quantity of a cart line.
     */
    public function incrementQuantity($index): void
    {
        if (isset($this->lines[$index])) {
            $this->lines[$index]['quantity']++;
            $this->updateLines();
        }
    }

    /**
     * Decrement the quantity of a cart line.
     */
    public function decrementQuantity($index): void
    {
        if (isset($this->lines[$index]) && $this->lines[$index]['quantity'] > 1) {
            $this->lines[$index]['quantity']--;
            $this->updateLines();
        }
    }

    /**
     * Remove a cart line.
     */
    public function removeLine($id): void
    {
        CartSession::remove($id);
        $this->mapLines();
        $this->dispatch('cartUpdated');
    }

    /**
     * Map the cart lines.
     */
    public function mapLines(): void
    {
        $this->lines = $this->cartLines->map(function ($line) {
            return [
                'id' => $line->id,
                'identifier' => $line->purchasable->getIdentifier(),
                'quantity' => $line->quantity,
                'description' => $line->purchasable->getDescription(),
                'thumbnail' => $line->purchasable->getThumbnail()?->getUrl(),
                'option' => $line->purchasable->getOption(),
                'options' => $line->purchasable->getOptions()->implode(' / '),
                'sub_total' => $line->subTotal->formatted(),
                'unit_price' => $line->unitPrice->formatted(),
            ];
        })->toArray();
    }

    /**
     * Handle add-to-cart event.
     */
    public function handleAddToCart(): void
    {
        $this->mapLines();
        $this->linesVisible = true;
        $this->dispatch('cartUpdated');
    }

    public function render(): View
    {
        return view('livewire.components.cart');
    }
}
