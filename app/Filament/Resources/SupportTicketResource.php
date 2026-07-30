<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupportTicketResource\Pages;
use App\Models\SupportTicket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SupportTicketResource extends Resource
{
    protected static ?string $model = SupportTicket::class;

    protected static ?string $navigationIcon = 'heroicon-o-lifebuoy';
    protected static ?string $navigationGroup = 'Operations';
    protected static ?int $navigationSort = 3;
    protected static ?string $recordTitleAttribute = 'ticket_number';

    public static function getNavigationBadge(): ?string
    {
        return (string) SupportTicket::whereIn('status', ['open', 'in_progress'])->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['user', 'assignee']);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Ticket Details')
                ->schema([
                    Forms\Components\TextInput::make('ticket_number')
                        ->disabled()
                        ->dehydrated(false)
                        ->visible(fn ($record) => $record !== null),
                    Forms\Components\Select::make('user_id')
                        ->label('Customer')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\Select::make('assigned_to')
                        ->label('Assigned To')
                        ->relationship('assignee', 'name')
                        ->searchable()
                        ->preload()
                        ->nullable(),
                    Forms\Components\Select::make('category')
                        ->required()
                        ->options([
                            'order_issue' => 'Order Issue',
                            'payment_issue' => 'Payment Issue',
                            'driver_complaint' => 'Driver Complaint',
                            'app_bug' => 'App Bug',
                            'feature_request' => 'Feature Request',
                            'account_issue' => 'Account Issue',
                            'refund_request' => 'Refund Request',
                            'general_inquiry' => 'General Inquiry',
                            'other' => 'Other',
                        ]),
                    Forms\Components\Select::make('priority')
                        ->required()
                        ->options([
                            'low' => 'Low',
                            'medium' => 'Medium',
                            'high' => 'High',
                            'urgent' => 'Urgent',
                        ])
                        ->default('medium'),
                    Forms\Components\Select::make('status')
                        ->required()
                        ->options([
                            'open' => 'Open',
                            'in_progress' => 'In Progress',
                            'waiting_customer' => 'Waiting Customer',
                            'waiting_internal' => 'Waiting Internal',
                            'resolved' => 'Resolved',
                            'closed' => 'Closed',
                            'cancelled' => 'Cancelled',
                        ])
                        ->default('open'),
                    Forms\Components\TextInput::make('subject')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Textarea::make('description')
                        ->required()
                        ->rows(4)
                        ->columnSpanFull(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ticket_number')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->colors([
                        'danger' => 'order_issue',
                        'warning' => 'payment_issue',
                        'info' => 'driver_complaint',
                        'gray' => 'app_bug',
                        'success' => 'feature_request',
                        'primary' => 'account_issue',
                    ])
                    ->formatStateUsing(fn ($state) => ucwords(str_replace('_', ' ', $state))),
                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->colors([
                        'gray' => 'low',
                        'info' => 'medium',
                        'warning' => 'high',
                        'danger' => 'urgent',
                    ]),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'open' => 'danger',
                        'in_progress' => 'warning',
                        'waiting_customer' => 'info',
                        'waiting_internal' => 'gray',
                        'resolved' => 'success',
                        'closed' => 'gray',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => ucwords(str_replace('_', ' ', $state)))
                    ->editable(),
                Tables\Columns\TextColumn::make('assignee.name')
                    ->label('Assigned')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('last_activity_at')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'open' => 'Open',
                        'in_progress' => 'In Progress',
                        'waiting_customer' => 'Waiting Customer',
                        'waiting_internal' => 'Waiting Internal',
                        'resolved' => 'Resolved',
                        'closed' => 'Closed',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\SelectFilter::make('priority')
                    ->options([
                        'low' => 'Low',
                        'medium' => 'Medium',
                        'high' => 'High',
                        'urgent' => 'Urgent',
                    ]),
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'order_issue' => 'Order Issue',
                        'payment_issue' => 'Payment Issue',
                        'driver_complaint' => 'Driver Complaint',
                        'app_bug' => 'App Bug',
                        'feature_request' => 'Feature Request',
                        'account_issue' => 'Account Issue',
                        'refund_request' => 'Refund Request',
                        'general_inquiry' => 'General Inquiry',
                        'other' => 'Other',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Chat'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped();
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSupportTickets::route('/'),
            'create' => Pages\CreateSupportTicket::route('/create'),
            'view' => Pages\ViewSupportTicket::route('/{record}'),
            'edit' => Pages\EditSupportTicket::route('/{record}/edit'),
        ];
    }
}
