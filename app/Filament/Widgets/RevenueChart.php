<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class RevenueChart extends ChartWidget
{
    protected static bool $isLazy = true;
    protected static ?string $heading = 'Revenue Trend';
    protected static ?string $description = 'Monthly revenue performance';
    protected static ?int $sort = 3;
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
        return Cache::remember('revenue_chart', 60, function () {
            $data = $this->getRevenuePerMonth();

            return [
                'datasets' => [
                    [
                        'label' => 'Revenue (₱)',
                        'data' => $data['revenuePerMonth'],
                        'backgroundColor' => [
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(56, 189, 248, 0.9)', // Current month highlighted
                        ],
                        'borderColor' => [
                            'rgba(34, 197, 94, 1)',
                            'rgba(34, 197, 94, 1)',
                            'rgba(34, 197, 94, 1)',
                            'rgba(34, 197, 94, 1)',
                            'rgba(34, 197, 94, 1)',
                            'rgba(34, 197, 94, 1)',
                            'rgba(34, 197, 94, 1)',
                            'rgba(34, 197, 94, 1)',
                            'rgba(34, 197, 94, 1)',
                            'rgba(34, 197, 94, 1)',
                            'rgba(34, 197, 94, 1)',
                            'rgba(56, 189, 248, 1)',
                        ],
                        'borderWidth' => 2,
                        'borderRadius' => 8,
                        'borderSkipped' => false,
                    ],
                ],
                'labels' => $data['months'],
            ];
        });
    }

    protected function getType(): string
    {
        return 'bar';
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
                    'callbacks' => [
                        'label' => 'function(context) { return "Revenue: ₱" + context.parsed.y.toLocaleString(); }',
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
                        'callback' => 'function(value) { return "₱" + value.toLocaleString(); }',
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

    private function getRevenuePerMonth(): array
    {
        $now = Carbon::now();
        $months = [];
        $revenuePerMonth = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $months[] = $month->format('M Y');
            
            $revenue = Payment::where('status', 'paid')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('amount');
            
            $revenuePerMonth[] = (float) $revenue;
        }

        return [
            'months' => $months,
            'revenuePerMonth' => $revenuePerMonth,
        ];
    }
}
