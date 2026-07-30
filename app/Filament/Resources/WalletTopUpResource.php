<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WalletTopUpResource\Pages;
use App\Models\WalletTopUp;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class WalletTopUpResource extends Resource
{
    protected static ?string $model = WalletTopUp::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-circle';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['user']);
    }
    protected static ?string $navigationGroup = 'Finance';
    protected static ?int $navigationSort = 3;
    protected static ?string $modelLabel = 'Wallet Top-Up';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')->relationship('user', 'name')->searchable()->preload()->required(),
            Forms\Components\TextInput::make('amount')->numeric()->prefix('PHP')->required(),
            Forms\Components\Select::make('status')->options(['pending' => 'Pending', 'paid' => 'Paid', 'failed' => 'Failed', 'expired' => 'Expired'])->default('pending'),
            Forms\Components\TextInput::make('payment_method')->default('xendit'),
            Forms\Components\TextInput::make('external_id')->nullable(),
            Forms\Components\TextInput::make('gateway_name')->nullable(),
            Forms\Components\TextInput::make('gateway_reference')->nullable(),
            Forms\Components\TextInput::make('convenience_fee')->numeric()->prefix('PHP')->nullable(),
            Forms\Components\TextInput::make('total_charged')->numeric()->prefix('PHP')->nullable(),
            Forms\Components\DateTimePicker::make('paid_at')->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('user.name')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('amount')->money('PHP')->sortable(),
            Tables\Columns\TextColumn::make('status')->badge()->colors(['warning' => 'pending', 'success' => 'paid', 'danger' => 'failed', 'gray' => 'expired']),
            Tables\Columns\TextColumn::make('payment_method'),
            Tables\Columns\TextColumn::make('paid_at')->dateTime(),
            Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
        ])->filters([
            Tables\Filters\SelectFilter::make('status')->options(['pending' => 'Pending', 'paid' => 'Paid', 'failed' => 'Failed', 'expired' => 'Expired']),
        ])->actions([Tables\Actions\ViewAction::make(), Tables\Actions\EditAction::make()])
        ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListWalletTopUps::route('/'), 'create' => Pages\CreateWalletTopUp::route('/create'), 'edit' => Pages\EditWalletTopUp::route('/{record}/edit')];
    }
}
