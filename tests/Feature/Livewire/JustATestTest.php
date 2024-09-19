<?php

use App\Livewire\JustATest;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(JustATest::class)
        ->assertStatus(200);
});
