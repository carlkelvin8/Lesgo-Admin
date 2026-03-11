<?php

namespace App\Filament\Widgets\Finance;

use App\Models\Payment;
use Filament\Widgets\ChartWidget;

class PaymentMethodsChart extends ChartWidget
{
    protected static ?string $heading = 'Payment Methods Distribution';
    protected static ?string $description = 'Revenue by payment method';
    protected static ?int $sort = 3;
    protected static ?string $maxHeight = '300px';
    
    protected int | string | array $columnSpan = [
        'default' => 1,
        'sm' => 2,
        'md' => 3,
        'lg' => 2,
        'xl' => 2,
        '2xl' => 3,
    ];

    protected function getData(): array
    {
        $cash = Payment::where('status', 'paid')
            ->where('method', 'cash')
            ->sum('amount');
        
        $card = Payment::where('status', 'paid')
            ->where('method', 'card')
            ->sum('amount');
        
        $wallet = Payment::where('status', 'paid')
            ->where('method', 'wallet')
            ->sum('amount');
        
        $online = Payment::where('status', 'paid')
            ->where('method', 'online')
            ->sum('amount');

        return [
            'datasets' => [
                [
                    'label' => 'Revenue (₱)',
                    'data' => [$cash, $card, $wallet, $online],
                    'backgroundColor' => [
                        'rgba(34, 197, 94, 0.9)',
                        'rgba(59, 130, 246, 0.9)',
                        'rgba(168, 85, 247, 0.9)',
                        'rgba(251, 191, 36, 0.9)',
                    ],
                    'borderColor' => '#fff',
                    'borderWidth' => 3,
                    'hoverOffset' => 15,
                ],
            ],
            'labels' => ['Cash', 'Card', 'Wallet', 'Online'],
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
                ],
            ],
            'cutout' => '65%',
            'maintainAspectRatio' => false,
        ];
    }
}
