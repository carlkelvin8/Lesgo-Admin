<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class OrderStatusChart extends ChartWidget
{
    protected static bool $isLazy = true;
    protected static ?string $heading = 'Order Status Distribution';
    protected static ?string $description = 'Current order pipeline breakdown';
    protected static ?int $sort = 4;
    protected static ?string $maxHeight = '280px';
    
    protected int | string | array $columnSpan = [
        'default' => 1,
        'sm' => 2,
        'md' => 3,
        'lg' => 2,
        'xl' => 2,
        '2xl' => 2,
    ];

    protected function getData(): array
    {
        return Cache::remember('order_status_chart', 60, function () {
            $pending = Order::where('status', 'pending')->count();
            $accepted = Order::where('status', 'accepted')->count();
            $pickedUp = Order::where('status', 'picked_up')->count();
            $completed = Order::where('status', 'completed')->count();
            $cancelled = Order::where('status', 'cancelled')->count();

            return [
                'datasets' => [
                    [
                        'label' => 'Orders',
                        'data' => [$pending, $accepted, $pickedUp, $completed, $cancelled],
                        'backgroundColor' => [
                            'rgba(251, 191, 36, 0.9)',  // warning - pending
                            'rgba(59, 130, 246, 0.9)',  // info - accepted
                            'rgba(168, 85, 247, 0.9)',  // primary - picked_up
                            'rgba(34, 197, 94, 0.9)',   // success - completed
                            'rgba(239, 68, 68, 0.9)',   // danger - cancelled
                        ],
                        'borderColor' => '#fff',
                        'borderWidth' => 3,
                        'hoverOffset' => 15,
                    ],
                ],
                'labels' => ['Pending', 'Accepted', 'Picked Up', 'Completed', 'Cancelled'],
            ];
        });
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
    
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                    'labels' => [
                        'usePointStyle' => true,
                        'padding' => 15,
                        'font' => [
                            'size' => 13,
                            'weight' => '500',
                        ],
                        'generateLabels' => 'function(chart) {
                            const data = chart.data;
                            if (data.labels.length && data.datasets.length) {
                                return data.labels.map((label, i) => {
                                    const value = data.datasets[0].data[i];
                                    const total = data.datasets[0].data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return {
                                        text: label + ": " + value + " (" + percentage + "%)",
                                        fillStyle: data.datasets[0].backgroundColor[i],
                                        hidden: false,
                                        index: i
                                    };
                                });
                            }
                            return [];
                        }',
                    ],
                ],
                'tooltip' => [
                    'enabled' => true,
                    'backgroundColor' => 'rgba(0, 0, 0, 0.8)',
                    'padding' => 12,
                    'cornerRadius' => 8,
                    'titleFont' => [
                        'size' => 14,
                        'weight' => 'bold',
                    ],
                    'bodyFont' => [
                        'size' => 13,
                    ],
                    'callbacks' => [
                        'label' => 'function(context) {
                            const label = context.label || "";
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return label + ": " + value + " orders (" + percentage + "%)";
                        }',
                    ],
                ],
            ],
            'cutout' => '65%',
            'maintainAspectRatio' => false,
        ];
    }
}
