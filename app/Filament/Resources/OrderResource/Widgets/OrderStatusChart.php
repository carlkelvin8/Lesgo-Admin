<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrderStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Order Status Distribution';
    protected static ?string $maxHeight = '240px';
    protected int | string | array $columnSpan = '1';

    protected function getData(): array
    {
        $statuses = ['pending', 'accepted', 'picked_up', 'completed', 'cancelled'];
        $counts = [];
        foreach ($statuses as $status) {
            $counts[] = Order::where('status', $status)->count();
        }

        return [
            'datasets' => [[
                'data' => $counts,
                'backgroundColor' => ['#f59e0b', '#3b82f6', '#8b5cf6', '#22c55e', '#ef4444'],
                'borderWidth' => 0,
                'hoverOffset' => 8,
            ]],
            'labels' => ['Pending', 'Accepted', 'Picked Up', 'Completed', 'Cancelled'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => ['legend' => ['position' => 'bottom', 'labels' => ['usePointStyle' => true, 'pointStyle' => 'circle', 'padding' => 16]]],
            'cutout' => '70%',
            'maintainAspectRatio' => false,
        ];
    }
}
