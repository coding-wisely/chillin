<?php

namespace App\Filament\Resources\ExpenseResource\Pages;

use App\Filament\Resources\ExpenseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Concerns\HasFilters;

class ListExpenses extends ListRecords
{
    use HasFilters;
    protected static string $resource = ExpenseResource::class;


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            ExpenseResource\Widgets\ExpenseStatsWidget::class
        ];
    }
}
