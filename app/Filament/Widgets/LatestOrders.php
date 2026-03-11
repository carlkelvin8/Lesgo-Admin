<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget
{
    protected static ?int $sort = 7;
    
    protected int | string | array $columnSpan = [
        'default' => 1,
        'sm' => 2,
        'md' => 3,
        'lg' => 4,
        'xl' => 6,
        '2xl' => 8,
    ];
    
    protected static ?string $heading = 'Recent Orders';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->with(['customer', 'service', 'driver'])
                    ->latest()
                    ->limit(15)
            )
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Order #')
                    ->sortable()
                    ->searchable()
                    ->weight('bold')
                    ->color('primary'),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->icon('heroicon-m-user')
                    ->description(fn (Order $record): string => $record->customer?->email ?? 'N/A'),
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Service')
                    ->searchable()
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('driver.user.name')
                    ->label('Driver')
                    ->default('Not assigned')
                    ->icon('heroicon-m-truck')
                    ->toggleable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'accepted',
                        'primary' => 'picked_up',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ])
                    ->icons([
                        'heroicon-m-clock' => 'pending',
                        'heroicon-m-check-circle' => 'accepted',
                        'heroicon-m-truck' => 'picked_up',
                        'heroicon-m-check-badge' => 'completed',
                        'heroicon-m-x-circle' => 'cancelled',
                    ]),
                Tables\Columns\TextColumn::make('actual_fare')
                    ->label('Fare')
                    ->money('PHP')
                    ->sortable()
                    ->weight('semibold')
                    ->color('success'),
                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('Payment')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger' => 'failed',
                        'gray' => 'refunded',
                    ])
                    ->icons([
                        'heroicon-m-clock' => 'pending',
                        'heroicon-m-check-circle' => 'paid',
                        'heroicon-m-x-circle' => 'failed',
                        'heroicon-m-arrow-path' => 'refunded',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->since()
                    ->description(fn (Order $record): string => $record->created_at->format('M d, Y H:i:s')),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s')
            ->striped();
    }
}
