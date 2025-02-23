<?php

namespace App\Providers;

use App\Filament\Resources\UserProfileResource\Pages\ViewUserProfile;
use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Filament\Navigation\UserMenuItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Filament::serving(function () {
            if (Auth::check()) {
                $userId = Auth::id();
                $user = Auth::user();
                Filament::registerUserMenuItems([
                    UserMenuItem::make()
                        ->label('Profile')
                        ->url(route('filament.admin.resources.user-profiles.view', ['record' => $userId]))
                        ->icon('heroicon-s-user'),
                ]);

                if ($user && !$user->hasRole('admin')) {
                    Filament::getDefaultPanel()->navigation(false);
                }
            }
        });
    }
}