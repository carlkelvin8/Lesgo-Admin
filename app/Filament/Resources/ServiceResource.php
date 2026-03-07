<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Business';
    protected static ?int $navigationSort = 2;
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Service Information')
                ->schema([
                    Forms\Components\TextInput::make('code')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(100),
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('partner_id')
                        ->label('Partner')
                        ->relationship('partner', 'name')
                        ->searchable()
                        ->preload()
                        ->nullable(),
                    Forms\Components\Toggle::make('is_active')
                        ->default(true),
                    Forms\Components\Textarea::make('description')
                        ->columnSpanFull()
                        ->nullable(),
                ])->columns(2),
            Forms\Components\Section::make('Pricing')
                ->schema([
                    Forms\Components\TextInput::make('base_fare')
                        ->required()
                        ->numeric()
                        ->prefix('PHP')
                        ->default(0),
                    Forms\Components\TextInput::make('per_km_rate')
                        ->required()
                        ->numeric()
                        ->prefix('PHP/km')
                        ->default(0),
                    Forms\Components\TextInput::make('per_minute_rate')
                        ->required()
                        ->numeric()
                        ->prefix('PHP/min')
                        ->default(0),
                    Forms\Components\TextInput::make('minimum_fare')
                        ->required()
                        ->numeric()
                        ->prefix('PHP')
                        ->default(0),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('partner.name')
                    ->label('Partner')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('base_fare')
                    ->money('PHP')
                    ->sortable(),
                Tables\Columns\TextColumn::make('minimum_fare')
                    ->money('PHP')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
                Tables\Filters\SelectFilter::make('partner')
                    ->relationship('partner', 'name'),
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
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'view' => Pages\ViewService::route('/{record}'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
