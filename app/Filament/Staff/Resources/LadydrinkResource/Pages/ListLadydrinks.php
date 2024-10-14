<?php

namespace App\Filament\Staff\Resources\LadydrinkResource\Pages;

use App\Filament\Staff\Resources\LadydrinkResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLadydrinks extends ListRecords
{
    protected static string $resource = LadydrinkResource::class;

    public function getTitle(): string
    {
        return __('custom.Ladydrink');
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label(__('custom.Add Ladydrink')),
        ];
    }
}
