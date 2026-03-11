<?php

namespace App\Filament\Pages;

use App\Models\Order;
use App\Models\DriverProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class BulkOperations extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-queue-list';
    
    protected static ?string $navigationLabel = 'Bulk Operations';
    
    protected static ?string $title = 'Bulk Operations';
    
    protected static ?string $navigationGroup = 'Operations';
    
    protected static ?int $navigationSort = 5;

    protected static string $view = 'filament.pages.bulk-operations';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Bulk Operations')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Assign Driver')
                            ->icon('heroicon-o-user-group')
                            ->schema([
                                Forms\Components\Section::make('Bulk Assign Driver to Orders')
                                    ->description('Assign a driver to multiple orders at once')
                                    ->schema([
                                        Forms\Components\Select::make('driver_id')
                                            ->label('Select Driver')
                                            ->options(DriverProfile::query()->pluck('name', 'id'))
                                            ->searchable()
                                            ->required(),
                                        Forms\Components\CheckboxList::make('order_ids')
                                            ->label('Select Orders')
                                            ->options(
                                                Order::where('status', 'pending')
                                                    ->orWhere('status', 'confirmed')
                                                    ->get()
                                                    ->mapWithKeys(fn ($order) => [
                                                        $order->id => "Order #{$order->id} - {$order->pickup_address}"
                                                    ])
                                            )
                                            ->columns(2)
                                            ->required(),
                                    ]),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Update Status')
                            ->icon('heroicon-o-arrow-path')
                            ->schema([
                                Forms\Components\Section::make('Bulk Update Order Status')
                                    ->description('Update status for multiple orders')
                                    ->schema([
                                        Forms\Components\Select::make('new_status')
                                            ->label('New Status')
                                            ->options([
                                                'confirmed' => 'Confirmed',
                                                'assigned' => 'Assigned',
                                                'picked_up' => 'Picked Up',
                                                'in_transit' => 'In Transit',
                                                'delivered' => 'Delivered',
                                                'cancelled' => 'Cancelled',
                                            ])
                                            ->required(),
                                        Forms\Components\CheckboxList::make('status_order_ids')
                                            ->label('Select Orders')
                                            ->options(
                                                Order::whereNotIn('status', ['delivered', 'cancelled'])
                                                    ->get()
                                                    ->mapWithKeys(fn ($order) => [
                                                        $order->id => "Order #{$order->id} - Current: {$order->status}"
                                                    ])
                                            )
                                            ->columns(2)
                                            ->required(),
                                    ]),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Export Data')
                            ->icon('heroicon-o-arrow-down-tray')
                            ->schema([
                                Forms\Components\Section::make('Export Data')
                                    ->description('Export data in various formats')
                                    ->schema([
                                        Forms\Components\Select::make('export_type')
                                            ->label('Data Type')
                                            ->options([
                                                'orders' => 'Orders',
                                                'payments' => 'Payments',
                                                'drivers' => 'Drivers',
                                                'partners' => 'Partners',
                                                'users' => 'Users',
                                            ])
                                            ->required(),
                                        Forms\Components\Select::make('export_format')
                                            ->label('Format')
                                            ->options([
                                                'csv' => 'CSV',
                                                'excel' => 'Excel',
                                                'pdf' => 'PDF',
                                            ])
                                            ->default('csv')
                                            ->required(),
                                        Forms\Components\DatePicker::make('date_from')
                                            ->label('From Date'),
                                        Forms\Components\DatePicker::make('date_to')
                                            ->label('To Date'),
                                    ])->columns(2),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function assignDriver(): void
    {
        $data = $this->form->getState();
        
        Order::whereIn('id', $data['order_ids'])
            ->update(['driver_id' => $data['driver_id']]);

        Notification::make()
            ->title('Driver assigned successfully')
            ->success()
            ->send();
    }

    public function updateStatus(): void
    {
        $data = $this->form->getState();
        
        Order::whereIn('id', $data['status_order_ids'])
            ->update(['status' => $data['new_status']]);

        Notification::make()
            ->title('Status updated successfully')
            ->success()
            ->send();
    }

    public function exportData(): void
    {
        Notification::make()
            ->title('Export started')
            ->body('Your export will be ready shortly')
            ->info()
            ->send();
    }
}
