<?php

namespace App\Filament\Widgets\Finance;

use App\Models\Payment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentTransactionsWidget extends BaseWidget
{
    protected static bool $isLazy = true;
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Recent Transactions';

    public function table(Table $table): Table
    {
        return $table
            ->query(Payment::query()->with(['order.customer'])->latest())
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('#')->sortable(),
                Tables\Columns\TextColumn::make('order.customer.name')->label('Customer')->default('N/A'),
                Tables\Columns\TextColumn::make('amount')->money('PHP')->sortable(),
                Tables\Columns\TextColumn::make('method')->badge(),
                Tables\Columns\TextColumn::make('status')->badge()->colors(['warning' => 'pending', 'success' => 'paid', 'danger' => 'failed']),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([5]);
    }
}
