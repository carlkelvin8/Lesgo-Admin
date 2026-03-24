<?php

namespace App\Filament\Resources\PartnerResource\Pages;

use App\Filament\Resources\PartnerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPartners extends ListRecords
{
    protected static string $resource = PartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [10, 25, 50, 100];
    }

    protected function getTableFiltersLayout(): ?string
    {
        return \Filament\Tables\Enums\FiltersLayout::AboveContent;
    }

    protected function getTableDeferredLoading(): bool
    {
        return true;
    }

    protected function getGlobalSearchDebounce(): string
    {
        return '500ms';
    }
}
