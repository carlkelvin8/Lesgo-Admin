<?php

namespace App\Filament\Resources\DriverProfileResource\Pages;

use App\Filament\Resources\DriverProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDriverProfile extends ViewRecord
{
    protected static string $resource = DriverProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make()->requiresConfirmation(),
        ];
    }
}
