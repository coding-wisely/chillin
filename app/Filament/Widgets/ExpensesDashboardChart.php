<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class ExpensesDashboardChart extends ChartWidget
{
    protected static ?string $heading = 'Expenses';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
