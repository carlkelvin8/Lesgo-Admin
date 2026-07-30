<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\User;
use App\Models\Partner;
use App\Models\Payment;
use App\Models\SupportTicket;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('Registered users')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Active Partners', Partner::where('status', 'active')->count())
                ->description('Verified merchants')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('success'),

            Stat::make('Total Orders', Order::count())
                ->description('All time')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info'),

            Stat::make('Pending Orders', Order::where('status', 'pending')->count())
                ->description('Needs attention')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make("Today's Revenue", 'PHP ' . number_format(Payment::where('status', 'paid')->whereDate('created_at', today())->sum('amount'), 2))
                ->description('Earnings today')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Active Drivers', \App\Models\DriverProfile::where('status', 'active')->count())
                ->description('Available riders')
                ->descriptionIcon('heroicon-m-truck')
                ->color('primary'),
            Stat::make('Open Tickets', SupportTicket::whereIn('status', ['open', 'in_progress'])->count())
                ->description('Needs attention')
                ->descriptionIcon('heroicon-m-lifebuoy')
                ->color('danger'),
        ];
    }
}
