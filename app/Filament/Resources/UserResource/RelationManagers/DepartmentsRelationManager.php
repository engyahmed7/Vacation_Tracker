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


class DepartmentsRelationManager extends RelationManager
{
    protected static  string $relationship = 'departments';
    protected static ? string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('description')
                    ->maxLength(500),
                // Add other fields as needed
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Department Name')
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


// function to show the departments if he was created a department
    public function getEloquentQuery(User $user): \Illuminate\Database\Eloquent\Builder
    {
        if ($user && $user->hasRole('admin')) {
            return parent::getEloquentQuery()
                ->where('user_id', $user->id);
        }

    }

    // function to show the departments if he was created a department
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        $hasDepartments = $ownerRecord->departments()->exists();

        return !empty($hasDepartments);
    }


}


/*
1- package integrate with mattermost
2- web hook url
3- channel name
4- notification class
*/