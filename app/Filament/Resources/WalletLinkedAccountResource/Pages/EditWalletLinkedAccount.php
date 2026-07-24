<?php

namespace App\Filament\Resources\WalletLinkedAccountResource\Pages;

use App\Filament\Resources\WalletLinkedAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWalletLinkedAccount extends EditRecord
{
    protected static string $resource = WalletLinkedAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
