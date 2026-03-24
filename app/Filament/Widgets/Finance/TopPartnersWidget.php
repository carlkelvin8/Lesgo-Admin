<?php

namespace App\Filament\Widgets\Finance;

use App\Models\Partner;
use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TopPartnersWidget extends BaseWidget
{
    protected static bool $isLazy = true;
    protected static ?int $sort = 4;
    
    protected int | string | array $columnSpan = [
        'default' => 1,
        'sm' => 2,
        'md' => 3,
        'lg' => 4,
        'xl' => 6,
        '2xl' => 8,
    ];
    
    protected static ?string $heading = 'Top Revenue Partners';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Partner::query()
                    ->withCount('orders')
                    ->withSum([
                        'orders as revenue' => function ($query) {
                            $query->where('status', 'completed');
                        }
                    ], 'actual_fare')
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Partner')
                    ->searchable()
                    ->weight('bold')
                    ->icon('heroicon-m-building-office-2'),
                
                Tables\Columns\TextColumn::make('business_type')
                    ->label('Type')
                    ->badge()
                    ->color('info'),
                
                Tables\Columns\TextColumn::make('orders_count')
                    ->label('Orders')
                    ->sortable()
                    ->alignCenter()
                    ->color('primary'),
                
                Tables\Columns\TextColumn::make('revenue')
                    ->label('Total Revenue')
                    ->money('PHP')
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),
                
                Tables\Columns\TextColumn::make('avg_order_value')
                    ->label('Avg Order Value')
                    ->getStateUsing(function (Partner $record): float {
                        $ordersCount = $record->orders()->where('status', 'completed')->count();
                        if ($ordersCount === 0) {
                            return 0;
                        }
                        $totalRevenue = $record->orders()->where('status', 'completed')->sum('actual_fare');
                        return $totalRevenue / $ordersCount;
                    })
                    ->money('PHP')
                    ->color('info'),
            ])
            ->defaultSort('revenue', 'desc')
            ->paginated([10, 25, 50]);
    }
}
