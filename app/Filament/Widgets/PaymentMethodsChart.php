<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Filament\Widgets\ChartWidget;

class PaymentMethodsChart extends ChartWidget
{
    protected static ?string $heading = 'Payment Methods';
    protected static ?string $description = 'Distribution of payment methods';
    protected static ?string $maxHeight = '280px';
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = '1';

    protected function getData(): array
    {
        $methods = Payment::selectRaw('method, COUNT(*) as total')
            ->groupBy('method')
            ->pluck('total', 'method')
            ->toArray();

        $colors = [
            'cash' => '#22c55e',
            'gcash' => '#3b82f6',
            'maya' => '#8b5cf6',
            'card' => '#f59e0b',
            'wallet' => '#ec4899',
        ];

        $labels = array_map(fn ($m) => ucfirst($m), array_keys($methods));

        return [
            'datasets' => [[
                'data' => array_values($methods),
                'backgroundColor' => array_map(fn ($m) => $colors[$m] ?? '#6b7280', array_keys($methods)),
                'borderWidth' => 0,
                'hoverOffset' => 8,
            ]],
            'labels' => $labels,
        ];
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
                    'labels' => ['usePointStyle' => true, 'pointStyle' => 'circle', 'padding' => 12, 'font' => ['size' => 11]],
                ],
            ],
            'cutout' => '65%',
            'maintainAspectRatio' => false,
        ];
    }
}
