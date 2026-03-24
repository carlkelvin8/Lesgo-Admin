<?php

namespace App\Filament\Resources\VehicleResource\Pages;

use App\Filament\Resources\VehicleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVehicles extends ListRecords
{
    protected static string $resource = VehicleResource::class;

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
