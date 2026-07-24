<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WalletLinkedAccountResource\Pages;
use App\Models\WalletLinkedAccount;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WalletLinkedAccountResource extends Resource
{
    protected static ?string $model = WalletLinkedAccount::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Linked Account Details')
                ->schema([
                    Forms\Components\Select::make('user_id')
                        ->label('User')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\TextInput::make('provider')
                        ->required()
                        ->maxLength(100),
                    Forms\Components\TextInput::make('account_number')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('account_name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Toggle::make('is_default')
                        ->default(false),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('provider')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('account_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('account_name')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_default')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('provider')
                    ->options(fn () => WalletLinkedAccount::distinct()->pluck('provider', 'provider')->toArray()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListWalletLinkedAccounts::route('/'),
            'create' => Pages\CreateWalletLinkedAccount::route('/create'),
            'view' => Pages\ViewWalletLinkedAccount::route('/{record}'),
            'edit' => Pages\EditWalletLinkedAccount::route('/{record}/edit'),
        ];
    }
}
