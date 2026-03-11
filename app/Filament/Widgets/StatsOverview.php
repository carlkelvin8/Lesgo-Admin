<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\User;
use App\Models\Partner;
use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
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
        $totalOrders = Order::count();
        $completedOrders = Order::where('status', 'completed')->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalRevenue = Payment::where('status', 'paid')->sum('amount');
        $todayRevenue = Payment::where('status', 'paid')
            ->whereDate('created_at', today())
            ->sum('amount');
        $activePartners = Partner::where('status', 'active')->count();
        
        // Calculate trends
        $lastMonthOrders = Order::whereBetween('created_at', [now()->subMonth(), now()])->count();
        $previousMonthOrders = Order::whereBetween('created_at', [now()->subMonths(2), now()->subMonth()])->count();
        $orderTrend = $previousMonthOrders > 0 ? (($lastMonthOrders - $previousMonthOrders) / $previousMonthOrders) * 100 : 0;
        
        $lastMonthRevenue = Payment::where('status', 'paid')
            ->whereBetween('created_at', [now()->subMonth(), now()])
            ->sum('amount');
        $previousMonthRevenue = Payment::where('status', 'paid')
            ->whereBetween('created_at', [now()->subMonths(2), now()->subMonth()])
            ->sum('amount');
        $revenueTrend = $previousMonthRevenue > 0 ? (($lastMonthRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100 : 0;
        
        return [
            Stat::make('Total Orders', number_format($totalOrders))
                ->description(($orderTrend >= 0 ? '+' : '') . number_format($orderTrend, 1) . '% from last month')
                ->descriptionIcon($orderTrend >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($orderTrend >= 0 ? 'success' : 'danger')
                ->chart($this->getOrderTrendData()),
            
            Stat::make('Pending Orders', number_format($pendingOrders))
                ->description($pendingOrders > 20 ? 'High volume - needs attention' : 'Normal volume')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingOrders > 20 ? 'warning' : 'success')
                ->chart($this->getPendingTrendData()),
            
            Stat::make('Completed Orders', number_format($completedOrders))
                ->description(number_format(($completedOrders / max($totalOrders, 1)) * 100, 1) . '% completion rate')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart($this->getCompletedTrendData()),
            
            Stat::make('Total Revenue', '₱' . number_format($totalRevenue, 2))
                ->description(($revenueTrend >= 0 ? '+' : '') . number_format($revenueTrend, 1) . '% from last month')
                ->descriptionIcon($revenueTrend >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($revenueTrend >= 0 ? 'success' : 'danger')
                ->chart($this->getRevenueTrendData()),
            
            Stat::make('Today\'s Revenue', '₱' . number_format($todayRevenue, 2))
                ->description('Earnings today')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('primary')
                ->chart($this->getTodayRevenueTrendData()),
            
            Stat::make('Active Partners', number_format($activePartners))
                ->description('Verified and active')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('info')
                ->chart($this->getPartnerTrendData()),
        ];
    }
    
    private function getOrderTrendData(): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $data[] = Order::whereDate('created_at', now()->subDays($i))->count();
        }
        return $data;
    }
    
    private function getPendingTrendData(): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $data[] = Order::where('status', 'pending')
                ->whereDate('created_at', now()->subDays($i))
                ->count();
        }
        return $data;
    }
    
    private function getCompletedTrendData(): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $data[] = Order::where('status', 'completed')
                ->whereDate('created_at', now()->subDays($i))
                ->count();
        }
        return $data;
    }
    
    private function getRevenueTrendData(): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $data[] = (float) Payment::where('status', 'paid')
                ->whereDate('created_at', now()->subDays($i))
                ->sum('amount');
        }
        return $data;
    }
    
    private function getTodayRevenueTrendData(): array
    {
        $data = [];
        for ($i = 23; $i >= 0; $i--) {
            $data[] = (float) Payment::where('status', 'paid')
                ->whereBetween('created_at', [
                    now()->subHours($i + 1),
                    now()->subHours($i)
                ])
                ->sum('amount');
        }
        return $data;
    }
    
    private function getPartnerTrendData(): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $data[] = Partner::where('status', 'active')
                ->whereDate('created_at', '<=', now()->subDays($i))
                ->count();
        }
        return $data;
    }
}
