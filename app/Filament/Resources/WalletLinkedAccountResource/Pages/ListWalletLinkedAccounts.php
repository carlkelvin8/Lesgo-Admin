<?php

namespace App\Filament\Resources\WalletLinkedAccountResource\Pages;

use App\Filament\Resources\WalletLinkedAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWalletLinkedAccounts extends ListRecords
{
    protected static string $resource = WalletLinkedAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
