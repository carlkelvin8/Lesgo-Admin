<?php

namespace App\Filament\Widgets;

use App\Models\AuditLog;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentActivityWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = 5;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                AuditLog::query()
                    ->with('user')
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->icon('heroicon-o-user')
                    ->iconColor('primary'),
                Tables\Columns\TextColumn::make('action')
                    ->badge()
                    ->colors([
                        'success' => 'created',
                        'warning' => 'updated',
                        'danger' => 'deleted',
                        'info' => 'viewed',
                    ]),
                Tables\Columns\TextColumn::make('model_type')
                    ->label('Resource')
                    ->formatStateUsing(fn ($state) => class_basename($state))
                    ->icon('heroicon-o-cube'),
                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->tooltip(fn ($state) => $state),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP')
                    ->icon('heroicon-o-globe-alt')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Time')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->icon('heroicon-o-clock'),
            ])
            ->heading('📋 Recent Activity')
            ->description('Latest actions performed in the system')
            ->paginated(false);
    }
}
