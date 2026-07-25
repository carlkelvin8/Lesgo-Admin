<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            OrderResource\Widgets\OrderStatsWidget::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            OrderResource\Widgets\DailyOrdersChart::class,
            OrderResource\Widgets\OrderStatusChart::class,
        ];
    }

    public function getFooterWidgetsColumns(): int | array
    {
        return 2;
    }
}
