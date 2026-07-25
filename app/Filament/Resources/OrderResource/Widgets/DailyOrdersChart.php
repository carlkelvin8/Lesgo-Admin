<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class DailyOrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Daily Orders (Last 7 Days)';
    protected static ?string $maxHeight = '240px';
    protected int | string | array $columnSpan = '1';

    protected function getData(): array
    {
        $days = collect(range(6, 0))->map(fn ($i) => now()->subDays($i));
        $labels = $days->map(fn ($d) => $d->format('D'))->toArray();

        $orders = Order::where('created_at', '>=', now()->subDays(7)->startOfDay())
            ->selectRaw("DATE(created_at) as date, COUNT(*) as total")
            ->groupByRaw('DATE(created_at)')
            ->pluck('total', 'date')
            ->toArray();

        $data = $days->map(fn ($d) => (int) ($orders[$d->toDateString()] ?? 0))->toArray();

        return [
            'datasets' => [[
                'label' => 'Orders',
                'data' => $data,
                'borderColor' => '#8b5cf6',
                'backgroundColor' => 'rgba(139, 92, 246, 0.1)',
                'borderWidth' => 2.5,
                'fill' => true,
                'tension' => 0.4,
                'pointRadius' => 4,
                'pointBackgroundColor' => '#8b5cf6',
                'pointBorderColor' => '#fff',
                'pointBorderWidth' => 2,
            ]],
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
            'plugins' => ['legend' => ['display' => false]],
            'scales' => [
                'x' => ['grid' => ['display' => false]],
                'y' => ['beginAtZero' => true, 'grid' => ['color' => 'rgba(148, 163, 184, 0.08)'], 'ticks' => ['stepSize' => 1]],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}
