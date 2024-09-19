<?php

use App\Livewire\D2e;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(D2e::class)
        ->assertStatus(200);
});
