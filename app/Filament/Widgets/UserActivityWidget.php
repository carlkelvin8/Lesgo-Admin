<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserActivityWidget extends BaseWidget
{
    protected static ?int $sort = 5;
    
    protected int | string | array $columnSpan = [
        'default' => 1,
        'sm' => 2,
        'md' => 3,
        'lg' => 4,
        'xl' => 6,
        '2xl' => 8,
    ];

    protected function getStats(): array
    {
        $totalUsers = User::count();
        $newUsersToday = User::whereDate('created_at', today())->count();
        $newUsersThisWeek = User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $verifiedUsers = User::whereNotNull('email_verified_at')->count();
        $bannedUsers = User::where('is_banned', true)->count();
        $activeDrivers = User::where('role', 'driver')
            ->whereHas('driverProfile', fn($q) => $q->where('status', 'active'))
            ->count();

        return [
            Stat::make('Total Users', $totalUsers)
                ->description('All registered users')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->chart([50, 60, 70, 80, 90, 100, $totalUsers]),
            
            Stat::make('New Today', $newUsersToday)
                ->description('Registered today')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('success'),
            
            Stat::make('New This Week', $newUsersThisWeek)
                ->description('Last 7 days')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),
            
            Stat::make('Verified Users', $verifiedUsers)
                ->description(round(($verifiedUsers / max($totalUsers, 1)) * 100, 1) . '% verified')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
            
            Stat::make('Banned Users', $bannedUsers)
                ->description('Currently banned')
                ->descriptionIcon('heroicon-m-no-symbol')
                ->color('danger'),
            
            Stat::make('Active Drivers', $activeDrivers)
                ->description('Available for orders')
                ->descriptionIcon('heroicon-m-truck')
                ->color('warning'),
        ];
    }
}
