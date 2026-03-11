<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class FinanceDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    
    protected static ?string $navigationLabel = 'Finance Dashboard';
    
    protected static ?string $title = 'Finance Dashboard';
    
    protected static ?string $navigationGroup = 'Finance';
    
    protected static ?int $navigationSort = 0;

    protected static string $view = 'filament.pages.finance-dashboard';

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

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\Finance\RevenueStatsWidget::class,
            \App\Filament\Widgets\Finance\RevenueByMonthChart::class,
            \App\Filament\Widgets\Finance\PaymentMethodsChart::class,
            \App\Filament\Widgets\Finance\TopPartnersWidget::class,
            \App\Filament\Widgets\Finance\RecentTransactionsWidget::class,
        ];
    }
}
