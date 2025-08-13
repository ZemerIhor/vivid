<?php

namespace App\Livewire;

use App\Settings\AboutUsSettings;
use Livewire\Component;

class AboutUsPage extends Component
{
    public function render()
    {
        $settings = app(AboutUsSettings::class);
        return view('livewire.about-us-page', [
            'settings' => $settings,
        ]);
    }
}
