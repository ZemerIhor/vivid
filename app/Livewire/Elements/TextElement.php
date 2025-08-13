<?php

namespace App\Livewire\Elements;

use Livewire\Component;

class TextElement extends Component
{
    public $content;

    public function mount($content)
    {
        $this->content = $content;
    }

    public function render()
    {
        dd('suka');
        return view('livewire.elements.text-element');
    }
}
