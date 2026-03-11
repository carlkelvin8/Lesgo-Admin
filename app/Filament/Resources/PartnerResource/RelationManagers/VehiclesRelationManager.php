<?php

namespace App\Filament\Resources\PartnerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class VehiclesRelationManager extends RelationManager
{
    protected static string $relationship = 'vehicles';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('driver_id')
                    ->label('Driver')
                    ->relationship('driver', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->user->name ?? 'N/A')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('type')
                    ->required()
                    ->options([
                        'sedan' => 'Sedan',
                        'suv' => 'SUV',
                        'van' => 'Van',
                        'motorcycle' => 'Motorcycle',
                        'truck' => 'Truck',
                    ]),
                Forms\Components\TextInput::make('plate_number')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('brand')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('model')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('color')
                    ->maxLength(50),
                Forms\Components\TextInput::make('year')
                    ->numeric()
                    ->minValue(1990)
                    ->maxValue(2030),
                Forms\Components\Select::make('status')
                    ->required()
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'maintenance' => 'Maintenance',
                    ])
                    ->default('active'),
                Forms\Components\Toggle::make('is_primary')
                    ->label('Primary Vehicle'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('plate_number')
            ->columns([
                Tables\Columns\TextColumn::make('driver.user.name')
                    ->label('Driver'),
                Tables\Columns\TextColumn::make('type')
                    ->badge(),
                Tables\Columns\TextColumn::make('plate_number'),
                Tables\Columns\TextColumn::make('brand'),
                Tables\Columns\TextColumn::make('model'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'gray' => 'inactive',
                        'warning' => 'maintenance',
                    ]),
                Tables\Columns\IconColumn::make('is_primary')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'maintenance' => 'Maintenance',
                    ]),
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'sedan' => 'Sedan',
                        'suv' => 'SUV',
                        'van' => 'Van',
                        'motorcycle' => 'Motorcycle',
                        'truck' => 'Truck',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
