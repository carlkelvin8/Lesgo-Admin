<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;

class UserGrowthChart extends ChartWidget
{
    protected static ?string $heading = 'User Growth';
    protected static ?string $description = 'New user registrations over the last 30 days';
    protected static ?string $maxHeight = '280px';
    protected static ?int $sort = 6;
    protected int | string | array $columnSpan = '1';

    protected function getData(): array
    {
        $days = collect(range(29, 0))->map(fn ($i) => now()->subDays($i));
        $labels = $days->map(fn ($d) => $d->format('M d'))->toArray();

        $users = User::where('created_at', '>=', now()->subDays(30)->startOfDay())
            ->selectRaw("DATE(created_at) as date, COUNT(*) as total")
            ->groupByRaw('DATE(created_at)')
            ->pluck('total', 'date')
            ->toArray();

        $data = $days->map(fn ($d) => (int) ($users[$d->toDateString()] ?? 0))->toArray();

        return [
            'datasets' => [[
                'label' => 'New Users',
                'data' => $data,
                'borderColor' => '#3b82f6',
                'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                'borderWidth' => 2.5,
                'fill' => true,
                'tension' => 0.4,
                'pointRadius' => 0,
                'pointHoverRadius' => 5,
                'pointHoverBackgroundColor' => '#3b82f6',
                'pointHoverBorderColor' => '#fff',
                'pointHoverBorderWidth' => 2,
            ]],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => ['legend' => ['display' => false]],
            'scales' => [
                'x' => ['grid' => ['display' => false], 'ticks' => ['font' => ['size' => 10], 'maxTicksLimit' => 10]],
                'y' => ['beginAtZero' => true, 'grid' => ['color' => 'rgba(148, 163, 184, 0.08)'], 'ticks' => ['font' => ['size' => 10], 'stepSize' => 1]],
            ],
            'interaction' => ['intersect' => false, 'mode' => 'index'],
            'maintainAspectRatio' => false,
        ];
    }
}
