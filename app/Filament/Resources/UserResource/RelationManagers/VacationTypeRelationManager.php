<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use App\Models\User;
use Filament\Tables\Table;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Database\Eloquent\Model;

class VacationTypeRelationManager extends RelationManager
{
    protected static string $relationship = 'vacation_types';
    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('description')
                    ->maxLength(500),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Vacation Type Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime(),
                TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime(),
            ])
            ->filters([
                // Add table filters if necessary
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

//function to show the departments id he is a user overrite the function getEloquentQuery to return the vacation types for the user that created it

    public function getEloquentQuery(User $user): \Illuminate\Database\Eloquent\Builder
    {
        if ($user && $user->hasRole('admin')) {
            return parent::getEloquentQuery()
                ->where('user_id', $user->id);

        }
    }

//function to show the vacation types if he was created a vacation type

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        $hasVacation = $ownerRecord->vacation_types()->exists();

        return !empty($hasVacation);
    }

}
