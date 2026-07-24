<?php

namespace App\Filament\Widgets\Finance;

use App\Models\Payment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RevenueByMonthChart extends ChartWidget
{
    protected static bool $isLazy = true;
    protected static ?string $heading = 'Revenue by Month';
    protected static ?int $sort = 2;
    protected static ?string $maxHeight = '300px';

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $months = collect(range(11, 0))->map(fn ($i) => now()->subMonths($i));
        $labels = $months->map(fn ($m) => $m->format('M Y'))->toArray();

        // Single query for all months
        $revenue = Payment::where('status', 'paid')
            ->where('created_at', '>=', now()->subMonths(12)->startOfMonth())
            ->selectRaw("to_char(created_at, 'YYYY-MM') as month, sum(amount) as total")
            ->groupByRaw("to_char(created_at, 'YYYY-MM')")
            ->pluck('total', 'month')
            ->toArray();

        $data = $months->map(fn ($m) => (float) ($revenue[$m->format('Y-m')] ?? 0))->toArray();

        return [
            'datasets' => [['label' => 'Revenue (₱)', 'data' => $data, 'borderColor' => 'rgba(34, 197, 94, 1)', 'fill' => true, 'tension' => 0.4]],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
