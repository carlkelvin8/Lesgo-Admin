<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Filament\Widgets\ChartWidget;

class RevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Revenue';
    protected static ?string $description = 'Weekly revenue breakdown';
    protected static ?string $maxHeight = '280px';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $weeks = collect(range(7, 0))->map(fn ($i) => now()->subWeeks($i)->startOfWeek());
        $labels = $weeks->map(fn ($w) => $w->format('M d'))->toArray();

        $payments = Payment::where('status', 'paid')
            ->where('created_at', '>=', now()->subWeeks(8)->startOfWeek())
            ->get();

        $weekly = collect();
        foreach ($weeks as $week) {
            $weekEnd = (clone $week)->endOfWeek();
            $total = $payments->filter(fn ($p) => $p->created_at >= $week && $p->created_at <= $weekEnd)->sum('amount');
            $weekly->push((float) $total);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Revenue (PHP)',
                    'data' => $weekly->toArray(),
                    'backgroundColor' => 'rgba(139, 92, 246, 0.85)',
                    'borderColor' => '#8b5cf6',
                    'borderWidth' => 0,
                    'borderRadius' => 6,
                    'hoverBackgroundColor' => '#7c3aed',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['display' => false],
            ],
            'scales' => [
                'x' => ['grid' => ['display' => false], 'ticks' => ['font' => ['size' => 11]]],
                'y' => ['beginAtZero' => true, 'grid' => ['color' => 'rgba(148, 163, 184, 0.08)'], 'ticks' => ['font' => ['size' => 11]]],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}
