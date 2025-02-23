<?php

use App\Filament\Resources\UserProfileResource\Pages\ViewUserProfile;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/user-profiles/{record}', function ($record) {
    $user = Auth::user();
    if ($user && !$user->hasRole('admin')) {
        Filament::getDefaultPanel()->navigation(false);
    }

    return (new ViewUserProfile())->__invoke();
})->name('filament.admin.resources.user-profiles.view');