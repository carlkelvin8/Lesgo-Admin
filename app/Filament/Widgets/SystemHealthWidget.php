<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Partner;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SystemHealthWidget extends BaseWidget
{
    protected static bool $isLazy = true;
    protected static ?int $sort = 6;

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
        return [
            Stat::make('Pending Orders', Order::where('status', 'pending')->count())
                ->color('warning')
                ->descriptionIcon('heroicon-m-clock'),
            Stat::make('Failed Payments', Payment::where('status', 'failed')->count())
                ->color('danger')
                ->descriptionIcon('heroicon-m-x-circle'),
            Stat::make('Suspended Partners', Partner::where('status', 'suspended')->count())
                ->color('danger')
                ->descriptionIcon('heroicon-m-building-office'),
        ];
    }
}
