<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class OrdersOverviewChart extends ChartWidget
{
    protected static ?string $heading = 'Orders Overview';
    protected static ?string $description = 'Daily orders for the last 14 days';
    protected static ?string $maxHeight = '280px';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $days = collect(range(13, 0))->map(fn ($i) => now()->subDays($i));
        $labels = $days->map(fn ($d) => $d->format('M d'))->toArray();

        $orders = Order::where('created_at', '>=', now()->subDays(14)->startOfDay())
            ->selectRaw("DATE(created_at) as date, COUNT(*) as total, SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed")
            ->groupByRaw('DATE(created_at)')
            ->pluck('total', 'date')
            ->toArray();

        $completed = Order::where('created_at', '>=', now()->subDays(14)->startOfDay())
            ->where('status', 'completed')
            ->selectRaw("DATE(created_at) as date, COUNT(*) as total")
            ->groupByRaw('DATE(created_at)')
            ->pluck('total', 'date')
            ->toArray();

        $totalData = $days->map(fn ($d) => $orders[$d->toDateString()] ?? 0)->toArray();
        $completedData = $days->map(fn ($d) => $completed[$d->toDateString()] ?? 0)->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Total Orders',
                    'data' => $totalData,
                    'borderColor' => '#8b5cf6',
                    'backgroundColor' => 'rgba(139, 92, 246, 0.08)',
                    'borderWidth' => 2.5,
                    'fill' => true,
                    'tension' => 0.4,
                    'pointRadius' => 0,
                    'pointHoverRadius' => 5,
                    'pointHoverBackgroundColor' => '#8b5cf6',
                    'pointHoverBorderColor' => '#fff',
                    'pointHoverBorderWidth' => 2,
                ],
                [
                    'label' => 'Completed',
                    'data' => $completedData,
                    'borderColor' => '#22c55e',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.05)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.4,
                    'pointRadius' => 0,
                    'pointHoverRadius' => 5,
                    'pointHoverBackgroundColor' => '#22c55e',
                    'pointHoverBorderColor' => '#fff',
                    'pointHoverBorderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['display' => true, 'position' => 'top', 'labels' => ['usePointStyle' => true, 'pointStyle' => 'circle', 'padding' => 20]],
            ],
            'scales' => [
                'x' => ['grid' => ['display' => false], 'ticks' => ['font' => ['size' => 11]]],
                'y' => ['beginAtZero' => true, 'grid' => ['color' => 'rgba(148, 163, 184, 0.08)'], 'ticks' => ['font' => ['size' => 11], 'stepSize' => 1]],
            ],
            'interaction' => ['intersect' => false, 'mode' => 'index'],
            'maintainAspectRatio' => false,
        ];
    }
}
