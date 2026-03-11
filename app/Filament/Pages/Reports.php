<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Models\DriverProfile;

class Reports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    
    protected static ?string $navigationLabel = 'Reports';
    
    protected static ?string $title = 'Reports & Analytics';
    
    protected static ?string $navigationGroup = 'Finance';
    
    protected static ?int $navigationSort = 10;

    protected static string $view = 'filament.pages.reports';

    public function getReportData(): array
    {
        return [
            'orders' => [
                'total' => Order::count(),
                'today' => Order::whereDate('created_at', today())->count(),
                'this_week' => Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'this_month' => Order::whereMonth('created_at', now()->month)->count(),
            ],
            'revenue' => [
                'total' => Payment::where('status', 'paid')->sum('amount'),
                'today' => Payment::where('status', 'paid')->whereDate('created_at', today())->sum('amount'),
                'this_week' => Payment::where('status', 'paid')->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('amount'),
                'this_month' => Payment::where('status', 'paid')->whereMonth('created_at', now()->month)->sum('amount'),
            ],
            'users' => [
                'total' => User::count(),
                'active' => User::where('is_active', true)->count(),
                'new_this_month' => User::whereMonth('created_at', now()->month)->count(),
            ],
            'drivers' => [
                'total' => DriverProfile::count(),
                'active' => DriverProfile::where('status', 'active')->count(),
                'available' => DriverProfile::where('availability_status', 'available')->count(),
            ],
        ];
    }
}
