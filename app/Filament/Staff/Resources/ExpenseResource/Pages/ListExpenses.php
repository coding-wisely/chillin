<?php

namespace App\Filament\Staff\Resources\ExpenseResource\Pages;

use App\Filament\Staff\Resources\ExpenseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;

class ListExpenses extends ListRecords
{
    protected static string $resource = ExpenseResource::class;

    public function getTitle(): string|Htmlable
    {
        return __('custom.Expense'); // TODO: Change the autogenerated stub
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label(__('custom.Add Expense')),
        ];
    }
}
