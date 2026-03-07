<?php

namespace App\Filament\Resources\DriverProfileResource\Pages;

use App\Filament\Resources\DriverProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDriverProfile extends EditRecord
{
    protected static string $resource = DriverProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()->requiresConfirmation(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
