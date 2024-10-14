<?php

namespace App\Filament\Staff\Resources\LadydrinkResource\Pages;

use App\Filament\Staff\Resources\LadydrinkResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLadydrink extends CreateRecord
{
    protected static string $resource = LadydrinkResource::class;
    public function getTitle(): string
    {
        return __('custom.Add Ladydrink');
    }
}
