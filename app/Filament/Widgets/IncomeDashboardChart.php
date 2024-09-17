<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class IncomeDashboardChart extends ChartWidget
{
    protected static ?string $heading = 'Income';
    protected static ?int $sort = 3;


    protected function getData(): array
    {
        return [

        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
