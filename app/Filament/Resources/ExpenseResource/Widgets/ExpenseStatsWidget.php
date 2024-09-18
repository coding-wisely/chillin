<?php

namespace App\Filament\Resources\ExpenseResource\Widgets;

use App\Models\Category;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ExpenseStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $categories = Category::all();
        $stats = [];
        foreach ($categories as $category) {
            $category->expenses = \Number::currency($category->expenses()->sum('amount'),'THB', 'th');

            $stats[] = Stat::make($category->title, $category->expenses ??0);
        }
        return $stats;
    }
}
