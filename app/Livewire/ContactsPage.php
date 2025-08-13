<?php

namespace App\Livewire;

use App\Settings\ContactSettings;
use Livewire\Component;

class ContactsPage extends Component
{
    public function render()
    {
        $settings = app(ContactSettings::class);

        return view('livewire.contacts-page', [
            'settings' => $settings,
        ]);
    }
}
