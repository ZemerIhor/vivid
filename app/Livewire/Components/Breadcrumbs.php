<?php

namespace App\Livewire\Components;

use Illuminate\View\View;
use Livewire\Component;


class Breadcrumbs extends Component
{
    public string $currentPage;
    public array $items;

    public function __construct(string $currentPage = '', array $items = [])
    {
        $this->currentPage = $currentPage;
        $this->items = $items;
    }

    public function render():View
    {
        return view('livewire.components.breadcrumbs');
    }
}
