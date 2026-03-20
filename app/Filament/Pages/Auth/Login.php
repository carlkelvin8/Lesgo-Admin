<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;

class Login extends BaseLogin
{
    public function getHeading(): string
    {
        return 'Welcome Back';
    }

    public function getSubheading(): ?string
    {
        return 'Lesgo Admin — Premium Logistics Suite';
    }
}
