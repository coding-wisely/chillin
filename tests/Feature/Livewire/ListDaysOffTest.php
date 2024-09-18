<?php

use App\Livewire\ListDaysOff;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(ListDaysOff::class)
        ->assertStatus(200);
});
