<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DriverDutyAttendanceResource\Pages;
use App\Models\DriverDutyAttendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DriverDutyAttendanceResource extends Resource
{
    protected static ?string $model = DriverDutyAttendance::class;
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Operations';
    protected static ?int $navigationSort = 6;
    protected static ?string $modelLabel = 'Duty Attendance';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('driver_profile_id')->relationship('driver', 'id')->getOptionLabelFromRecordUsing(fn ($record) => $record->user->name ?? 'Driver #' . $record->id)->searchable()->preload()->required(),
            Forms\Components\DateTimePicker::make('clock_in_at')->required(),
            Forms\Components\DateTimePicker::make('clock_out_at')->nullable(),
            Forms\Components\Select::make('status')->options(['on_duty' => 'On Duty', 'off_duty' => 'Off Duty', 'break' => 'Break'])->default('on_duty'),
            Forms\Components\TextInput::make('total_hours')->numeric()->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('driver.user.name')->label('Driver')->searchable(),
            Tables\Columns\TextColumn::make('clock_in_at')->dateTime()->sortable(),
            Tables\Columns\TextColumn::make('clock_out_at')->dateTime(),
            Tables\Columns\TextColumn::make('status')->badge()->colors(['success' => 'on_duty', 'gray' => 'off_duty', 'warning' => 'break']),
            Tables\Columns\TextColumn::make('total_hours')->suffix(' hrs'),
        ])->actions([Tables\Actions\EditAction::make()])
        ->defaultSort('clock_in_at', 'desc');
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListDriverDutyAttendances::route('/'), 'create' => Pages\CreateDriverDutyAttendance::route('/create'), 'edit' => Pages\EditDriverDutyAttendance::route('/{record}/edit')];
    }
}
