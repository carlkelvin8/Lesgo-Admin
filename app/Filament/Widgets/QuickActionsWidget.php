<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class QuickActionsWidget extends Widget
{
    protected static string $view = 'filament.widgets.quick-actions-widget';

    protected static ?int $sort = 0;

    public function getActions(): array
    {
        return [
            [
                'label' => 'New Order',
                'icon' => 'heroicon-o-shopping-bag',
                'url' => route('filament.admin.resources.orders.create'),
                'color' => 'primary',
            ],
            [
                'label' => 'Add User',
                'icon' => 'heroicon-o-user-plus',
                'url' => route('filament.admin.resources.users.create'),
                'color' => 'success',
            ],
            [
                'label' => 'New Driver',
                'icon' => 'heroicon-o-truck',
                'url' => route('filament.admin.resources.driver-profiles.create'),
                'color' => 'info',
            ],
            [
                'label' => 'Add Partner',
                'icon' => 'heroicon-o-building-office',
                'url' => route('filament.admin.resources.partners.create'),
                'color' => 'warning',
            ],
            [
                'label' => 'Support Tickets',
                'icon' => 'heroicon-o-lifebuoy',
                'url' => route('filament.admin.resources.support-tickets.index'),
                'color' => 'danger',
            ],
            [
                'label' => 'Settings',
                'icon' => 'heroicon-o-cog-6-tooth',
                'url' => route('filament.admin.pages.settings'),
                'color' => 'gray',
            ],
        ];
    }
}
