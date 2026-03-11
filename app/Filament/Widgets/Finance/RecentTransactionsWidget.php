<?php

namespace App\Filament\Widgets\Finance;

use App\Models\Payment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentTransactionsWidget extends BaseWidget
{
    protected static ?int $sort = 5;
    
    protected int | string | array $columnSpan = [
        'default' => 1,
        'sm' => 2,
        'md' => 3,
        'lg' => 4,
        'xl' => 6,
        '2xl' => 8,
    ];
    
    protected static ?string $heading = 'Recent Transactions';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Payment::query()
                    ->with(['order.customer', 'order.service'])
                    ->latest()
                    ->limit(20)
            )
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Payment #')
                    ->sortable()
                    ->searchable()
                    ->weight('bold')
                    ->color('primary'),
                
                Tables\Columns\TextColumn::make('order.id')
                    ->label('Order #')
                    ->searchable()
                    ->url(fn (Payment $record): string => $record->order_id 
                        ? route('filament.admin.resources.orders.view', ['record' => $record->order_id])
                        : '#')
                    ->color('info'),
                
                Tables\Columns\TextColumn::make('order.customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->icon('heroicon-m-user')
                    ->default('N/A'),
                
                Tables\Columns\TextColumn::make('order.service.name')
                    ->label('Service')
                    ->badge()
                    ->color('gray')
                    ->default('N/A'),
                
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->money('PHP')
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),
                
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Method')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'cash' => 'success',
                        'card' => 'info',
                        'wallet' => 'warning',
                        'online' => 'primary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                
                Tables\Columns\BadgeColumn::make('status')
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
                    ->description(fn (Payment $record): string => $record->created_at->format('M d, Y H:i:s')),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s')
            ->striped();
    }
}
