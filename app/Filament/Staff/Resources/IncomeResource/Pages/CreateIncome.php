<?php

namespace App\Filament\Staff\Resources\IncomeResource\Pages;

use App\Filament\Staff\Resources\IncomeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateIncome extends CreateRecord
{
    protected static string $resource = IncomeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('create');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Income created!';
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $data['received_at'] = now();
        return $data;
    }
}
