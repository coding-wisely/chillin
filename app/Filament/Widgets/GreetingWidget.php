<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Foundation\Inspiring;

class GreetingWidget extends Widget
{
    protected static string $view = 'filament.widgets.greeting-widget';

    protected int|string|array $columnSpan = 'full';

    public string $hello;

    public function __construct()
    {
        $this->hello = Inspiring::quote();
    }
}
