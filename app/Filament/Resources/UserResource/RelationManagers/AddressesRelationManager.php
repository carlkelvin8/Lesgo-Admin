<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AddressesRelationManager extends RelationManager
{
    protected static string $relationship = 'addresses';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('label')
                    ->required()
                    ->maxLength(100)
                    ->placeholder('Home, Office, etc.'),
                Forms\Components\TextInput::make('contact_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('contact_phone')
                    ->tel()
                    ->maxLength(50),
                Forms\Components\TextInput::make('address_line1')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address_line2')
                    ->maxLength(255),
                Forms\Components\TextInput::make('city')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('region')
                    ->maxLength(100),
                Forms\Components\TextInput::make('country')
                    ->default('Philippines')
                    ->maxLength(100),
                Forms\Components\TextInput::make('postal_code')
                    ->maxLength(20),
                Forms\Components\TextInput::make('latitude')
                    ->numeric(),
                Forms\Components\TextInput::make('longitude')
                    ->numeric(),
                Forms\Components\Toggle::make('is_default')
                    ->label('Default Address'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('label')
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->badge(),
                Tables\Columns\TextColumn::make('address_line1')
                    ->limit(40),
                Tables\Columns\TextColumn::make('city'),
                Tables\Columns\TextColumn::make('contact_phone'),
                Tables\Columns\IconColumn::make('is_default')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_default')
                    ->label('Default Address'),
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
