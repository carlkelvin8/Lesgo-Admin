<?php

namespace App\Filament\Widgets\Finance;

use App\Models\Partner;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopPartnersWidget extends BaseWidget
{
    protected static bool $isLazy = true;
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Top Revenue Partners';

    public function table(Table $table): Table
    {
        return $table
            ->query(Partner::query()->withCount('orders'))
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Partner')->searchable(),
                Tables\Columns\TextColumn::make('business_type')->label('Type')->badge(),
                Tables\Columns\TextColumn::make('orders_count')->label('Orders')->sortable(),
                Tables\Columns\TextColumn::make('status')->badge()->colors(['success' => 'active', 'warning' => 'pending', 'danger' => 'suspended']),
            ])
            ->defaultSort('orders_count', 'desc')
            ->paginated([5]);
    }
}
