<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.dashboard';

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\StatsOverview::class,
            \App\Filament\Widgets\OrdersOverviewChart::class,
            \App\Filament\Widgets\RevenueChart::class,
            \App\Filament\Widgets\DriverPerformanceChart::class,
            \App\Filament\Widgets\PaymentMethodsChart::class,
            \App\Filament\Widgets\UserGrowthChart::class,
            \App\Filament\Widgets\ServiceDemandChart::class,
            \App\Filament\Widgets\PartnerPerformanceChart::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return [
            'md' => 2,
            'xl' => 3,
        ];
    }
}
