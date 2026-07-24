<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GeofenceResource\Pages;
use App\Models\Geofence;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GeofenceResource extends Resource
{
    protected static ?string $model = Geofence::class;
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationGroup = 'System';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required()->maxLength(255),
            Forms\Components\Select::make('type')->options(['circle' => 'Circle', 'polygon' => 'Polygon'])->required(),
            Forms\Components\TextInput::make('radius')->numeric()->nullable()->suffix('meters'),
            Forms\Components\Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('type')->badge(),
            Tables\Columns\TextColumn::make('radius')->suffix(' m'),
            Tables\Columns\IconColumn::make('is_active')->boolean(),
            Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
        ])->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
        ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListGeofences::route('/'), 'create' => Pages\CreateGeofence::route('/create'), 'edit' => Pages\EditGeofence::route('/{record}/edit')];
    }
}
