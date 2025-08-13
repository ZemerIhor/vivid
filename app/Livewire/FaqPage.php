<?php

namespace App\Livewire;

use App\Settings\FaqSettings;
use Livewire\Component;

class FaqPage extends Component
{
    public function render()
    {
        $settings = app(FaqSettings::class);
        return view('livewire.faq-page', [
            'settings' => $settings,
        ]);
    }
}
