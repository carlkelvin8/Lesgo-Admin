<?php

namespace App\Filament\Widgets\Finance;

use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RevenueStatsWidget extends BaseWidget
{
    protected static bool $isLazy = true;
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $totalRevenue = Payment::where('status', 'paid')->sum('amount') ?? 0;

        $todayRevenue = Payment::where('status', 'paid')
            ->whereDate('created_at', today())
            ->sum('amount') ?? 0;

        $thisMonthRevenue = Payment::where('status', 'paid')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('amount') ?? 0;

        $monthTotalTransactions = Payment::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        return [
            Stat::make('Total Revenue', '₱' . number_format($totalRevenue, 2))
                ->description('All-time earnings')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('This Month', '₱' . number_format($thisMonthRevenue, 2))
                ->description('Monthly earnings')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('primary'),

            Stat::make("Today's Revenue", '₱' . number_format($todayRevenue, 2))
                ->description('Earnings today')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('info'),

            Stat::make("Transactions", number_format($monthTotalTransactions))
                ->description('This month')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('warning'),
        ];
    }
}
