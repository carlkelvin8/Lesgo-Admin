<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MissionTemplateResource\Pages;
use App\Models\MissionTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MissionTemplateResource extends Resource
{
    protected static ?string $model = MissionTemplate::class;
    protected static ?string $navigationIcon = 'heroicon-o-flag';
    protected static ?string $navigationGroup = 'Operations';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')->required()->maxLength(255),
            Forms\Components\Textarea::make('description')->nullable(),
            Forms\Components\Select::make('type')->options(['daily' => 'Daily', 'weekly' => 'Weekly', 'monthly' => 'Monthly', 'one_time' => 'One Time'])->required(),
            Forms\Components\Select::make('goal_type')->options(['complete_orders' => 'Complete Orders', 'get_rating' => 'Get Rating', 'refer_friend' => 'Refer Friend', 'specific_service' => 'Specific Service'])->required(),
            Forms\Components\TextInput::make('goal_target')->numeric()->required(),
            Forms\Components\TextInput::make('reward_amount')->numeric()->prefix('PHP')->required(),
            Forms\Components\TextInput::make('service_code')->nullable(),
            Forms\Components\Select::make('target_audience')->options(['driver' => 'Driver', 'customer' => 'Customer', 'merchant' => 'Merchant'])->nullable(),
            Forms\Components\Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('type')->badge(),
            Tables\Columns\TextColumn::make('goal_type')->badge(),
            Tables\Columns\TextColumn::make('goal_target'),
            Tables\Columns\TextColumn::make('reward_amount')->money('PHP'),
            Tables\Columns\TextColumn::make('target_audience')->badge(),
            Tables\Columns\IconColumn::make('is_active')->boolean(),
        ])->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
        ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListMissionTemplates::route('/'), 'create' => Pages\CreateMissionTemplate::route('/create'), 'edit' => Pages\EditMissionTemplate::route('/{record}/edit')];
    }
}
