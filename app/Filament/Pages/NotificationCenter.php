<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use App\Models\User;

class NotificationCenter extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-bell';
    
    protected static ?string $navigationLabel = 'Notifications';
    
    protected static ?string $title = 'Notification Center';
    
    protected static ?string $navigationGroup = 'System';
    
    protected static ?int $navigationSort = 8;

    protected static string $view = 'filament.pages.notification-center';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Send Notification')
                    ->description('Send notifications to users')
                    ->schema([
                        Forms\Components\Select::make('recipient_type')
                            ->label('Send To')
                            ->options([
                                'all' => 'All Users',
                                'drivers' => 'All Drivers',
                                'customers' => 'All Customers',
                                'specific' => 'Specific Users',
                            ])
                            ->required()
                            ->live(),
                        Forms\Components\Select::make('user_ids')
                            ->label('Select Users')
                            ->multiple()
                            ->searchable()
                            ->options(User::pluck('name', 'id'))
                            ->visible(fn (Forms\Get $get) => $get('recipient_type') === 'specific'),
                        Forms\Components\TextInput::make('title')
                            ->label('Notification Title')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\Textarea::make('message')
                            ->label('Message')
                            ->required()
                            ->rows(4)
                            ->maxLength(500),
                        Forms\Components\Select::make('type')
                            ->label('Type')
                            ->options([
                                'info' => 'Information',
                                'success' => 'Success',
                                'warning' => 'Warning',
                                'danger' => 'Alert',
                            ])
                            ->default('info')
                            ->required(),
                        Forms\Components\Toggle::make('send_email')
                            ->label('Also Send Email')
                            ->default(false),
                        Forms\Components\Toggle::make('send_sms')
                            ->label('Also Send SMS')
                            ->default(false)
                            ->helperText('SMS service must be configured'),
                    ])->columns(2),
            ])
            ->statePath('data');
    }

    public function sendNotification(): void
    {
        $data = $this->form->getState();
        
        // Logic to send notifications would go here
        
        Notification::make()
            ->title('Notifications sent successfully')
            ->success()
            ->send();
            
        $this->form->fill();
    }

    protected function getFormActions(): array
    {
        return [
            Forms\Components\Actions\Action::make('send')
                ->label('Send Notification')
                ->submit('sendNotification')
                ->color('primary'),
        ];
    }
}
