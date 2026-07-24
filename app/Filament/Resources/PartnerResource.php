<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartnerResource\Pages;
use App\Models\Partner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class PartnerResource extends Resource
{
    protected static ?string $model = Partner::class;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['user']);
    }

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Business';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationDescription(): ?string
    {
        return 'Manage merchants and stores';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Business Information')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-building-office')
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug($state))),
                    Forms\Components\TextInput::make('legal_name')
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-document-text'),
                    Forms\Components\TextInput::make('slug')
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-link'),
                    Forms\Components\Select::make('business_type')
                        ->options([
                            'logistics' => 'Logistics',
                            'delivery' => 'Delivery',
                            'transport' => 'Transport',
                            'courier' => 'Courier',
                            'food' => 'Food & Restaurant',
                            'other' => 'Other',
                        ]),
                    Forms\Components\Select::make('category')
                        ->options([
                            'food' => 'Food & Beverage',
                            'grocery' => 'Grocery',
                            'logistics' => 'Logistics',
                            'delivery' => 'Delivery',
                            'services' => 'Services',
                        ])
                        ->nullable(),
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
                    Forms\Components\Textarea::make('description')
                        ->maxLength(1000)
                        ->columnSpanFull(),
                ])->columns(2),

            Forms\Components\Section::make('Branding')
                ->schema([
                    Forms\Components\TextInput::make('logo_url')
                        ->label('Logo URL')
                        ->url()
                        ->maxLength(500),
                    Forms\Components\TextInput::make('cover_image_url')
                        ->label('Cover Image URL')
                        ->url()
                        ->maxLength(500),
                ])->columns(2),

            Forms\Components\Section::make('Contact & Owner')
                ->schema([
                    Forms\Components\TextInput::make('support_email')
                        ->email()
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-envelope'),
                    Forms\Components\TextInput::make('support_phone')
                        ->tel()
                        ->maxLength(100)
                        ->prefixIcon('heroicon-o-phone'),
                    Forms\Components\Select::make('user_id')
                        ->label('Owner Account')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->nullable(),
                ])->columns(2),

            Forms\Components\Section::make('Delivery & Operations')
                ->schema([
                    Forms\Components\TextInput::make('delivery_fee')
                        ->numeric()
                        ->prefix('PHP')
                        ->default(0),
                    Forms\Components\TextInput::make('min_order_amount')
                        ->numeric()
                        ->prefix('PHP')
                        ->default(0),
                    Forms\Components\TextInput::make('estimated_delivery_minutes')
                        ->numeric()
                        ->suffix('mins')
                        ->default(30),
                    Forms\Components\TextInput::make('rating')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(5)
                        ->default(0),
                    Forms\Components\TextInput::make('total_reviews')
                        ->numeric()
                        ->default(0),
                    Forms\Components\Toggle::make('is_open')
                        ->label('Currently Open')
                        ->default(true),
                    Forms\Components\Toggle::make('is_featured')
                        ->label('Featured Partner')
                        ->default(false),
                    Forms\Components\Toggle::make('accepts_online_payment')
                        ->default(true),
                ])->columns(2),

            Forms\Components\Section::make('Tags & Cuisine')
                ->schema([
                    Forms\Components\TagsInput::make('tags')
                        ->nullable(),
                    Forms\Components\TagsInput::make('cuisine_types')
                        ->label('Cuisine Types')
                        ->nullable(),
                ])->columns(2),

            Forms\Components\Section::make('Merchant Documents')
                ->description('Documents submitted by the merchant for verification (selfie, valid ID, digital signature, permits)')
                ->schema([
                    Forms\Components\ViewField::make('documents_display')
                        ->label('')
                        ->view('filament.components.partner-documents')
                        ->columnSpanFull()
                        ->visible(fn ($record) => !empty($record?->documents)),
                    Forms\Components\KeyValue::make('documents')
                        ->label('Document Paths')
                        ->keyLabel('Document Type')
                        ->valueLabel('File Path')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('business_type')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'active',
                        'danger' => 'suspended',
                        'gray' => 'rejected',
                    ]),
                Tables\Columns\TextColumn::make('rating')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_open')
                    ->boolean()
                    ->label('Open'),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean()
                    ->label('Featured')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('delivery_fee')
                    ->money('PHP')
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
                        'suspended' => 'Suspended',
                        'rejected' => 'Rejected',
                    ]),
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'food' => 'Food & Beverage',
                        'grocery' => 'Grocery',
                        'logistics' => 'Logistics',
                        'delivery' => 'Delivery',
                        'services' => 'Services',
                    ]),
                Tables\Filters\TernaryFilter::make('is_open')
                    ->label('Open Status'),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured'),
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
            PartnerResource\RelationManagers\BranchesRelationManager::class,
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
        return null;
    }
}

