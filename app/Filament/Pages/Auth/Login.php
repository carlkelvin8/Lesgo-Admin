<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;

class Login extends BaseLogin
{
    public function getHeading(): string
    {
        return 'Sign in to your account';
    }

    public function getSubheading(): ?string
    {
        return 'Lesgo Admin Panel';
    }
}
