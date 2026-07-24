<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WalletResource\Pages;
use App\Models\Wallet;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WalletResource extends Resource
{
    protected static ?string $model = Wallet::class;
    protected static ?string $navigationIcon = 'heroicon-o-wallet';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?int $navigationSort = 2;

    public static function getNavigationDescription(): ?string
    {
        return 'View user wallet balances';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')->relationship('user', 'name')->searchable()->preload()->required(),
            Forms\Components\TextInput::make('balance')->numeric()->prefix('PHP')->default(0),
            Forms\Components\TextInput::make('currency')->default('PHP')->maxLength(3),
            Forms\Components\Select::make('status')->options(['active' => 'Active', 'frozen' => 'Frozen', 'closed' => 'Closed'])->default('active'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('user.name')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('balance')->money('PHP')->sortable(),
            Tables\Columns\TextColumn::make('status')->badge()->colors(['success' => 'active', 'danger' => 'frozen', 'gray' => 'closed']),
            Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
        ])->filters([
            Tables\Filters\SelectFilter::make('status')->options(['active' => 'Active', 'frozen' => 'Frozen', 'closed' => 'Closed']),
        ])->actions([Tables\Actions\ViewAction::make(), Tables\Actions\EditAction::make()])
        ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListWallets::route('/'), 'create' => Pages\CreateWallet::route('/create'), 'edit' => Pages\EditWallet::route('/{record}/edit')];
    }
}
