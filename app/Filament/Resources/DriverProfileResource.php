<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DriverProfileResource\Pages;
use App\Models\DriverProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DriverProfileResource extends Resource
{
    protected static ?string $model = DriverProfile::class;
    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'User Management';
    protected static ?int $navigationSort = 2;
    protected static ?string $modelLabel = 'Driver';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Driver Details')
                ->schema([
                    Forms\Components\Select::make('user_id')
                        ->label('User Account')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\Select::make('partner_id')
                        ->label('Partner')
                        ->relationship('partner', 'name')
                        ->searchable()
                        ->preload()
                        ->nullable(),
                    Forms\Components\Select::make('status')
                        ->required()
                        ->options([
                            'pending' => 'Pending',
                            'active' => 'Active',
                            'inactive' => 'Inactive',
                            'suspended' => 'Suspended',
                        ])
                        ->default('pending'),
                    Forms\Components\TextInput::make('rating')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(5)
                        ->default(0),
                    Forms\Components\TextInput::make('total_trips')
                        ->numeric()
                        ->default(0),
                    Forms\Components\TextInput::make('license_number')
                        ->maxLength(100)
                        ->nullable(),
                    Forms\Components\DatePicker::make('license_expiry_date')
                        ->nullable(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Driver Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('partner.name')
                    ->label('Partner')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'active',
                        'gray' => 'inactive',
                        'danger' => 'suspended',
                    ]),
                Tables\Columns\TextColumn::make('rating')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_trips')
                    ->label('Trips')
                    ->sortable(),
                Tables\Columns\TextColumn::make('license_number')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('license_expiry_date')
                    ->date()
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
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'suspended' => 'Suspended',
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
            DriverProfileResource\RelationManagers\VehiclesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDriverProfiles::route('/'),
            'create' => Pages\CreateDriverProfile::route('/create'),
            'view' => Pages\ViewDriverProfile::route('/{record}'),
            'edit' => Pages\EditDriverProfile::route('/{record}/edit'),
        ];
    }
}
