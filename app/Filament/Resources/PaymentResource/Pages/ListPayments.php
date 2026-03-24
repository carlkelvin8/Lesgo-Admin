<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use Filament\Resources\Pages\ListRecords;

class ListPayments extends ListRecords
{
    protected static string $resource = PaymentResource::class;

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
