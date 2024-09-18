<?php

use App\Livewire\TotalExpenses;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(TotalExpenses::class)
        ->assertStatus(200);
});
