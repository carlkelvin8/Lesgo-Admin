<?php

namespace App\Filament\Widgets;

use App\Models\Partner;
use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PartnerPerformanceChart extends ChartWidget
{
    protected static ?string $heading = 'Top Partners';
    protected static ?string $description = 'Top 10 partners by order volume';
    protected static ?string $maxHeight = '280px';
    protected static ?int $sort = 8;
    protected int | string | array $columnSpan = '1';

    protected function getData(): array
    {
        $partners = Order::selectRaw('partner_id, COUNT(*) as total')
            ->whereNotNull('partner_id')
            ->with('partner')
            ->groupBy('partner_id')
            ->orderBy('total', 'desc')
            ->take(10)
            ->get();

        $colors = [
            '#8b5cf6', '#3b82f6', '#22c55e', '#f59e0b', '#ef4444',
            '#ec4899', '#14b8a6', '#f97316', '#6366f1', '#0ea5e9',
        ];

        return [
            'datasets' => [[
                'label' => 'Orders',
                'data' => $partners->pluck('total')->toArray(),
                'backgroundColor' => array_map(fn ($i) => $colors[$i] ?? '#8b5cf6', array_keys($partners->toArray())),
                'borderRadius' => 4,
                'borderWidth' => 0,
            ]],
            'labels' => $partners->map(fn ($s) => $s->partner?->name ?? "Partner #{$s->partner_id}")->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'plugins' => ['legend' => ['display' => false]],
            'scales' => [
                'x' => ['beginAtZero' => true, 'grid' => ['color' => 'rgba(148, 163, 184, 0.08)'], 'ticks' => ['font' => ['size' => 10], 'stepSize' => 1]],
                'y' => ['grid' => ['display' => false], 'ticks' => ['font' => ['size' => 10]]],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}
