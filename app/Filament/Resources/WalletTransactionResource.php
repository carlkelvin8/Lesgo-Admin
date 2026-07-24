<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WalletTransactionResource\Pages;
use App\Models\WalletTransaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WalletTransactionResource extends Resource
{
    protected static ?string $model = WalletTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Transaction Details')
                ->schema([
                    Forms\Components\Select::make('wallet_id')
                        ->label('Wallet')
                        ->relationship('wallet', 'id')
                        ->getOptionLabelFromRecordUsing(fn ($record) => $record->user->name . ' - Wallet #' . $record->id)
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\Select::make('type')
                        ->options([
                            'credit' => 'Credit',
                            'debit' => 'Debit',
                        ])
                        ->required(),
                    Forms\Components\TextInput::make('amount')
                        ->numeric()
                        ->prefix('PHP')
                        ->required(),
                    Forms\Components\TextInput::make('balance_after')
                        ->numeric()
                        ->prefix('PHP'),
                    Forms\Components\TextInput::make('description')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('reference')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('reference_type')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('reference_id')
                        ->numeric(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('wallet.user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'credit' => 'success',
                        'debit' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('amount')
                    ->money('PHP')
                    ->sortable(),
                Tables\Columns\TextColumn::make('balance_after')
                    ->money('PHP'),
                Tables\Columns\TextColumn::make('description')
                    ->limit(30)
                    ->searchable(),
                Tables\Columns\TextColumn::make('reference')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'credit' => 'Credit',
                        'debit' => 'Debit',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWalletTransactions::route('/'),
            'create' => Pages\CreateWalletTransaction::route('/create'),
            'view' => Pages\ViewWalletTransaction::route('/{record}'),
            'edit' => Pages\EditWalletTransaction::route('/{record}/edit'),
        ];
    }
}
