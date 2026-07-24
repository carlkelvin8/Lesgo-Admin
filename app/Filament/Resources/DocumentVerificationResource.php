<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentVerificationResource\Pages;
use App\Models\DocumentVerification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DocumentVerificationResource extends Resource
{
    protected static ?string $model = DocumentVerification::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationGroup = 'User Management';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')->relationship('user', 'name')->searchable()->preload()->required(),
            Forms\Components\Select::make('document_type')->options(['id_card' => 'ID Card', 'drivers_license' => "Driver's License", 'nbi_clearance' => 'NBI Clearance', 'business_permit' => 'Business Permit', 'other' => 'Other'])->required(),
            Forms\Components\TextInput::make('document_url')->url()->required(),
            Forms\Components\Select::make('status')->options(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'])->default('pending'),
            Forms\Components\Textarea::make('reviewer_notes')->nullable(),
            Forms\Components\Select::make('reviewed_by')->relationship('reviewer', 'name')->searchable()->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('user.name')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('document_type')->badge(),
            Tables\Columns\TextColumn::make('status')->badge()->colors(['warning' => 'pending', 'success' => 'approved', 'danger' => 'rejected']),
            Tables\Columns\TextColumn::make('reviewer.name')->label('Reviewed By'),
            Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
        ])->filters([
            Tables\Filters\SelectFilter::make('status')->options(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected']),
        ])->actions([Tables\Actions\EditAction::make()])
        ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListDocumentVerifications::route('/'), 'create' => Pages\CreateDocumentVerification::route('/create'), 'edit' => Pages\EditDocumentVerification::route('/{record}/edit')];
    }

    public static function getNavigationBadge(): ?string
    {
        return null;
    }
}
