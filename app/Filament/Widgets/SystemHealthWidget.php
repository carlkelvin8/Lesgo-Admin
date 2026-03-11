<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Partner;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

class SystemHealthWidget extends Widget
{
    protected static string $view = 'filament.widgets.system-health-widget';
    protected static ?int $sort = 6;
    
    protected int | string | array $columnSpan = [
        'default' => 1,
        'sm' => 2,
        'md' => 3,
        'lg' => 4,
        'xl' => 6,
        '2xl' => 8,
    ];

    public function getViewData(): array
    {
        $pendingOrders = Order::where('status', 'pending')->count();
        $failedPayments = Payment::where('status', 'failed')->count();
        $inactivePartners = Partner::where('status', 'suspended')->count();
        $unverifiedUsers = \App\Models\User::whereNull('email_verified_at')->count();

        $databaseSize = $this->getDatabaseSize();
        $avgOrderTime = Order::where('status', 'completed')
            ->whereNotNull('completed_at')
            ->whereNotNull('created_at')
            ->selectRaw('AVG(EXTRACT(EPOCH FROM (completed_at - created_at))/60) as avg_minutes')
            ->value('avg_minutes');

        return [
            'metrics' => [
                [
                    'label' => 'Pending Orders',
                    'value' => $pendingOrders,
                    'status' => $pendingOrders > 20 ? 'warning' : 'success',
                    'icon' => 'heroicon-o-clock',
                ],
                [
                    'label' => 'Failed Payments',
                    'value' => $failedPayments,
                    'status' => $failedPayments > 10 ? 'danger' : 'success',
                    'icon' => 'heroicon-o-x-circle',
                ],
                [
                    'label' => 'Inactive Partners',
                    'value' => $inactivePartners,
                    'status' => $inactivePartners > 0 ? 'warning' : 'success',
                    'icon' => 'heroicon-o-building-office',
                ],
                [
                    'label' => 'Unverified Users',
                    'value' => $unverifiedUsers,
                    'status' => $unverifiedUsers > 50 ? 'warning' : 'info',
                    'icon' => 'heroicon-o-user',
                ],
                [
                    'label' => 'Database Size',
                    'value' => $databaseSize,
                    'status' => 'info',
                    'icon' => 'heroicon-o-circle-stack',
                ],
                [
                    'label' => 'Avg Order Time',
                    'value' => round($avgOrderTime ?? 0) . ' min',
                    'status' => 'info',
                    'icon' => 'heroicon-o-clock',
                ],
            ],
        ];
    }

    protected function getDatabaseSize(): string
    {
        try {
            $size = DB::select("SELECT pg_size_pretty(pg_database_size(current_database())) as size")[0]->size ?? 'N/A';
            return $size;
        } catch (\Exception $e) {
            return 'N/A';
        }
    }
}
