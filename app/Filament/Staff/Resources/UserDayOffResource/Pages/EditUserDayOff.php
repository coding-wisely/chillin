<?php

namespace App\Filament\Staff\Resources\UserDayOffResource\Pages;

use App\Filament\Staff\Resources\UserDayOffResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserDayOff extends EditRecord
{
    protected static string $resource = UserDayOffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
