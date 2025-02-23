<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserProfileResource\Pages\ViewUserProfile;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserProfileResource\RelationManagers;
use App\Filament\Resources\UserResource\RelationManagers\AnnualVacationsRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\CasualVacationsRelationManager;
use App\Models\UserProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class UserProfileResource extends UserResource
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'view' => ViewUserProfile::route('/{record}'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            AnnualVacationsRelationManager::class,
            CasualVacationsRelationManager::class,

        ];
    }
}