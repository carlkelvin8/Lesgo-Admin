<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartnerResource\Pages;
use App\Models\Partner;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PartnerResource extends Resource
{
    protected static ?string $model = Partner::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Business';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Business Information')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug($state))),
                    Forms\Components\TextInput::make('legal_name')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('slug')
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    Forms\Components\Select::make('business_type')
                        ->options([
                            'logistics' => 'Logistics',
                            'delivery' => 'Delivery',
                            'transport' => 'Transport',
                            'courier' => 'Courier',
                            'other' => 'Other',
                        ]),
                    Forms\Components\TextInput::make('tax_id')
                        ->label('Tax ID')
                        ->maxLength(100),
                    Forms\Components\Select::make('status')
                        ->required()
                        ->options([
                            'pending' => 'Pending',
                            'active' => 'Active',
                            'suspended' => 'Suspended',
                            'rejected' => 'Rejected',
                        ])
                        ->default('pending'),
                ])->columns(2),
            Forms\Components\Section::make('Contact Information')
                ->schema([
                    Forms\Components\Textarea::make('store_address')
                        ->label('Store Address')
                        ->maxLength(500)
                        ->rows(3)
                        ->placeholder('Enter complete store address'),
                    Forms\Components\TextInput::make('support_phone')
                        ->tel()
                        ->maxLength(100),
                    Forms\Components\Select::make('user_id')
                        ->label('Owner Account')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->nullable(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('business_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('store_address')
                    ->label('Store Address')
                    ->searchable()
                    ->limit(50)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('support_phone')
                    ->toggleable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'active',
                        'danger' => 'suspended',
                        'gray' => 'rejected',
                    ]),
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
                        'suspended' => 'Suspended',
                        'rejected' => 'Rejected',
                    ]),
                Tables\Filters\SelectFilter::make('business_type')
                    ->options([
                        'logistics' => 'Logistics',
                        'delivery' => 'Delivery',
                        'transport' => 'Transport',
                        'courier' => 'Courier',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            PartnerResource\RelationManagers\BranchesRelationManager::class,
            PartnerResource\RelationManagers\ServicesRelationManager::class,
            PartnerResource\RelationManagers\DriversRelationManager::class,
            PartnerResource\RelationManagers\VehiclesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartners::route('/'),
            'create' => Pages\CreatePartner::route('/create'),
            'view' => Pages\ViewPartner::route('/{record}'),
            'edit' => Pages\EditPartner::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'active')->count();
    }
}
