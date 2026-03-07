<?php

namespace App\Filament\Resources\DriverProfileResource\Pages;

use App\Filament\Resources\DriverProfileResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDriverProfile extends CreateRecord
{
    protected static string $resource = DriverProfileResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
