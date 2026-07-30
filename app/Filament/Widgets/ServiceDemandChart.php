<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class ServiceDemandChart extends ChartWidget
{
    protected static ?string $heading = 'Service Demand';
    protected static ?string $description = 'Orders breakdown by service type';
    protected static ?string $maxHeight = '280px';
    protected static ?int $sort = 7;
    protected int | string | array $columnSpan = '1';

    protected function getData(): array
    {
        $services = Order::selectRaw('service_id, COUNT(*) as total')
            ->with('service')
            ->groupBy('service_id')
            ->orderBy('total', 'desc')
            ->take(8)
            ->get();

        $colors = ['#8b5cf6', '#3b82f6', '#22c55e', '#f59e0b', '#ef4444', '#ec4899', '#14b8a6', '#f97316'];

        return [
            'datasets' => [[
                'label' => 'Orders',
                'data' => $services->pluck('total')->toArray(),
                'backgroundColor' => array_slice($colors, 0, $services->count()),
                'borderRadius' => 6,
                'borderWidth' => 0,
                'hoverBackgroundColor' => '#7c3aed',
            ]],
            'labels' => $services->map(fn ($s) => $s->service?->name ?? "Service #{$s->service_id}")->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => ['legend' => ['display' => false]],
            'scales' => [
                'x' => ['grid' => ['display' => false], 'ticks' => ['font' => ['size' => 10]]],
                'y' => ['beginAtZero' => true, 'grid' => ['color' => 'rgba(148, 163, 184, 0.08)'], 'ticks' => ['font' => ['size' => 10], 'stepSize' => 1]],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}
