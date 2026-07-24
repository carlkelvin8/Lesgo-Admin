<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DriverProfileResource\Pages;
use App\Models\DriverProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DriverProfileResource extends Resource
{
    protected static ?string $model = DriverProfile::class;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['user', 'partner']);
    }

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'User Management';
    protected static ?int $navigationSort = 2;
    protected static ?string $modelLabel = 'Driver';

    public static function getNavigationDescription(): ?string
    {
        return 'Manage rider profiles and documents';
    }

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
                    Forms\Components\Select::make('package_tier')
                        ->options([
                            'basic' => 'Basic',
                            'standard' => 'Standard',
                            'premium' => 'Premium',
                        ])
                        ->nullable(),
                    Forms\Components\TextInput::make('commission_rate')
                        ->label('Commission Rate (%)')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->default(0)
                        ->suffix('%'),
                    Forms\Components\TextInput::make('rating')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(5)
                        ->default(0),
                    Forms\Components\TextInput::make('total_trips')
                        ->numeric()
                        ->default(0),
                ])->columns(2),

            Forms\Components\Section::make('License & Vehicle')
                ->schema([
                    Forms\Components\TextInput::make('license_number')
                        ->maxLength(100)
                        ->nullable(),
                    Forms\Components\DatePicker::make('license_expiry_date')
                        ->nullable(),
                    Forms\Components\Select::make('vehicle_type')
                        ->options([
                            'motorcycle' => 'Motorcycle',
                            'car' => 'Car',
                            'van' => 'Van',
                            'truck' => 'Truck',
                            'bicycle' => 'Bicycle',
                        ])
                        ->nullable(),
                    Forms\Components\TextInput::make('plate_number')
                        ->maxLength(20)
                        ->nullable(),
                ])->columns(2),

            Forms\Components\Section::make('Driver Requirements')
                ->description('Documents submitted by the rider for verification')
                ->schema([
                    Forms\Components\ViewField::make('documents_display')
                        ->label('')
                        ->view('filament.components.driver-documents')
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('id_document_path')
                        ->label('Valid ID Path')
                        ->disabled(),
                    Forms\Components\TextInput::make('clearance_document_path')
                        ->label('Clearance Path')
                        ->disabled(),
                    Forms\Components\TextInput::make('license_document_path')
                        ->label("License Path")
                        ->disabled(),
                    Forms\Components\TextInput::make('biodata_document_path')
                        ->label('Biodata Path')
                        ->disabled(),
                    Forms\Components\TextInput::make('motor_registration_path')
                        ->label('Motor Registration Path')
                        ->disabled(),
                    Forms\Components\TextInput::make('motor_or_path')
                        ->label('Motor OR Path')
                        ->disabled(),
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
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'active',
                        'gray' => 'inactive',
                        'danger' => 'suspended',
                    ]),
                Tables\Columns\TextColumn::make('vehicle_type')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('plate_number')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('commission_rate')
                    ->label('Commission')
                    ->suffix('%')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('rating')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_trips')
                    ->label('Trips')
                    ->sortable(),
                Tables\Columns\TextColumn::make('package_tier')
                    ->label('Tier')
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
                Tables\Filters\SelectFilter::make('vehicle_type')
                    ->options([
                        'motorcycle' => 'Motorcycle',
                        'car' => 'Car',
                        'van' => 'Van',
                        'truck' => 'Truck',
                        'bicycle' => 'Bicycle',
                    ]),
                Tables\Filters\SelectFilter::make('package_tier')
                    ->options([
                        'basic' => 'Basic',
                        'standard' => 'Standard',
                        'premium' => 'Premium',
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

