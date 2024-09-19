<?php

use App\Livewire\AnotherTest;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(AnotherTest::class)
        ->assertStatus(200);
});
