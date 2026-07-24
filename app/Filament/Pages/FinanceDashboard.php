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

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\Finance\RevenueStatsWidget::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            \App\Filament\Widgets\Finance\RevenueByMonthChart::class,
            \App\Filament\Widgets\Finance\TopPartnersWidget::class,
            \App\Filament\Widgets\Finance\RecentTransactionsWidget::class,
        ];
    }

    public function getFooterWidgetsColumns(): int | array
    {
        return 1;
    }
}
