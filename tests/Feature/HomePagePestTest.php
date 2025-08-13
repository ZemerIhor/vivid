<?php

use Illuminate\Support\Facades\Schema;

it('shows home livewire components', function () {
    $this->get('/')
        ->assertStatus(200)
        ->assertSeeLivewire('home')
        ->assertSeeLivewire('components.navigation');
})->skip(fn () => ! Schema::hasTable('lunar_products'), 'Skipping: database tables are not available in this environment.');
