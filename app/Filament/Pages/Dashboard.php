<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    protected static string $view = 'filament.pages.dashboard';

    public function getColumns(): int | string | array
    {
        return [
            'default' => 1,
            'sm' => 2,
            'md' => 3,
            'lg' => 4,
            'xl' => 6,
            '2xl' => 8,
        ];
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\QuickActionsWidget::class,
            \App\Filament\Widgets\StatsOverview::class,
            \App\Filament\Widgets\OrdersChart::class,
            \App\Filament\Widgets\RevenueChart::class,
            \App\Filament\Widgets\UserActivityWidget::class,
            \App\Filament\Widgets\SystemHealthWidget::class,
            \App\Filament\Widgets\RecentActivityWidget::class,
            \App\Filament\Widgets\LatestOrders::class,
        ];
    }
}
