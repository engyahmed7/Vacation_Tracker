<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as FilamentLogin;

class Login extends FilamentLogin
{
    public $email = 'admin@admin.com';
    public $password = 'admin';

    protected function getFormSchema(): array
    {
        return [
            \Filament\Forms\Components\TextInput::make('email')
                ->label(__('Email'))
                ->default('admin@example.com')
                ->required()
                ->email(),

            \Filament\Forms\Components\TextInput::make('password')
                ->label(__('Password'))
                ->default('password123')
                ->password()
                ->required(),
        ];
    }
}
