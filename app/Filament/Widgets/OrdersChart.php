<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class OrdersChart extends ChartWidget
{
    protected static bool $isLazy = true;
    protected static ?string $heading = 'Orders Overview';
    protected static ?string $description = 'Monthly order volume trends';
    protected static ?int $sort = 2;
    protected static ?string $maxHeight = '300px';
    
    protected int | string | array $columnSpan = [
        'default' => 1,
        'sm' => 2,
        'md' => 3,
        'lg' => 2,
        'xl' => 3,
        '2xl' => 4,
    ];

    protected function getData(): array
    {
        return Cache::remember('orders_chart', 60, function () {
            $data = $this->getOrdersPerMonth();

            return [
                'datasets' => [
                    [
                        'label' => 'Orders',
                        'data' => $data['ordersPerMonth'],
                        'backgroundColor' => 'rgba(56, 189, 248, 0.1)',
                        'borderColor' => 'rgba(56, 189, 248, 1)',
                        'borderWidth' => 3,
                        'fill' => true,
                        'tension' => 0.4,
                        'pointRadius' => 4,
                        'pointHoverRadius' => 6,
                        'pointBackgroundColor' => 'rgba(56, 189, 248, 1)',
                        'pointBorderColor' => '#fff',
                        'pointBorderWidth' => 2,
                        'pointHoverBackgroundColor' => '#fff',
                        'pointHoverBorderColor' => 'rgba(56, 189, 248, 1)',
                    ],
                ],
                'labels' => $data['months'],
            ];
        });
    }

    protected function getType(): string
    {
        return 'line';
    }
    
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                    'labels' => [
                        'usePointStyle' => true,
                        'padding' => 15,
                        'font' => [
                            'size' => 13,
                            'weight' => '500',
                        ],
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
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'display' => true,
                        'color' => 'rgba(0, 0, 0, 0.05)',
                    ],
                    'ticks' => [
                        'precision' => 0,
                        'font' => [
                            'size' => 12,
                        ],
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                    'ticks' => [
                        'font' => [
                            'size' => 12,
                        ],
                    ],
                ],
            ],
            'interaction' => [
                'intersect' => false,
                'mode' => 'index',
            ],
            'maintainAspectRatio' => false,
        ];
    }

    private function getOrdersPerMonth(): array
    {
        $now = now();
        $months = [];
        $ordersPerMonth = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $months[] = $month->format('M Y');
            
            $count = Order::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            
            $ordersPerMonth[] = $count;
        }

        return [
            'months' => $months,
            'ordersPerMonth' => $ordersPerMonth,
        ];
    }
}
