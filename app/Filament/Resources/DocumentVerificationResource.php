<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentVerificationResource\Pages;
use App\Models\DriverProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DocumentVerificationResource extends Resource
{
    protected static ?string $model = DriverProfile::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationGroup = 'User Management';
    protected static ?int $navigationSort = 4;
    protected static ?string $modelLabel = 'Document Verification';
    protected static ?string $pluralModelLabel = 'Document Verifications';
    protected static ?string $slug = 'document-verifications';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['user'])->whereNotNull('id_document_path')
            ->orWhereNotNull('clearance_document_path')
            ->orWhereNotNull('license_document_path')
            ->orWhereNotNull('biodata_document_path')
            ->orWhereNotNull('motor_registration_path')
            ->orWhereNotNull('motor_or_path');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Driver Information')
                ->schema([
                    Forms\Components\Select::make('user_id')
                        ->relationship('user', 'name')
                        ->disabled(),
                    Forms\Components\Select::make('status')
                        ->options(['pending' => 'Pending', 'active' => 'Active', 'suspended' => 'Suspended'])
                        ->required(),
                ])->columns(2),

            Forms\Components\Section::make('Uploaded Documents')
                ->description('Documents submitted by the driver for verification')
                ->schema([
                    Forms\Components\FileUpload::make('id_document_path')
                        ->label('Valid ID')
                        ->directory('driver-requirements')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf'])
                        ->openable()
                        ->downloadable(),
                    Forms\Components\FileUpload::make('clearance_document_path')
                        ->label('Barangay / Police / NBI Clearance')
                        ->directory('driver-requirements')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf'])
                        ->openable()
                        ->downloadable(),
                    Forms\Components\FileUpload::make('license_document_path')
                        ->label("Driver's License")
                        ->directory('driver-requirements')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf'])
                        ->openable()
                        ->downloadable(),
                    Forms\Components\FileUpload::make('biodata_document_path')
                        ->label('Biodata / Resume')
                        ->directory('driver-requirements')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf'])
                        ->openable()
                        ->downloadable(),
                    Forms\Components\FileUpload::make('motor_registration_path')
                        ->label('Motor Registration (CR)')
                        ->directory('driver-requirements')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf'])
                        ->openable()
                        ->downloadable(),
                    Forms\Components\FileUpload::make('motor_or_path')
                        ->label('Motor Official Receipt (OR)')
                        ->directory('driver-requirements')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf'])
                        ->openable()
                        ->downloadable(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('user.name')
                ->label('Driver Name')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('status')
                ->badge()
                ->colors(['warning' => 'pending', 'success' => 'active', 'danger' => 'suspended']),
            Tables\Columns\IconColumn::make('id_document_path')
                ->label('ID')
                ->boolean()
                ->getStateUsing(fn ($record) => !empty($record->id_document_path)),
            Tables\Columns\IconColumn::make('clearance_document_path')
                ->label('Clearance')
                ->boolean()
                ->getStateUsing(fn ($record) => !empty($record->clearance_document_path)),
            Tables\Columns\IconColumn::make('license_document_path')
                ->label('License')
                ->boolean()
                ->getStateUsing(fn ($record) => !empty($record->license_document_path)),
            Tables\Columns\IconColumn::make('biodata_document_path')
                ->label('Biodata')
                ->boolean()
                ->getStateUsing(fn ($record) => !empty($record->biodata_document_path)),
            Tables\Columns\IconColumn::make('motor_registration_path')
                ->label('CR')
                ->boolean()
                ->getStateUsing(fn ($record) => !empty($record->motor_registration_path)),
            Tables\Columns\IconColumn::make('motor_or_path')
                ->label('OR')
                ->boolean()
                ->getStateUsing(fn ($record) => !empty($record->motor_or_path)),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable(),
        ])->filters([
            Tables\Filters\SelectFilter::make('status')
                ->options(['pending' => 'Pending', 'active' => 'Active', 'suspended' => 'Suspended']),
        ])->actions([
            Tables\Actions\EditAction::make()->label('Review'),
            Tables\Actions\Action::make('approve')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->action(fn ($record) => $record->update(['status' => 'active']))
                ->visible(fn ($record) => $record->status === 'pending'),
            Tables\Actions\Action::make('reject')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->action(fn ($record) => $record->update(['status' => 'suspended']))
                ->visible(fn ($record) => $record->status !== 'suspended'),
        ])
        ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocumentVerifications::route('/'),
            'edit' => Pages\EditDocumentVerification::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return null;
    }
}
