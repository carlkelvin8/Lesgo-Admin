<?php

namespace App\Filament\Resources\WalletLinkedAccountResource\Pages;

use App\Filament\Resources\WalletLinkedAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewWalletLinkedAccount extends ViewRecord
{
    protected static string $resource = WalletLinkedAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
