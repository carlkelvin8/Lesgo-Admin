<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupportTicketResource\Pages;
use App\Models\SupportTicket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SupportTicketResource extends Resource
{
    protected static ?string $model = SupportTicket::class;
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationGroup = 'Support';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Ticket Info')->schema([
                Forms\Components\TextInput::make('ticket_number')->disabled()->dehydrated(false),
                Forms\Components\Select::make('user_id')->relationship('user', 'name')->searchable()->preload()->required(),
                Forms\Components\Select::make('order_id')->relationship('order', 'id')->searchable()->nullable(),
                Forms\Components\Select::make('assigned_to')->relationship('assignee', 'name')->searchable()->preload()->nullable(),
                Forms\Components\TextInput::make('subject')->required()->maxLength(255),
                Forms\Components\Textarea::make('description')->required()->columnSpanFull(),
                Forms\Components\Select::make('category')->options(['order_issue' => 'Order Issue', 'payment_issue' => 'Payment Issue', 'driver_complaint' => 'Driver Complaint', 'app_bug' => 'App Bug', 'refund_request' => 'Refund Request', 'general_inquiry' => 'General Inquiry', 'other' => 'Other'])->required(),
                Forms\Components\Select::make('priority')->options(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'urgent' => 'Urgent'])->default('medium'),
                Forms\Components\Select::make('status')->options(['open' => 'Open', 'in_progress' => 'In Progress', 'waiting_customer' => 'Waiting Customer', 'resolved' => 'Resolved', 'closed' => 'Closed'])->default('open'),
                Forms\Components\TextInput::make('satisfaction_rating')->numeric()->minValue(1)->maxValue(5)->nullable(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('ticket_number')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('user.name')->searchable(),
            Tables\Columns\TextColumn::make('subject')->limit(30)->searchable(),
            Tables\Columns\TextColumn::make('category')->badge(),
            Tables\Columns\TextColumn::make('priority')->badge()->colors(['gray' => 'low', 'info' => 'medium', 'warning' => 'high', 'danger' => 'urgent']),
            Tables\Columns\TextColumn::make('status')->badge()->colors(['warning' => 'open', 'info' => 'in_progress', 'gray' => 'waiting_customer', 'success' => 'resolved', 'primary' => 'closed']),
            Tables\Columns\TextColumn::make('assignee.name')->label('Assigned To'),
            Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
        ])->filters([
            Tables\Filters\SelectFilter::make('status')->options(['open' => 'Open', 'in_progress' => 'In Progress', 'resolved' => 'Resolved', 'closed' => 'Closed']),
            Tables\Filters\SelectFilter::make('priority')->options(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'urgent' => 'Urgent']),
        ])->actions([Tables\Actions\ViewAction::make(), Tables\Actions\EditAction::make()])
        ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListSupportTickets::route('/'), 'create' => Pages\CreateSupportTicket::route('/create'), 'edit' => Pages\EditSupportTicket::route('/{record}/edit')];
    }

    public static function getNavigationBadge(): ?string
    {
        return null;
    }
}
