<?php

use App\Livewire\TotalSaldo;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(TotalSaldo::class)
        ->assertStatus(200);
});
