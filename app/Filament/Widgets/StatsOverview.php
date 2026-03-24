<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\User;
use App\Models\Partner;
use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    protected static bool $isLazy = true;
    protected static ?int $sort = 1;
    
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
        return Cache::remember('stats_overview', 60, function () {
            // Consolidated query for orders and payments
            $sevenDaysAgo = now()->subDays(6)->startOfDay();
            
            $orderStats = Order::select(
                    DB::raw('COUNT(*) as total'),
                    DB::raw('SUM(CASE WHEN status = \'completed\' THEN 1 ELSE 0 END) as completed'),
                    DB::raw('SUM(CASE WHEN status = \'pending\' THEN 1 ELSE 0 END) as pending'),
                    DB::raw('DATE(created_at) as date')
                )
                ->where('created_at', '>=', $sevenDaysAgo)
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get()
                ->keyBy('date');

            $paymentStats = Payment::select(
                    DB::raw('SUM(amount) as total_revenue'),
                    DB::raw('DATE(created_at) as date')
                )
                ->where('status', 'paid')
                ->where('created_at', '>=', $sevenDaysAgo)
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get()
                ->keyBy('date');

            $dailyOrderCounts = [];
            $dailyPendingCounts = [];
            $dailyCompletedCounts = [];
            $dailyRevenue = [];

            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i)->toDateString();
                $dailyOrderCounts[] = $orderStats[$date]->total ?? 0;
                $dailyPendingCounts[] = $orderStats[$date]->pending ?? 0;
                $dailyCompletedCounts[] = $orderStats[$date]->completed ?? 0;
                $dailyRevenue[] = (float) ($paymentStats[$date]->total_revenue ?? 0);
            }

            $totalOrders = array_sum($dailyOrderCounts);
            $totalPending = array_sum($dailyPendingCounts);
            $totalCompleted = array_sum($dailyCompletedCounts);
            $totalRevenue = array_sum($dailyRevenue);

            $activePartners = Partner::where('status', 'active')->count();
            $todayRevenue = (float) Payment::where('status', 'paid')->whereDate('created_at', today())->sum('amount');

            return [
                Stat::make('Total Orders', number_format($totalOrders))
                    ->description('Last 7 days')
                    ->color('success')
                    ->chart($dailyOrderCounts),
                
                Stat::make('Pending Orders', number_format($totalPending))
                    ->description('Last 7 days')
                    ->color('warning')
                    ->chart($dailyPendingCounts),
                
                Stat::make('Completed Orders', number_format($totalCompleted))
                    ->description('Last 7 days')
                    ->color('success')
                    ->chart($dailyCompletedCounts),
                
                Stat::make('Total Revenue', '₱' . number_format($totalRevenue, 2))
                    ->description('Last 7 days')
                    ->color('success')
                    ->chart($dailyRevenue),
                
                Stat::make('Today\'s Revenue', '₱' . number_format($todayRevenue, 2))
                    ->description('Live earnings')
                    ->color('primary'),
                
                Stat::make('Active Partners', number_format($activePartners))
                    ->description('Verified partners')
                    ->color('info'),
            ];
        });
    }
}
