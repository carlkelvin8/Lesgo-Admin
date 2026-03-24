<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleResource\Pages;
use App\Models\Vehicle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['driver.user', 'partner']);
    }

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Operations';
    protected static ?int $navigationSort = 2;
    protected static ?string $recordTitleAttribute = 'plate_number';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Vehicle Details')
                ->schema([
                    Forms\Components\Select::make('driver_id')
                        ->label('Driver')
                        ->relationship('driver.user', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\Select::make('partner_id')
                        ->label('Partner')
                        ->relationship('partner', 'name')
                        ->searchable()
                        ->preload()
                        ->nullable(),
                    Forms\Components\Select::make('type')
                        ->required()
                        ->options([
                            'motorcycle' => 'Motorcycle',
                            'car' => 'Car',
                            'van' => 'Van',
                            'truck' => 'Truck',
                            'bicycle' => 'Bicycle',
                        ]),
                    Forms\Components\TextInput::make('plate_number')
                        ->required()
                        ->maxLength(100),
                    Forms\Components\TextInput::make('brand')
                        ->maxLength(100)
                        ->nullable(),
                    Forms\Components\TextInput::make('model')
                        ->maxLength(100)
                        ->nullable(),
                    Forms\Components\TextInput::make('color')
                        ->maxLength(50)
                        ->nullable(),
                    Forms\Components\TextInput::make('year')
                        ->numeric()
                        ->minValue(1990)
                        ->maxValue(2030)
                        ->nullable(),
                    Forms\Components\Select::make('status')
                        ->required()
                        ->options([
                            'active' => 'Active',
                            'inactive' => 'Inactive',
                            'maintenance' => 'Maintenance',
                        ])
                        ->default('active'),
                    Forms\Components\Toggle::make('is_primary')
                        ->default(true),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('plate_number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge(),
                Tables\Columns\TextColumn::make('brand')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('model')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('color')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('year')
                    ->toggleable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'gray' => 'inactive',
                        'warning' => 'maintenance',
                    ]),
                Tables\Columns\IconColumn::make('is_primary')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'motorcycle' => 'Motorcycle',
                        'car' => 'Car',
                        'van' => 'Van',
                        'truck' => 'Truck',
                        'bicycle' => 'Bicycle',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'maintenance' => 'Maintenance',
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicle::route('/create'),
            'view' => Pages\ViewVehicle::route('/{record}'),
            'edit' => Pages\EditVehicle::route('/{record}/edit'),
        ];
    }
}
