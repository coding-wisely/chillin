<?php

namespace App\Filament\Staff\Resources\LadydrinkResource\Pages;

use App\Filament\Staff\Resources\LadydrinkResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLadydrinks extends ListRecords
{
    protected static string $resource = LadydrinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
