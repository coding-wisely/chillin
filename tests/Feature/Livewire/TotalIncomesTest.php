<?php

use App\Livewire\TotalIncomes;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(TotalIncomes::class)
        ->assertStatus(200);
});
