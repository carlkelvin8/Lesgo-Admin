<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class QuickActionsWidget extends Widget
{
    protected static string $view = 'filament.widgets.quick-actions-widget';
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = 1;

    public function getActions(): array
    {
        return [
            [
                'label' => 'Create Order',
                'icon' => 'heroicon-o-plus-circle',
                'url' => route('filament.admin.resources.orders.create'),
                'color' => 'primary',
            ],
            [
                'label' => 'Add Driver',
                'icon' => 'heroicon-o-user-plus',
                'url' => route('filament.admin.resources.driver-profiles.create'),
                'color' => 'success',
            ],
            [
                'label' => 'New Partner',
                'icon' => 'heroicon-o-building-office',
                'url' => route('filament.admin.resources.partners.create'),
                'color' => 'info',
            ],
            [
                'label' => 'View Reports',
                'icon' => 'heroicon-o-chart-bar',
                'url' => route('filament.admin.pages.finance-dashboard'),
                'color' => 'warning',
            ],
            [
                'label' => 'Settings',
                'icon' => 'heroicon-o-cog-6-tooth',
                'url' => route('filament.admin.pages.settings'),
                'color' => 'gray',
            ],
            [
                'label' => 'Audit Logs',
                'icon' => 'heroicon-o-shield-check',
                'url' => route('filament.admin.resources.audit-logs.index'),
                'color' => 'danger',
            ],
        ];
    }
}
