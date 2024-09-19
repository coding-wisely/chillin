<?php

use App\Livewire\Jokes;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Jokes::class)
        ->assertStatus(200);
});
