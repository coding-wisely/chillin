<?php

namespace App\Filament\Staff\Resources\LadydrinkResource\Pages;

use App\Filament\Staff\Resources\LadydrinkResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLadydrink extends EditRecord
{
    protected static string $resource = LadydrinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
