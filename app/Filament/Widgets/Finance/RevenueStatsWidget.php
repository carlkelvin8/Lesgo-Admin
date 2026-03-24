<?php

namespace App\Filament\Widgets\Finance;

use App\Models\Payment;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class RevenueStatsWidget extends BaseWidget
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
        // Total Revenue
        $totalRevenue = Payment::where('status', 'paid')->sum('amount');
        
        // Today's Revenue
        $todayRevenue = Payment::where('status', 'paid')
            ->whereDate('created_at', today())
            ->sum('amount');
        
        // This Month Revenue
        $thisMonthRevenue = Payment::where('status', 'paid')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('amount');
        
        // Last Month Revenue
        $lastMonthRevenue = Payment::where('status', 'paid')
            ->whereYear('created_at', now()->subMonth()->year)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->sum('amount');
        
        // Calculate month-over-month growth
        $monthGrowth = $lastMonthRevenue > 0 
            ? (($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 
            : 0;
        
        // Average Order Value
        $avgOrderValue = Order::where('status', 'completed')
            ->avg('actual_fare') ?? 0;
        
        // Pending Payments
        $pendingPayments = Payment::where('status', 'pending')->sum('amount');
        
        // Failed Payments
        $failedPayments = Payment::where('status', 'failed')->count();
        
        // Total Transactions
        $totalTransactions = Payment::where('status', 'paid')->count();
        
        return [
            Stat::make('Total Revenue', '₱' . number_format($totalRevenue, 2))
                ->description('All-time earnings')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart($this->getRevenueTrendData(30)),
            
            Stat::make('This Month', '₱' . number_format($thisMonthRevenue, 2))
                ->description(($monthGrowth >= 0 ? '+' : '') . number_format($monthGrowth, 1) . '% from last month')
                ->descriptionIcon($monthGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($monthGrowth >= 0 ? 'success' : 'danger')
                ->chart($this->getMonthlyTrendData()),
            
            Stat::make('Today\'s Revenue', '₱' . number_format($todayRevenue, 2))
                ->description('Earnings today')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('primary')
                ->chart($this->getTodayHourlyData()),
            
            Stat::make('Average Order Value', '₱' . number_format($avgOrderValue, 2))
                ->description('Per completed order')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('info'),
            
            Stat::make('Pending Payments', '₱' . number_format($pendingPayments, 2))
                ->description(Payment::where('status', 'pending')->count() . ' transactions')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            
            Stat::make('Total Transactions', number_format($totalTransactions))
                ->description($failedPayments . ' failed')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('success'),
        ];
    }
    
    private function getRevenueTrendData(int $days = 30): array
    {
        $data = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $data[] = (float) Payment::where('status', 'paid')
                ->whereDate('created_at', now()->subDays($i))
                ->sum('amount');
        }
        return $data;
    }
    
    private function getMonthlyTrendData(): array
    {
        $data = [];
        for ($i = 29; $i >= 0; $i--) {
            $data[] = (float) Payment::where('status', 'paid')
                ->whereDate('created_at', now()->subDays($i))
                ->sum('amount');
        }
        return $data;
    }
    
    private function getTodayHourlyData(): array
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
}
