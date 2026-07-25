<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Orders', Order::count())
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),
            Stat::make('Pending', Order::where('status', 'pending')->count())
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            Stat::make('Completed', Order::where('status', 'completed')->count())
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Cancelled', Order::where('status', 'cancelled')->count())
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
        ];
    }
}
