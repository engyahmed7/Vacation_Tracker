<?php

namespace App\Providers;

use App\Filament\Resources\UserProfileResource\Pages\ViewUserProfile;
use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Filament\Navigation\UserMenuItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
                $url = config('app.url') . 'user-profiles/' . $userId;
                $user = Auth::user();
                Filament::registerUserMenuItems([
                    UserMenuItem::make()
                        ->label('Profile')
                        ->url($url)
                        ->icon('heroicon-s-user'),
                ]);

                if ($user && !$user->hasRole('admin')) {
                    Filament::getDefaultPanel()->navigation(false);
                }
            } else {
                Log::info("User is not authenticated.");
            }
        });
    }
}
