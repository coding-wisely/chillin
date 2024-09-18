<?php

use App\Livewire\ListLadyDrinks;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(ListLadyDrinks::class)
        ->assertStatus(200);
});
