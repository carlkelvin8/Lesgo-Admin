<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['customer', 'service', 'partner', 'driver']);
    }

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Operations';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Order Details')
                ->schema([
                    Forms\Components\Select::make('customer_id')
                        ->label('Customer')
                        ->relationship('customer', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\Select::make('service_id')
                        ->label('Service')
                        ->relationship('service', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\Select::make('partner_id')
                        ->label('Partner')
                        ->relationship('partner', 'name')
                        ->searchable()
                        ->preload()
                        ->nullable(),
                    Forms\Components\Select::make('driver_id')
                        ->label('Driver')
                        ->relationship('driver', 'id')
                        ->searchable()
                        ->preload()
                        ->nullable(),
                    Forms\Components\Select::make('status')
                        ->required()
                        ->options([
                            'pending' => 'Pending',
                            'accepted' => 'Accepted',
                            'driver_arrived' => 'Driver Arrived',
                            'in_progress' => 'In Progress',
                            'picked_up' => 'Picked Up',
                            'completed' => 'Completed',
                            'cancelled' => 'Cancelled',
                        ])
                        ->default('pending'),
                    Forms\Components\Select::make('payment_method')
                        ->options([
                            'cash' => 'Cash',
                            'gcash' => 'GCash',
                            'maya' => 'Maya',
                            'card' => 'Card',
                            'wallet' => 'Wallet',
                        ])
                        ->default('cash'),
                    Forms\Components\Select::make('payment_status')
                        ->options([
                            'pending' => 'Pending',
                            'paid' => 'Paid',
                            'failed' => 'Failed',
                            'refunded' => 'Refunded',
                        ])
                        ->default('pending'),
                ])->columns(2),

            Forms\Components\Section::make('Fare & Financials')
                ->schema([
                    Forms\Components\TextInput::make('estimated_fare')
                        ->numeric()
                        ->prefix('PHP'),
                    Forms\Components\TextInput::make('actual_fare')
                        ->numeric()
                        ->prefix('PHP'),
                    Forms\Components\TextInput::make('partner_share')
                        ->numeric()
                        ->prefix('PHP'),
                    Forms\Components\TextInput::make('driver_share')
                        ->numeric()
                        ->prefix('PHP'),
                    Forms\Components\TextInput::make('platform_fee')
                        ->numeric()
                        ->prefix('PHP'),
                    Forms\Components\TextInput::make('platform_share')
                        ->numeric()
                        ->prefix('PHP'),
                    Forms\Components\TextInput::make('voucher_code')
                        ->maxLength(50)
                        ->nullable(),
                    Forms\Components\TextInput::make('voucher_discount')
                        ->numeric()
                        ->prefix('PHP')
                        ->nullable(),
                ])->columns(2),

            Forms\Components\Section::make('Distance & Timing')
                ->schema([
                    Forms\Components\TextInput::make('estimated_distance_m')
                        ->label('Estimated Distance (m)')
                        ->numeric(),
                    Forms\Components\TextInput::make('actual_distance_m')
                        ->label('Actual Distance (m)')
                        ->numeric(),
                    Forms\Components\DateTimePicker::make('scheduled_at'),
                    Forms\Components\DateTimePicker::make('scheduled_delivery_time'),
                    Forms\Components\DateTimePicker::make('accepted_at'),
                    Forms\Components\DateTimePicker::make('driver_arrived_at'),
                    Forms\Components\DateTimePicker::make('picked_up_at'),
                    Forms\Components\DateTimePicker::make('completed_at'),
                    Forms\Components\DateTimePicker::make('cancelled_at'),
                    Forms\Components\Textarea::make('cancel_reason')
                        ->nullable()
                        ->columnSpanFull(),
                ])->columns(2),

            Forms\Components\Section::make('Delivery Evidence')
                ->schema([
                    Forms\Components\FileUpload::make('pickup_picture')
                        ->label('Pick-up Picture')
                        ->image()
                        ->directory('order-evidence'),
                    Forms\Components\FileUpload::make('dropoff_picture')
                        ->label('Drop-off Picture')
                        ->image()
                        ->directory('order-evidence'),
                    Forms\Components\FileUpload::make('proof_pickup_image')
                        ->label('Proof of Pickup')
                        ->image()
                        ->directory('order-evidence'),
                    Forms\Components\FileUpload::make('proof_delivery_image')
                        ->label('Proof of Delivery')
                        ->image()
                        ->directory('order-evidence'),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Order #')
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Service')
                    ->searchable(),
                Tables\Columns\TextColumn::make('partner.name')
                    ->label('Partner')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'accepted',
                        'primary' => fn ($state) => in_array($state, ['picked_up', 'driver_arrived', 'in_progress']),
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ]),
                Tables\Columns\BadgeColumn::make('payment_status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger' => 'failed',
                        'gray' => 'refunded',
                    ]),
                Tables\Columns\TextColumn::make('actual_fare')
                    ->label('Fare')
                    ->money('PHP')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'accepted' => 'Accepted',
                        'driver_arrived' => 'Driver Arrived',
                        'in_progress' => 'In Progress',
                        'picked_up' => 'Picked Up',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ]),
                Tables\Filters\SelectFilter::make('payment_method')
                    ->options([
                        'cash' => 'Cash',
                        'gcash' => 'GCash',
                        'maya' => 'Maya',
                        'card' => 'Card',
                        'wallet' => 'Wallet',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            OrderResource\RelationManagers\PaymentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return Cache::remember('pending_orders_count', 60, function () {
            return static::getModel()::where('status', 'pending')->count();
        });
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getNavigationBadge() > 0 ? 'warning' : 'success';
    }
}
