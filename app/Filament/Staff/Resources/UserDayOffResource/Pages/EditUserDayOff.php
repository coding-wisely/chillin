<?php

namespace App\Filament\Staff\Resources\UserDayOffResource\Pages;

use App\Filament\Staff\Resources\UserDayOffResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserDayOff extends EditRecord
{
    protected static string $resource = UserDayOffResource::class;

    public function getTitle(): string
    {
        return __('custom.Edit Day off');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
