<?php

namespace App\Filament\Widgets;

use App\Models\DriverProfile;
use Filament\Widgets\ChartWidget;

class DriverPerformanceChart extends ChartWidget
{
    protected static ?string $heading = 'Top Drivers';
    protected static ?string $description = 'Top 10 drivers by trips completed';
    protected static ?string $maxHeight = '280px';
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = '1';

    protected function getData(): array
    {
        $drivers = DriverProfile::where('total_trips', '>', 0)
            ->with('user')
            ->orderBy('total_trips', 'desc')
            ->take(10)
            ->get();

        return [
            'datasets' => [[
                'label' => 'Trips',
                'data' => $drivers->pluck('total_trips')->toArray(),
                'backgroundColor' => [
                    'rgba(139, 92, 246, 0.85)',
                    'rgba(59, 130, 246, 0.85)',
                    'rgba(16, 185, 129, 0.85)',
                    'rgba(245, 158, 11, 0.85)',
                    'rgba(239, 68, 68, 0.85)',
                    'rgba(236, 72, 153, 0.85)',
                    'rgba(168, 85, 247, 0.7)',
                    'rgba(99, 102, 241, 0.7)',
                    'rgba(14, 165, 233, 0.7)',
                    'rgba(34, 197, 94, 0.7)',
                ],
                'borderRadius' => 4,
                'hoverBackgroundColor' => '#7c3aed',
            ]],
            'labels' => $drivers->map(fn ($d) => $d->user?->name ?? "Driver #{$d->id}")->toArray(),
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
