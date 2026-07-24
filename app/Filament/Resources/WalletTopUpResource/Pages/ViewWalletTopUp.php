<?php

namespace App\Filament\Resources\WalletTopUpResource\Pages;

use App\Filament\Resources\WalletTopUpResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewWalletTopUp extends ViewRecord
{
    protected static string $resource = WalletTopUpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
