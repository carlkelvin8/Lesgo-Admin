<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class Settings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    
    protected static ?string $navigationLabel = 'Settings';
    
    protected static ?string $title = 'Application Settings';
    
    protected static ?string $navigationGroup = 'System';
    
    protected static ?int $navigationSort = 10;

    protected static string $view = 'filament.pages.settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->getSettingsData());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Settings')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('General')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Forms\Components\Section::make('Application Information')
                                    ->schema([
                                        Forms\Components\TextInput::make('app_name')
                                            ->label('Application Name')
                                            ->default('Lesgo Admin')
                                            ->required(),
                                        Forms\Components\Textarea::make('app_description')
                                            ->label('Application Description')
                                            ->rows(3)
                                            ->default('Logistics Management System'),
                                        Forms\Components\TextInput::make('app_url')
                                            ->label('Application URL')
                                            ->url()
                                            ->default(config('app.url')),
                                        Forms\Components\TextInput::make('support_email')
                                            ->label('Support Email')
                                            ->email()
                                            ->default('support@lesgo.com'),
                                        Forms\Components\TextInput::make('support_phone')
                                            ->label('Support Phone')
                                            ->tel()
                                            ->default('+63 123 456 7890'),
                                    ])->columns(2),
                                
                                Forms\Components\Section::make('Business Settings')
                                    ->schema([
                                        Forms\Components\TextInput::make('company_name')
                                            ->label('Company Name')
                                            ->default('Lesgo Logistics'),
                                        Forms\Components\Textarea::make('company_address')
                                            ->label('Company Address')
                                            ->rows(3),
                                        Forms\Components\TextInput::make('tax_id')
                                            ->label('Tax ID / Business Registration')
                                            ->default('123-456-789-000'),
                                        Forms\Components\Select::make('currency')
                                            ->label('Default Currency')
                                            ->options([
                                                'PHP' => 'Philippine Peso (₱)',
                                                'USD' => 'US Dollar ($)',
                                                'EUR' => 'Euro (€)',
                                            ])
                                            ->default('PHP'),
                                        Forms\Components\Select::make('timezone')
                                            ->label('Timezone')
                                            ->options([
                                                'Asia/Manila' => 'Asia/Manila (GMT+8)',
                                                'UTC' => 'UTC',
                                                'America/New_York' => 'America/New_York',
                                            ])
                                            ->default('Asia/Manila'),
                                    ])->columns(2),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Orders')
                            ->icon('heroicon-o-shopping-bag')
                            ->schema([
                                Forms\Components\Section::make('Order Settings')
                                    ->schema([
                                        Forms\Components\Toggle::make('auto_accept_orders')
                                            ->label('Auto Accept Orders')
                                            ->helperText('Automatically accept new orders without manual approval')
                                            ->default(false),
                                        Forms\Components\TextInput::make('order_timeout_minutes')
                                            ->label('Order Timeout (minutes)')
                                            ->helperText('Cancel order if not accepted within this time')
                                            ->numeric()
                                            ->default(30)
                                            ->minValue(5)
                                            ->maxValue(120),
                                        Forms\Components\TextInput::make('max_orders_per_driver')
                                            ->label('Max Orders Per Driver')
                                            ->helperText('Maximum concurrent orders a driver can handle')
                                            ->numeric()
                                            ->default(5)
                                            ->minValue(1)
                                            ->maxValue(20),
                                        Forms\Components\Toggle::make('require_order_notes')
                                            ->label('Require Order Notes')
                                            ->helperText('Make order notes mandatory')
                                            ->default(false),
                                    ])->columns(2),
                                
                                Forms\Components\Section::make('Pricing Settings')
                                    ->schema([
                                        Forms\Components\TextInput::make('base_fare')
                                            ->label('Base Fare (₱)')
                                            ->numeric()
                                            ->default(50)
                                            ->prefix('₱'),
                                        Forms\Components\TextInput::make('per_km_rate')
                                            ->label('Per Kilometer Rate (₱)')
                                            ->numeric()
                                            ->default(10)
                                            ->prefix('₱'),
                                        Forms\Components\TextInput::make('minimum_fare')
                                            ->label('Minimum Fare (₱)')
                                            ->numeric()
                                            ->default(100)
                                            ->prefix('₱'),
                                        Forms\Components\Toggle::make('enable_surge_pricing')
                                            ->label('Enable Surge Pricing')
                                            ->helperText('Allow dynamic pricing during peak hours')
                                            ->default(false),
                                    ])->columns(2),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Payments')
                            ->icon('heroicon-o-credit-card')
                            ->schema([
                                Forms\Components\Section::make('Payment Methods')
                                    ->schema([
                                        Forms\Components\Toggle::make('enable_cash_payment')
                                            ->label('Enable Cash Payment')
                                            ->default(true),
                                        Forms\Components\Toggle::make('enable_card_payment')
                                            ->label('Enable Card Payment')
                                            ->default(true),
                                        Forms\Components\Toggle::make('enable_wallet_payment')
                                            ->label('Enable Wallet Payment')
                                            ->default(true),
                                        Forms\Components\Toggle::make('enable_online_payment')
                                            ->label('Enable Online Payment')
                                            ->default(true),
                                    ])->columns(2),
                                
                                Forms\Components\Section::make('Payment Settings')
                                    ->schema([
                                        Forms\Components\TextInput::make('payment_timeout_minutes')
                                            ->label('Payment Timeout (minutes)')
                                            ->helperText('Cancel order if payment not received within this time')
                                            ->numeric()
                                            ->default(15)
                                            ->minValue(5)
                                            ->maxValue(60),
                                        Forms\Components\Toggle::make('auto_refund_cancelled_orders')
                                            ->label('Auto Refund Cancelled Orders')
                                            ->helperText('Automatically process refunds for cancelled orders')
                                            ->default(false),
                                        Forms\Components\TextInput::make('refund_processing_days')
                                            ->label('Refund Processing Days')
                                            ->numeric()
                                            ->default(7)
                                            ->minValue(1)
                                            ->maxValue(30),
                                    ])->columns(2),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Notifications')
                            ->icon('heroicon-o-bell')
                            ->schema([
                                Forms\Components\Section::make('Email Notifications')
                                    ->schema([
                                        Forms\Components\Toggle::make('notify_new_order')
                                            ->label('New Order Notifications')
                                            ->default(true),
                                        Forms\Components\Toggle::make('notify_order_status')
                                            ->label('Order Status Change Notifications')
                                            ->default(true),
                                        Forms\Components\Toggle::make('notify_payment_received')
                                            ->label('Payment Received Notifications')
                                            ->default(true),
                                        Forms\Components\Toggle::make('notify_new_user')
                                            ->label('New User Registration Notifications')
                                            ->default(true),
                                    ])->columns(2),
                                
                                Forms\Components\Section::make('SMS Notifications')
                                    ->schema([
                                        Forms\Components\Toggle::make('enable_sms_notifications')
                                            ->label('Enable SMS Notifications')
                                            ->default(false),
                                        Forms\Components\TextInput::make('sms_provider')
                                            ->label('SMS Provider')
                                            ->default('Twilio')
                                            ->disabled(),
                                    ])->columns(2),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Security')
                            ->icon('heroicon-o-shield-check')
                            ->schema([
                                Forms\Components\Section::make('Security Settings')
                                    ->schema([
                                        Forms\Components\Toggle::make('require_email_verification')
                                            ->label('Require Email Verification')
                                            ->helperText('Users must verify email before accessing the app')
                                            ->default(true),
                                        Forms\Components\Toggle::make('enable_two_factor')
                                            ->label('Enable Two-Factor Authentication')
                                            ->helperText('Allow users to enable 2FA for their accounts')
                                            ->default(false),
                                        Forms\Components\TextInput::make('max_login_attempts')
                                            ->label('Max Login Attempts')
                                            ->helperText('Lock account after this many failed attempts')
                                            ->numeric()
                                            ->default(5)
                                            ->minValue(3)
                                            ->maxValue(10),
                                        Forms\Components\TextInput::make('session_lifetime')
                                            ->label('Session Lifetime (minutes)')
                                            ->numeric()
                                            ->default(120)
                                            ->minValue(30)
                                            ->maxValue(1440),
                                    ])->columns(2),
                                
                                Forms\Components\Section::make('Data Protection')
                                    ->schema([
                                        Forms\Components\Toggle::make('enable_audit_logs')
                                            ->label('Enable Audit Logs')
                                            ->helperText('Track all user actions')
                                            ->default(true),
                                        Forms\Components\TextInput::make('audit_log_retention_days')
                                            ->label('Audit Log Retention (days)')
                                            ->numeric()
                                            ->default(365)
                                            ->minValue(30)
                                            ->maxValue(730),
                                    ])->columns(2),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Maintenance')
                            ->icon('heroicon-o-wrench-screwdriver')
                            ->schema([
                                Forms\Components\Section::make('Maintenance Mode')
                                    ->schema([
                                        Forms\Components\Toggle::make('maintenance_mode')
                                            ->label('Enable Maintenance Mode')
                                            ->helperText('Put the application in maintenance mode')
                                            ->default(false)
                                            ->live(),
                                        Forms\Components\Textarea::make('maintenance_message')
                                            ->label('Maintenance Message')
                                            ->rows(3)
                                            ->default('We are currently performing scheduled maintenance. Please check back soon.')
                                            ->visible(fn (Forms\Get $get) => $get('maintenance_mode')),
                                    ]),
                                
                                Forms\Components\Section::make('System Maintenance')
                                    ->schema([
                                        Forms\Components\Toggle::make('auto_backup')
                                            ->label('Enable Auto Backup')
                                            ->helperText('Automatically backup database daily')
                                            ->default(true),
                                        Forms\Components\Select::make('backup_frequency')
                                            ->label('Backup Frequency')
                                            ->options([
                                                'daily' => 'Daily',
                                                'weekly' => 'Weekly',
                                                'monthly' => 'Monthly',
                                            ])
                                            ->default('daily'),
                                        Forms\Components\TextInput::make('backup_retention_days')
                                            ->label('Backup Retention (days)')
                                            ->numeric()
                                            ->default(30)
                                            ->minValue(7)
                                            ->maxValue(365),
                                    ])->columns(2),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    protected function getSettingsData(): array
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        
        // Provide defaults if settings don't exist
        return array_merge([
            'app_name' => 'Lesgo Admin',
            'app_description' => 'Logistics Management System',
            'app_url' => config('app.url'),
            'support_email' => 'support@lesgo.com',
            'support_phone' => '+63 123 456 7890',
            'company_name' => 'Lesgo Logistics',
            'currency' => 'PHP',
            'timezone' => 'Asia/Manila',
            'auto_accept_orders' => false,
            'order_timeout_minutes' => 30,
            'max_orders_per_driver' => 5,
            'base_fare' => 50,
            'per_km_rate' => 10,
            'minimum_fare' => 100,
            'enable_cash_payment' => true,
            'enable_card_payment' => true,
            'enable_wallet_payment' => true,
            'enable_online_payment' => true,
            'payment_timeout_minutes' => 15,
            'notify_new_order' => true,
            'notify_order_status' => true,
            'notify_payment_received' => true,
            'require_email_verification' => true,
            'max_login_attempts' => 5,
            'session_lifetime' => 120,
            'enable_audit_logs' => true,
            'audit_log_retention_days' => 365,
            'maintenance_mode' => false,
            'auto_backup' => true,
            'backup_frequency' => 'daily',
            'backup_retention_days' => 30,
        ], $settings);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            $type = match (true) {
                is_bool($value) => 'boolean',
                is_int($value) => 'integer',
                is_array($value) => 'json',
                default => 'string',
            };

            Setting::set($key, $value, $type);
        }

        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Forms\Components\Actions\Action::make('save')
                ->label('Save Settings')
                ->submit('save')
                ->color('primary'),
        ];
    }
}
