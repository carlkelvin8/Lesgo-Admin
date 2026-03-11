<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'User Management';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Personal Information')
                ->description('Basic user information and contact details')
                ->icon('heroicon-o-user')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-user'),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-envelope'),
                    Forms\Components\TextInput::make('phone_number')
                        ->tel()
                        ->maxLength(50)
                        ->prefixIcon('heroicon-o-phone'),
                    Forms\Components\Select::make('role')
                        ->required()
                        ->options([
                            'admin' => 'Admin',
                            'staff' => 'Staff',
                            'partner' => 'Partner',
                            'driver' => 'Driver',
                            'customer' => 'Customer',
                        ])
                        ->native(false)
                        ->prefixIcon('heroicon-o-shield-check'),
                ])->columns(2),
            
            Forms\Components\Section::make('Security')
                ->description('Password and verification settings')
                ->icon('heroicon-o-lock-closed')
                ->schema([
                    Forms\Components\TextInput::make('password')
                        ->password()
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-key')
                        ->revealable(),
                    Forms\Components\DateTimePicker::make('email_verified_at')
                        ->label('Email Verified At')
                        ->prefixIcon('heroicon-o-check-badge'),
                ])->columns(2),
            
            Forms\Components\Section::make('Ban Information')
                ->description('User ban status and details')
                ->icon('heroicon-o-no-symbol')
                ->schema([
                    Forms\Components\Toggle::make('is_banned')
                        ->label('Banned')
                        ->inline(false)
                        ->helperText('Toggle to ban/unban this user'),
                    Forms\Components\Textarea::make('ban_reason')
                        ->label('Ban Reason')
                        ->rows(3)
                        ->maxLength(500)
                        ->visible(fn (Forms\Get $get) => $get('is_banned')),
                ])->columns(1)
                ->collapsible()
                ->collapsed(fn ($record) => !$record?->is_banned),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->label('')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&color=38BDF8&background=F0F9FF'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->email),
                Tables\Columns\TextColumn::make('phone_number')
                    ->searchable()
                    ->icon('heroicon-o-phone')
                    ->toggleable(),
                Tables\Columns\BadgeColumn::make('role')
                    ->colors([
                        'danger' => 'admin',
                        'warning' => 'staff',
                        'success' => 'partner',
                        'info' => 'driver',
                        'gray' => 'customer',
                    ])
                    ->icons([
                        'heroicon-o-shield-check' => 'admin',
                        'heroicon-o-briefcase' => 'staff',
                        'heroicon-o-building-office' => 'partner',
                        'heroicon-o-truck' => 'driver',
                        'heroicon-o-user' => 'customer',
                    ]),
                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Verified')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),
                Tables\Columns\IconColumn::make('is_banned')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-no-symbol')
                    ->falseIcon('heroicon-o-check-badge')
                    ->trueColor('danger')
                    ->falseColor('success')
                    ->tooltip(fn ($record) => $record->is_banned ? 'Banned: ' . $record->ban_reason : 'Active'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'staff' => 'Staff',
                        'partner' => 'Partner',
                        'driver' => 'Driver',
                        'customer' => 'Customer',
                    ])
                    ->multiple()
                    ->preload(),
                Tables\Filters\TernaryFilter::make('email_verified_at')
                    ->label('Email Verified')
                    ->nullable()
                    ->trueLabel('Verified')
                    ->falseLabel('Not Verified')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('email_verified_at'),
                        false: fn (Builder $query) => $query->whereNull('email_verified_at'),
                    ),
                Tables\Filters\TernaryFilter::make('is_banned')
                    ->label('Ban Status')
                    ->nullable()
                    ->trueLabel('Banned')
                    ->falseLabel('Active')
                    ->queries(
                        true: fn (Builder $query) => $query->where('is_banned', true),
                        false: fn (Builder $query) => $query->where('is_banned', false),
                    ),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('ban')
                        ->icon('heroicon-o-no-symbol')
                        ->color('danger')
                        ->visible(fn ($record) => !$record->is_banned && $record->role !== 'admin')
                        ->requiresConfirmation()
                        ->form([
                            Forms\Components\Textarea::make('ban_reason')
                                ->label('Reason for Ban')
                                ->required()
                                ->rows(3)
                                ->maxLength(500),
                        ])
                        ->action(function ($record, array $data) {
                            $record->update([
                                'is_banned' => true,
                                'banned_at' => now(),
                                'ban_reason' => $data['ban_reason'],
                                'banned_by' => auth()->id(),
                            ]);
                        })
                        ->successNotificationTitle('User banned successfully'),
                    Tables\Actions\Action::make('unban')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->visible(fn ($record) => $record->is_banned)
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            $record->update([
                                'is_banned' => false,
                                'banned_at' => null,
                                'ban_reason' => null,
                                'banned_by' => null,
                            ]);
                        })
                        ->successNotificationTitle('User unbanned successfully'),
                    Tables\Actions\DeleteAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('ban')
                        ->icon('heroicon-o-no-symbol')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->form([
                            Forms\Components\Textarea::make('ban_reason')
                                ->label('Reason for Ban')
                                ->required()
                                ->rows(3)
                                ->maxLength(500),
                        ])
                        ->action(function ($records, array $data) {
                            foreach ($records as $record) {
                                if ($record->role !== 'admin') {
                                    $record->update([
                                        'is_banned' => true,
                                        'banned_at' => now(),
                                        'ban_reason' => $data['ban_reason'],
                                        'banned_by' => auth()->id(),
                                    ]);
                                }
                            }
                        })
                        ->deselectRecordsAfterCompletion()
                        ->successNotificationTitle('Selected users banned successfully'),
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s')
            ->striped();
    }

    public static function getRelations(): array
    {
        return [
            UserResource\RelationManagers\AddressesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
